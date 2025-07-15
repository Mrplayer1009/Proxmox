@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Validation des livreurs</h2>
    @if(session('success'))<div class="mb-4 text-green-600">{{ session('success') }}</div>@endif
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Pièce justificative</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livreurs as $livreur)
                <tr>
                    <td>{{ $livreur->id_livreur }}</td>
                    <td>{{ $livreur->id_utilisateur }}</td>
                    <td>
                        @if($livreur->pieces_justificatives)
                            <a href="{{ asset('storage/' . $livreur->pieces_justificatives) }}" target="_blank" class="text-blue-600 underline">Voir le document</a>
                        @else
                            <span class="text-gray-500">Aucun</span>
                        @endif
                    </td>
                    <td>{{ $livreur->statut_validation }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.livreurs.changer_statut', $livreur->id_livreur) }}" class="inline">
                            @csrf
                            <select name="statut_validation" class="border rounded px-2 py-1">
                                <option value="validé">Valider</option>
                                <option value="refusé">Refuser</option>
                            </select>
                            <button type="submit" class="ml-2 px-3 py-1 bg-blue-600 text-white rounded">OK</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 