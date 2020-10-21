<script type="text/javascript">
	function initFileUpload() {
		var form = jQuery('#fileupload'),
			url = '<?php echo admin_url('admin-ajax.php');?>',
			pageId;
		if (form.length) {
			pageId = form.closest('.homework-respnose-form').attr('page-id');
			form.fileupload({
				url        : url,
				autoUpload : true,
				formData   : [
					{name : 'wpm_task', value : pageId},
					{name : 'action', value : 'load_ajax_function'}
				]
			});
			form.addClass('fileupload-processing');
			jQuery.ajax({
				//xhrFields: {withCredentials: true},
				url             : url,
				data            : {
					action   : "load_ajax_function",
					wpm_task : pageId
				},
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
	initFileUpload();
});
</script>