<?php

namespace App\Services;

use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAudit;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use App\Models\AccountSetting;
use App\Models\HRMS\UserCashSummary;

class VoucherAuditService
{
    function storeVoucher($type, $data, $status, $parent_id = null, $reverse = null) {
        if($status == 'store'){
            if(file_exists(base_path('config/accounts.php'))) {
                $data['id'] = $parent_id;
                return $this->storeLab($data, $reverse);
            }
        }
        else{
            return 0;
        }
    }

    function storeLab($data ,$type = null) {

        $advCoaValue = AccountSetting::where('key', 'Advance')->value('value');
        $cashCoaValue = AccountSetting::where('key', 'cash_in_hand')->value('value');
        $adv_coa_id = ChartOfAccount::where('acc_code', $advCoaValue)->value('id');
        $cash_coa_id = ChartOfAccount::where('acc_code', $cashCoaValue)->value('id');
        $MiscValue = AccountSetting::where('key', 'MISC_RECEIPTS')->value('value');
        $zFValue = AccountSetting::where('key', 'ZF_Patients')->value('value');
        $discValue = AccountSetting::where('key', 'DISCOUNT')->value('value');

    $packageNames = [];


    foreach ($data['patient_test_details'] as $testDetail) {
        if (!empty($testDetail['package_id'])) {
            $packageNames[] = $testDetail['selectedPackage']['name'] ?? '';
        } else {
            if (!empty($testDetail['test_general_details'])) {
                foreach ($testDetail['test_general_details'] as $generalDetail) {
                    if (!empty($generalDetail['sub_package'])) {
                        $packageNames[] = $generalDetail['sub_package']['name'] ?? '';
                    }

                    $labTest = $generalDetail['lab_test'] ?? null;
                    if ($labTest) {
                        $packageNames[] = $labTest['name'] ?? '';
                    }
                }
            }
        }
    }

    $packageList = implode(', ', array_filter($packageNames));
    // Log::info('Packages:', ['package_names' => $packageList]);
        $voucher = VoucherAudit::create([
            'voucher_type_id' => '1',
            'type' => 'lab',
            'parent_id' => $data['id'],
            'voucher_date' => $data['test_date'],
            // 'narration' => $data['file_no'].' '.$data['patient_name'],
            'narration' => 'Slip No : ' . $data['id'] . ' - ' . $data['patient_name'].' Test: '.(strlen($packageList) > 50 ? substr($packageList, 0, 50) . '...' : $packageList),
            'created_by' => \Auth::id()
        ]);
        $discount = 0;
        if($data['discount_type'] == 'Percentage'){
            $discount = ($data['total_amount'] / 100) * $data['discount_value'];
        }
        else if($data['discount_type'] == 'Fixed'){
            $discount = $data['discount_value'];
        }

        // Other_Receipt
        if(!file_exists(base_path('config/donation.php'))) {
            $data['zf_fee'] = 0;
            $data['donor_fee'] = 0;
        }
        $coh = +$data['total_amount'] - +$data['zf_fee'] - +$data['donor_fee'] - +$discount;

        if($data['test_nature'] == 'Other_Receipt'){
            $lab_test_id = $data['patient_test_details'][0]['test_general_details'][0]['lab_test']['id'] ?? null;
            $coa_id = LabTest::whereId($lab_test_id)->value('coa_id');
        }
        else{
            $labValue = AccountSetting::where('key', 'LAB_RECEIPTS')->value('value');
            $coa_id = ChartOfAccount::where('acc_code', $labValue)->value('id');
            // $coa_id = ChartOfAccount::where('acc_code', '131011002')->value('id');
        }
        VoucherAuditDetail::create([
            'voucher_audit_id' => $voucher->id,
            'acc_code' => $coa_id ?? ChartOfAccount::where('acc_code', '131011002')->value('id'),
            'dr' => $type == 'reverse' ? $data['total_amount'] : 0,
            'cr' => $type == 'reverse' ? 0 : $data['total_amount'],
            'transaction_type' => 'lab',
            'transaction_no' => $data['id'],
            'remarks' => null
        ]);

        if($data['zf_fee'] > 0){
            VoucherAuditDetail::create([
                'voucher_audit_id' => $voucher->id,
                // 'acc_code' => ChartOfAccount::where('acc_code', '121101001')->value('id'),
                'acc_code' => ChartOfAccount::where('acc_code', $zFValue)->value('id'),
                'dr' => $type == 'reverse' ? 0 : $data['zf_fee'],
                'cr' => $type == 'reverse' ? $data['zf_fee'] : 0,
                'transaction_type' => 'lab',
                'transaction_no' => $data['id'],
                'remarks' => null
            ]);
        }

        if(file_exists(base_path('config/donation.php'))) {
            if($data['careoff_id']){
                $donor = Donor::whereId($data['careoff_id'])->first();
                if($donor->coa_id){
                    VoucherAuditDetail::create([
                        'voucher_audit_id' => $voucher->id,
                        'acc_code' => $donor->coa_id,
                        'dr' => $type == 'reverse' ? 0 : $data['donor_fee'],
                        'cr' => $type == 'reverse' ? $data['donor_fee'] : 0,
                        'transaction_type' => 'lab',
                        'transaction_no' => $data['id'],
                        'remarks' => null
                    ]);
                }
            }
        }

        if($discount > 0){
            VoucherAuditDetail::create([
                'voucher_audit_id' => $voucher->id,
                'acc_code' => ChartOfAccount::where('acc_code', $discValue)->value('id'),
                // 'acc_code' => ChartOfAccount::where('acc_code', '121101023')->value('id'),
                'dr' => $type == 'reverse' ? 0 : $discount,
                'cr' => $type == 'reverse' ? $discount : 0,
                'transaction_type' => 'lab',
                'transaction_no' => $data['id'],
                'remarks' => null
            ]);
        }

        if($coh > 0){
            VoucherAuditDetail::create([
                'voucher_audit_id' => $voucher->id,
                'acc_code' => ChartOfAccount::where('acc_code', $cashCoaValue)->value('id'),
                // 'acc_code' => ChartOfAccount::where('acc_code', '101051001')->value('id'),
                'dr' => $type == 'reverse' ? 0 : $coh,
                'cr' => $type == 'reverse' ? $coh : 0,
                'transaction_type' => 'lab',
                'transaction_no' => $data['id'],
                'remarks' => null
            ]);
        }
        $userId = $data['created_by'] ?? \Auth::id();
        $userCashSummary = UserCashSummary::where('user_id', $userId)
                                          ->latest()
                                          ->first();

        if (!$userCashSummary || $userCashSummary->status == 'complete') {
            UserCashSummary::create([
                'user_id' => $userId,
                'receipt_count' => 1,
                'total_amount' => $coh,
                'status' => 'pending',
                'activity_time' => $data['test_date'] . ' ' . ($data['test_time'] ?? now()->format('H:i:s')),
            ]);
        } else {
            $userCashSummary->increment('receipt_count', 1);
            $userCashSummary->increment('total_amount', $coh);
            $userCashSummary->update([
                'activity_time' => $data['test_date'] . ' ' . ($data['test_time'] ?? now()->format('H:i:s'))
            ]);
        }
        return 1;
    }
}
