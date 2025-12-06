<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PerfumeResource;
use App\Models\Perfume;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    /**
     * Display a listing of perfumes
     */
    public function index(Request $request)
    {
        $query = Perfume::with('supplier');

        // Filter by search (name or brand)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Filter by supplier
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by availability
        if ($request->has('available')) {
            if ($request->available == 'true' || $request->available == '1') {
                $query->available();
            }
        }

        $perfumes = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => PerfumeResource::collection($perfumes),
            'pagination' => [
                'current_page' => $perfumes->currentPage(),
                'last_page' => $perfumes->lastPage(),
                'per_page' => $perfumes->perPage(),
                'total' => $perfumes->total(),
            ]
        ]);
    }

    /**
     * Display the specified perfume
     */
    public function show($id)
    {
        $perfume = Perfume::with('supplier')->find($id);

        if (!$perfume) {
            return response()->json([
                'success' => false,
                'message' => 'Perfume not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new PerfumeResource($perfume)
        ]);
    }
}
