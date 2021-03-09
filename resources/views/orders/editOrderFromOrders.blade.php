@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('getAllOrders') }}">Aufträge</a>
    </li>
    <li class="breadcrumb-item active">Edit Order</li>
</ol>
    <div class="col-md-4 offset-md-4">
    <div class="card mb-3">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    toastr.error('{{ $error }}', {timeOut:5000})
                </script>
            @endforeach
        @endif
        <!-- <div class="panel-header text-center"> <h3>Create Order</h3> </div> -->
        <div class="card-header">
            <span style="font-size:20px;"><i class="fas fa-pen"></i> Edit Order</span>
        </div>
        <div class="card-body">
                <form action="/admin/orders/{{ $order->id }}/update" method="post" class="needs-validation" novalidate>
                    {{ csrf_field() }}
                    @method('PATCH')
                    <div class="form-group">
                        <label class=" control-label">Rechnungsnummer*</label>
                        <input class="form-control" type="text" name="bill_number" value="{{ $order->bill_number }}" required>
                        <div class="invalid-feedback">
                            This field is required
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label">Rechnungsdatum</label>
                        <input class="form-control" type="text" id="datepicker" name="date_of_invoice" value="{{ $order->date_of_invoice }}" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class=" control-label">Objekt*</label>
                        <input class="form-control" type="text" name="object" value="{{ $order->object }}" required>
                        <div class="invalid-feedback">
                            This field is required
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label">Betrag exkl. MwSt</label>
                        <input class="form-control" type="number" placeholder="CHF" name="amount" value="{{ $order->amount }}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-primary form-control" >Update</button>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
