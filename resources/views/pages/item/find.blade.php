@extends('layouts.layout')

@section('content')
<main>
  <div class="container py-4">
    <header class="pb-3 mb-4 border-bottom">
      <a href="{{ route("items.all") }}" class="d-flex align-items-center text-dark text-decoration-none">
        <span class="fs-4">Items</span>
      </a>
    </header>

    <div class="p-5 mb-4 bg-light rounded-3">
      <div class="container-fluid">
      <a href="{{ route("items.all") }}" class="btn btn-primary mb-3">Back</a>
      
        <div class="card">
          <h5 class="card-header">{{ $item->id ." - ". $item->name }}</h5>
          <div class="card-body">
            <p class="card-text">Value: {{ $item->value }}</p>
            <p class="card-text">Created at: {{ $item->created_at ?? 'NEVER' }}</p>
            <p class="card-text">Updated at: {{ $item->updated_at ?? 'NEVER' }}</p>
            <p class="card-text">Deleted at: {{ $item->deleted_at ?? 'NEVER' }}</p>
          </div>
        </div>

      </div>
    </div>
    <footer class="pt-3 mt-4 text-muted border-top">
      © 2022
    </footer>
  </div>
</main>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@endsection