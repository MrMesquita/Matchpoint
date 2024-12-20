<?php

namespace App\Services;

use App\Models\Arena;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ArenaService
{
    public function getAllArenas()
    {
        return Arena::all();
    }

    public function createArena(Request $request, string $adminID): Arena
    {
        $data = $this->validateArenaData($request);
        $arena = new Arena([...$data, 'admin_id' => $adminID]);

        $arena->save();

        return $arena;
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
        return $request->validate([
            'name' => 'required|string|max:50',
            'street' => 'required|string|max:50',
            'number' => 'required|string|max:50',
            'neighborhood' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'zip_code' => 'required|string|max:50'
        ]);
    }

    public function findArenaOrFail(string $id): Arena
    {
        $arena = Arena::find($id);
        if (!$arena) {
            throw new NotFoundResourceException('Arena not found', Response::HTTP_NOT_FOUND);
        }

        return $arena;
    }
}