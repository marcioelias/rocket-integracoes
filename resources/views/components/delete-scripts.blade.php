<script>
    function confirmDelete(e, self) {
        e.preventDefault();
        let message = $(self).attr('data-message')
        swal({
        title: 'Remover o registro '+message,
        text: "Essa ação não poderá ser desfeita!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Remover',
        padding: '2em'
        }).then(function(result) {
        if (result.value) {
            let url = $(self).attr('href');
            axios.delete(url)
                .then(function(r) {
                    let tbl = window.LaravelDataTables['alter_pagination'];
                    tbl.ajax.reload();
                    swal(
                    'Ok!',
                    'Registro '+message+' foi removido com sucesso.',
                    'success'
                    )
                })
                .catch(function(e) {
                    swal(
                    'Ooops!',
                    e.response.data.message,
                    'error'
                    )
                })
            }
        })
    }
</script>
