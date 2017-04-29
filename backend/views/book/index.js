$('#book-dlg').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var team_id = button.data('team');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    //var modal = $(this);
    $('#book-team_id').val(team_id);
    $('#book-name').val('');
});

$('#btn-save').click(function () {
    $.post($('#book-form').attr('action'),$('#book-form').serialize(),function () {
        
    });
});