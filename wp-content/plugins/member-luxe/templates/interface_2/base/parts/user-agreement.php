<div class="user-agreement">
    <label>
        <input name="user_agreement" type="checkbox" class="user_agreement_input" tabindex="91" />
        &nbsp;<?php _e('Принимаю', 'wpm'); ?>
        <a class="link"
           data-toggle="modal"
           data-target="#wpm_user_agreement"><?php echo wpm_get_option('user_agreement.registration_link_title', __('пользовательское соглашение', 'wpm')); ?></a>
    </label>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        $('#wpm_user_agreement_reject').on('click', function(){
            $('#wpm_user_agreement').modal('hide');
            $('.user_agreement_input').prop('checked', false);
            $('.register-submit').prop('disabled', true);

            return false;
        });
        $('#wpm_user_agreement_accept').on('click', function(){
            $('#wpm_user_agreement').modal('hide');
            $('.user_agreement_input').prop('checked', true);
            $('.register-submit').prop('disabled', false);

            return false;
        });
        $('.user_agreement_input').on('change', function() {
            var $this = $(this),
                $form = $this.closest('form');
            $form.find('.register-submit').prop('disabled', !$this.prop('checked'));
        });
    });
</script>