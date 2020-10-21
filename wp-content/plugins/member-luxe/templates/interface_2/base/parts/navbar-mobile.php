<section class="mobile-menu-row">
    <div class="inner">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <ul class="mobile-menu">
                        <?php if (current_user_can('manage_options')) : ?>
                            <li class="menu-item"><a href="<?php echo admin_url('edit.php?post_type=wpm-page'); ?>"><span class="iconmoon icon-sliders"></span><?php _e('Панель управления', 'mbl'); ?></a></li>
                        <?php endif; ?>
                        <?php if (is_single() && $editPostUrl = get_edit_post_link()) : ?>
                            <li class="menu-item"><a href="<?php echo apply_filters('edit_post_link', esc_url($editPostUrl)); ?>"><span class="iconmoon icon-pencil"></span><?php _e('Редактировать', 'mbl'); ?></a></li>
                        <?php endif; ?>
                        <?php if (wpm_user_is_active()) : ?>
                            <li class="menu-item"><a href="<?php echo get_permalink(wpm_get_option('home_id')); ?>"><span class="iconmoon icon-home"></span><?php _e('Главная', 'mbl'); ?></a></li>
                        <?php endif; ?>
                        <?php if (!wpm_option_is('hide_schedule', 'on') && wpm_get_option('schedule_id') && !(wpm_option_is('schedule_id', 'no'))) : ?>
                            <li class="menu-item"><a href="<?php echo get_permalink(wpm_get_option('schedule_id')); ?>"><span class="iconmoon icon-calendar-o"></span><?php _e('Расписание', 'mbl'); ?></a></li>
                        <?php endif; ?>
                        <?php if (!wpm_option_is('main.hide_ask', 'hide') && (is_user_logged_in() || !wpm_option_is('main.hide_ask_for_not_registered', 'on'))) : ?>
                            <li class="menu-item"><a class="panel-toggler"><span class="iconmoon icon-question-circle"></span><?php _e('Задать вопрос', 'mbl'); ?> <span class="close-button"><span class="icon-close"></span></span></a>
                                <div class="slide-down-wrap">
                                    <?php wpm_render_partial('ask-form', 'base', array('full' => false)) ?>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php if (!is_user_logged_in()) : ?>
                            <li class="menu-item"><a class="panel-toggler"><span class="iconmoon icon-sign-in"></span><?php _e('Войти', 'mbl'); ?> <span class="close-button"><span class="icon-close"></span></span></a>
                                <div class="slide-down-wrap">
                                    <?php wpm_render_partial('login-form', 'base', array('full' => false)); ?>
                                </div>
                            </li>
                            <li class="menu-item main-holder"><a class="panel-toggler"><span class="iconmoon icon-sign-in"></span><?php _e('Регистрация', 'mbl'); ?> <span class="close-button"><span class="icon-close"></span></span></a>
                                <div class="slide-down-wrap holder">
                                    <?php wpm_render_partial('register-form', 'base', array('full' => false)); ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
