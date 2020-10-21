<?php $avatar = wpm_get_avatar($user->ID, 150, null, false) ?>
<?php $hasAvatar = !empty($avatar) && strpos($avatar,'gravatar.com') === false ?>
<table class="form-table">
    <tr>
        <th><label for="avatar">Аватар</label></th>
        <td>
            <input type="hidden" id="avatar" name="avatar"
                   value="<?php echo $avatar; ?>"
                   class="wide">

            <div class="wpm-avatar-preview-wrap">
                <div class="wpm-avatar-preview-box">
                    <img src="<?php echo $avatar; ?>" class="thumbnail" alt="" id="wpm-avatar-preview">
                </div>
            </div>
            <div class="wpm-control-row">
                <p>
                    <button type="button" class="wpm-media-upload-button button"
                            data-id="avatar"><?php _e('Загрузить', 'mbl'); ?></button>
                    &nbsp;&nbsp; <a id="delete-wpm-avatar"
                                    class="wpm-delete-media-button button submit-delete"
                                    data-id="avatar"<?php if (!$hasAvatar) echo 'style="display:none"'; ?>><?php _e('Удалить', 'wpm'); ?></a>
                </p>
            </div>
        </td>
    </tr>
</table>