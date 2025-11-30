@extends('layouts.app')

@section('title', 'Gestion des Fournisseurs')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Gestion des Fournisseurs</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item active">Fournisseurs</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Liste des fournisseurs</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                 Nouveau fournisseur
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('suppliers.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Rechercher un fournisseur (nom, email, téléphone...)"
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i data-feather="search"></i> Rechercher
                                </button>
                                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                                    <i data-feather="x"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Suppliers Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Adresse</th>
                                    <th>Parfums</th>
                                    <th>Date d'ajout</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                <tr>
                                    <td><strong>{{ $supplier->id }}</strong></td>
                                    <td>
                                        <strong>{{ $supplier->name }}</strong>
                                    </td>
                                    <td>
                                        @if($supplier->email)
                                            <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($supplier->phone)
                                            <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($supplier->address)
                                            {{ Str::limit($supplier->address, 40) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">
                                            {{ $supplier->perfumes()->count() }} parfum(s)
                                        </span>
                                    </td>
                                    <td>{{ $supplier->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-action" role="group">
                                            <a href="{{ route('suppliers.show', $supplier) }}"
                                               class="btn btn-sm btn-outline-info btn-action"
                                               title="Voir détails">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}"
                                               class="btn btn-sm btn-outline-primary btn-action"
                                               title="Modifier">
                                                <i data-feather="edit"></i>
                                            </a>
                                            <form action="{{ route('suppliers.destroy', $supplier) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ? Tous les parfums associés seront également supprimés.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Supprimer">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;" class="text-muted mb-2"></i>
                                        <p class="text-muted">Aucun fournisseur trouvé</p>
                                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                            <i data-feather="plus"></i> Ajouter un fournisseur
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
