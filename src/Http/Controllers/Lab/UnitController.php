<?php

namespace App\Http\Controllers\Lab;

use Inertia\Inertia;
use XelentAbrar\HospitalLab\Models\Lab\Unit;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Http\Requests\Lab\UnitRequest;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:units.index')->only('index');
        $this->middleware('checkPermission:units.create')->only('create', 'store');
        $this->middleware('checkPermission:units.show')->only('show');
        $this->middleware('checkPermission:units.edit')->only('edit', 'update');
        $this->middleware('checkPermission:units.destroy')->only('destroy');
    }
    public function index()
    {
        $units = Unit::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render('Lab/Units/Index', [
            'units' => $units,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Lab/Units/Create');
    }

    public function store(UnitRequest $request)
    {
        DB::beginTransaction();

        $unit = new Unit();
        $formData = $request->only($unit->getFillable());

        Unit::create($formData);

        DB::commit();

        return redirect()->route('units.index');
    }


    public function edit(Unit $unit)
    {
        return Inertia::render('Lab/Units/Create', [
            'unit' => $unit,
        ]);
    }


    public function update(UnitRequest $request, Unit $unit)
    {
        DB::beginTransaction();

        $formData = $request->only($unit->getFillable());
        $unit->update($formData);

        DB::commit();

        return redirect()->route('units.index')->with('message', 'Unit updated successfully.');
    }


    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
