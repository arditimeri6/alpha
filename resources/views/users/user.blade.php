@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Mein Account</li>
</ol>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card mb-3">
                @if (session('userUpdate'))
                    <script>
                        toastr.success('{{ session('userUpdate') }}', {timeOut:5000})
                    </script>
                @endif
                @if (session('userUpdateFail'))
                    <script>
                        toastr.error('{{ session('userUpdateFail') }}', {timeOut:5000})
                    </script>
                @endif
                <div class="card-header">
                    <span style="font-size:25px;"><i class="fas fa-user-alt"></i> Mein Account</span>
                    <a href="{{ route('editProfile', Auth::user()->id) }}" class="btn btn-outline-primary" style="float: right;"><i class="far fa-edit"></i> Change</a>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class=" control-label">Name</label>
                                    <input class="form-control" value="{{ $user->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Vorname</label>
                                    <input class="form-control" value="{{ $user->lastname }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Emailadresse</label>
                                    <input class="form-control" value="{{ $user->email }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">PLZ</label>
                                    <input class="form-control" value="{{ $user->postcode }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Ort</label>
                                    <input class="form-control" value="{{ $user->place }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">AHV Nr. (Sozialversicherungsnummer)</label>
                                    <input class="form-control" value="{{ $user->social_security_number }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Unternehmen</label>
                                    <input class="form-control" value="{{ $user->company }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Unternehmensanschrift (Straße, PLZ, Ort)</label>
                                    <input class="form-control" value="{{ $user->company_address }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class=" control-label">Funktion im Unternehmen</label>
                                    <input class="form-control" value="{{ $user->function_in_the_company }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Telefonnummer Fest</label>
                                    <input class="form-control" value="{{ $user->fix_phone_number }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Telefonnummer Mobil</label>
                                    <input class="form-control" value="{{ $user->mobile_phone_number }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Name der Bank</label>
                                    <input class="form-control" value="{{ $user->bank }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Kontonummer</label>
                                    <input class="form-control" value="{{ $user->account_number }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">IBAN</label>
                                    <input class="form-control" value="{{ $user->iban }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class=" control-label">Karteninhaber</label>
                                    <input class="form-control" value="{{ $user->cardholder }}" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!--panel body-->
            </div><!-- panel -->
        </div><!-- col-xs-12 -->
    </div><!-- row -->
</div>

@endsection
