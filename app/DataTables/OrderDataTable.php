<?php

namespace App\DataTables;

use App\Order;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('amount', '{{$amount}} CHF')
            ->editColumn('confirmed', '@if( $confirmed == null) Not Confirmed @else Confirmed @endif')
            ->addColumn('action', '
                        <button type="submit" title="Delete" class="btn btn-sm btn-outline-danger deleteButton"><i class="fas fa-trash-alt"></i></button>
                        <button title="Edit" class="btn btn-sm btn-outline-secondary editButton"><i class="fas fa-pen"></i></button>
                        @if( $confirmed == null)
                            <button title="Confirm" class="btn btn-sm btn-outline-success confirmOrderButton"><i class="fas fa-check-circle"></i></button>
                        @endif
                    ')
            ->addColumn('check', '<input type="checkbox" class="check " name="checkDelete" value="{{ $id }}">')
            ->rawColumns(['action', 'check'])
            ->setRowId('{{ $id }}')
            ->setRowClass('{{ $confirmed == " Not Confirmed " ? "text-danger" : "text-success" }}');
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        return $model->newQuery()->select('id', 'bill_number', 'date_of_invoice', 'object', 'amount', 'confirmed', 'created_at');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns([
                        ['data' => 'check', 'title' => '', 'orderable' => false, 'className'=> 'text-center'],
                        ['data' => 'bill_number', 'title' => 'Rechnungsnummer'],
                        ['data' => 'date_of_invoice', 'title' => 'Rechnungsdatum'],
                        ['data' => 'object', 'title' => 'Objekt'],
                        ['data' => 'amount', 'title' => 'Betrag exkl. MwSt'],
                        ['data' => 'confirmed', 'title' => 'Status'],
                        ['data' => 'created_at', 'title' => 'Created At'],
                        ['data' => 'action', 'title' => 'Action', 'orderable' => false]
                    ])
                    ->minifiedAjax()
                    ->parameters([
                        'initComplete' => "function () {
                            $('<tr class=\"columnSearch\" id=\"inputRow\"><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>')
                            .appendTo($(this).find('thead'))
                            this.api().columns([1,2,3,4,6]).every(function (i) {
                                var column = this;
                                var input = document.createElement(\"input\");
                                input.setAttribute('class', 'form-control');
                                $(input).appendTo($('#inputRow').children('th')[i])
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                        }"
                    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'bill_number',
            'date_of_invoice',
            'object',
            'amount',
            'confirmed',
            'created_at',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Order_' . date('YmdHis');
    }
}
