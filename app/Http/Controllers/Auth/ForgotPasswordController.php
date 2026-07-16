<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form (step 1: enter email).
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Verify the email exists and show reset form (step 2).
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        // Search in both users and mahasiswas tables
        $user = User::where('email', $request->email)->first();
        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if (!$user && !$mahasiswa) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.'])->withInput();
        }

        // Store email and source in session for the reset step
        session([
            'reset_email'  => $request->email,
            'reset_source' => $user ? 'user' : 'mahasiswa',
        ]);

        return redirect()->route('password.reset');
    }

    /**
     * Show the reset password form (step 2: enter new password).
     */
    public function showResetForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.forgot');
        }

        return view('auth.reset-password');
    }

    /**
     * Update the user's password.
     */
    public function resetPassword(Request $request)
    {
        if (!session('reset_email')) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'password'              => 'required|min:6|confirmed',
        ], [
            'password.required'     => 'Password baru wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $email  = session('reset_email');
        $source = session('reset_source');

        if ($source === 'user') {
            $record = User::where('email', $email)->first();
        } else {
            $record = Mahasiswa::where('email', $email)->first();
        }

        if (!$record) {
            session()->forget(['reset_email', 'reset_source']);
            return redirect()->route('password.forgot')->withErrors(['email' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }

        $record->password = Hash::make($request->password);
        $record->save();

        session()->forget(['reset_email', 'reset_source']);

        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
}
