<div class="wpm-row">
    <label>
        <input
            type="radio"
            value="1"
            name="main_options[interface_version]"
            <?php echo wpm_option_is('interface_version', 1, 1) ? 'checked' : ''; ?>
            >Интерфейс 1.0<br>
    </label>
    <br>
    <label>
        <input
            type="radio"
            value="2"
            name="main_options[interface_version]"
            <?php echo wpm_option_is('interface_version', 2, 1) ? 'checked' : ''; ?>
            >Интерфейс 2.0<br>
    </label>
</div>

<?php wpm_render_partial('settings-save-button', 'common'); ?>