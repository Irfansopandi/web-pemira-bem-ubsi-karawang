<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display administrator profile page.
     */
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.index', [
            'admin' => $admin,
        ]);
    }

    /**
     * Update administrator identity information.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'username' => ['required', 'string', 'max:100', Rule::unique('admins')->ignore($admin->id)],
        ], [
            'name.required' => 'Nama lengkap administrator wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
        ]);

        $oldData = [
            'name' => $admin->name,
            'email' => $admin->email,
            'username' => $admin->username,
        ];

        $admin->update($validated);

        // Audit Trail
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'UPDATE_PROFILE',
            'entity_type' => 'Admin',
            'entity_id' => $admin->id,
            'old_values' => $oldData,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Profil administrator berhasil diperbarui.');
    }

    /**
     * Update administrator account password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal berjumlah 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai dengan data kami.']);
        }

        $admin->update([
            'password' => $validated['password'],
        ]);

        // Audit Trail
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'UPDATE_PASSWORD',
            'entity_type' => 'Admin',
            'entity_id' => $admin->id,
            'old_values' => ['password' => '***HIDDEN***'],
            'new_values' => ['password' => '***UPDATED***'],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Password administrator berhasil diperbarui.');
    }
}
