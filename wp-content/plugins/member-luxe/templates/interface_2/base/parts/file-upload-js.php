<script type="text/javascript">
    function initFileUpload() {
        var forms = jQuery('.wpm-fileupload'),
            url = '<?php echo admin_url('admin-ajax.php');?>';
        if (forms.length) {
            forms.each(function () {
                var form = $(this),
                    pageId,
                    name;

                pageId = form.closest('form').data('id');
                name = form.closest('form').data('name');
                form.fileupload({
                    url        : url,
                    autoUpload : true,
                    formData   : [
                        {name : 'wpm_type[' + name + ']', value : pageId},
                        {name : 'action', value : 'load_ajax_function'}
                    ],
                    started : function () {
                        changeFileUploadText(form);
                    },
                    change : function () {
                        changeFileUploadText(form);
                    },
                    always : function () {
                        changeFileUploadText(form);
                    },
                    destroyed : function () {
                        changeFileUploadText(form);
                    }
                });
                form.addClass('fileupload-processing');

                reloadFileUpload(form);

                form.bind('reload_files', function() {
                    reloadFileUpload(form);
                });

                form.bind('delete_files', function () {
                    var destroyed = 0,
                        total = form.find('button.delete').length;

                    if(total) {
                        form.fadeOut('fast');

                        form.bind('destroyed', function () {
                            if (++destroyed >= total) {
                                form.unbind('destroyed');
                                form.show();
                                reloadFileUpload(form);
                            }
                        });

                        form.find('button.delete').each(function () {
                            $(this).click();
                        })
                    }
                });

                form.on('click', '.wpm_jqhfu-file-error button.delete', function() {
                    var row = $(this).closest('.wpm_jqhfu-file-error');

                    row.fadeOut('fast', function() {
                        row.remove();
                        form.trigger('destroyed');
                    });

                    return false;
                })
            });
        }

        function reloadFileUpload(form) {
            var name = form.closest('form').data('name'),
                data = {
                    action   : "load_ajax_function",
                    wpm_type : {}
                };
            form.find('tbody.files').html('');
            data.wpm_type[name] = form.closest('form').data('id');
            jQuery.ajax({
                url             : url,
                data            : data,
                acceptFileTypes : /(\.|\/)(gif|jpeg|jpg|png|pdf|zip|rar|mp3|mp4|wmv|doc|docx|xls|xlsx|ppt|pptx|pages|numbers|keynote)$/i,
                dataType        : 'json',
                context         : form[0]
            }).always(function () {
                jQuery(this).removeClass('fileupload-processing');
            }).done(function (result) {
                jQuery(this).fileupload('option', 'done')
                    .call(this, jQuery.Event('done'), {result : result});
                changeFileUploadText(form);
            });
        }
    }
    
    function changeFileUploadText(form) {
        var length = form.find('tr.wpm_jqhfu-fade').length,
            textElem = form.find('.selected-file-name');

        if(length > 0) {
            textElem.hide();
        } else {
            textElem.show();
        }
    }

    jQuery(function () {
        'use strict';
        initFileUpload();
    });
</script>