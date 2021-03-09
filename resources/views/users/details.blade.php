@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('usersList') }}">Kunden</a>
    </li>
    <li class="breadcrumb-item active">User Details</li>
</ol>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
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
                @if (session('adminUserUpdate'))
                    <script>
                        toastr.success('{{ session('adminUserUpdate') }}', {timeOut:5000})
                    </script>
                @endif
                @if (session('adminUserUpdateFail'))
                    <script>
                        toastr.error('{{ session('adminUserUpdateFail') }}', {timeOut:5000})
                    </script>
                @endif
                @if (session('orderUpdate'))
                    <script>
                        toastr.success('{{ session('orderUpdate') }}', {timeOut:5000})
                    </script>
                @endif
                @if (session('orderUpdateFail'))
                    <script>
                        toastr.error('{{ session('orderUpdateFail') }}', {timeOut:5000})
                    </script>
                @endif
                <div class="card-header">
                    <span style="font-size:25px;"><i class="fas fa-user-alt"></i> {{ $user->name }}'s Profile</span>
                    <a href="{{ route('editUserProfile', $user->id) }}" class="btn btn-outline-primary" style="float: right;"><i class="far fa-edit"></i> Change</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Vorname</th>
                                <td>{{ $user->lastname }}</td>
                            </tr>
                            <tr>
                                <th>Emailadresse</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Email confirmed</th>
                                <td>
                                    @if($user->email_verified_at == null)
                                        <span style="color:red">Not Confirmed </span>
                                    @else
                                        <span style="color:green">Confirmed</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>PLZ</th>
                                <td>{{ $user->postcode }}</td>
                            </tr>
                            <tr>
                                <th>Ort</th>
                                <td>{{ $user->place }}</td>
                            </tr>
                            <tr>
                                <th>AHV Nr. (Sozialversicherungsnummer)</th>
                                <td>{{ $user->social_security_number }}</td>
                            </tr>
                            <tr>
                                <th>Unternehmen</th>
                                <td>{{ $user->company }}</td>
                            </tr>
                            <tr>
                                <th>Unternehmensanschrift (Straße, PLZ, Ort)</th>
                                <td>{{ $user->company_address }}</td>
                            </tr>
                            <tr>
                                <th>Funktion im Unternehmen</th>
                                <td>{{ $user->function_in_the_company }}</td>
                            </tr>
                            <tr>
                                <th>Telefonnummer Fest</th>
                                <td>{{ $user->fix_phone_number }}</td>
                            </tr>
                            <tr>
                                <th>Telefonnummer Mobil</th>
                                <td>{{ $user->mobile_phone_number }}</td>
                            </tr>
                            <tr>
                                <th>Name der Bank</th>
                                <td>{{ $user->bank }}</td>
                            </tr>
                            <tr>
                                <th>Kontonummer</th>
                                <td>{{ $user->account_number }}</td>
                            </tr>
                            <tr>
                                <th>IBAN</th>
                                <td>{{ $user->iban }}</td>
                            </tr>
                            <tr>
                                <th>Karteninhaber</th>
                                <td>{{ $user->cardholder }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $user->created_at }} ({{ $user->created_at->diffForHumans() }})</td>
                            </tr>

                        </table>
                    </div><!--tab panel-->
                </div><!--panel body-->
            </div><!-- panel -->
        </div><!-- col-xs-12 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <span style="font-size:25px;"><i class="fas fa-table"></i> {{ $user->name }}'s Orders </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="userOrderList" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Rechnungsnummer</th>
                                <th>Rechnungsdatum</th>
                                <th>Objekt</th>
                                <th>Betrag exkl. MwSt</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card mb-3" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Total Amount</h5>
                            <p class="card-text" style="color:green;">Confirmed: <span style="text-decoration: underline;">{{ $confirmed }} CHF</span></p>
                            <p class="card-text" style="color:red;">Not Confirmed: <span style="text-decoration: underline;">{{ $notConfirmed }} CHF</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- row -->
</div>
@endsection

@section('scripts')
  <script>
    $(document).ready( function () {
      $('#userOrderList').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('userOrders', $user->id) !!}',
        columns: [
          { data: 'bill_number', name: 'bill_number' },
          { data: 'date_of_invoice', name: 'date_of_invoice' },
          { data: 'object', name: 'object' },
          { data: 'amount', name: 'amount' },
          { data: 'confirmed_order', name: 'confirmed' },
          { data: 'created_at', name: 'created_at' },
          { data: 'actions', name: 'actions' },
        ],
        language: { search: "" },
      });
      $('.dataTables_filter input[type="search"]').addClass('form-control');
    });
  </script>
@endsection
