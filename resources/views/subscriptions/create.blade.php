@extends('layouts.app')

@section('title', 'S\'inscrire à ' . $tontine->name)

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>S'inscrire à une Tontine</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('tontines.index') }}">Tontines</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tontines.show', $tontine) }}">{{ $tontine->name }}</a></li>
                <li class="breadcrumb-item active">S'inscrire</li>
            </ol>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Colonne Gauche : Sélection des parfums -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="text-white">Détails de la Tontine</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Nom :</strong> {{ $tontine->name }}</p>
                            <p><strong>Description :</strong> {{ $tontine->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date de début :</strong> {{ $tontine->start_date->format('d F Y') }}</p>
                            <p><strong>Statut :</strong>
                                <span class="badge badge-{{ $tontine->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($tontine->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i data-feather="info"></i>
                        <strong>Comment ça marche ?</strong><br>
                        1. Sélectionnez les parfums que vous souhaitez commander<br>
                        2. Indiquez la quantité pour chaque parfum<br>
                        3. Votre montant total sera divisé en <strong>4 tranches mensuelles</strong><br>
                        4. Vous recevrez vos parfums après paiement complet
                    </div>
                </div>
            </div>

            <!-- Liste des parfums disponibles -->
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sélectionnez vos parfums</h5>
                    <span class="badge badge-primary">{{ $perfumes->total() }} parfum(s)</span>
                </div>
                <div class="card-body">
                    <!-- Barre de recherche -->
                    <form method="GET" action="{{ route('subscriptions.create', $tontine) }}" class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i data-feather="search"></i>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Rechercher un parfum par nom, marque...">
                            <button type="submit" class="btn btn-primary">
                                Rechercher
                            </button>
                            @if(request('search'))
                                <a href="{{ route('subscriptions.create', $tontine) }}" class="btn btn-secondary">
                                    <i data-feather="x"></i>
                                </a>
                            @endif
                        </div>
                    </form>

                    @if(request('search'))
                        <div class="alert alert-info mb-3">
                            <i data-feather="info"></i>
                            Résultats pour "<strong>{{ request('search') }}</strong>" : {{ $perfumes->total() }} parfum(s) trouvé(s)
                        </div>
                    @endif

                    <div class="row">
                        @forelse($perfumes as $perfume)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 perfume-card">
                                <div class="card-body">
                                    @if($perfume->image_url)
                                        <img src="{{ Storage::url($perfume->image_url) }}"
                                             alt="{{ $perfume->name }}"
                                             class="img-fluid rounded mb-3"
                                             style="max-height: 150px; object-fit: cover; width: 100%;">
                                    @endif

                                    <h6 class="mb-2">{{ $perfume->name }}</h6>
                                    <p class="text-muted small mb-2">{{ $perfume->brand }}</p>
                                    <p class="text-primary h5 mb-3">{{ number_format($perfume->price, 0, ',', ' ') }} FCFA</p>

                                    <p class="small mb-3">
                                        <i data-feather="package"></i>
                                        <strong>Stock :</strong> {{ $perfume->stock_quantity }} unités
                                    </p>

                                    <form action="{{ route('subscriptions.cart.add', $tontine) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="perfume_id" value="{{ $perfume->id }}">

                                        <div class="input-group mb-2">
                                            <input type="number"
                                                   class="form-control"
                                                   name="quantity"
                                                   value="1"
                                                   min="1"
                                                   max="{{ $perfume->stock_quantity }}"
                                                   required>
                                            <button type="submit" class="btn btn-primary">
                                                <i data-feather="shopping-cart"></i> Ajouter
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i data-feather="alert-circle"></i>
                                @if(request('search'))
                                    Aucun parfum trouvé pour "<strong>{{ request('search') }}</strong>".
                                    <a href="{{ route('subscriptions.create', $tontine) }}">Voir tous les parfums</a>
                                @else
                                    Aucun parfum disponible pour le moment.
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($perfumes->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $perfumes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Panier -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-success">
                    <h5 class="text-white">
                        <i data-feather="shopping-cart"></i> Mon Panier
                    </h5>
                </div>
                <div class="card-body">
                    @if(!empty($cart))
                        <div class="cart-items mb-3">
                            @php $total = 0; @endphp
                            @foreach($cart as $perfumeId => $item)
                                @php $total += $item['subtotal']; @endphp
                                <div class="cart-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $item['name'] }}</h6>
                                            <small class="text-muted">{{ $item['brand'] }}</small>
                                        </div>
                                        <form action="{{ route('subscriptions.cart.remove', [$tontine, $perfumeId]) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Retirer">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <form action="{{ route('subscriptions.cart.update', [$tontine, $perfumeId]) }}"
                                              method="POST"
                                              class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number"
                                                   name="quantity"
                                                   value="{{ $item['quantity'] }}"
                                                   min="1"
                                                   max="100"
                                                   class="form-control form-control-sm"
                                                   style="width: 60px;"
                                                   onchange="this.form.submit()">
                                            <span class="ms-2">× {{ number_format($item['price'], 0, ',', ' ') }}</span>
                                        </form>
                                        <strong>{{ number_format($item['subtotal'], 0, ',', ' ') }} FCFA</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="cart-summary">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total :</strong>
                                <strong class="text-primary h5">{{ number_format($total, 0, ',', ' ') }} FCFA</strong>
                            </div>

                            <div class="alert alert-info small mb-3">
                                <strong>Paiement en 4 tranches :</strong><br>
                                <i data-feather="arrow-right"></i> {{ number_format($total / 4, 0, ',', ' ') }} FCFA / mois
                            </div>

                            <form action="{{ route('subscriptions.store', $tontine) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i data-feather="check-circle"></i> Valider ma commande
                                </button>
                            </form>

                            <form action="{{ route('subscriptions.cart.clear', $tontine) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i data-feather="x"></i> Vider le panier
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i data-feather="shopping-cart" style="width: 48px; height: 48px;" class="text-muted mb-2"></i>
                            <p class="text-muted">Votre panier est vide</p>
                            <small>Ajoutez des parfums pour commencer</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.perfume-card {
    transition: all 0.3s;
    border: 2px solid #e0e0e0;
}
.perfume-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
    border-color: var(--bs-primary);
}
.cart-item {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
}
</style>
@endpush
