@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Kunden</li>
</ol>
<div class="card mb-3">
  <div class="card-header">
    <i class="fas fa-users"></i> Kunden
  </div>
  <div class="card-body">
    <div class="table-responsive">
      {!! $dataTable->table((['class' => 'table table-bordered table-hover'])) !!}
    </div>
  </div>
</div>

@endsection

@section('scripts')
  {!! $dataTable->scripts() !!}
  <script>
    $('.dataTables_filter input[type="search"]').addClass('form-control');
  </script>
@endsection
