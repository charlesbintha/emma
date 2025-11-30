@extends('layouts.app')

@section('title', $supplier->name)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ $supplier->name }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Fournisseurs</a></li>
                <li class="breadcrumb-item active">{{ $supplier->name }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Nom</label>
                        <h6>{{ $supplier->name }}</h6>
                    </div>

                    @if($supplier->email)
                    <div class="mb-3">
                        <label class="text-muted">Email</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $supplier->email }}">
                                <i data-feather="mail"></i> {{ $supplier->email }}
                            </a>
                        </p>
                    </div>
                    @endif

                    @if($supplier->phone)
                    <div class="mb-3">
                        <label class="text-muted">Téléphone</label>
                        <p class="mb-0">
                            <a href="tel:{{ $supplier->phone }}">
                                <i data-feather="phone"></i> {{ $supplier->phone }}
                            </a>
                        </p>
                    </div>
                    @endif

                    @if($supplier->address)
                    <div class="mb-3">
                        <label class="text-muted">Adresse</label>
                        <p class="mb-0">
                            <i data-feather="map-pin"></i> {{ $supplier->address }}
                        </p>
                    </div>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted">Date d'ajout</label>
                        <p class="mb-0">{{ $supplier->created_at->format('d F Y') }}</p>
                    </div>

                    @if($supplier->updated_at != $supplier->created_at)
                    <div class="mb-3">
                        <label class="text-muted">Dernière modification</label>
                        <p class="mb-0">{{ $supplier->updated_at->format('d F Y') }}</p>
                    </div>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted">Statistiques</label>
                        <p class="mb-1">
                            <strong>{{ $supplier->perfumes()->count() }}</strong> parfum(s)
                        </p>
                        <p class="mb-0">
                            <strong>{{ $supplier->perfumes()->where('is_available', true)->count() }}</strong> disponible(s)
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary w-100 mb-2">
                            <i data-feather="edit"></i> Modifier
                        </a>
                        <a href="{{ route('perfumes.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-success w-100 mb-2">
                            <i data-feather="plus"></i> Ajouter un parfum
                        </a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ? Tous les parfums associés seront également supprimés.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i data-feather="trash-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Parfums de ce fournisseur</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('perfumes.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-sm btn-primary">
                                <i data-feather="plus"></i> Ajouter un parfum
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($supplier->perfumes()->count() > 0)
                    <div class="row">
                        @foreach($supplier->perfumes as $perfume)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex">
                                        @if($perfume->image_url)
                                            <img src="{{ Storage::url($perfume->image_url) }}"
                                                 alt="{{ $perfume->name }}"
                                                 class="me-3"
                                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div class="bg-light me-3 d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px; border-radius: 8px;">
                                                <i data-feather="package" class="text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $perfume->name }}</h6>
                                            <p class="text-muted mb-1 small">{{ $perfume->brand }}</p>
                                            <h5 class="text-primary mb-2">{{ number_format($perfume->price, 0, ',', ' ') }} FCFA</h5>
                                            <div class="mb-2">
                                                @if($perfume->is_available && $perfume->stock_quantity > 0)
                                                    <span class="badge badge-success small">Disponible</span>
                                                @else
                                                    <span class="badge badge-danger small">Rupture de stock</span>
                                                @endif
                                                <span class="badge badge-light-info small">Stock: {{ $perfume->stock_quantity }}</span>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('perfumes.show', $perfume) }}" class="btn btn-outline-info">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="{{ route('perfumes.edit', $perfume) }}" class="btn btn-outline-primary">
                                                    <i data-feather="edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i data-feather="inbox" style="width: 48px; height: 48px;" class="text-muted mb-2"></i>
                        <p class="text-muted mb-3">Aucun parfum pour ce fournisseur</p>
                        <a href="{{ route('perfumes.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-primary">
                            <i data-feather="plus"></i> Ajouter un parfum
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
