<?php

namespace App\Http\Controllers\Lab;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalLab\Models\Lab\WidalTest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Http\Requests\Lab\WidalTestRequest;

class WidalTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:widal-tests.index')->only('index');
        $this->middleware('checkPermission:widal-tests.create')->only('create', 'store');
        $this->middleware('checkPermission:widal-tests.show')->only('show');
        $this->middleware('checkPermission:widal-tests.edit')->only('edit', 'update');
        $this->middleware('checkPermission:widal-tests.destroy')->only('destroy');
    }
    public function index()
    {
        $widal_tests = WidalTest::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render('Lab/WidalTests/Index', [
            'widal_tests' => $widal_tests,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Lab/WidalTests/Create');
    }

    public function store(WidalTestRequest $request)
    {
        DB::beginTransaction();
        $widal_test = new WidalTest();
        $formData = $request->only($widal_test->getFillable());

        WidalTest::create($formData);

        DB::commit();

        return redirect()->route('widal-tests.index');
    }


    public function edit(WidalTest $widal_test)
    {
        return Inertia::render('Lab/WidalTests/Create', [
            'widal_test' => $widal_test,
        ]);
    }


    public function update(WidalTestRequest $request, WidalTest $widal_test)
    {
        DB::beginTransaction();
        $formData = $request->only($widal_test->getFillable());
        $widal_test->update($formData);

        DB::commit();

        return redirect()->route('widal-tests.index')->with('message', 'Widal test updated successfully.');
    }


    public function destroy($id)
    {
        $widal_test = WidalTest::findOrFail($id);
        $widal_test->delete();

        return redirect()->route('widal-tests.index')->with('success', 'Widal test deleted successfully.');
    }
}
