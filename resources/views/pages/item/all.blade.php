@extends('layouts.layout')

@section('content')
<main>
  <div class="container py-4">
    <header class="pb-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
        <span class="fs-4">Items</span>
      </a>
    </header>

    <div class="p-5 mb-4 bg-light rounded-3">
      <div class="container-fluid">
        <a href="{{ route("items.all") }}" class="btn btn-success mb-3">CREATE AN ITEM</a>
        <div class="row py-3">
            <table id="items_table" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Deleted at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                      <th scope="row">{{ $item->id }}</th>
                      <td>{{ $item->name }}</td>
                      <td>{{ $item->value }}</td>
                      <td>{{ $item->created_at ?? 'NEVER' }}</td>
                      <td>{{ $item->updated_at ?? 'NEVER' }}</td>
                      <td>{{ $item->deleted_at ?? 'NEVER' }}</td>
                      <td><button class="btn btn-primary" onclick="window.location.href = 'items/{{ $item->id }}'">DETAILS</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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