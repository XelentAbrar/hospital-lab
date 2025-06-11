<?php

namespace App\Http\Controllers\Lab;

use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalLab\Models\Lab\TestGroup;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Http\Requests\Lab\TestCategoryRequest;

class TestCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:test-categories.index')->only('index');
        $this->middleware('checkPermission:test-categories.create')->only('create', 'store');
        $this->middleware('checkPermission:test-categories.show')->only('show');
        $this->middleware('checkPermission:test-categories.edit')->only('edit', 'update');
        $this->middleware('checkPermission:test-categories.destroy')->only('destroy');
    }
    public function index()
    {
        $test_categories = TestCategory::with('testGroup:id,name')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render('Lab/TestCategories/Index', [
            'test_categories' => $test_categories,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $test_groups = TestGroup::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Lab/TestCategories/Create', [
            'test_groups' => $test_groups
        ]);
    }

    public function store(TestCategoryRequest $request)
    {
        DB::beginTransaction();
        $test_category = new TestCategory();
        $formData = $request->only($test_category->getFillable());

        TestCategory::create($formData);

        DB::commit();

        return redirect()->route('test-categories.index');
    }


    public function edit(TestCategory $test_category)
    {
        $test_groups = TestGroup::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Lab/TestCategories/Create', [
            'test_category' => $test_category,
            'test_groups' => $test_groups,
        ]);
    }


    public function update(TestCategoryRequest $request, TestCategory $test_category)
    {
        DB::beginTransaction();

        $formData = $request->only($test_category->getFillable());
        $test_category->update($formData);

        DB::commit();

        return redirect()->route('test-categories.index')->with('message', 'Test category updated successfully.');
    }


    public function destroy($id)
    {
        $test_category = TestCategory::findOrFail($id);
        $test_category->delete();

        return redirect()->route('test-categories.index')->with('success', 'Test category deleted successfully.');
    }
}
