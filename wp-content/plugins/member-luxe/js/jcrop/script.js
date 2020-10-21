jQuery(document).ready(function ($) {
    var croppable = function () {
        var $this = $(this),
            $buttonsHolder = $this.parent(),
            $otherButtons = $buttonsHolder.find('.wpm-delete-media-button, .wpm-media-upload-button'),
            $input = $('#' + $this.data('croppable')),
            $orig = $('#' + $this.data('orig')),
            image = $('#' + $this.data('image')),
            $holder = image.parent(),
            width = parseInt($this.data('width')),
            height = parseInt($this.data('height')),
            ratio = parseFloat(width / height),
            saveButton = $('<a />', {href : '#', text : $this.data('save-title'), 'class' : 'button'}),
            cancelButton = $('<a />', {href : '#', text : $this.data('cancel-title'), 'class' : 'button'}).css('margin-right', '11px'),
            cropOffset = 10,
            zoom,
            newImage,
            cb;

        newImage = $('<img />', {src : $orig.val()});
        image.replaceWith(newImage);
        $this.replaceWith(saveButton);
        cancelButton.insertBefore(saveButton);
        $otherButtons.hide();
        $holder.parent().removeClass('inactive');

        $holder.hide();

        getImgSize($orig.val(), initCrop);

        function getImgSize(imgSrc, callback) {
            var newImg = new Image();

            newImg.onload = function () {
                callback(newImg.width, newImg.height);
            };

            newImg.src = imgSrc;
        }

        function initCrop(origWidth, origHeight) {
            var origRatio = parseInt(origWidth)/parseInt(origHeight);
            if(origRatio < ratio) {
                zoom = parseInt(origHeight) / height;
            } else {
                zoom = parseInt(origWidth) / width;
            }
            $holder.show();
            newImage.Jcrop({
                aspectRatio : ratio,
                setSelect   : [
                    cropOffset * zoom,
                    cropOffset * zoom,
                    origWidth - cropOffset * zoom * 2,
                    origHeight - cropOffset * zoom * 2
                ],
                bgOpacity   : .75,
                bgColor     : 'black',
                boxWidth    : width,
                boxHeight   : height
            }, function () {
                this.initComponent('Thumbnailer', { width: width, height: height });
                interfaceLoad(this);
                $('<div />', {text: $this.data('preview-title')}).insertBefore($holder.find('.jcrop-thumb-holder'));
            });
        }

        function interfaceLoad(obj) {
            cb = obj;
        }

        saveButton.on('click', function () {
            var selection = cb.getSelection(),
                data = {
                    orig    : $orig.val(),
                    w       : selection.w * zoom,
                    h       : selection.h * zoom,
                    x       : selection.x * zoom,
                    y       : selection.y * zoom,
                    action  : $this.data('action'),
                    iwidth  : width,
                    iheight : height
                };

            saveButton.attr('disabled', true);

            $.post(ajaxurl, data, function (response) {
                if(response.result === 'success') {
                    $holder.html($('<img />', {src : response.path, id : $this.data('image')}));
                    $input.val(response.path);
                }

                destroy();

            }, "json");

            return false;
        });

        cancelButton.on('click', function(){
            destroy();
            cb.destroy();
            $holder.html(image);

            return false;
        });

        function destroy() {
            saveButton.off('click');
            saveButton.attr('disabled', false);
            saveButton.replaceWith($this);
            saveButton.remove();
            cancelButton.remove();
            $otherButtons.show();
            $(document).off('click', '[data-croppable]');
            $(document).on('click', '[data-croppable]', croppable);
            $holder.parent().addClass('inactive');
        }

        return false;
    };
    $(document).on('click', '[data-croppable]', croppable);
});