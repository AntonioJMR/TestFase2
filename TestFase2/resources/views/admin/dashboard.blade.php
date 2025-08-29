@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bienvenido al Panel de Administración</h1>
        <p>Este es el dashboard para administradores.</p>
        <a href="{{ route('logout') }}" class="btn btn-danger">Cerrar Sesión</a>
    </div>
@endsection