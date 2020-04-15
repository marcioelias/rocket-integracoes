<?php

namespace App\DataTables;

use App\traits\DatatableTrait;
use App\WebhookCall;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WebhookCallsDataTable extends DataTable
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
            ->addColumn('action', 'webhook_calls.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\WebhookCall $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WebhookCall $model)
    {
        return $model->with('webhook')->orderBy('id', 'desc')->newQuery();
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
                        'class' => 'table table-hover',
                    ])
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('<"row mb-3"<"col-md-4"B><"col-md-8"+\'Webhooks\'+>><"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-8"f>>rt<"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>')
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
            Column::make('transaction_code')->title('Transação'),
            Column::make('webhook.name')->title('Webhook'),
            Column::computed('action')
                  ->title('Ações')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'WebhookCalls_' . date('YmdHis');
    }

    /**
     * Sobrescreve a função padrão de impressão de botões para
     * remover o botão de adicionar, uma vez que esse é um caso de uso
     * somente leitura
     *
     * @return void
     */
    public function getButtons() {
        return [
            Button::make([
                'text' => '<span data-toggle="tooltip" title="Imprimir tabela"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>',
                'extend' => 'print',
            ])
        ];
    }
}
