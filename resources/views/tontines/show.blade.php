@extends('layouts.app')

@section('title', $tontine->name)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ $tontine->name }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('tontines.index') }}">Tontines</a></li>
                <li class="breadcrumb-item active">{{ $tontine->name }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Informations Générales</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Statut</label>
                        <div>
                            @if($tontine->status === 'pending')
                                <span class="badge badge-warning">En attente</span>
                            @elseif($tontine->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($tontine->status === 'completed')
                                <span class="badge badge-secondary">Complétée</span>
                            @else
                                <span class="badge badge-danger">Annulée</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Date de début</label>
                        <p><strong>{{ $tontine->start_date->format('d F Y') }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Mode de paiement</label>
                        <p><strong>4 tranches mensuelles</strong></p>
                        <small class="text-muted">Le montant est calculé selon les parfums que vous commandez</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Participants</label>
                        <p><strong>{{ $tontine->subscriptions->count() }} / {{ $tontine->max_participants }}</strong></p>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width: {{ ($tontine->subscriptions->count() / $tontine->max_participants) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    @if(!$tontine->isFull() && in_array($tontine->status, ['pending', 'active']))
                        @php
                            $userSubscription = $tontine->subscriptions->where('user_id', auth()->id())->first();
                        @endphp
                        @if(!$userSubscription)
                            <a href="{{ route('subscriptions.create', $tontine) }}" class="btn btn-primary w-100">
                                <i data-feather="user-plus"></i> S'inscrire à cette tontine
                            </a>
                        @else
                            <div class="alert alert-info">
                                <i data-feather="check"></i> Vous êtes déjà inscrit à cette tontine
                            </div>
                            <a href="{{ route('subscriptions.show', $userSubscription) }}" class="btn btn-outline-primary w-100">
                                Voir mon inscription
                            </a>
                        @endif
                    @elseif($tontine->isFull())
                        <div class="alert alert-warning">
                            <i data-feather="users"></i> Cette tontine est complète
                        </div>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <hr>
                        <div class="mt-3">
                            <a href="{{ route('tontines.edit', $tontine) }}" class="btn btn-secondary w-100 mb-2">
                                <i data-feather="edit"></i> Modifier
                            </a>
                            @if($tontine->status === 'pending')
                                <form action="{{ route('tontines.activate', $tontine) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 mb-2">
                                        <i data-feather="play"></i> Activer la tontine
                                    </button>
                                </form>
                            @endif
                            @if($tontine->status === 'active')
                                <form action="{{ route('tontines.complete', $tontine) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-info w-100 mb-2">
                                        <i data-feather="check-circle"></i> Marquer comme complétée
                                    </button>
                                </form>
                            @endif
                            @if(in_array($tontine->status, ['pending', 'active']))
                                <form action="{{ route('tontines.cancel', $tontine) }}" method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette tontine ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i data-feather="x-circle"></i> Annuler la tontine
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Liste des Participants</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Participant</th>
                                    <th>Commande</th>
                                    <th>Date d'inscription</th>
                                    <th>Statut</th>
                                    <th>Paiements</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tontine->subscriptions->load('items.perfume') as $subscription)
                                <tr>
                                    <td>
                                        {{ $subscription->user->name }}
                                        @if($subscription->user_id === auth()->id())
                                            <span class="badge badge-light-primary">Vous</span>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($subscription->items->take(2) as $item)
                                            {{ $item->perfume->name }} ({{ $item->quantity }}x)@if(!$loop->last), @endif
                                        @endforeach
                                        @if($subscription->items->count() > 2)
                                            <br><small class="text-muted">+{{ $subscription->items->count() - 2 }} autre(s)</small>
                                        @endif
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
                                            $paidCount = $subscription->payments->where('status', 'paid')->count();
                                            $totalCount = $subscription->payments->count();
                                        @endphp
                                        <span class="badge badge-light-info">{{ $paidCount }} / {{ $totalCount }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucun participant pour le moment</td>
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
