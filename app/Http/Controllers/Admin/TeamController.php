<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $teams = Team::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.teams.index', compact('teams', 'search'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('teams', 'public');
        }

        Team::create($data);

        return redirect()->route('admin.teams.index')->with('status', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $team = Team::findOrFail($id);
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, string $id)
    {
        $team = Team::findOrFail($id);
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            if ($team->image && ! str_starts_with($team->image, 'images/')) {
                Storage::disk('public')->delete($team->image);
            }
            $data['image'] = $request->file('image')->store('teams', 'public');
        }

        $team->update($data);

        return redirect()->route('admin.teams.index')->with('status', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $team = Team::findOrFail($id);

        if ($team->image && ! str_starts_with($team->image, 'images/')) {
            Storage::disk('public')->delete($team->image);
        }

        $team->delete();

        return redirect()->route('admin.teams.index')->with('status', 'Anggota tim berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'initial' => ['nullable', 'string', 'max:5'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        unset($data['image']);

        return $data;
    }
}
