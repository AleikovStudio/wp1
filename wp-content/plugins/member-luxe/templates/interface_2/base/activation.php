<?php $activation = new MBLActivation() ?>
<?php $showPage = is_user_logged_in() || wpm_get_option('main.opened'); ?>
<?php wpm_render_partial('head', 'base', compact('post')) ?>
<?php wpm_render_partial('navbar') ?>
<div class="site-content">
    <?php wpm_render_partial('header-cover'); ?>
    <?php wpm_render_partial('breadcrumbs', 'base', array('breadcrumbs' => $activation->getBreadcrumbs() )); ?>
    <?php if ($showPage) : ?>
        <section class="main-content-row clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-content-wrap">
                            <div class="activation-form-wrap">
                                <form class="activation-form" method="post">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group form-icon icon-key">
                                                <input type="text" name="activation" id="user_key" value="" placeholder="<?php _e('Ваш ключ', 'mbl'); ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                                <button type="submit" name="button" class="mbr-btn btn-default btn-solid btn-green text-uppercase"><?php _e('Добавить', 'mbl'); ?></button>
                                        </div>
                                    </div>
                                    <div class="result" style="display: none;"></div>
                                </form>
                            </div>
                            <!--  -->
                            <div class="keys-table">
                                <div class="key-row keys-header hidden-xs">
                                    <div class="col-list"><span class="iconmoon icon-list"></span></div>
                                    <div class="col-key"><span class="iconmoon icon-key"></span> <?php _e('Уровень доступа', 'mbl'); ?></div>
                                    <?php /*
                                        <div class="col-retake"><span class="iconmoon icon-refresh"></span> <?php _e('Пересдач', 'mbl'); ?></div>
                                    */ ?>
                                    <div class="col-start"><span class="iconmoon icon-calendar-o"></span> <?php _e('Старт', 'mbl'); ?></div>
                                    <div class="col-end"><span class="iconmoon icon-calendar-o"></span> <?php _e('Конец', 'mbl'); ?></div>
                                </div>
                                <?php $i = 0; ?>
                                <?php foreach ($activation->getRows() as $activationRow) : ?>
                                    <div class="key-row <?php echo $i%2 ? 'even' : 'odd'; ?> <?php echo $activationRow->isActive() ? '' : 'disabled'; ?>">
                                        <div class="col-item col-list">
                                            <div class="key-icon">
                                                <span class="iconmoon icon-list"></span>
                                            </div>
                                            <div class="key-content">
                                                <?php if ($activationRow->isActive()) : ?>
                                                    <button type="button" name="button" class="toggle-key-cats">
                                                        <span class="num"><?php echo ++$i; ?></span>
                                                        <span class="iconmoon icon-angle-down"></span>
                                                    </button>
                                                <?php else : ?>
                                                    <div class="num"><?php echo ++$i; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-item col-key">
                                            <div class="key-icon" data-toggle="tooltip" data-placement="top" title="<?php _e('Уровень доступа', 'mbl'); ?>">
                                                <span class="iconmoon icon-key"></span>
                                            </div>
                                            <div class="key-content">
                                                <div class="course"><?php echo $activationRow->getLevelName(); ?></div>
                                                <div class="key"><?php echo $activationRow->getKey(); ?></div>
                                            </div>
                                        </div>
                                        <?php /*
                                            <div class="col-item col-retake">
                                                <div class="key-icon">
                                                    <span class="iconmoon icon-refresh"></span>
                                                </div>
                                                <div class="key-content">
                                                    <div class="date">10</div>
                                                </div>
                                            </div>
                                        */ ?>
                                        <div class="col-item col-start">
                                            <div class="key-icon">
                                                <span class="iconmoon icon-calendar-o"></span>
                                            </div>
                                            <div class="key-content">
                                                <div class="date"><?php echo $activationRow->getDateStart(); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-item col-end">
                                            <div class="key-icon" data-toggle="tooltip" data-placement="top" title="<?php _e('Конец', 'mbl'); ?>">
                                                <span class="iconmoon icon-calendar-o"></span>
                                            </div>
                                            <div class="key-content">
                                                <div class="date ok"><?php echo $activationRow->getDateEnd(); ?></div>
                                                <?php if ($activationRow->isActive()) : ?>
                                                    <div class="note red">
                                                        <span class="iconmoon icon-exclamation-circle"></span>
                                                        <?php _e('осталось', 'mbl'); ?>:<br> <?php echo $activationRow->getDaysLeft(); ?> <?php _e('дней', 'mbl'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-item col-more-info">
                                            <div class="key-icon" data-toggle="tooltip" data-placement="top" title="<?php _e('Показать детали', 'mbl'); ?>">
                                                <button type="button" name="button" class="toggle-key-cats">
                                                    <span class="iconmoon icon-angle-down"></span>
                                                </button>
                                            </div>
                                            <div class="key-content">
                                                <div class="more-info toggle-key-cats">
                                                    <?php _e('Показать детали', 'mbl'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($activationRow->isActive()) : ?>
                                        <div class="row-key-categories <?php echo $i%2 ? 'odd' : 'even'; ?>">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php foreach ($activationRow->getCategories() as $category) : ?>
                                                        <?php wpm_render_partial('folder', 'base', compact('category')) ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <!--  -->
                            </div>
                        </div>
                        <!--  -->

                    </div>
                </div>
            </div>
        </section>
        <script>
            jQuery(function ($) {
                $('.activation-form').on('submit', function (e) {
                    var result = $(this).find('.result');
                    result.hide();
                    result.html('<p class="alert alert-info"><?php _e('Загрузка...', 'mbl'); ?></p>').fadeIn();
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: ajaxurl,
                        data: {
                            action: "wpm_add_key_to_user_action",
                            key: $('#user_key').val(),
                            user_id: "<?php echo get_current_user_id(); ?>"
                        },
                        success: function (data) {
                            if (data.error) {
                                result.fadeOut('slow', function () {
                                    result.html('<p class="alert alert-warning">' + data.message + '</p>').fadeIn();
                                    setTimeout(function () {
                                        result.fadeOut('slow', function () {
                                            result.html('');
                                            $('#user_key').val('');
                                        });
                                    }, 2000);
                                });
                            } else {
                                result.fadeOut('slow', function () {
                                    result.html('<p class="alert alert-success">' + data.message + '</p>').fadeIn();
                                    $('#user-keys').html(data.keys);
                                    $('.user-keys-wrap').removeClass('hidden');
                                });
                                setTimeout(function () {
                                    result.fadeOut('slow', function () {
                                        result.html('');
                                        $('#user_key').val('');
                                    });
                                    location.reload();
                                }, 2000);
                            }
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                    e.preventDefault();
                });
            });
        </script>
    <?php else : ?>
        <?php wpm_render_partial('restricted') ?>
    <?php endif; ?>
</div>
<?php wpm_render_partial('footer') ?>
<?php wpm_render_partial('footer-scripts') ?>
