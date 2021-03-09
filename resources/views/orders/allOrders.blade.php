@extends('layouts.app')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Aufträge</li>
</ol>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this order?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Selected</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the orders you selected
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteSelected">Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to confirm the order?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="confirmOrder">Confirm</button>
      </div>
    </div>
  </div>
</div>
@if (session('orderUpdateAdmin'))
  <script>
    toastr.success('{{ session('orderUpdateAdmin') }}', {timeOut:5000})
  </script>
@endif
@if (session('orderUpdateAdminFail'))
  <script>
    toastr.error('{{ session('orderUpdateAdminFail') }}', {timeOut:5000})
  </script>
@endif
@if (session('successConfirm'))
  <script>
    toastr.success('{{ session('successConfirm') }}', {timeOut:5000})
  </script>
@endif
@if (session('successDelete'))
  <script>
    toastr.success('{{ session('successDelete') }}', {timeOut:5000})
  </script>
@endif
@if (session('orderIsConfirmed'))
  <script>
    toastr.info('{{ session('orderIsConfirmed') }}', {timeOut:5000})
  </script>
@endif
<div class="card mb-3">
  <div class="card-header">
    <i class="fas fa-table"></i> Aufträge
  </div>
  <div class="card-body">
    <div class="table-responsive">
      {!! $dataTable->table((['class' => 'table table-bordered table-hover'])) !!}
    </div>
    <div class="mt-3">
      <button class="btn btn-danger" disabled id="deletecheck"><i class="fas fa-trash"></i> Delete</button>
      <a class="btn btn-outline-success float-right ml-2" href="{{ route('convertExcel') }}"><i class="fas fa-file-excel"></i> Export to Excel</a>
      <a class="btn btn-outline-danger float-right " href="{{ route('convertPdf') }}"><i class="fas fa-file-pdf"></i> Export to PDF</a>
    </div>
  </div>
</div>
<!-- url: '/admin/orders/'+id, -->
@endsection

@section('scripts')
  {!! $dataTable->scripts() !!}
<script>
  var id
  var selectBoxArray = []
  $('#dataTableBuilder').on( 'draw.dt', function () {
    var oldDataArray = {}
    $('.editButton').click(function(){
      var oldData={}
      var orderId = $(this).closest('tr').attr('id')
      var parent = $(this).parent()
      parent.children().hide()
      parent.append(
        '<button class="float-left mr-1 btn btn-sm btn-outline-secondary" id="'+'close'+orderId+'">Close</button>'+
        '<button class="btn btn-sm btn-outline-primary w-50" id="'+'save'+orderId+'">Save</button>'
      )
      /* */
      parent.closest('tr').children().each(function(i,e){
        if(i>0 && i<5){
          if(i==2){
            var text = $(this).text()
            oldData[i] = text
            $(this).text("")
            $(this).append("<input class='form-control datepicker' autocomplete='off' value='"+text+"'/>")
            $( ".datepicker" ).datepicker({
              dateFormat: 'yy-mm-dd'
            })
          }
          else if(i==4){
            var text = $(this).text()
            oldData[i] = text
            $(this).text("")
            $(this).append("<input type='number' class='form-control' value='"+text.substring(0,text.length-4)+"'/>")
          }
          else{
            var text = $(this).text()
            oldData[i] = text
            $(this).text("")
            $(this).append("<input class='form-control' value='"+text+"'/>")
          }
        }
      })
      oldDataArray[orderId] = oldData
      $('#close'+orderId).click(function(){
        var lastIndex = $('#'+orderId).children().length-1;
        $('#'+orderId).children().each(function(i,e){
          if(i>0 && i<5){
              $(this).empty()
              $(this).append(oldDataArray[orderId][i])
          }
          else if(i == lastIndex)
          {
            $('#close'+orderId).remove()
            $('#save'+orderId).remove()
            $(this).children().show()
          }
        })
      })
      var arrayOrder = []
      $('#save'+orderId).click(function(){
        $('#'+orderId).children().each(function(i,e){
          if(i>0 && i<5)
            arrayOrder.push($(this).children().first().val());
        })
        $.ajax({
          url: "/admin/orders/"+orderId+"/update",
          method: "POST",
          data: {
            "_token": '{{ csrf_token() }}',
            "bill_number": arrayOrder[0],
            "date_of_invoice": arrayOrder[1],
            "object": arrayOrder[2],
            "amount": arrayOrder[3]
          },
          success: function (data) {
            $('#dataTableBuilder').DataTable().draw()
            toastr.success('Order Saved', {timeOut:5000})
          },
          error: function(xhr){
            console.log(xhr)
          }
        })
      })
    })

/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    $('.deleteButton').click(function () {
        $('#exampleModal').modal('show')
        id = $(this).parent().parent().attr('id')
    })
    $('.confirmOrderButton').click(function () {
        $('#exampleModal3').modal('show')
        id = $(this).parent().parent().attr('id')
    })


    $('.check').change(function() {
      $('.check:checked').length ? $('#deletecheck').removeAttr('disabled') : $('#deletecheck').attr('disabled',true)
    })
    $('#deletecheck').click(function(){
        selectBoxArray = []
        $.each($('input[name="checkDelete"]:checked'), function(){
            selectBoxArray.push(this.value)
        })
        selectBoxArray.length>0 ? $('#exampleModal2').modal('show') : null;
    })
  })
  $('#confirmOrder').click(function () {
      $.ajax({
          type: 'GET',
          data: { _token: '{{ csrf_token() }}' },
          url: '/admin/orders/'+id+'/confirm',
          success: function (e) {
              $('#dataTableBuilder').DataTable().draw()
              $('#exampleModal3').modal('hide')
              toastr.success('Order Confirmed', {timeOut:5000})
          },
          error: function (e) { console.log(e) }
      })
  })
  $('#confirmDelete').click(function () {
      $.ajax({
          type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          url: '/admin/orders/'+id,
          success: function (e) {
              $('#dataTableBuilder').DataTable().draw()
              $('#exampleModal').modal('hide')
              toastr.success('Order Deleted', {timeOut:5000})
          },
          error: function (e) { console.log(e) }
      })
  })
  $('#confirmDeleteSelected').click(function(){
      $.ajax({
          type : 'DELETE',
          data: { _token:'{{ csrf_token() }}'},
          url : '/admin/deleteorders/'+selectBoxArray,
          success: function(e){
              $('#dataTableBuilder').DataTable().draw()
              $('#exampleModal2').modal('hide')
              toastr.success('Selected Orders Deleted', {timeOut:5000})
          },
          error: function(e){console.log(e)}
      })
  })
  $('.dataTables_filter input[type="search"]').addClass('form-control');
</script>
@endsection
