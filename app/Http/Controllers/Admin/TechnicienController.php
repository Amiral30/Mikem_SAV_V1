<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TechnicienController extends Controller
{
    public function index(Request $request)
    {
        $query = User::techniciens()->withCount('missions');
        if ($request->filled('disponibilite')) {
            $query->where('disponible', $request->disponibilite === 'disponible');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $techniciens = $query->latest()->paginate(15);
        return view('admin.techniciens.index', compact('techniciens'));
    }

    public function create()
    {
        return view('admin.techniciens.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'technicien',
            'disponible' => true,
        ]);

        return redirect()->route('admin.techniciens.index')
            ->with('success', 'Technicien créé avec succès.');
    }

    public function show(User $technicien)
    {
        $technicien->load(['missions' => function ($q) {
            $q->latest()->take(20);
        }, 'rapports.mission']);
        return view('admin.techniciens.show', compact('technicien'));
    }

    public function edit(User $technicien)
    {
        return view('admin.techniciens.edit', compact('technicien'));
    }

    public function update(Request $request, User $technicien)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $technicien->id,
            'telephone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $technicien->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $technicien->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.techniciens.show', $technicien)
            ->with('success', 'Technicien mis à jour.');
    }

    public function destroy(User $technicien)
    {
        $technicien->delete();
        return redirect()->route('admin.techniciens.index')
            ->with('success', 'Technicien supprimé.');
    }
}
