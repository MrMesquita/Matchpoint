<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return success_response($this->adminService->getAllAdmins());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = $this->adminService->createAdmin($request);
        return success_response($admin, null, Response::HTTP_CREATED);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = $this->adminService->getAdminById($id);
        return success_response($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = $this->adminService->updateAdmin($request, $id);
        return success_response($admin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->adminService->deleteAdmin($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
