<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class AdminService
{
    public function getAllAdmins()
    {
        return Admin::all();
    }

    public function createAdmin(Request $request)
    {
        $data = $this->validateAdminData($request);
        return $this->storeAdmin($data);
    }

    public function getAdminById(string $id)
    {
        return $this->findAdminOrFail($id);
    }

    public function updateAdmin(Request $request, string $id)
    {
        $admin = $this->findAdminOrFail($id);

        $data = $this->validateAdminData($request, $admin);
        $this->updateAdminData($admin, $data);

        return $admin;
    }

    public function deleteAdmin(string $id)
    {
        $admin = $this->findAdminOrFail($id);

        $this->deleteAdminRecord($admin);
    }

    private function validateAdminData(Request $request, Admin $admin = null): array
    {
        $uniquePhoneRule = $admin
            ? Rule::unique('users')->ignore($admin->id)
            : 'unique:users';

        $uniqueEmailRule = $admin
            ? Rule::unique('users')->ignore($admin->id)
            : 'unique:users';

        return $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => ['required', $uniquePhoneRule],
            'email' => ['required', 'email', $uniqueEmailRule],
            'password' => 'required|string|min:6'
        ]);
    }

    private function storeAdmin(array $data): Admin
    {
        $data['password'] = Hash::make($data['password']);
        return Admin::create($data);
    }

    private function findAdminOrFail(string $id): Admin
    {
        $admin = Admin::find($id);
        if (!$admin) {
            throw new NotFoundResourceException('Admin not found', 404);
        }

        return $admin;
    }

    private function updateAdminData(Admin $admin, array $data): void
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $admin->update($data);
    }

    private function deleteAdminRecord(Admin $admin): void
    {
        $admin->delete();
    }
}
