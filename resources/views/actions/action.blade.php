<x-datatable.btn-edit route="actions" :id="$id" />
<x-datatable.btn-delete route="actions" :id="$id" :name="$model->product->name.' / '.$model->event->name" />
