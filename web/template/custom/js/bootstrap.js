$(document).ready(function () {
    $('[data-toggle=tooltip]').tooltip();
    
    $('.modal').modal({backdrop: 'static', keyboard: false, show: false})
        .on('show.bs.modal', function (e) {
            $(this).find('#continue').data('href', $(e.relatedTarget).attr('href'));
        })
        .one('click', '#continue', function () {
            window.location.replace($(this).data('href'));
        });
});