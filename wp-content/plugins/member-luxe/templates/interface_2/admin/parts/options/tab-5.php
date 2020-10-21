<div class="wpm-tab-content">
    <div class="wpm-inner-tabs" tab-id="h-tabs-5">
        <ul class="wpm-inner-tabs-nav">
            <li><a href="#mbl_inner_tab_5_1">Настройки отправки писем</a></li>
            <li><a href="#mbl_inner_tab_5_2">Письмо при регистрации пользователя</a></li>
            <li><a href="#mbl_inner_tab_5_3">Автотренинги</a></li>
            <li><a href="#mbl_inner_tab_5_4">Уведомление о новом комментарии</a></li>
            <li><a href="#mbl_inner_tab_5_5">Задать вопрос</a></li>
        </ul>
        <div id="mbl_inner_tab_5_1" class="wpm-tab-content">
            <div class="wpm-row">
                <?php $mandrill_is_on = (array_key_exists('mandrill_is_on', $main_options['letters']) && $main_options['letters']['mandrill_is_on'] == 'on'); ?>
                <?php $ses_is_on = (array_key_exists('ses_is_on', $main_options['letters']) && $main_options['letters']['ses_is_on'] == 'on') ?>
                <?php
                $ses_hosts = array(
                    'EU (Ireland)'          => 'email.eu-west-1.amazonaws.com',
                    'US East (N. Virginia)' => 'email.us-east-1.amazonaws.com',
                    'US West (Oregon)'      => 'email.us-west-2.amazonaws.com',
                );
                ?>

                <label>
                    <input type="radio"
                           name="main_options[letters][type]"
                           value="mandrill"
                           class="letter_options"
                           id="mandrill_is_on" <?php echo $mandrill_is_on ? 'checked' : ''; ?>>Отправлять письма через Mandrill
                </label>
                <div id="mandrill_api_key_label"
                       class="<?php echo $mandrill_is_on ? '' : 'invisible'; ?> letter_options_label">
                    Укажите Mandrill API key  &nbsp; <input type="text"
                                                                                   name="main_options[letters][mandrill_api_key]"
                                                                                   id="mandrill_api_key"
                                                                                   class="large-text"
                                                                                   value="<?php echo $main_options['letters']['mandrill_api_key']; ?>"/>
                </div>
                <br/>
                <label>
                    <input type="radio"
                           name="main_options[letters][type]"
                           value="ses"
                           class="letter_options"
                           id="ses_is_on" <?php echo $ses_is_on ? 'checked' : ''; ?>>Отправлять письма через Amazon SES
                </label>
                <div id="ses_api_key_label"
                       class="<?php echo $ses_is_on ? '' : 'invisible'; ?> letter_options_label">
                    Укажите Amazon SES Access Key ID &nbsp;
                    <input type="text"
                           name="main_options[letters][ses_access_key]"
                           id="ses_access_key"
                           class="large-text"
                           value="<?php echo $main_options['letters']['ses_access_key']; ?>"/>
                    <br><br/>
                    Укажите Amazon SES Secret Access Key &nbsp;
                    <input type="text"
                           name="main_options[letters][ses_secret_key]"
                           id="ses_secret_key"
                           class="large-text"
                           value="<?php echo $main_options['letters']['ses_secret_key']; ?>"/>
                    <br><br/>
                    Укажите верифицированный email &nbsp;
                    <input type="text"
                           name="main_options[letters][ses_email]"
                           id="ses_email"
                           class="large-text"
                           value="<?php echo $main_options['letters']['ses_email']; ?>"/>
                    <br><br/>
                    Укажите регион &nbsp;
                    <select id="ses_host"
                            name="main_options[letters][ses_host]"
                            class="users-level">
                        <?php foreach ($ses_hosts AS $host_name => $ses_host) : ?>
                            <option value="<?php echo $ses_host; ?>"
                                <?php echo $main_options['letters']['ses_host'] == $ses_host ? 'selected="selected"' : '' ?>
                            ><?php echo $host_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br/>
                    <br/>
                    <div>
                        <button type="button" class="button" id="test_ses">Отправить тестовое письмо</button>
                        <div id="test_ses_response"></div>
                    </div>
                </div>
                <br/>
                <label>
                    <input type="radio"
                           name="main_options[letters][type]"
                           value="wp"
                           class="letter_options"
                           id="wpmail_is_on" <?php echo !$ses_is_on && !$mandrill_is_on ? 'checked' : ''; ?>>Отправлять письма через Wordpress
                </label>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_5_2" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    Заголовок письма<br>
                    <input type="text" name="main_options[letters][registration][title]"
                           value="<?php echo $main_options['letters']['registration']['title'] ?>"
                           class="large-text">
                </label>

            </div>
            <div class="wpm-control-row">
                <?php
                wp_editor(stripslashes($main_options['letters']['registration']['content']), 'wpm_letter_registration', array('textarea_name' => 'main_options[letters][registration][content]', 'editor_height' => 300));
                ?>
            </div>
            <div class="wpm-help-wrap">
                <p>
                    <span class="code-string">[user_name]</span> - имя пользователя <br>
                    <span class="code-string">[user_login]</span> - логин пользователя <br>
                    <span class="code-string">[user_pass]</span> - пароль пользователя <br>
                    <span class="code-string">[start_page]</span> - страница входа <br>
                </p>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_5_3" class="wpm-tab-content">
            <div>
                <h3>Уведомление администратора об ответе на домашнее заданее</h3>
                <div class="wpm-row">
                    <label>
                        Заголовок письма<br>
                        <input type="text" name="main_options[letters][response_admin][title]"
                               value="<?php echo wpm_get_option('letters.response_admin.title') ?>"
                               class="large-text">
                    </label>

                </div>
                <div class="wpm-control-row">
                    <?php wp_editor(stripslashes(wpm_get_option('letters.response_admin.content')), 'wpm_letter_response_admin', array('textarea_name' => 'main_options[letters][response_admin][content]', 'editor_height' => 250));?>
                </div>
                <div class="wpm-help-wrap">
                    <p>
                        <span class="code-string">[material_name]</span> - название материала <br>
                        <span class="code-string">[user_name]</span> - имя пользователя <br>
                        <span class="code-string">[user_email]</span> - email пользователя <br>
                        <span class="code-string">[user_response]</span> - текст ответа <br>
                        <span class="code-string">[admin_url]</span> - ссылка на панель управления <br>
                    </p>
                </div>
            </div>
            <br><br>
            <div>
                <h3>Уведомление пользователя о статусе домашнего задания</h3>
                <div class="wpm-row">
                    <label>
                        Заголовок письма<br>
                        <input type="text" name="main_options[letters][response_status][title]"
                               value="<?php echo wpm_get_option('letters.response_status.title') ?>"
                               class="large-text">
                    </label>

                </div>
                <div class="wpm-control-row">
                    <?php wp_editor(stripslashes(wpm_get_option('letters.response_status.content')), 'wpm_letter_response_status', array('textarea_name' => 'main_options[letters][response_status][content]', 'editor_height' => 250));?>
                </div>
                <div class="wpm-help-wrap">
                    <p>
                        <span class="code-string">[material_name]</span> - название материала <br>
                        <span class="code-string">[status]</span> - статус <br>
                        <span class="code-string">[material_url]</span> - ссылка на материал <br>
                    </p>
                </div>
            </div>
            <br><br>
            <div>
                <h3>Уведомление пользователя о комментарии к ответу на домашнее заданее</h3>
                <div class="wpm-row">
                    <label>
                        Заголовок письма<br>
                        <input type="text" name="main_options[letters][response_review][title]"
                               value="<?php echo wpm_get_option('letters.response_review.title') ?>"
                               class="large-text">
                    </label>

                </div>
                <div class="wpm-control-row">
                    <?php wp_editor(stripslashes(wpm_get_option('letters.response_review.content')), 'wpm_letter_response_review', array('textarea_name' => 'main_options[letters][response_review][content]', 'editor_height' => 250));?>
                </div>
                <div class="wpm-help-wrap">
                    <p>
                        <span class="code-string">[material_name]</span> - название материала <br>
                        <span class="code-string">[user_response]</span> - текст ответа <br>
                        <span class="code-string">[response_review]</span> - текст комментария <br>
                        <span class="code-string">[material_url]</span> - ссылка на материал <br>
                    </p>
                </div>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_5_4" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    Заголовок письма<br>
                    <input type="text" name="main_options[letters][comment_subscription][title]"
                           value="<?php echo wpm_get_option('letters.comment_subscription.title') ?>"
                           class="large-text">
                </label>

            </div>
            <div class="wpm-control-row">
                <?php
                wp_editor(stripslashes(wpm_get_option('letters.comment_subscription.content')), 'wpm_letter_comment_subscription', array('textarea_name' => 'main_options[letters][comment_subscription][content]', 'editor_height' => 300));
                ?>
            </div>
            <div class="wpm-help-wrap">
                <p>
                    <span class="code-string">[user_name]</span> - имя пользователя <br>
                    <span class="code-string">[page_link]</span> - ссылка на страницу <br>
                    <span class="code-string">[page_title]</span> - название страницы <br>
                </p>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_5_5" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <?php if (array_key_exists('hide_ask', $main_options['main']) && $main_options['main']['hide_ask'] == 'hide') { ?>
                        <input type="checkbox" name="main_options[main][hide_ask]" value="hide"
                               checked="checked">
                        <?php
                    } else { ?>
                        <input type="checkbox" name="main_options[main][hide_ask]" value="hide">
                    <?php } ?>

                    Не отображать</label>
            </div>
            <div class="wpm-row">
                <?php if (empty($main_options['main']['ask_email'])) $main_options['main']['ask_email'] = get_option('admin_email'); ?>
                <label>Емейл для получения вопросов от пользователя.<br>
                    <input type="text" name="main_options[main][ask_email]"
                           class="large-text"
                           value="<?php echo $main_options['main']['ask_email']; ?>">
                </label>
            </div>

            <div class="wpm-row">
                <label><input type="checkbox"
                              name="main_options[main][hide_ask_for_not_registered]"
                        <?php echo (array_key_exists('hide_ask_for_not_registered', $main_options['main']) && $main_options['main']['hide_ask_for_not_registered'] == 'on') ? ' checked' : ''; ?>>
                    Не отображать "Задать вопрос" для незарегистрированных
                    пользователей</label>
            </div>

           <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
    </div>
</div>