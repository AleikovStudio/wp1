<div class="wpm-audio-block wpm-audio-audio wpm-audio-<?php echo $color; ?> wpm-audio-ws" id="<?php echo $id; ?>">
    <div class="wpm-audio-loader"><?php _e('Подождите, идет загрузка…', 'wpm'); ?></div>
    <div class="wpm-audio-player"></div>
    <div class="wpm-audio-progress">
        <progress class="wpm-audio-loading" value="0" max="100"></progress>
    </div>
    <div class="wpm-audio-buttons_set">
        <button type="button" class="wpm-audio-play"></button>
        <button type="button" class="wpm-audio-stop"></button>
        <button type="button" class="wpm-audio-mute"></button>
        <div class="wpm-audio-time"></div>
        <div class="wpm-audio-duration"></div>
    </div>
</div>