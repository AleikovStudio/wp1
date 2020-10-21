<div class="wpm-row">
    <label>
        <input type="hidden" name="page_meta[indicators][individual_indicators]" value="0">
        <input
            type="checkbox"
            id="individual_indicators_checkbox"
            name="page_meta[indicators][individual_indicators]"
            value="1"
            <?php echo wpm_array_get($page_meta, 'indicators.individual_indicators', 0) ? 'checked="checked"' : ''; ?>
        >
        Индивидуальные настройки индикаторов данного материала
    </label>
</div>
<div
    style="padding-left: 25px; <?php echo wpm_array_get($page_meta, 'indicators.individual_indicators', 0) ? '' : 'display:none;'; ?>"
    class="wpm-control-row"
    id="individual_indicators">
    <div class="wpm-row">
        <label>
            <input type="hidden" name="page_meta[indicators][number_show]" value="0">
            <input
                type="checkbox"
                name="page_meta[indicators][number_show]"
                value="1"
                <?php echo wpm_array_get($page_meta, 'indicators.number_show', 0) ? 'checked="checked"' : ''; ?>
            >
            Отображать порядковый номер материала
        </label>
    </div>

    <div class="wpm-row">
        <label>
            <input type="hidden" name="page_meta[indicators][content_type_show]" value="0">
            <input
                type="checkbox"
                name="page_meta[indicators][content_type_show]"
                value="1"
                <?php echo wpm_array_get($page_meta, 'indicators.content_type_show', 0) ? 'checked="checked"' : ''; ?>
            >
            Отображать тип контента
        </label>
    </div>

    <div class="wpm-row">
        <label>
            <input type="hidden" name="page_meta[indicators][homework_status_show]" value="0">
            <input
                type="checkbox"
                name="page_meta[indicators][homework_status_show]"
                value="1"
                <?php echo wpm_array_get($page_meta, 'indicators.homework_status_show', 0) ? 'checked="checked"' : ''; ?>
            >
            Отображать статус ДЗ
        </label>
    </div>

    <div class="wpm-row">
        <label>
            <input type="hidden" name="page_meta[indicators][date_show]" value="0">
            <input
                type="checkbox"
                name="page_meta[indicators][date_show]"
                value="1"
                <?php echo wpm_array_get($page_meta, 'indicators.date_show', 0) ? 'checked="checked"' : ''; ?>
            >
            Отображать дату
        </label>
    </div>

    <div class="wpm-row">
        <label>
            <input type="hidden" name="page_meta[indicators][comments_show]" value="0">
            <input
                type="checkbox"
                name="page_meta[indicators][comments_show]"
                value="1"
                <?php echo wpm_array_get($page_meta, 'indicators.comments_show', 0) ? 'checked="checked"' : ''; ?>
            >
            Отображать комментарии
        </label>
    </div>

    <div class="wpm-row">
        <label>
            <input type="hidden" name="page_meta[indicators][views_show]" value="0">
            <input
                type="checkbox"
                name="page_meta[indicators][views_show]"
                value="1"
                <?php echo wpm_array_get($page_meta, 'indicators.views_show', 0) ? 'checked="checked"' : ''; ?>
            >
            Отображать количество просмотров
        </label>
    </div>

    <div class="wpm-row">
        <label>
            <input type="hidden" name="page_meta[indicators][access_show]" value="0">
            <input
                type="checkbox"
                name="page_meta[indicators][access_show]"
                value="1"
                <?php echo wpm_array_get($page_meta, 'indicators.access_show', 0) ? 'checked="checked"' : ''; ?>
            >
            Отображать доступность
        </label>
    </div>
</div>

<script>
    jQuery(function ($) {
        $('#individual_indicators_checkbox').on('change', function() {
            var $holder = $('#individual_indicators');

            if($(this).prop('checked')) {
                $holder.slideDown();
            } else {
                $holder.slideUp();
            }
        });
    });
</script>