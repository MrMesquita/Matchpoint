<?php

namespace App\Services;

use App\Exceptions\CourtNotFoundException;
use App\Exceptions\AdminNotFoundException;
use App\Models\Admin;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtService
{
    public function getAllCourts()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSystem()) {
            return Court::all();
        }

        if ($user->isAdmin()) {
            return $this->getAdminCourts($user);
        }

        return collect();
    }

    public function save(Request $request): Court
    {
        $validated = $this->validateCourtData($request);
        return Court::create($validated);
    }

    public function getCourtById(string $id): Court
    {
        $court = $this->findCourtOrFail($id);
        $this->authorizeCourtAccess($court);
        return $court;
    }

    public function updateCourt(Request $request, string $id): Court
    {
        $court = $this->findCourtOrFail($id);
        $this->authorizeCourtAccess($court);
        $data = $this->validateCourtData($request);
        $court->update($data);
        return $court;
    }

    public function deleteCourt(string $id): void
    {
        $court = $this->findCourtOrFail($id);
        $this->authorizeCourtAccess($court);
        $court->delete();
    }

    protected function validateCourtData(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'arena_id' => 'required|exists:arenas,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $validated['arena_id'] = $this->getAdminArenaId($user);
        }

        return $validated;
    }

    protected function getAdminCourts($user)
    {
        $admin = Admin::find($user->id);
        return $admin ? $admin->arenas()->with('courts')->get()->pluck('courts')->flatten() : collect();
    }

    protected function authorizeCourtAccess(Court $court): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSystem()) {
            return;
        }

        if ($user->isAdmin() && $court->arena->admin_id !== $user->id) {
            throw new CourtNotFoundException();
        }
    }

    protected function getAdminArenaId($user)
    {
        $admin = Admin::find($user->id);
        if ($admin && $admin->arenas->isNotEmpty()) {
            return $admin->arenas->first()->id;
        }

        throw new AdminNotFoundException();
    }

    protected function findCourtOrFail(string $id): Court
    {
        $court = Court::find($id);
        if (!$court) {
            throw new CourtNotFoundException();
        }

        return $court;
    }
}
