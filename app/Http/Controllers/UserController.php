<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::where('role', 'kasir')->latest()->get();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // role diisi langsung oleh sistem, bukan dari input form
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = 'kasir';
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Akun kasir berhasil ditambahkan.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role !== 'kasir') {
            abort(403, 'Hanya akun kasir yang dapat dihapus.');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            return back()->withErrors([
                'user' => 'Akun tidak dapat dihapus karena sudah memiliki transaksi.',
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Akun kasir berhasil dihapus.');
    }
}
