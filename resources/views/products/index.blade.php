@extends('layouts.app')

@push('styles')
    <link href="{{ asset('template/plugins/table/datatable/datatables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/assets/css/components/custom-modal.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="container-fluid">
        {{$dataTable->table()}}
    </div>
    <div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancelar</button>
                    <button class="btn btn-primary" data-dismiss="modal" id="confirm">Excluir</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="{{ asset('template/plugins/highlight/highlight.pack.js') }}"></script>
{{$dataTable->scripts()}}
<script>
$('#deleteConfirmation').on('show.bs.modal', function (e) {
    $message = $(e.relatedTarget).attr('data-message');
    $(this).find('.modal-body p').text($message);
    $title = $(e.relatedTarget).attr('data-title');
    $(this).find('.modal-title').text($title);

    // Pass form reference to modal for submission on yes/ok
    var form = $(e.relatedTarget).closest('form');
    $(this).find('.modal-footer #confirm').data('form', form);
});

$('#deleteConfirmation').find('.modal-footer #confirm').on('click', function(){
    $(this).data('form').submit();
});
</script>

@endpush
