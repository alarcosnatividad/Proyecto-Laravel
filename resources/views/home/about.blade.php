@extends('layouts.app')
@section('titulo', $viewData["title"])
@section('subtitulo', $viewData["subtitle"])

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-lg-4 ms-auto">
            <p class="lead">{{ $viewData["description"] }}</p>
        </div>
        <div class="col-lg-4 me-auto">
            <p class="lead">{{ $viewData["autor"] }}</p>
        </div>
    </div>
</div>
@endsection