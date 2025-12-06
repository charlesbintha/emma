<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TontineResource;
use App\Models\Tontine;
use Illuminate\Http\Request;

class TontineController extends Controller
{
    /**
     * Display a listing of tontines
     */
    public function index(Request $request)
    {
        $query = Tontine::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // By default, show only active and pending tontines
            $query->whereIn('status', ['active', 'pending']);
        }

        $tontines = $query->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => TontineResource::collection($tontines)
        ]);
    }

    /**
     * Display the specified tontine with payment schedule
     */
    public function show($id)
    {
        $tontine = Tontine::find($id);

        if (!$tontine) {
            return response()->json([
                'success' => false,
                'message' => 'Tontine not found'
            ], 404);
        }

        $data = (new TontineResource($tontine))->toArray(request());

        // Add payment schedule
        $paymentDates = $tontine->getPaymentDueDates();
        $data['payment_schedule'] = collect($paymentDates)->map(function ($date, $index) {
            return [
                'number' => $index + 1,
                'due_date' => $date->format('Y-m-d'),
                'description' => 'Paiement ' . ($index + 1) . ' sur 4'
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
