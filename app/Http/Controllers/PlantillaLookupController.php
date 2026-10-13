<?php

namespace App\Http\Controllers;

use App\Models\EmploymentStatus;
use App\Models\BasicInformation;
use App\Models\PlantillaDb;
use Illuminate\Http\Request;

class PlantillaLookupController extends Controller
{
    public function search(Request $request)
    {
        $actor = $request->user();
        abort_unless($actor && in_array($actor->role, ['super_admin', 'admin'], true), 403);

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
        ]);
        $search = trim($validated['q'] ?? '');

        // Accept a six-digit number or a pasted full item number.
        if (! preg_match('/(?:^|\D)(\d{6})(?!\d)/', $search, $matches)) {
            return response()->json([]);
        }
        $number = $matches[1];

        // Reuse the existing relation so no new table or model name is assumed.
        $plantillaModel = (new EmploymentStatus())->plantilla()->getRelated();
        $items = $plantillaModel->newQuery()
            ->where('item_number', 'like', "%{$number}%")
            ->orderBy('item_number')
            ->limit(40)
            ->get(['item_number', 'position_title'])
            ->map(fn ($item) => [
                'value' => (string) $item->item_number,
                'text' => $item->item_number.' - '.$item->position_title,
            ])
            ->values();

        return response()->json($items);
    }

    public function assignments(Request $request, $person)
    {
        $actor = $request->user();
        abort_unless($actor && in_array($actor->role, ['super_admin', 'admin'], true), 403);

        $personnel = BasicInformation::whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        })->with('user.employmentStatus')->findOrFail($person);

        if ($actor->role === 'admin') {
            $schoolId = $actor->employmentStatus?->school_db_id;
            $employeeSchoolId = $personnel->user?->employmentStatus?->school_db_id;
            abort_unless(
                $schoolId !== null && $employeeSchoolId !== null &&
                (string) $schoolId === (string) $employeeSchoolId,
                403
            );
        }

        $validated = $request->validate([
            'item_number' => ['required', 'string', 'max:255'],
        ]);
        $itemNumber = trim($validated['item_number']);
        // Invalid or removed items must not be reported as available.
        abort_unless(PlantillaDb::where('item_number', $itemNumber)->exists(), 404);
        $currentUserId = $personnel->user->id;

        $records = EmploymentStatus::query()
            ->whereHas('plantilla', function ($query) use ($itemNumber) {
                $query->where('item_number', $itemNumber);
            })
            ->whereHas('user', function ($query) use ($currentUserId) {
                $query->whereNull('deleted_at')->where('id', '<>', $currentUserId);
            })
            ->with('user.basicInformation')
            ->get();

        $names = $records->unique('users_id')->map(function ($record) {
            $basic = $record->user?->basicInformation;
            $name = trim(implode(' ', array_filter([
                $basic?->first_name,
                $basic?->middle_name,
                $basic?->last_name,
                $basic?->extension_name,
            ])));
            return $name !== '' ? $name : 'Employee #'.$record->users_id;
        })->sort()->values();

        return response()->json([
            'item_number' => $itemNumber,
            'names' => $names,
            'available' => $names->isEmpty(),
            'checked_at' => now()->toIso8601String(),
        ])->header('Cache-Control', 'no-store, private');
    }
}