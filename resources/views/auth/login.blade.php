@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Connexion</h2>
    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            @error('password')<div class="error">{{ $message }}</div>@enderror
        </div>
        <button type="submit">Se connecter</button>
    </form>
</div>
@endsection 