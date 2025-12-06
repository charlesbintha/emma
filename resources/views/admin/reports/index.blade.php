@extends('layouts.app')

@section('title', 'Rapport des Parfums')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Rapport des Parfums</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item active">Rapports</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Statistiques des Parfums</h5>
                    <span class="text-muted">Vue d'ensemble des ventes et des stocks</span>
                </div>
                <div class="card-body">
                    <!-- Filtre par tontine -->
                    <form method="GET" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label class="form-label" for="tontine_id">Filtrer par Tontine</label>
                                <select name="tontine_id" id="tontine_id" class="form-select">
                                    <option value="">Toutes les tontines</option>
                                    @foreach($tontines as $tontine)
                                        <option value="{{ $tontine->id }}" {{ $selectedTontineId == $tontine->id ? 'selected' : '' }}>
                                            {{ $tontine->name }} - {{ $tontine->start_date->format('d/m/Y') }}
                                            @if($tontine->status === 'active')
                                                <span>(En cours)</span>
                                            @elseif($tontine->status === 'completed')
                                                <span>(Terminée)</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i data-feather="filter"></i> Filtrer
                                </button>
                            </div>
                            @if($selectedTontineId)
                            <div class="col-md-2">
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary w-100">
                                    <i data-feather="x"></i> Réinitialiser
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fournisseur</th>
                                    <th>Catégorie (Marque)</th>
                                    <th>Parfum</th>
                                    <th class="text-center">Nombre Commandé</th>
                                    <th class="text-end">Prix d'Achat</th>
                                    <th class="text-end">Prix de Vente</th>
                                    <th class="text-end">Marge</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalOrdered = 0;
                                    $totalRevenue = 0;
                                    $totalCost = 0;
                                @endphp
                                @forelse($perfumes as $perfume)
                                <tr>
                                    <td>
                                        <strong>{{ $perfume->supplier->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $perfume->brand }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $perfume->name }}</strong>
                                            @if($perfume->total_ordered > 0)
                                                <span class="badge badge-light-success ms-2">
                                                    <i data-feather="trending-up"></i> Populaire
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $perfume->total_ordered > 0 ? 'success' : 'secondary' }}">
                                            {{ $perfume->total_ordered }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if($perfume->prix_achat)
                                            <span class="text-muted">{{ number_format($perfume->prix_achat, 0, ',', ' ') }} FCFA</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($perfume->price, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td class="text-end">
                                        @if($perfume->prix_achat)
                                            @php
                                                $marge = $perfume->price - $perfume->prix_achat;
                                                $margePercentage = $perfume->prix_achat > 0 ? (($marge / $perfume->prix_achat) * 100) : 0;
                                            @endphp
                                            <span class="badge badge-{{ $marge > 0 ? 'success' : 'danger' }}">
                                                {{ number_format($marge, 0, ',', ' ') }} FCFA
                                                ({{ number_format($margePercentage, 1) }}%)
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $totalOrdered += $perfume->total_ordered;
                                    if ($perfume->prix_achat) {
                                        $totalCost += ($perfume->prix_achat * $perfume->total_ordered);
                                    }
                                    if ($perfume->price) {
                                        $totalRevenue += ($perfume->price * $perfume->total_ordered);
                                    }
                                @endphp
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;" class="text-muted mb-2"></i>
                                        <p class="text-muted">Aucun parfum disponible</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($perfumes->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Totaux:</th>
                                    <th class="text-center">
                                        <span class="badge badge-primary">{{ $totalOrdered }}</span>
                                    </th>
                                    <th class="text-end">
                                        <strong>{{ number_format($totalCost, 0, ',', ' ') }} FCFA</strong>
                                    </th>
                                    <th class="text-end">
                                        <strong>{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</strong>
                                    </th>
                                    <th class="text-end">
                                        @php
                                            $totalMarge = $totalRevenue - $totalCost;
                                        @endphp
                                        <strong class="text-{{ $totalMarge > 0 ? 'success' : 'danger' }}">
                                            {{ number_format($totalMarge, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($perfumes->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="package" class="feather-lg text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Parfums</h6>
                            <h4 class="mb-0">{{ $perfumes->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="shopping-cart" class="feather-lg text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Commandé</h6>
                            <h4 class="mb-0">{{ $totalOrdered }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="dollar-sign" class="feather-lg text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Revenu Total</h6>
                            <h4 class="mb-0">{{ number_format($totalRevenue, 0, ',', ' ') }}</h4>
                            <small class="text-muted">FCFA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="trending-up" class="feather-lg text-{{ ($totalRevenue - $totalCost) > 0 ? 'success' : 'danger' }}"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Marge Totale</h6>
                            <h4 class="mb-0 text-{{ ($totalRevenue - $totalCost) > 0 ? 'success' : 'danger' }}">
                                {{ number_format($totalRevenue - $totalCost, 0, ',', ' ') }}
                            </h4>
                            <small class="text-muted">FCFA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
