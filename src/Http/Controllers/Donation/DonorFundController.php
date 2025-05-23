<?php

namespace XelentAbrar\HospitalDonation\Http\Controllers\Donation;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use XelentAbrar\HospitalDonation\Models\Donation\DonorFund;
use XelentAbrar\HospitalDonation\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalDonation\Services\VoucherAuditService;
use XelentAbrar\HospitalDonation\Http\Requests\Donation\DonorFundRequest;

class DonorFundController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:donor-funds.index')->only('index');
        $this->middleware('checkPermission:donor-funds.create')->only('create', 'store');
        $this->middleware('checkPermission:donor-funds.show')->only('show');
        $this->middleware('checkPermission:donor-funds.edit')->only('edit', 'update');
        $this->middleware('checkPermission:donor-funds.destroy')->only('destroy');
    }
    public function index()
    {
        $donorFunds = DonorFund::with('donor:id,name','creator:id,name')->orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $donorFunds = $donorFunds->whereHas('donor', function($q){
                $q = $q->where('name', 'LIKE', '%'.$_GET['search'].'%');
            });
        }
        $donorFunds = $donorFunds->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render('Donation/DonorFunds/Index', [
            'donorFunds' => $donorFunds,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        $donors = Donor::orderBy('created_at', 'desc')->get();
        return Inertia::render('Donation/DonorFunds/Create', [
            'donors' => $donors,
        ]);
    }

    // public function store(DonorFundRequest $request)
    // {
    //     $donorFund = new DonorFund();
    //     $employeeDeductionData = $request->only($donorFund->getFillable());

    //     $data = DonorFund::create($employeeDeductionData);

    //     $voucher_audit = new VoucherAuditService();
    //     $voucher_audit->storeVoucher('donor_fund', $data, 'store');

    //     return redirect()->route('donor-funds.index');
    // }
    public function store(DonorFundRequest $request)
    {
        $donorFund = new DonorFund();
        $employeeDeductionData = $request->only($donorFund->getFillable());
        $maxDonorSerial = DonorFund::max('donor_serial');
        $employeeDeductionData['donor_serial'] = intval($maxDonorSerial) ? intval($maxDonorSerial) + 1 : 1;
        $employeeDeductionData['donor_time'] = now()->format('H:i');
        $employeeDeductionData['created_by'] = auth()->id();
        $data = DonorFund::create($employeeDeductionData);

        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('donor_fund', $data, 'store');

        return redirect()->route('donor-funds.index');
    }


    public function edit(DonorFund $donorFund)
    {
        $donors = Donor::orderBy('created_at', 'desc')->get();
        return Inertia::render('Donation/DonorFunds/Create', [
            'donorFund' => $donorFund,
            'donors' => $donors,
        ]);
    }


    public function update(DonorFundRequest $request, DonorFund $donorFund)
    {
        $employeeDeductionData = $request->only($donorFund->getFillable());

        $voucher_audit = new VoucherAuditService();
        $voucher_audit = $voucher_audit->storeVoucher('donor_fund', $employeeDeductionData, 'update', $donorFund->id);
        if(!$voucher_audit){
            return redirect()->route('donor-funds.index')->with('message', 'Expense Already Sent to Accounts.');
        }
        $employeeDeductionData['updated_by'] = auth()->id();

        $donorFund->update($employeeDeductionData);

        return redirect()->route('donor-funds.index')->with('message', 'DonorFund updated successfully.');
    }
    public function print(DonorFund $donorFund)
    {
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        if (!$role && $donorFund->is_printed == 1 && $donorFund->is_printed == 2) {
            return redirect()->route('donor-funds.index')->with('error', 'This receivable has already been printed.');
        }
        $newPrintedStatus = $donorFund->is_printed == 0 ? 1 : 2;
        $donorFund->update(['is_printed' => $newPrintedStatus]);
        // $donorFund->update(['is_printed' => 1]);

        $donor = DonorFund::with('donor:id,name','creator:id,name')->find($donorFund->id);
        // return $donor;
        return Inertia::render('Donation/DonorFunds/Print', [
            'donorData' => $donor,
            'donor' => $donorFund->donor,
        ]);
    }


    public function destroy($id)
    {
        $donorFund = DonorFund::findOrFail($id);
        $donorFund->delete();

        return redirect()->route('donor-funds.index')->with('success', 'DonorFund deleted successfully.');
    }
}
