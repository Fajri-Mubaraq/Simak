<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $role = session('user_role');
        $id = session('user_id');

        if ($role === 'mahasiswa') {
            // Student ID is stored with prefix 'mhs_'
            $realId = substr($id, 4);
            $profile = Mahasiswa::findOrFail($realId);
        } else {
            $profile = User::findOrFail($id);
        }

        return view('profile.edit', compact('profile', 'role'));
    }

    /**
     * Update the profile data.
     */
    public function update(Request $request)
    {
        $role = session('user_role');
        $id = session('user_id');

        if ($role === 'mahasiswa') {
            $realId = substr($id, 4);
            $profile = Mahasiswa::findOrFail($realId);

            $rules = [
                'nama'          => 'required|string|max:100',
                'email'         => 'required|email|max:100|unique:mahasiswas,email,' . $realId,
                'no_telp'       => 'nullable|string|max:20',
                'alamat'        => 'nullable|string',
                'password'      => 'nullable|min:6|confirmed',
                'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Up to 5MB before crop
                'cropped_image' => 'nullable|string',
            ];

            $validated = $request->validate($rules, [
                'email.unique'       => 'Email sudah digunakan.',
                'password.min'       => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            // Handle Cropped Image (Base64)
            if ($request->filled('cropped_image')) {
                // Delete old file if exists
                if ($profile->foto_profil) {
                    $oldPath = public_path($profile->foto_profil);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $base64Image = $request->input('cropped_image');
                $imageParts = explode(";base64,", $base64Image);
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'jpeg';
                $imageBase64 = base64_decode($imageParts[1]);

                $filename = 'mhs_' . $realId . '_' . time() . '.' . $imageType;
                $destinationPath = public_path('uploads/profile');
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                $file = $destinationPath . '/' . $filename;
                file_put_contents($file, $imageBase64);
                $profile->foto_profil = 'uploads/profile/' . $filename;

            } elseif ($request->hasFile('foto_profil')) {
                // Fallback for regular upload
                if ($profile->foto_profil) {
                    $oldPath = public_path($profile->foto_profil);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $file = $request->file('foto_profil');
                $filename = 'mhs_' . $realId . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                $destinationPath = public_path('uploads/profile');
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                $file->move($destinationPath, $filename);
                $profile->foto_profil = 'uploads/profile/' . $filename;
            }

            // Update attributes
            $profile->nama = $validated['nama'];
            $profile->email = $validated['email'];
            $profile->no_telp = $validated['no_telp'];
            $profile->alamat = $validated['alamat'];

            if ($request->filled('password')) {
                $profile->password = Hash::make($validated['password']);
            }

            $profile->save();

            // Update session values
            session([
                'user_name'        => $profile->nama,
                'user_email'       => $profile->email,
                'user_foto_profil' => $profile->foto_profil,
            ]);

        } else {
            $profile = User::findOrFail($id);

            $rules = [
                'name'          => 'required|string|max:100',
                'email'         => 'required|email|max:100|unique:users,email,' . $id,
                'password'      => 'nullable|min:6|confirmed',
                'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'cropped_image' => 'nullable|string',
            ];

            $validated = $request->validate($rules, [
                'email.unique'       => 'Email sudah digunakan.',
                'password.min'       => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            // Handle Cropped Image (Base64)
            if ($request->filled('cropped_image')) {
                // Delete old file if exists
                if ($profile->foto_profil) {
                    $oldPath = public_path($profile->foto_profil);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $base64Image = $request->input('cropped_image');
                $imageParts = explode(";base64,", $base64Image);
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'jpeg';
                $imageBase64 = base64_decode($imageParts[1]);

                $filename = 'usr_' . $id . '_' . time() . '.' . $imageType;
                $destinationPath = public_path('uploads/profile');
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                $file = $destinationPath . '/' . $filename;
                file_put_contents($file, $imageBase64);
                $profile->foto_profil = 'uploads/profile/' . $filename;

            } elseif ($request->hasFile('foto_profil')) {
                // Fallback for regular upload
                if ($profile->foto_profil) {
                    $oldPath = public_path($profile->foto_profil);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $file = $request->file('foto_profil');
                $filename = 'usr_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                $destinationPath = public_path('uploads/profile');
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                $file->move($destinationPath, $filename);
                $profile->foto_profil = 'uploads/profile/' . $filename;
            }

            $profile->name = $validated['name'];
            $profile->email = $validated['email'];

            if ($request->filled('password')) {
                $profile->password = Hash::make($validated['password']);
            }

            $profile->save();

            // Update session values
            session([
                'user_name'        => $profile->name,
                'user_email'       => $profile->email,
                'user_foto_profil' => $profile->foto_profil,
            ]);
        }

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
