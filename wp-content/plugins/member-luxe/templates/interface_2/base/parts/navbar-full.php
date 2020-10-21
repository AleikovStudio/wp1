<header class="header-row">
    <section class="top-nav-row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 not-relative top-nav-row--inner">
                    <button type="button" name="button" class="mobile-menu-button visible-xs visible-sm">
                        <span class="line line-1"></span>
                        <span class="line line-2"></span>
                        <span class="line line-3"></span>
                    </button>
                    <?php if (current_user_can('manage_options')) : ?>
                        <a class="nav-item hidden-xs hidden-sm" href="<?php echo admin_url('edit.php?post_type=wpm-page'); ?>" title="<?php _e('Панель управления', 'mbl'); ?>"><span class="icon-sliders"></span></a>
                    <?php endif; ?>
                    <?php if (is_single() && $editPostUrl = get_edit_post_link()) : ?>
                        <a class="nav-item hidden-xs hidden-sm" href="<?php echo apply_filters('edit_post_link', esc_url($editPostUrl)); ?>" title="<?php _e('Редактировать', 'mbl'); ?>"><span class="icon-pencil"></span></a>
                    <?php endif; ?>
                    <?php if (wpm_user_is_active()) : ?>
                        <a class="nav-item hidden-xs hidden-sm"
                           href="<?php echo get_permalink(wpm_get_option('home_id')); ?>">
                            <span class="iconmoon icon-home"></span>
                            <?php _e('Главная', 'mbl'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!wpm_option_is('hide_schedule', 'on') && wpm_get_option('schedule_id') && !(wpm_option_is('schedule_id', 'no'))) : ?>
                        <a class="nav-item hidden-xs hidden-sm"
                           href="<?php echo get_permalink(wpm_get_option('schedule_id')); ?>">
                            <span class="iconmoon icon-calendar-o"></span>
                            <?php _e('Расписание', 'mbl'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!wpm_option_is('main.hide_ask', 'hide') && (is_user_logged_in() || !wpm_option_is('main.hide_ask_for_not_registered', 'on'))) : ?>
                        <div class="dropdown user-registration-button nav-item navbar-left hidden-xs hidden-sm">
                            <a id="ask-dropdown" class="dropdown-button dropdown-toggle">
                                <span class="iconmoon icon-question-circle"></span><?php _e('Задать вопрос', 'mbl'); ?>
                            </a>
                            <div class="mbl-dropdown-menu dropdown-panel" aria-labelledby="ask-dropdown">
                                <?php wpm_render_partial('ask-form', 'base', array('full' => true)) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="search-hint-form">
                        <?php if (wpm_option_is('main.search_visible', 'on', 'on')) : ?>
                            <a class="nav-item search-toggle-button" title="<?php _e('Найти', 'mbl'); ?>"><span class="iconmoon icon-search"></span></a>
                            <form class="form" action="<?php echo wpm_search_link(); ?>">
                                <?php if (get_option('permalink_structure') == '') : ?>
                                    <input type="hidden" name="wpm-page" value="search">
                                <?php endif; ?>
                                <input class="search-hint-input" id="search-input" type="text" name="s" autocomplete="off" placeholder="<?php _e('Поиск...', 'mbl'); ?>">
                                <button type="submit" class="search-input-icon icon-search"></button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php if (!is_user_logged_in()) : ?>
                        <div class="dropdown user-login-button nav-item navbar-right hidden-xs hidden-sm">
                            <a id="login-dropdown" class="dropdown-button dropdown-toggle" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                <span class="iconmoon icon-sign-in"></span><?php _e('Войти', 'mbl'); ?>
                            </a>
                            <div class="dropdown-menu dropdown-panel" aria-labelledby="login-dropdown">
                                <?php wpm_render_partial('login-form', 'base', array('full' => true)); ?>
                            </div>
                        </div>
                        <div class="dropdown user-registration-button nav-item navbar-right hidden-xs hidden-sm main-holder">
                            <a id="registration-dropdown" class="dropdown-button dropdown-toggle" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                <span class="iconmoon icon-user"></span><?php _e('Регистрация', 'mbl'); ?>
                            </a>
                            <div class="dropdown-menu dropdown-panel" aria-labelledby="registration-dropdown">
                                <?php wpm_render_partial('register-form', 'base', array('full' => true)); ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="dropdown user-profile-button nav-item navbar-right">
                            <a id="user-profile-dropdown" class="dropdown-button user-profile-dropdown-button dropdown-toggle" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                <span class="user-profile-humbnail"><?php echo wpm_get_avatar_tag(get_current_user_id(), 32); ?></span><span class="user-name hidden-xs hidden-sm hidden-md"><?php echo wpm_get_current_user('display_name'); ?></span>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="user-profile-dropdown">
                                <li><a href="<?php echo admin_url('/user-edit.php?user_id=' . get_current_user_id()); ?>"><span class="iconmoon icon-cog"></span><?php _e('Профиль', 'mbl'); ?></a></li>
                                <li><a href="<?php echo wpm_activation_link(); ?>"><span class="iconmoon icon-key"></span><?php _e('Активация', 'mbl'); ?></a></li>
                                <?php /*
                                    <li><a href="./billing.php"><span class="iconmoon icon-dollar"></span><?php _e('Биллинг', 'mbl'); ?></a></li>
                                */ ?>
                                <li><a href="<?php echo wp_logout_url(wpm_get_start_url()); ?>"><span class="iconmoon icon-sign-out"></span><?php _e('Выход', 'mbl'); ?></a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</header>
