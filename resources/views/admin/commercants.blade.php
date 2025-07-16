@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Commerçants & Contrats</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Contrats</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commercants as $commercant)
                <tr>
                    <td>{{ $commercant->nom }}</td>
                    <td>{{ $commercant->email }}</td>
                    <td>
                        @if($commercant->contrats->isEmpty())
                            <span class="text-muted">Aucun contrat</span>
                        @else
                            <ul>
                                @foreach($commercant->contrats as $contrat)
                                    <li>
                                        {{ $contrat->type ?? 'Type inconnu' }} - Statut : <strong>{{ $contrat->statut }}</strong>
                                        @if($contrat->statut !== 'approuvé')
                                            <form action="{{ route('admin.contrat.approuver', $contrat->id_contrat) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 