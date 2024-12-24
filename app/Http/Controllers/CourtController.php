<?php

namespace App\Http\Controllers;

use App\Services\CourtService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourtController
{
    private $courtService;

    public function __construct(CourtService $courtService)
    {
        $this->courtService = $courtService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courts = $this->courtService->getAllCourts();
        return success_response($courts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $court = $this->courtService->save($request);
        return success_response($court, "Court create succesfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $court = $this->courtService->getCourtById($id);
        return success_response($court);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $court = $this->courtService->updateCourt($request, $id);
        return success_response($court);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->courtService->deleteCourt($id);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
