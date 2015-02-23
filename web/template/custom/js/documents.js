$(document).ready(function () {
    /**
     *
     */
    $('a.refresher').click(function () {
        var $target = $('#' + $(this).data('target')),
            url = $target.data('type');
        $(this).find('i.fa.fa-refresh').addClass('fa-spin');
        refreshData($target, ('/' + url + '/'));
    })
        .click();
    /**
     *
     */
    $('select#ot_release_id').change(function () {
        var $option = $("option:selected", this),
            $startDate = $('#date_start'),
            $dueDate = $('#date_due');
        $startDate.val($option.data('date-start'));
        $dueDate.val($option.data('date-due'));
        $startDate.addClass('automatic-completion');
        $dueDate.addClass('automatic-completion');
    });
    /**
     * Get the data asynchronously
     * @param $selector
     * @param url
     */
    function refreshData($selector, url) {
        var selected_id = $selector.data('current');
        $.ajax({
            url: url,
            method: 'get',
            success: function (data) {
                $selector.parent('p').find('i.fa.fa-spin').removeClass('fa-spin');
                $selector.find('.addbyajax').remove();
                $.each(data, function (k, item) {
                    $selector.append(
                        '<option class="addbyajax" ' + (selected_id == item.id ? 'selected="selected"' : '')
                        + 'value="' + item.id + '"' +
                        (item.date_start != undefined ? 'data-date-start="' + item.date_start + '"' : '') +
                        (item.date_due != undefined ? 'data-date-due="' + item.date_due + '"' : '') +
                        '>' + item.name + '</option>'
                    )
                    ;
                });
            }
        });
    }
});