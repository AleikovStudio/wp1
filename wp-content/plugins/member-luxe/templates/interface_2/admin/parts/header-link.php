<div class="wpm-control-row">
    <label>Ссылка<br>
        <input type="text"
               class="large-text"
               name="main_options[header_bg][<?php echo $name; ?>][link]"
               value="<?php echo wpm_get_option('header_bg.' . $name . '.link'); ?>"
        >
    </label>
</div>
<div class="wpm-control-row">
    <label>Открывать в:</label>
    <label><input type="radio"
                  name="main_options[header_bg][<?php echo $name; ?>][link_target]"
                  value="self"
                 <?php echo wpm_option_is('header_bg.' . $name . '.link_target', 'self') ? 'checked' : ''; ?>
        >текущем окне
    </label>
    &nbsp;
    <label><input type="radio"
                  name="main_options[header_bg][<?php echo $name; ?>][link_target]"
                  value="blank"
                 <?php echo wpm_option_is('header_bg.' . $name . '.link_target', 'blank', 'blank') ? 'checked' : ''; ?>
        >новом окне
    </label>
</div>
