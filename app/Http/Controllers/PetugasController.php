<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::with('user')->latest()->paginate(10);
        return view('admin.petugas', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:petugas,email',
            'no_hp' => 'required|string|max:20',
        ]);

        // Create user account
        $username = strtolower(str_replace(' ', '', $request->nama_petugas));
        $user = User::create([
            'username' => $username,
            'name' => $request->nama_petugas,
            'email' => $request->email,
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        // Create petugas record linked to user
        Petugas::create([
            'user_id' => $user->id,
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status_aktif' => true,
        ]);

        return redirect()->back()->with('success', 'Petugas berhasil ditambahkan. Username: ' . $username . ', Password: petugas123');
    }

    public function updateById(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $petugas->user_id . '|unique:petugas,email,' . $id,
            'no_hp' => 'required|string|max:20',
            'status_aktif' => 'required|boolean',
        ]);

        // Update petugas
        $petugas->update([
            'nama_petugas' => $request->nama_petugas,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status_aktif' => $request->status_aktif,
        ]);

        // Update linked user
        if ($petugas->user) {
            $petugas->user->update([
                'name' => $request->nama_petugas,
                'email' => $request->email,
            ]);
        }

        return redirect()->back()->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        
        // Delete linked user
        if ($petugas->user) {
            $petugas->user->delete();
        }
        
        $petugas->delete();

        return redirect()->back()->with('success', 'Data petugas berhasil dihapus.');
    }
}
