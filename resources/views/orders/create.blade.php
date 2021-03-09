@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Auftrag eingeben</li>
</ol>
    <div class="col-md-6 offset-md-3">
    <div class="card mb-3">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    toastr.error('{{ $error }}', {timeOut:5000})
                </script>
            @endforeach
        @endif
        @if (session('orderCreate'))
            <script>
                toastr.success('{{ session('orderCreate') }}', {timeOut:5000})
            </script>
        @endif
        @if (session('orderCreateFail'))
            <script>
                toastr.error('{{ session('orderCreateFail') }}', {timeOut:5000})
            </script>
        @endif
        <!-- <div class="panel-header text-center"> <h3>Create Order</h3> </div> -->
        <div class="card-header">
            <span style="font-size:20px;"><i class="fas fa-plus-circle"></i> Auftrag eingeben</span>
        </div>
        <div class="card-body">
                <form action="{{ route('storeOrder') }}" method="post" class="needs-validation" novalidate>
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class=" control-label">Rechnungsnummer*</label>
                        <input class="form-control" type="text" name="bill_number" required>
                        <div class="invalid-feedback">
                            This field is required
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label">Rechnungsdatum</label>
                        <input class="form-control" type="text" id="datepicker" name="date_of_invoice" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class=" control-label">Objekt*</label>
                        <input class="form-control" type="text" name="object" required>
                        <div class="invalid-feedback">
                            This field is required
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label">Betrag exkl. MwSt</label>
                        <input class="form-control" type="number" placeholder="CHF" name="amount">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-primary form-control" >Create</button>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
