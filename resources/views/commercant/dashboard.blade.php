@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="container">
    <h2>Tableau de bord Commerçant</h2>
    <p>Bienvenue sur votre espace commerçant EcoDeli.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3>Créer un nouveau produit</h3>
    <form action="{{ route('commercant.produits.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="prix">Prix</label>
            <input type="number" step="0.01" name="prix" id="prix" class="form-control">
        </div>
        <div class="form-group">
            <label for="quantite">Quantité</label>
            <input type="number" name="quantite" id="quantite" class="form-control" value="0">
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Créer</button>
    </form>

    <h3 class="mt-4">Mes produits</h3>
    @if($produits->isEmpty())
        <p>Aucun produit trouvé.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produits->where('affiche', 1) as $produit)
                <tr>
                    <form action="{{ route('commercant.produits.update', $produit->id_produits) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <td><input type="text" name="nom" value="{{ $produit->nom }}" class="form-control" required></td>
                        <td><textarea name="description" class="form-control">{{ $produit->description }}</textarea></td>
                        <td><input type="number" step="0.01" name="prix" value="{{ $produit->prix }}" class="form-control"></td>
                        <td><input type="number" name="quantite" value="{{ $produit->quantite }}" class="form-control"></td>
                        <td>
                            @if($produit->image_url)
                                <img src="{{ $produit->image_url }}" alt="Image produit" style="max-width: 100px; max-height: 100px;">
                            @endif
                            <input type="file" name="image" class="form-control mt-1">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-success btn-sm mb-1">Modifier</button>
                    </form>
                    <form action="{{ route('commercant.produits.toggleAffiche', $produit->id_produits) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm mb-1">
                            Retirer de l'affichage
                        </button>
                    </form>
                    <form action="{{ route('commercant.produits.delete', $produit->id_produits) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3 class="mt-4">Produits retirés de la vente</h3>
    @php $produitsRetires = $produits->where('affiche', 0); @endphp
    @if($produitsRetires->isEmpty())
        <p>Aucun produit retiré.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produitsRetires as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->description }}</td>
                    <td>{{ $produit->prix }}</td>
                    <td>{{ $produit->quantite }}</td>
                    <td>
                        @if($produit->image_url)
                            <img src="{{ $produit->image_url }}" alt="Image produit" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('commercant.produits.toggleAffiche', $produit->id_produits) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm mb-1">Remettre en vente</button>
                        </form>
                        <form action="{{ route('commercant.produits.delete', $produit->id_produits) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
