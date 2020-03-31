<form id="deleteForm{{$id}}" action="{{ route($route.'.destroy', $id) }}" method="POST" style="display: inline">
    <span data-toggle="modal" data-target="#deleteConfirmation" data-title="Confirmação de Exculsão" data-message="Confirmar a exculsão do registro: {{ $id.' - '.$name }}? ">
        <a href="javascript: void(0);" data-toggle="tooltip" title="Remover"  class="table-cancel">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
        </a>
    </span>
    <input type="hidden" name="_method" value="DELETE">
    @csrf
</form>
