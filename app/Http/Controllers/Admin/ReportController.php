<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perfume;
use App\Models\Tontine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tontines = Tontine::orderBy('start_date', 'desc')->get();
        $selectedTontineId = $request->get('tontine_id');

        $query = Perfume::with('supplier')
            ->leftJoin('tontine_subscription_items', 'perfumes.id', '=', 'tontine_subscription_items.perfume_id')
            ->leftJoin('tontine_subscriptions', 'tontine_subscription_items.tontine_subscription_id', '=', 'tontine_subscriptions.id');

        // Filtrer par tontine si une tontine est sélectionnée
        if ($selectedTontineId) {
            $query->where('tontine_subscriptions.tontine_id', $selectedTontineId);
        }

        $perfumes = $query->select(
                'perfumes.id',
                'perfumes.name',
                'perfumes.brand',
                'perfumes.price',
                'perfumes.prix_achat',
                'perfumes.supplier_id',
                DB::raw('COALESCE(SUM(tontine_subscription_items.quantity), 0) as total_ordered')
            )
            ->groupBy('perfumes.id', 'perfumes.name', 'perfumes.brand', 'perfumes.price', 'perfumes.prix_achat', 'perfumes.supplier_id')
            ->get();

        return view('admin.reports.index', compact('perfumes', 'tontines', 'selectedTontineId'));
    }
}
