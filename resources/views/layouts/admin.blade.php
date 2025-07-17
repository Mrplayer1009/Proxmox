@extends('layouts.app')
<script src="https://cdn.tailwindcss.com"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin EcoDeli</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.prestataires') }}">Prestataires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.livreurs.validation') }}">Livreurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.commercants') }}">Commerçants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.batiments') }}">Bâtiments</a>
                </li>
            </ul>
        </div>
    </div>
</nav>