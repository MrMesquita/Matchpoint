<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReservationController
{
    public function __construct(
        private ReservationService $reservationService
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = $this->reservationService->getAllReservations();
        return success_response($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reservation = $this->reservationService->save($request);
        return success_response($reservation, "Reservation created successfully", Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $reservation)
    {
        $reservation = $this->reservationService->getReservationById($reservation);
        return success_response($reservation);
    }

    /**
     * Admin confirm a pending reservation.
     */
    public function confirmReservation(string $reservation)
    {
        $reservation = $this->reservationService->confirmReservation($reservation);
        return success_response($reservation, "Reservation confirmed successfully", Response::HTTP_OK);
    }

    /**
     * Admin or customer disable a pending reservation.
     */
    public function destroy(string $reservation)
    {
        $reservation = $this->reservationService->cancelReservation($reservation);
        return success_response($reservation, "Reservation canceled successfully", Response::HTTP_OK);
    }
}
