<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (session('user_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password'   => 'required|min:6',
        ], [
            'identifier.required' => 'NIM atau NIDN wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
        ]);

        $identifier = $request->identifier;

        // Step 1: Try to find mahasiswa by NIM
        $mahasiswa = Mahasiswa::where('nim', $identifier)->first();

        if ($mahasiswa) {
            if (!$mahasiswa->password || !Hash::check($request->password, $mahasiswa->password)) {
                return back()->withErrors(['identifier' => 'NIM atau password salah.'])->withInput();
            }

            session([
                'user_id'    => 'mhs_' . $mahasiswa->id,
                'user_name'  => $mahasiswa->nama,
                'user_email' => $mahasiswa->email,
                'user_role'  => 'mahasiswa',
                'user_nim'   => $mahasiswa->nim,
                'user_foto_profil' => $mahasiswa->foto_profil,
            ]);

            return redirect()->route('dashboard')->with('login_success', 'Selamat datang kembali, ' . $mahasiswa->nama . '!');
        }

        // Step 2: Try to find user (dosen/admin/staff) by NIDN
        $user = User::where('nidn', $identifier)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['identifier' => 'NIM/NIDN atau password salah.'])->withInput();
        }

        session([
            'user_id'    => $user->id,
            'user_name'  => $user->name,
            'user_email' => $user->email,
            'user_role'  => $user->role ?? 'admin',
            'user_nidn'  => $user->nidn,
            'user_foto_profil' => $user->foto_profil,
        ]);

        return redirect()->route('dashboard')->with('login_success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Berhasil logout. Sampai jumpa!');
    }
}
