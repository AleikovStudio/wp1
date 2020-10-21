<script type="text/javascript">
    function initFileUpload($) {
        var forms = jQuery('.wpm-fileupload'),
            url = '<?php echo admin_url('admin-ajax.php');?>';
        if (forms.length) {
            forms.each(function () {
                var form = jQuery(this),
                    pageId,
                    name;

                pageId = form.data('id');
                name = form.data('name');
                form.fileupload({
                    url        : url,
                    autoUpload : true,
                    formData   : [
                        {name : 'wpm_type[' + name + ']', value : pageId},
                        {name : 'action', value : 'load_ajax_function'}
                    ]
                });
                form.addClass('fileupload-processing');

                reloadFileUpload(form);

                form.bind('reload_files', function() {
                    reloadFileUpload(form);
                })
            });
        }

        function reloadFileUpload(form) {
            var name = form.data('name'),
                data = {
                    action   : "load_ajax_function",
                    wpm_type : {}
                };
            form.find('tbody.files').html('');
            data.wpm_type[name] = form.data('id');
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
            });
        }
    }

    jQuery(function () {
        'use strict';
        initFileUpload(jQuery);
    });
</script>