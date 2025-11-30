@extends('layouts.app')

@section('title', 'Parfums')

@push('styles')
<style>
.perfume-card {
    transition: transform 0.3s;
}
.perfume-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
.perfume-image {
    height: 200px;
    object-fit: cover;
}
</style>
@endpush

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>Catalogue de Parfums</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item active">Parfums</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Liste des Parfums</h5>
                    @if(auth()->user()->isAdmin())
                    <div class="card-header-right">
                        <a href="{{ route('perfumes.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i> Ajouter un Parfum
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher un parfum..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="supplier_id" class="form-select">
                                    <option value="">Tous les fournisseurs</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="available" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Disponibles uniquement</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i data-feather="search"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Perfumes Grid -->
                    <div class="row">
                        @forelse($perfumes as $perfume)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card perfume-card h-100">
                                @if($perfume->image_url)
                                    <img src="{{ Storage::url($perfume->image_url) }}" class="card-img-top perfume-image" alt="{{ $perfume->name }}">
                                @else
                                    <div class="card-img-top perfume-image bg-light d-flex align-items-center justify-content-center">
                                        <i data-feather="package" class="feather-xl text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $perfume->name }}</h5>
                                    <p class="text-muted mb-2">{{ $perfume->brand }}</p>
                                    <p class="card-text text-truncate">{{ Str::limit($perfume->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="text-primary mb-0">{{ number_format($perfume->price, 0, ',', ' ') }} FCFA</h4>
                                    </div>
                                    <div class="mb-2">
                                        @if($perfume->is_available && $perfume->stock_quantity > 0)
                                            <span class="badge badge-success">
                                                <i data-feather="check-circle"></i> En stock ({{ $perfume->stock_quantity }})
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i data-feather="x-circle"></i> Rupture de stock
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('perfumes.show', $perfume) }}" class="btn btn-outline-primary btn-sm">
                                            <i data-feather="eye"></i> Voir détails
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('perfumes.edit', $perfume) }}" class="btn btn-outline-secondary btn-sm">
                                                <i data-feather="edit"></i> Modifier
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i data-feather="info"></i> Aucun parfum disponible pour le moment
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $perfumes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
