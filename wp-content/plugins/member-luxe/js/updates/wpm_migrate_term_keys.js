jQuery(function ($) {
    var html = '<div id="wpm_migrate_keys_holder"><div id="wpm_migrate_keys_alert"><div class="inactive"><h1>База данных плагина MemberLux устарела</h1><button type="button">Обновить</button></div><div class="active"><h1>Обновление базы данных.<br><br>Не закрывайте вкладку браузера!</h1><div id="wpm_migrate_keys_process"></div></div></div></div>';
     $('body').css({position:'fixed'}).append(html);

    setTimeout(function () {
        $('#wpm_migrate_keys_holder').fadeIn('fast');
    }, 0);

    var $alert = $('#wpm_migrate_keys_alert');
    var $inactiveBlock = $alert.find('.inactive');
    var $activeBlock = $alert.find('.active');
    var $process = $activeBlock.find('#wpm_migrate_keys_process');
    
    $inactiveBlock.on('click', 'button', function () {
        $inactiveBlock.fadeOut('fast', function () {
            $process.text('0%');
            $activeBlock.fadeIn('fast');
        });
        send_wpm_keys_migrate_request();
        return false;
    });
    
    function send_wpm_keys_migrate_request() {
        $.post(ajaxurl, {action : 'wpm_migrate_term_keys_action'}, function (data) {
            if (data.done) {
                location.reload();
            } else if (data.progress) {
                $process.fadeOut('fast', function () {
                    $process.text('' + data.progress + '%');
                    $process.fadeIn('fast');
                });
                send_wpm_keys_migrate_request();
            } else {
                console.log(data);
                $activeBlock.hide();
                $inactiveBlock.show();
            }
        }, "json")
            .fail(function () {
                send_wpm_keys_migrate_request();
            });
    }
});