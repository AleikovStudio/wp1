<div class="wpm-control-row">
    <label><?php echo $label; ?><br>
        <input type="text"
               name="<?php echo isset($main) ? 'main_options' : 'design_options' ?>[<?php echo implode('][', explode('.', $key)); ?>]"
               class="color"
               value="<?php echo isset($main) ? wpm_get_option($key, strtoupper($default)) : wpm_get_design_option($key, strtoupper($default)); ?>">
    </label>
</div>