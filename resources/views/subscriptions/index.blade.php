@extends('layouts.app')

@section('title', 'Mes Inscriptions')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Mes Inscriptions aux Tontines</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item active">Mes Inscriptions</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Liste de mes inscriptions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tontine</th>
                                    <th>Parfum</th>
                                    <th>Date d'inscription</th>
                                    <th>Statut</th>
                                    <th>Paiements</th>
                                    <th>Progression</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>
                                        <strong>{{ $subscription->tontine->name }}</strong><br>
                                        <small class="text-muted">{{ number_format($subscription->totalAmount(), 0, ',', ' ') }} FCFA</small>
                                    </td>
                                    <td>
                                        @foreach($subscription->items as $item)
                                            <div class="d-flex align-items-center mb-2">
                                                @if($item->perfume->image_url)
                                                    <img src="{{ Storage::url($item->perfume->image_url) }}"
                                                         alt="{{ $item->perfume->name }}"
                                                         class="me-2"
                                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @endif
                                                <div>
                                                    {{ $item->perfume->name }} ({{ $item->quantity }}x)<br>
                                                    <small class="text-muted">{{ $item->perfume->brand }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ $subscription->subscription_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($subscription->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($subscription->status === 'completed')
                                            <span class="badge badge-secondary">Complétée</span>
                                        @else
                                            <span class="badge badge-danger">Annulée</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $paid = $subscription->payments->where('status', 'paid')->count();
                                            $total = $subscription->payments->count();
                                            $pending = $subscription->payments->where('status', 'pending')->count();
                                            $late = $subscription->payments->where('status', 'late')->count();
                                        @endphp
                                        <span class="badge badge-light-success">{{ $paid }} payés</span>
                                        @if($pending > 0)
                                            <span class="badge badge-light-warning">{{ $pending }} en attente</span>
                                        @endif
                                        @if($late > 0)
                                            <span class="badge badge-light-danger">{{ $late }} en retard</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $percentage = $total > 0 ? ($paid / $total) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 8px; min-width: 80px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ round($percentage) }}%</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('subscriptions.show', $subscription) }}"
                                           class="btn btn-sm btn-primary btn-action"
                                           title="Voir détails">
                                            <i data-feather="eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;" class="text-muted mb-2"></i>
                                        <p class="text-muted">Vous n'êtes inscrit à aucune tontine pour le moment</p>
                                        <a href="{{ route('tontines.index') }}" class="btn btn-primary">
                                            <i data-feather="search"></i> Découvrir les tontines
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
