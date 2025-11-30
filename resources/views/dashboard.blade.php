@extends('layouts.app')

@section('title', 'Dashboard - Tontine Parfums')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Dashboard</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Stats Cards -->
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-primary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0">Tontines Actives</span>
                            <h4 class="mb-0 counter">{{ \App\Models\Tontine::where('status', 'active')->count() }}</h4>
                            <i data-feather="briefcase" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-secondary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0">Parfums Disponibles</span>
                            <h4 class="mb-0 counter">{{ \App\Models\Perfume::where('is_available', true)->count() }}</h4>
                            <i data-feather="package" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-success b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0">Mes Inscriptions</span>
                            <h4 class="mb-0 counter">{{ auth()->user()->subscriptions()->count() }}</h4>
                            <i data-feather="users" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-warning b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="media-body">
                            <span class="m-0">Paiements En Attente</span>
                            <h4 class="mb-0 counter">
                                {{ \App\Models\Payment::whereIn('tontine_subscription_id', auth()->user()->subscriptions()->pluck('id'))->where('status', 'pending')->count() }}
                            </h4>
                            <i data-feather="credit-card" class="icon-bg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Tontines -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tontines Récentes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Places</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Tontine::latest()->take(5)->get() as $tontine)
                                <tr>
                                    <td>{{ $tontine->name }}</td>
                                    <td>{{ $tontine->subscriptions->count() }}/{{ $tontine->max_participants }}</td>
                                    <td>
                                        <span class="badge badge-{{ $tontine->status === 'active' ? 'success' : ($tontine->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($tontine->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tontines.show', $tontine) }}" class="btn btn-sm btn-primary">Voir</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune tontine disponible</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Subscriptions -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>Mes Dernières Inscriptions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tontine</th>
                                    <th>Parfums</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(auth()->user()->subscriptions()->with(['tontine', 'items.perfume'])->latest()->take(5)->get() as $subscription)
                                <tr>
                                    <td>{{ $subscription->tontine->name }}</td>
                                    <td>
                                        @foreach($subscription->items->take(2) as $item)
                                            {{ $item->perfume->name }} ({{ $item->quantity }}x)@if(!$loop->last), @endif
                                        @endforeach
                                        @if($subscription->items->count() > 2)
                                            <span class="text-muted">+{{ $subscription->items->count() - 2 }} autre(s)</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucune inscription</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
