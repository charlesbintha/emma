@extends('layouts.app')

@section('title', 'Tontines')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Tontines</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item active">Tontines</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Liste des Tontines</h5>
                    @if(auth()->user()->isAdmin())
                    <div class="card-header-right">
                        <a href="{{ route('tontines.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i> Nouvelle Tontine
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complétée</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Date Début</th>
                                    <th>Participants</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tontines as $tontine)
                                <tr>
                                    <td><strong>{{ $tontine->name }}</strong></td>
                                    <td>{{ $tontine->start_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-light-primary">
                                            {{ $tontine->subscriptions_count }} / {{ $tontine->max_participants }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($tontine->status === 'pending')
                                            <span class="badge badge-warning">En attente</span>
                                        @elseif($tontine->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($tontine->status === 'completed')
                                            <span class="badge badge-secondary">Complétée</span>
                                        @else
                                            <span class="badge badge-danger">Annulée</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-action" role="group">
                                            <a href="{{ route('tontines.show', $tontine) }}" class="btn btn-sm btn-info btn-action" title="Voir">
                                                <i data-feather="eye"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin())
                                                @if($tontine->status === 'pending')
                                                    <form action="{{ route('tontines.activate', $tontine) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success btn-action" title="Activer">
                                                            <i data-feather="play"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('tontines.edit', $tontine) }}" class="btn btn-sm btn-primary btn-action" title="Modifier">
                                                    <i data-feather="edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune tontine disponible</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $tontines->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
