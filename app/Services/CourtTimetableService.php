<?php

namespace App\Services;

use App\Enums\CourtTimetableStatus;
use App\Exceptions\CourtTimetableNotFoundException;
use App\Models\CourtTimetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CourtTimetableService
{
    public function __construct(
        private CourtService $courtService,
        private CourtTimetable $courtTimetable
    ) {}

    public function getCourtTimetables(string $courtId): array
    {
        $court = $this->courtService->getCourtById($courtId);
        return $court->timetables->all();
    }

    public function save(Request $request, string $courtId): CourtTimetable
    {
        $validated = $this->validateTimetableData($request, $courtId);
        $this->checkConflictingTimetable(
            $courtId,
            $validated['date'],
            $validated['end_time'],
            $validated['start_time']
        );

        return CourtTimetable::create($validated);
    }

    public function deleteTimetable(string $timetableId): void
    {
        $timetable = $this->findTimetableOrFail($timetableId);
        $this->authorizeCourtTimetableAccess($timetable);
        $timetable->delete();
    }

    protected function validateTimetableData(Request $request, string $courtId): array
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'status' => 'required|in:available,busy',
        ]);
    
        $validator->after(function ($validator) use ($request) {
            $startTime = $request->input('start_time');
            $endTime = $request->input('end_time');
    
            if (strtotime($endTime) <= strtotime($startTime) && $endTime !== '00:00') {
                $validator->errors()->add('end_time', 'The end time must be longer than the start time or cross midnight.');
            }
        });

        $validatedCourt = $this->courtService->getCourtById($courtId);
        $validator->validate();

        return [
            'court_id' => $validatedCourt->id,
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'status' => CourtTimetableStatus::from($request->input('status')),
        ];
    }

    protected function findTimetableOrFail(string $id): CourtTimetable
    {
        $timetable = CourtTimetable::find($id);
        if (!$timetable) {
            throw new CourtTimetableNotFoundException();
        }

        return $timetable;
    }

    public function checkConflictingTimetable($courtId, $date, $endTime, $startTime)
    {
        $conflictingTimetable = $this->courtTimetable->existsConflictingTimetable($courtId, $date, $endTime, $startTime);

        if ($conflictingTimetable) {
            throw ValidationException::withMessages([
                'start_time' => 'The time entered conflicts with an already scheduled time.',
            ]);
        }
    }

    protected function authorizeCourtTimetableAccess(CourtTimetable $courtTimetable): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->isAdmin() && $courtTimetable->court->arena->admin_id !== $user->id) {
            throw new CourtTimetableNotFoundException();
        }
    }
}
