<?php

namespace App\Http\Controllers;

use App\Services\CourtTimetableService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourtTimetableController
{
    public function __construct(
        private CourtTimetableService $courtTimetableService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(string $courtId)
    {
        $timetables = $this->courtTimetableService->getCourtTimetables($courtId);
        return success_response($timetables);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $courtId)
    {
        $timetable = $this->courtTimetableService->save($request, $courtId);
        return success_response($timetable, "Timetable created successfully", Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $court, string $timetable)
    {
        $this->courtTimetableService->deleteTimetable($timetable);
        return success_response(null, null, Response::HTTP_NO_CONTENT);
    }
}
