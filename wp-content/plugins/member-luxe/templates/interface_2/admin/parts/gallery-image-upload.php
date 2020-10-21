<input type="hidden"
       id="wpm_<?php echo $id; ?>"
       name="<?php echo $name; ?>"
       value="<?php echo $value; ?>"
       class="wide">
<div class="wpm-control-row">
    <p>
        <button type="button" class="wpm-media-upload-button button"
                data-id="<?php echo $id; ?>"><?php _e('Загрузить', 'wpm'); ?></button>
        &nbsp;&nbsp; <a id="delete-wpm-favicon"
                        class="wpm-delete-media-button button submit-delete"
                        data-id="<?php echo $id; ?>"><?php _e('Удалить', 'wpm'); ?></a>
    </p>
</div>
<div class="wpm-<?php echo $id; ?>-preview-wrap">
    <div class="wpm-<?php echo $id; ?>-preview-box <?php echo isset($previewClass) ? $previewClass :''; ?>">
        <img
            src="<?php echo $value; ?>"
            title=""
            alt=""
            id="wpm-<?php echo $id; ?>-preview">
    </div>
</div>