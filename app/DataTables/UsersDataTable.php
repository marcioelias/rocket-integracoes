<?php

namespace App\DataTables;

use App\traits\DatatableTrait;
use App\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    use DatatableTrait;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'users.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('alter_pagination')
                    ->setTableAttributes([
                        'class' => 'table table-hover'
                    ])
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('<"row mb-3"<"col"B>><"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-8"f>>rt<"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>')
                    ->orderBy(0, 'asc')
                    ->parameters($this->getParameters())
                    ->buttons($this->getButtons());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Nome'),
            Column::make('email')->title('E-mail'),
            Column::make('username')->title('Usuário'),
            Column::make('active')
                ->render('(data) ? \'<a href="JavaScript: $.post(\\\'/json/users/\'+full.id+\'/active/toggle\\\', function() {window.LaravelDataTables[\\\'alter_pagination\\\'].draw();});" data-toggle="tooltip" title="Ativar/Desativar"><i class="far fa-check-square fa-lg text-success"></i></a>\' : \'<a href="JavaScript: $.post(\\\'/json/users/\'+full.id+\'/active/toggle\\\', function() {window.LaravelDataTables[\\\'alter_pagination\\\'].draw();});" data-toggle="tooltip" title="Ativar/Desativar"><i class="far fa-square fa-lg text-danger"></i></a>\'')
                ->title('Ativo'),
            Column::computed('action')
                  ->title('Ações')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Users_' . date('YmdHis');
    }
}
