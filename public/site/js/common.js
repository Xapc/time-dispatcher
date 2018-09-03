$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#formControlSelect1').select2({
        placeholder: 'Выберите месяц'
    });
    $('#formControlSelect2').select2({
        placeholder: 'Выберите сотрудников'
    });
    $('[data-toggle=tooltip]').tooltip();
    $('[data-toggle="popover"]').popover()

    $(
        '#formControlSelect1, ' +
        '#formControlSelect2'
    ).change(function (event) {

        event.preventDefault();

        $.ajax({
            url: '/api/data/table',
            method: 'GET',
            cache: false,
            // dataType: 'json',
            data: {
                'monthId': $('#formControlSelect1').val(),
                'accountIds': $('#formControlSelect2').val()
            },
            success: function (result) {
                $('#table').html(result);
                $('#table [data-toggle="popover"]').popover();
            }
        });
    });

    $('#button').on('click', function (event) {
        var monthId = $('#formControlSelect1').val();
        var accountIds = $('#formControlSelect2').val();
        var url = '/api/data/csv?monthId=' + encodeURIComponent(monthId);
        for (var index = 0; index < accountIds.length; index++) {
            url += '&accountIds[]=' + encodeURIComponent(accountIds[index]);
        }
        $(this).attr('href', url);
    });
});
