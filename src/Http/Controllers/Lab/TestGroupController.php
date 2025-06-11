<?php

namespace App\Http\Controllers\Lab;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalLab\Models\Lab\TestGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Http\Requests\Lab\TestGroupRequest;

class TestGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:test-groups.index')->only('index');
        $this->middleware('checkPermission:test-groups.create')->only('create', 'store');
        $this->middleware('checkPermission:test-groups.show')->only('show');
        $this->middleware('checkPermission:test-groups.edit')->only('edit', 'update');
        $this->middleware('checkPermission:test-groups.destroy')->only('destroy');
    }
    public function index()
    {
        $test_groups = TestGroup::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render('Lab/TestGroups/Index', [
            'test_groups' => $test_groups,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('Lab/TestGroups/Create');
    }

    public function store(TestGroupRequest $request)
    {
        DB::beginTransaction();

        $test_group = new TestGroup();
        $formData = $request->only($test_group->getFillable());

        TestGroup::create($formData);

        DB::commit();

        return redirect()->route('test-groups.index');
    }


    public function edit(TestGroup $test_group)
    {
        return Inertia::render('Lab/TestGroups/Create', [
            'test_group' => $test_group,
        ]);
    }


    public function update(TestGroupRequest $request, TestGroup $test_group)
    {
        DB::beginTransaction();

        $formData = $request->only($test_group->getFillable());
        $test_group->update($formData);

        DB::commit();

        return redirect()->route('test-groups.index')->with('message', 'Test group updated successfully.');
    }


    public function destroy($id)
    {
        $test_group = TestGroup::findOrFail($id);
        $test_group->delete();

        return redirect()->route('test-groups.index')->with('success', 'Test group deleted successfully.');
    }
}
