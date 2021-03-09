@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Meine Aufträge</li>
</ol>
<div class="card mb-3">
  <div class="card-header">
    <i class="fas fa-table"></i> Meine Aufträge
  </div>
  <div class="card-body">
    <div class="table-responsive">
        {!! $dataTable->table((['class' => 'table table-bordered table-hover'])) !!}
    </div>
  </div>
</div>
<div class="card" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Total Amount</h5>
    <p class="card-text" style="color:green;">Confirmed: <span style="text-decoration: underline;">{{ $confirmed }} CHF</span></p>
    <p class="card-text" style="color:red;">Not Confirmed: <span style="text-decoration: underline;">{{ $notConfirmed }} CHF</span></p>
  </div>
</div>

@endsection

@section('scripts')
    {!! $dataTable->scripts() !!}
    <script>
       $('.dataTables_filter input[type="search"]').addClass('form-control');
    </script>
@endsection
