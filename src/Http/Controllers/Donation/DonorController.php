<?php

namespace XelentAbrar\HospitalDonation\Http\Controllers\Donation;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalDonation\Models\OPD\Donor;
use XelentAbrar\HospitalDonation\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalDonation\Http\Requests\OPD\DonorRequest;
use XelentAbrar\HospitalDonation\Models\Accounts\ChartOfAccount;

class DonorController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:donors.index')->only('index');
        $this->middleware('checkPermission:donors.create')->only('create', 'store');
        $this->middleware('checkPermission:donors.show')->only('show');
        $this->middleware('checkPermission:donors.edit')->only('edit', 'update');
        $this->middleware('checkPermission:donors.destroy')->only('destroy');
    }
    
    public function index()
    {
        $donors = Donor::orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $donors = $donors->where('name', 'LIKE', '%'.$_GET['search'].'%');
        }
        $donors = $donors->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Donation/Donors/Index', [
            'donors' => $donors,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        $coa = ChartOfAccount::select('id','acc_desc')->get();
        return Inertia::render('Donation/Donors/Create', [
            'coa' => $coa,
        ]);
    }

    public function store(DonorRequest $request)
    {
        $donor = new Donor();
        $employeeDeductionData = $request->only($donor->getFillable());

        Donor::create($employeeDeductionData);

        return redirect()->route('donors.index');
    }


    public function edit(Donor $donor)
    {
        $coa = ChartOfAccount::select('id','acc_desc')->get();
        return Inertia::render('Donation/Donors/Create', [
            'donor' => $donor,
            'coa' => $coa,
        ]);
    }


    public function update(DonorRequest $request, Donor $donor)
    {
        $employeeDeductionData = $request->only($donor->getFillable());
        $donor->update($employeeDeductionData);

        return redirect()->route('donors.index')->with('message', 'Donor updated successfully.');
    }


    public function destroy($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->delete();

        return redirect()->route('donors.index')->with('success', 'Donor deleted successfully.');
    }
}
