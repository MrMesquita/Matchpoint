<?php

namespace App\Services;

use App\Exceptions\AdminNotFoundException;
use App\Exceptions\ArenaNotFoundException;
use App\Models\Admin;
use App\Models\Arena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArenaService
{
    public function getAllArenas()
    {
        return Arena::all();
    }

    public function save(Request $request): Arena
    {
        $validated = $this->validateArenaData($request);
        return Arena::create(array_merge($validated));
    }    

    public function getArenaById(string $id)
    {
        return $this->findArenaOrFail($id);
    }

    public function updateArena(Request $request, string $id): Arena
    {
        $arena = $this->findArenaOrFail($id);

        $data = $this->validateArenaData($request);
        $arena->update($data);

        return $arena;
    }

    public function deleteArena(string $id): void
    {
        $arena = $this->findArenaOrFail($id);
        $arena->delete();
    }

    public function validateArenaData(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'street' => 'required|string|max:50',
            'number' => 'required|string|max:50',
            'neighborhood' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'zip_code' => 'required|string|max:50',
            'admin_id' => 'required'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!Admin::find($validated['admin_id'])) {
            throw new AdminNotFoundException();
        }

        if ($user->isAdmin()) {
            $validated['admin_id'] = $user->id;
        }

        return $validated;
    }

    public function findArenaOrFail(string $id): Arena
    {
        $arena = Arena::find($id);
        if (!$arena) {
            throw new ArenaNotFoundException();
        }

        return $arena;
    }
}