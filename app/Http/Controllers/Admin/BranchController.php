<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('manager')->withCount('orders')->paginate(10);
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        // Only managers not already assigned to a branch
        $managers = User::where('role', 'branch_manager')
            ->whereDoesntHave('managedBranch')
            ->get();
        return view('admin.branches.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email',
            'address'    => 'nullable|string',
            'city'       => 'nullable|string|max:100',
            'manager_id' => 'nullable|exists:users,id',
            'is_active'  => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Branch::create($data);
        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        $managers = User::where('role', 'branch_manager')
            ->where(function ($q) use ($branch) {
                $q->whereDoesntHave('managedBranch')
                  ->orWhere('id', $branch->manager_id);
            })->get();

        return view('admin.branches.edit', compact('branch', 'managers'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email',
            'address'    => 'nullable|string',
            'city'       => 'nullable|string|max:100',
            'manager_id' => 'nullable|exists:users,id',
            'is_active'  => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $branch->update($data);
        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return back()->with('success', 'Branch deleted.');
    }
}
