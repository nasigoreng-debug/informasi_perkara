<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Satker;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Akses Ditolak.');
        }

        $users = User::with(['satker', 'role'])->orderBy('role_id', 'asc')->get();
        return view('users.index', compact('users'));
    }

    // HALAMAN TAMBAH - Pastikan data Satker & Role dikirim ke View
    public function create()
    {
        $satkers = Satker::orderBy('nama', 'asc')->get();
        $roles = Role::all();
        return view('users.create', compact('satkers', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'satker_id' => 'required',
            'role_id' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'satker_id' => $request->satker_id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil disimpan.');
    }

    // HALAMAN EDIT - Pastikan variabel $user, $satkers, dan $roles dikirim
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $satkers = Satker::orderBy('nama', 'asc')->get();
        $roles = Role::all();
        return view('users.edit', compact('user', 'satkers', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'satker_id' => 'required',
            'role_id' => 'required'
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->satker_id = $request->satker_id;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if ($id == Auth::id()) return back()->with('error', 'Cegah hapus diri sendiri.');
        User::destroy($id);
        return back()->with('success', 'User dihapus.');
    }
}
