<a
    href="#"
    <?php echo $key ? '' : 'style="display:none;"'; ?>
    class="wpm-crop-media-button button"
    data-action="wpm_crop_action"
    data-croppable="wpm_<?php echo $id; ?>"
    data-orig="wpm_<?php echo $id; ?>_original"
    data-image="wpm-<?php echo $id; ?>-preview"
    data-width="<?php echo $w; ?>"
    data-height="<?php echo $h; ?>"
    data-save-title="Сохранить"
    data-cancel-title="Отменить"
    data-preview-title="Предпросмотр"
    data-id="<?php echo $id; ?>">Редактировать</a>