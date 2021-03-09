<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Order;
use Illuminate\Support\Facades\Auth;

class OrderListDataTable extends DataTable
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
            ->editColumn('confirmed', '@if( $confirmed == null) Not Confirmed @else Confirmed @endif')
            ->setRowClass('{{ $confirmed == " Not Confirmed " ? "text-danger" : "text-success" }}')
            ->editColumn('amount', '{{$amount}} CHF')
            ->setRowId('{{ $id }}');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        $user = Auth::user()->id;
        return $model->newQuery()->where('owner',$user)->select('id', 'bill_number', 'date_of_invoice', 'object', 'amount', 'confirmed', 'created_at');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters($this->getBuilderParameters());
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
            'created_at'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'OrderList_' . date('YmdHis');
    }
}
