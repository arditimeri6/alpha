@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('usersList') }}">Kunden</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('details', $user->id) }}">User Details</a>
    </li>
    <li class="breadcrumb-item active">Edit Profile</li>
</ol>
<div class="col-md-10 offset-md-1">
    <!-- <div class="panel panel-default panel-info p-3 editProfile"> -->
    <div class="card mb-3">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        <script>
                            toastr.error('{{ $error }}', {timeOut:5000})
                        </script>
                    </li>
                @endforeach
            </ul>
        @endif
        <!-- <div class="panel-header text-center"> <h3>Edit Profile</h3> </div> -->
        <div class="card-header">
            <span style="font-size:25px;"><i class="fas fa-user-edit"></i> Edit Profile</span>
        </div>
        <!-- <div class="panel-body"> -->
        <div class="card-body">

            <!-- <div class="form-horizontal"> -->
                <form action="{{route('updateUserProfile', $user->id)}}" method="post" class="needs-validation" novalidate>
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class=" control-label">Name</label>
                                <input class="form-control" type="text" name="name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Vorname</label>
                                <input class="form-control" type="text" name="lastname" value="{{ $user->lastname }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Emailadresse</label>
                                <input class="form-control" type="email" name="email" value="{{ $user->email }}" required>
                                <div class="invalid-feedback">
                                    The email is required
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label">PLZ</label>
                                <input class="form-control" type="text" name="postcode" value="{{ $user->postcode }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Ort</label>
                                <input class="form-control" type="text" name="place" value="{{ $user->place }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">AHV Nr. (Sozialversicherungsnummer)</label>
                                <input class="form-control" type="text" name="social_security_number" value="{{ $user->social_security_number }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Unternehmen</label>
                                <input class="form-control" type="text" name="company" value="{{ $user->company }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Unternehmensanschrift (Straße, PLZ, Ort)</label>
                                <input class="form-control" type="text" name="company_address" value="{{ $user->company_address }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class=" control-label">Funktion im Unternehmen</label>
                                <input class="form-control" type="text" name="function_in_the_company" value="{{ $user->function_in_the_company }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Telefonnummer Fest</label>
                                <input class="form-control" type="text" name="fix_phone_number" value="{{ $user->fix_phone_number }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Telefonnummer Mobil</label>
                                <input class="form-control" type="text" name="mobile_phone_number" value="{{ $user->mobile_phone_number }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Name der Bank</label>
                                <input class="form-control" type="text" name="bank" value="{{ $user->bank }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Kontonummer</label>
                                <input class="form-control" type="text" name="account_number" value="{{ $user->account_number }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">IBAN</label>
                                <input class="form-control" type="text" name="iban" value="{{ $user->iban }}">
                            </div>
                            <div class="form-group">
                                <label class=" control-label">Karteninhaber</label>
                                <input class="form-control" type="text" name="cardholder" value="{{ $user->cardholder }}">
                            </div>
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-outline-primary form-control">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            <!-- </div> -->
        </div>
    </div>
</div>
@endsection
