<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminAccountController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('studyProgram');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by Role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by Study Program
        if ($request->filled('study_program')) {
            $query->where('study_program_id', $request->input('study_program'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(15);
        $studyPrograms = StudyProgram::where('is_active', true)->get();

        return view('admin.accounts.index', compact('users', 'studyPrograms'));
    }

    public function create(): View
    {
        $studyPrograms = StudyProgram::where('is_active', true)->get();

        return view('admin.accounts.create', compact('studyPrograms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user'],
            'study_program_id' => ['nullable', 'exists:study_programs,id'],
            'is_active' => ['boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'study_program_id' => $validated['study_program_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $account): View
    {
        $studyPrograms = StudyProgram::where('is_active', true)->get();

        return view('admin.accounts.edit', compact('account', 'studyPrograms'));
    }

    public function update(Request $request, User $account): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($account->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user'],
            'study_program_id' => ['nullable', 'exists:study_programs,id'],
            'is_active' => ['boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'study_program_id' => $validated['study_program_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $account->update($data);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $account): RedirectResponse
    {
        if ($account->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account!');
        }

        $account->delete();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'User deleted successfully!');
    }

    public function toggleStatus(User $account): RedirectResponse
    {
        if ($account->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account!');
        }

        $account->update(['is_active' => !$account->is_active]);

        $status = $account->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "User {$status} successfully!");
    }
}
