<div class="block-holder">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="error" style="display: none"></div>
    <div class="wpm-control-row">
        <label><span><?php _e('Название фильтра', 'wpm'); ?></span>
            <input type="text"
                   name="name"
                   required="required"
                   value="<?php echo wpm_array_get($filter, 'name'); ?>">
        </label>
    </div>
    <div class="wpm-control-row">
        <label><span><?php _e('Интервал времени', 'wpm'); ?></span>
            <select name="time" class="users-level">
                <?php foreach (MBLStatsBlocker::getIntervals() as $interval => $name) : ?>
                    <option
                        value="<?php echo $interval; ?>"
                        <?php echo wpm_array_get($filter, 'interval') == $interval ? 'selected="selected"' : ''; ?>
                    ><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="wpm-control-row">
        <label><span><?php _e('Количество уникальных входов', 'wpm'); ?></span>
            <input type="text"
                   name="entries"
                   data-integers-only
                   required="required"
                   maxlength="6"
                   value="<?php echo wpm_array_get($filter, 'entries', '3'); ?>">
        </label>
    </div>
    <div class="wpm-tab-footer">
        <button type="button"
                class="button button-primary wpm-save-auto-block"><?php _e('Сохранить', 'wpm'); ?></button>
        <?php if ($id) : ?>
            <button type="button"
                    class="button button-danger wpm-remove-auto-block"><?php _e('Удалить', 'wpm'); ?></button>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("[data-integers-only]").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl/cmd+A
                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+C
                (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+X
                (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        $('.wpm-save-auto-block').on('click', function () {
            var $button = $(this);
            var $holder = $button.closest('.block-holder');

            $button.prop('disabled', 'disabled');
            $.post(ajaxurl, {
                action : 'wpm_add_block_filter_action',
                filter : {
                    name    : $holder.find('[name="name"]').val(),
                    time    : $holder.find('[name="time"]').val(),
                    entries : $holder.find('[name="entries"]').val(),
                    id      : $holder.find('[name="id"]').val()
                }
            }, function (data) {
                if (data.success) {
                    location.reload();
                } else {
                    $('.error').show().html(data.error);
                    $button.prop('disabled', false);
                }
            }, "json");

            return false;
        });
        $('.wpm-remove-auto-block').on('click', function () {
            var $button = $(this);
            var $holder = $button.closest('.block-holder');

            $button.prop('disabled', 'disabled');
            $.post(ajaxurl, {
                action : 'wpm_remove_block_filter_action',
                id      : $holder.find('[name="id"]').val()
            }, function () {
               location.reload();
            });

            return false;
        });
    });
</script>

