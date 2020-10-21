<div class="wpm-fileupload">
   <div class="fileupload-buttonbar">
   <div class="fileupload-buttons">
        <label class="wpm_jqhfu-file-container comments-attachment-button file-attachment-button">
            <span class="iconmoon icon-paperclip"></span>
            <input type="file" name="files[]" multiple class="wpm_jqhfu-inputfile">
            <span class="wpm-button wpm-homework-edit-popup-addfile-button help-link"><?php _e('Выбрать файлы', 'mbl'); ?></span>
            <span class="selected-file-name"><?php _e('Файлы не выбраны', 'mbl'); ?></span>
        </label>

        <span class="fileupload-process"></span>
    </div>
    <div class="fileupload-progress wpm_jqhfu-fade" style="display:none;max-width:500px;margin-top:2px;">
        <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-extended">&nbsp;</div>
    </div>
</div>
<div class="wpm_jqhfu-upload-download-table">
<table role="presentation"><tbody class="files"></tbody></table>
</div>
</div>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload wpm_jqhfu-fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <span class="name">{%=file.name%}</span>
            <strong class="error"></strong>
        </td>
        <td>
            <p class="size"><?php _e('Загрузка...', 'mbl'); ?></p>
            <div class="progress"></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="start wpm-button wpm-homework-edit-popup-upload-button" disabled><?php _e('Загрузить', 'mbl'); ?></button>
            {% } %}
            {% if (!i) { %}
                <button class="cancel wpm-button wpm-homework-edit-popup-cancel-button mbr-btn btn-small btn-solid btn-gray btn-bordered"><?php _e('Отмена', 'mbl'); ?></button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download wpm_jqhfu-fade {% if (file.error) { %} wpm_jqhfu-file-error {% } %}">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" rel="wpm_homework_file" class="fancybox"><img src="{%=file.thumbnailUrl%}"></a>
                {% } else { %}
                    <i class="fa fa-{%=file.icon_class%}-o" aria-hidden="true"></i>
                {% } %}
            </span>
        </td>
        <td>
            <span class="name">{%=file.name%}</span>
            {% if (file.error) { %}
                <div><span class="error"><?php _e('Ошибка', 'mbl'); ?></span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            <button class="delete wpm-button wpm-homework-edit-popup-delete-button mbr-btn btn-small btn-solid btn-gray btn-bordered" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}&action=load_ajax_function"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}><?php _e('Удалить', 'mbl'); ?></button>
        </td>
    </tr>
{% } %}
</script>