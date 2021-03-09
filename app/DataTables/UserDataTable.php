<?php

namespace App\DataTables;

use App\User;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class UserDataTable extends DataTable
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
            ->addColumn('action', '<a href="{{ route("details", $id) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-info-circle"> Details</i></a>');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->where('id', '!=', Auth::user()->id)->get();
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
                        ['data' => 'id', 'title' => 'Nr.'],
                        ['data' => 'name', 'title' => 'Name'],
                        ['data' => 'lastname', 'title' => 'Vorname'],
                        ['data' => 'email', 'title' => 'Emailadresse'],
                        ['data' => 'company', 'title' => 'Unternehmen'],
                        ['data' => 'created_at', 'title' => 'Created At'],
                    ])
                    ->minifiedAjax()
                    ->addAction(['width' => '80px'])
                    ->parameters([
                        'dom'          => 'Bfrtip',
                        'buttons'      => ['export', 'print', 'reset', 'reload'],
                        'initComplete' => "function () {
                            $('<tr id=\"inputRow\"><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>')
                            .appendTo($(this).find('thead'))
                            this.api().columns([0,1,2,3,4,5]).every(function (i) {
                                var column = this;
                                var input = document.createElement(\"input\");
                                input.setAttribute('class', 'form-control');
                                $(input).appendTo($('#inputRow').children('th')[i])
                                .on('change', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            });
                        }"
                    ,]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'name',
            'lastname',
            'email',
            'company',
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
        return 'User_' . date('YmdHis');
    }
}
