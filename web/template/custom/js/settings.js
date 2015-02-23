$(document).ready(function () {
    var $form = $('form#setting_form'),
        $parentSetting = $form.find('#setting_id'),
        $inheritables = $form.find('input[data-type=inheritable]'),
        $inheritOptions = $form.find('.inherit-block'),
        $radios = $form.find('input[data-type=inherit-handler]');
    /**********************************************************************/
    $parentSetting.change(function () {
        var $current = $(this);
        var activateRadio = parseInt($current.val().length > 0 ? 1 : 0);
        if (!activateRadio) {
            $inheritOptions.addClass('hidden');
        } else {
            $inheritOptions.removeClass('hidden');
        }
        $radios.each(function () {
            $(this).prop('checked', $current.val() == activateRadio);
        });
        $inheritables.change();
    }).change();
    /**********************************************************************/
    $inheritables.change(function () {
        var $current = $(this),
            $pTarget = $('p[data-target=' + $current.attr('id') + ']'),
            condition = $current.attr('type') === 'checkbox' ? $current.is(':checked') : $current.val().length > 0;
        if ($parentSetting.val().length > 0) {
            if (condition) {
                $pTarget.find('input[value=0]').prop('checked', true);
                $pTarget.find('input[value=1]').prop('checked', false);
            } else {
                $pTarget.find('input[value=0]').prop('checked', false);
                $pTarget.find('input[value=1]').prop('checked', true);
            }
        } else {
            $pTarget.find('input[value=0]').prop('checked', true);
        }
    }).change();
});