<style type="text/css">
    body{
        background-color: #<?php echo wpm_get_design_option('main.background_color', 'f7f8f9'); ?>;
        background-repeat: <?php echo wpm_get_design_option('main.background_image.repeat', 'repeat'); ?>;
        background-position: <?php echo wpm_get_design_option('main.background_image.position', 'center top'); ?>;
        background-size: <?php echo wpm_get_design_option('main.background_image.repeat', 'repeat') == 'no-repeat' ? 'cover' : 'auto'; ?>;
        <?php if (wpm_get_design_option('main.background_image.url')) : ?>
            background-image: url('<?php echo wpm_get_design_option('main.background_image.url', ''); ?>');
            background-attachment:  <?php echo wpm_get_design_option('main.background-attachment-fixed')=='on' ? 'fixed' : 'inherit'; ?>
        <?php endif; ?>
    }
    .search-hint-form,
    .top-nav-row {
        background: #<?php echo wpm_get_design_option('toolbar.background_color', 'f9f9f9'); ?>;
    }
    .top-nav-row {
        border-bottom-color: #<?php echo wpm_get_design_option('toolbar.border_bottom_color', 'e7e7e7'); ?>;
    }
    .top-nav-row a {
        color: #<?php echo wpm_get_design_option('toolbar.text_color', '868686'); ?>;
        transition: none;
    }
    .top-nav-row .nav-item:hover {
        color: #<?php echo wpm_get_design_option('toolbar.hover_color', '2e2e2e'); ?>;
    }
    .mobile-menu .menu-item > a .iconmoon,
    .search-hint-form .form .search-input-icon,
    .top-nav-row .nav-item .iconmoon {
        color: #<?php echo wpm_get_design_option('toolbar.icon_color', '868686'); ?>;
    }
    .mobile-menu-button .line {
        background: #<?php echo wpm_get_design_option('toolbar.icon_color', '868686'); ?>;
    }
    .top-nav-row .nav-item:hover > [class^="icon-"],
    .top-nav-row .dropdown-menu li a:hover > .iconmoon,
    .search-hint-form .form .search-input-icon:hover,
    .top-nav-row .nav-item:hover > .iconmoon {
        color: #<?php echo wpm_get_design_option('toolbar.icon_hover_color', '2e2e2e'); ?>;
    }
    .search-hint-form .search-toggle-button.active,
    .search-hint-form.active {
        background: #<?php echo wpm_get_design_option('toolbar.search_bg_color', 'f9f9f9'); ?>;
    }
    .search-hint-form.active {
        border: 1px solid #<?php echo wpm_get_design_option('toolbar.search_border_color', 'ededed'); ?>;
        /*border-bottom: none;*/
    }
    .search-hint-form .form .search-hint-input {
        background: #<?php echo wpm_get_design_option('toolbar.search_bg_color', 'f9f9f9'); ?>;
        color: #000;
    }
    .top-nav-row .mbl-dropdown-menu,
    .top-nav-row .dropdown-menu,
    .top-nav-row .mobile-menu,
    .top-nav-row .mobile-menu-button.active,
    .top-nav-row .nav-item.user-login-button.open,
    .top-nav-row .nav-item.user-login-button:hover,
    .top-nav-row .nav-item.user-profile-button:hover,
    .top-nav-row .nav-item.user-profile-button.open,
    .top-nav-row .nav-item.user-registration-button.open,
    .top-nav-row .nav-item.user-registration-button:hover {
        background: #<?php echo wpm_get_design_option('toolbar.menu_bg_color', 'efefef'); ?>;
        color:  #<?php echo wpm_get_design_option('toolbar.hover_color', '2e2e2e'); ?>;
    }
    .top-nav-row .mbl-dropdown-menu > a,
    .top-nav-row .dropdown-menu > a,
    .top-nav-row .mobile-menu > a,
    .top-nav-row .mobile-menu-button.active > a,
    .top-nav-row .nav-item.user-login-button.open > a,
    .top-nav-row .nav-item.user-login-button:hover > a,
    .top-nav-row .nav-item.user-profile-button:hover > a,
    .top-nav-row .nav-item.user-profile-button.open > a,
    .top-nav-row .nav-item.user-registration-button.open > a,
    .top-nav-row .nav-item.user-registration-button:hover > a {
        color:  #<?php echo wpm_get_design_option('toolbar.hover_color', '2e2e2e'); ?>;
    }
    .top-nav-row .mbl-dropdown-menu > a > .iconmoon,
    .top-nav-row .dropdown-menu > a > .iconmoon,
    .top-nav-row .mobile-menu > a > .iconmoon,
    .top-nav-row .mobile-menu-button.active > a > .iconmoon,
    .top-nav-row .nav-item.user-login-button.open > a > .iconmoon,
    .top-nav-row .nav-item.user-login-button:hover > a > .iconmoon,
    .top-nav-row .nav-item.user-profile-button:hover > a > .iconmoon,
    .top-nav-row .nav-item.user-profile-button.open > a > .iconmoon,
    .top-nav-row .nav-item.user-registration-button.open > a > .iconmoon,
    .top-nav-row .nav-item.user-registration-button:hover > a > .iconmoon {
        color: #<?php echo wpm_get_design_option('toolbar.icon_hover_color', '2e2e2e'); ?>;
    }
    .top-nav-row .dropdown-menu li a:hover {
        color: #<?php echo wpm_get_design_option('toolbar.hover_color', '2e2e2e'); ?>;
        background: #<?php echo wpm_get_design_option('toolbar.search_bg_color', 'f9f9f9'); ?>;
    }
    .top-nav-row .form-icon:before,
    .top-nav-row .note-editor.note-frame .note-placeholder,
    .search-hint-form.active .search-hint-input::placeholder,
    .top-nav-row .form-group input.form-control::placeholder {
        color: #<?php echo wpm_get_design_option('toolbar.placeholder_color', 'a9a9a9'); ?>;
    }

    .search-hint-form.active .search-hint-input,
    .top-nav-row .form-control {
        color: #<?php echo wpm_get_design_option('toolbar.input_color', '555555'); ?>
    }

    /** buttons **/
    .mbr-btn.btn-solid.btn-green {
        border-radius: 4px;
        background: #<?php echo wpm_get_design_option('toolbar.button_color', 'a0b0a1'); ?>;
        color: #<?php echo wpm_get_design_option('toolbar.button_text_color', 'ffffff'); ?>;
    }

    .mbr-btn.btn-solid.btn-green:hover {
        background: #<?php echo wpm_get_design_option('toolbar.button_hover_color', 'adbead'); ?>;
        color: #<?php echo wpm_get_design_option('toolbar.button_text_hover_color', 'ffffff'); ?>;
    }

    .mbr-btn.btn-solid.btn-green:active,
    .mbr-btn.btn-solid.btn-green:focus {
        background: #<?php echo wpm_get_design_option('toolbar.button_active_color', '8e9f8f'); ?>;
        color: #<?php echo wpm_get_design_option('toolbar.button_text_active_color', 'ffffff'); ?>;
    }
    /** buttons end **/

    /** close button **/
    .top-nav-row a.close-dropdown-panel {
        color: #<?php echo wpm_get_design_option('toolbar.close_text_color', '868686'); ?>;
    }
    .close-button .icon-close {
        color: #<?php echo wpm_get_design_option('toolbar.close_icon_color', 'ffffff'); ?>;
    }
    .close-button:hover .icon-close {
        color: #<?php echo wpm_get_design_option('toolbar.close_icon_hover_color', 'ffffff'); ?>;
    }
    .close-button .icon-close:active {
        color: #<?php echo wpm_get_design_option('toolbar.close_icon_active_color', 'ffffff'); ?>;
    }
    .close-button {
        background-color: #<?php echo wpm_get_design_option('toolbar.close_icon_bg_color', 'c1c1c1'); ?>;
    }
    .close-button:hover {
        background-color: #<?php echo wpm_get_design_option('toolbar.close_icon_bg_hover_color', 'd4d4d4'); ?>;
    }
    .close-button:active {
        background-color: #<?php echo wpm_get_design_option('toolbar.close_icon_bg_active_color', 'b4b4b4'); ?>;
    }
    /** close button end **/

    /** breadcrumbs **/
    .breadcrumbs-wrap .breadcrumbs .item {
        color: #<?php echo wpm_get_design_option('breadcrumbs.color', '8e8e8e'); ?>;
    }
    .breadcrumbs-wrap .icon-angle-right {
        color: #<?php echo wpm_get_design_option('breadcrumbs.separator_color', '8e8e8e'); ?>;
    }
    .breadcrumbs-wrap .breadcrumbs .item:last-child {
        color: #<?php echo wpm_get_design_option('breadcrumbs.active_color', '8e8e8e'); ?>;
    }
    .breadcrumbs-wrap .breadcrumbs .item:hover {
        color: #<?php echo wpm_get_design_option('breadcrumbs.hover_color', '000000'); ?>;
    }
    .breadcrumbs-wrap .breadcrumbs .item [class^="icon-"],
    .breadcrumbs-wrap .breadcrumbs .item .iconmoon {
        color: #<?php echo wpm_get_design_option('breadcrumbs.icon_color', '8e8e8e'); ?>;
    }
    .breadcrumbs-wrap .breadcrumbs .item:last-child .iconmoon {
        color: #<?php echo wpm_get_design_option('breadcrumbs.icon_active_color', '8e8e8e'); ?>;
    }
    .breadcrumbs-wrap .breadcrumbs .item:hover [class^="icon-"],
    .breadcrumbs-wrap .breadcrumbs .item:hover .iconmoon {
        color: #<?php echo wpm_get_design_option('breadcrumbs.icon_hover_color', '000000'); ?>;
    }
    .breadcrumbs-wrap {
        border-bottom: 1px solid #<?php echo wpm_get_design_option('breadcrumbs.border_color', 'e7e7e7'); ?>;
    }
    /** breadcrumbs end **/

    .page-title-row .page-title {
        color: #<?php echo wpm_get_design_option('term_header.color', '000000'); ?>;
        font-weight: <?php echo wpm_get_design_option('term_header.bold') == 'on' ? 'bold' : 'normal'; ?>;
        font-style: <?php echo wpm_get_design_option('term_header.italic') == 'on' ? 'italic' : 'normal'; ?>;
        text-decoration: <?php echo wpm_get_design_option('term_header.bordered') == 'on' ? 'underline' : 'none'; ?>;
        font-size: <?php echo wpm_get_design_option('term_header.font_size', '20'); ?>px;
        display: <?php echo wpm_get_design_option('term_header.hide') == 'on' ? 'none' : 'block'; ?>;
    }

    .page-title-row .page-description {
        color: #<?php echo wpm_get_design_option('term_subheader.color', '949494'); ?>;
        font-weight: <?php echo wpm_get_design_option('term_subheader.bold') == 'on' ? 'bold' : 'normal'; ?>;
        font-style: <?php echo wpm_get_design_option('term_subheader.italic') == 'on' ? 'italic' : 'normal'; ?>;
        text-decoration: <?php echo wpm_get_design_option('term_subheader.bordered') == 'on' ? 'underline' : 'none'; ?>;
        font-size: <?php echo wpm_get_design_option('term_subheader.font_size', '16'); ?>px;
        display: <?php echo wpm_get_design_option('term_subheader.hide') == 'on' ? 'none' : 'block'; ?>;
    }

    .page-title-row .page-description-content .mbr-btn.btn-bordered.btn-gray {
        background-color: #<?php echo wpm_get_design_option('more_button.collapsed.bg_color', 'ffffff'); ?>;
        color: #<?php echo wpm_get_design_option('more_button.collapsed.color', 'acacac'); ?>;
        border-color: #<?php echo wpm_get_design_option('more_button.collapsed.border_color', 'c1c1c1'); ?>;
    }
    .page-title-row .page-description-content .mbr-btn.btn-bordered.btn-gray .iconmoon {
        color: #<?php echo wpm_get_design_option('more_button.collapsed.icon_color', 'acacac'); ?>;
    }
    .page-title-row .page-description-content.visible .mbr-btn.btn-bordered.btn-gray.active {
        background-color: #<?php echo wpm_get_design_option('more_button.expanded.bg_color', 'fbfbfb'); ?>;
        color: #<?php echo wpm_get_design_option('more_button.expanded.color', 'acacac'); ?>;
        border-color: #<?php echo wpm_get_design_option('more_button.expanded.bg_color', 'fbfbfb'); ?>;
    }
    .page-title-row .page-description-content.visible .mbr-btn.btn-bordered.btn-gray.active .iconmoon {
        color: #<?php echo wpm_get_design_option('more_button.expanded.icon_color', 'acacac'); ?>;
    }
    .page-title-row .page-description-content.visible {
        background: #<?php echo wpm_get_design_option('more_button.expanded.bg_color', 'fbfbfb'); ?>;
        border: 1px solid #<?php echo wpm_get_design_option('more_button.expanded.border_color', 'e3e3e3'); ?>;
    }
    .folder-wrap .folder-content .title {
        font-size: <?php echo wpm_get_design_option('term_font_size', 17) ?>px;
        <?php if (intval(wpm_get_design_option('term_font_size', 17)) > 17) : ?>
            line-height: 1.1;
        <?php endif; ?>
        color: #<?php echo wpm_get_design_option('folders.color', 'fff'); ?> !important;
    }

    /** materials **/
    .material-item .content-overlay .doc-label.opened {
        color: #<?php echo wpm_get_design_option('materials.open_button_color', 'fff'); ?> !important;
        background: #<?php echo wpm_get_design_option('materials.open_button_bg_color', 'a0b0a1'); ?>;
    }

    .material-item .content-overlay .doc-label.locked {
        color: #<?php echo wpm_get_design_option('materials.closed_button_color', 'fff'); ?> !important;
        background: #<?php echo wpm_get_design_option('materials.closed_button_bg_color', 'd29392'); ?>;
    }

    .material-item.material-inaccessible .content-overlay .doc-label {
        color: #<?php echo wpm_get_design_option('materials.inaccessible_button_color', 'fff'); ?> !important;
        background: #<?php echo wpm_get_design_option('materials.inaccessible_button_bg_color', '838788'); ?> !important;
    }

    .material-item .thumbnail-wrap .icons-bottom {
        color: #<?php echo wpm_get_design_option('materials.indicator_color', 'fff'); ?> !important;
    }
    .material-item .thumbnail-wrap .icons-top .m-icon.count,
    .material-item .thumbnail-wrap .icons-top .m-icon {
        background: rgba(<?php echo implode(', ', wpm_hex_to_rgb(wpm_get_design_option('materials.filetype_bg_color', '000000'))); ?>, 0.6);
        color: #<?php echo wpm_get_design_option('materials.filetype_color', 'fff'); ?>;
    }

    .material-item .col-content {
        border: 1px solid #<?php echo wpm_get_design_option('materials.desc_border_color', 'eaeaea'); ?>;
        background: #<?php echo wpm_get_design_option('materials.desc_bg_color', 'fafafa'); ?>;
    }

    .material-item.material-opened:hover .col-content {
        background: #<?php echo wpm_get_design_option('materials.open_hover_desc_bg_color', 'dfece0'); ?>;
        border-color: #<?php echo wpm_get_design_option('materials.open_hover_desc_border_color', 'cedccf'); ?>;
    }
    .material-item.material-closed:hover .col-content,
    .material-item.material-locked:hover .col-content {
        background: #<?php echo wpm_get_design_option('materials.closed_hover_desc_bg_color', 'eed5d5'); ?>;
        border-color: #<?php echo wpm_get_design_option('materials.closed_hover_desc_border_color', 'ddc4c4'); ?>;
    }

    .material-itemmaterial-inaccessible:hover .col-content {
        background: #<?php echo wpm_get_design_option('materials.inaccessible_hover_desc_bg_color', 'd8d8d8'); ?>;
        border-color: #<?php echo wpm_get_design_option('materials.inaccessible_hover_desc_border_color', 'bebebe'); ?>;
    }

    .lesson-tabs.bordered-tabs .nav-tabs li a .tab-label {
        color: #<?php echo wpm_get_design_option('material_inner.tab_color', '9b9b9b'); ?>;
    }
    .lesson-tabs.bordered-tabs .nav-tabs li a [class^="icon-"] {
        color: #<?php echo wpm_get_design_option('material_inner.tab_icon_color', '9b9b9b'); ?>;
    }
    .lesson-tabs.bordered-tabs .nav-tabs li.active a .tab-label {
        color: #<?php echo wpm_get_design_option('material_inner.active_tab_color', '555555'); ?>;
    }
    .lesson-tabs.bordered-tabs .nav-tabs li.active a [class^="icon-"] {
        color: #<?php echo wpm_get_design_option('material_inner.active_tab_icon_color', '555555'); ?>;
    }
    .lesson-tabs.bordered-tabs .tab-content:before,
    .lesson-tabs.bordered-tabs .tab-content:after,
    .lesson-tabs.white-tabs .nav-tabs li.active a,
    .lesson-tabs .content-wrap .question-answer-row,
    .lesson-tabs.white-tabs .tab-content .tab-pane {
        background: #<?php echo wpm_get_design_option('material_inner.content_bg_color', 'ffffff'); ?>;
    }

    .lesson-tabs.bordered-tabs .nav-tabs li a {
        background: #<?php echo wpm_get_design_option('material_inner.inactive_tab_bg_color', 'efefef'); ?>;
    }

    .content-wrap .question-answer-row .answer .answer-meta {
        background: rgba(0, 0, 0, 0.04);
    }

    .lesson-tabs.bordered-tabs .tab-content:before,
    .lesson-tabs.bordered-tabs .tab-content:after,
    .lesson-tabs.bordered-tabs .nav-tabs > li.active > a,
    .lesson-tabs.bordered-tabs .tab-content {
        border-color: #<?php echo wpm_get_design_option('material_inner.content_border_color', 'e3e3e3'); ?>;
    }

    .reading-status-row .next,
    .reading-status-row .prev,
    .reading-status-row .status-toggle-wrap {
        color: #<?php echo wpm_get_design_option('material_inner.lesson_status_color', '9b9b9b'); ?>;
    }
    .reading-status-row .next:hover,
    .reading-status-row .prev:hover,
    .reading-status-row .status-toggle-wrap:hover {
        color: #<?php echo wpm_get_design_option('material_inner.lesson_status_hover_color', '555555'); ?>;
    }

    .comments-tabs.bordered-tabs .nav-tabs li a .tab-label{
        color: #<?php echo wpm_get_design_option('material_comments.tabs_color', '9b9b9b'); ?>;
    }
    .comments-tabs.bordered-tabs .nav-tabs li a .iconmoon {
        color: #<?php echo wpm_get_design_option('material_comments.tabs_icon_color', '9b9b9b'); ?>;
    }
    .comments-row .comments-header .all-comments-count,
    .comments-tabs.bordered-tabs .nav-tabs li.active a .tab-label{
        color: #<?php echo wpm_get_design_option('material_comments.tabs_active_color', '555555'); ?>;
    }
    .comments-tabs.bordered-tabs .nav-tabs li.active a .iconmoon {
        color: #<?php echo wpm_get_design_option('material_comments.tabs_active_icon_color', '555555'); ?>;
    }

    .comments-tabs.bordered-tabs .tab-content:before,
    .comments-tabs.bordered-tabs .tab-content:after,
    .comments-tabs.bordered-tabs .nav-tabs li.active a,
    .comments-tabs.bordered-tabs .content-wrap .question-answer-row,
    .comments-tabs.bordered-tabs .tab-content,
    .comments-tabs.bordered-tabs .tab-content .tab-pane {
        background-color: #<?php echo wpm_get_design_option('material_comments.bg_color', 'ffffff'); ?>;
    }

    .comments-tabs.bordered-tabs .nav-tabs li a {
        background: #<?php echo wpm_get_design_option('material_comments.inactive_tab_bg_color', 'efefef'); ?>;
    }

    .comments-list .comment.comment-admin {
        background: rgba(0, 0, 0, 0.025);
    }

    .comments-tabs.bordered-tabs .tab-content:before,
    .comments-tabs.bordered-tabs .tab-content:after,
    .comments-tabs.bordered-tabs .nav-tabs > li.active > a,
    .comments-tabs.bordered-tabs .tab-content {
        border-color: #<?php echo wpm_get_design_option('material_comments.border_color', 'e3e3e3'); ?>;
    }
    /** materials end **/

    /** pagination **/
    .wp-pagenavi a,
    .wp-pagenavi .current,
    .wp-pagenavi .nextpostslink:hover,
    .wp-pagenavi .prevpostslink:hover,
    .wp-pagenavi .extend:hover,
    .wp-pagenavi .nextpostslink,
    .wp-pagenavi .prevpostslink,
    .wp-pagenavi .extend {
        color: #<?php echo wpm_get_design_option('pagination.color', '7e7e7e'); ?>;
    }

    .wp-pagenavi a:hover,
    .wp-pagenavi .current:hover {
        color: #<?php echo wpm_get_design_option('pagination.hover_color', '000000'); ?>;
    }

    .wp-pagenavi .page {
        border-color: #<?php echo wpm_get_design_option('pagination.border_color', 'c1c1c1'); ?>;
        background: #<?php echo wpm_get_design_option('pagination.bg_color', 'fbfbfb'); ?>;
        color: #<?php echo wpm_get_design_option('pagination.color', '7e7e7e'); ?>;
    }
    .wp-pagenavi .page:hover {
        border-color: #<?php echo wpm_get_design_option('pagination.hover_border_color', 'c1c1c1'); ?>;
        background: #<?php echo wpm_get_design_option('pagination.hover_bg_color', 'fbfbfb'); ?>;
        color: #<?php echo wpm_get_design_option('pagination.hover_color', '000000'); ?>;
    }

    .wp-pagenavi .current {
        background: #<?php echo wpm_get_design_option('pagination.border_color', 'c1c1c1'); ?>;
        border-color: #<?php echo wpm_get_design_option('pagination.border_color', 'c1c1c1'); ?>;
        color: #<?php echo wpm_get_design_option('pagination.bg_color', 'fbfbfb'); ?>;
    }
    /** pagination end **/
    /** progress **/
    .course-progress-wrap .progress .progress-bar.progress-bar-unlock,
    .reading-status-row .progress-wrap .progress .progress-bar,
    .breadcrumbs-wrap .course-progress-wrap .progress-bar {
        background-color: #<?php echo wpm_get_design_option('progress.line_color', '65bf49'); ?>;
    }
    .progress {
        background-color: #<?php echo wpm_get_design_option('progress.bg_color', 'f5f5f5'); ?>;
    }
    .course-progress-wrap .progress-count {
        color: #<?php echo wpm_get_design_option('progress.text_color', '4a4a4a'); ?>;
    }
    .folder-content .course-progress-wrap .progress-count {
        color: #ffffff;
    }
    /** progress end **/
    .footer-row {
        <?php if (wpm_get_design_option('footer_options.transparent', '0') == '1') : ?>
            background: transparent;
            border-top: none;
        <?php else: ?>
            background: #<?php echo wpm_get_design_option('footer_options.background', 'f9f9f9'); ?>;
            border-top-color: #<?php echo wpm_get_design_option('footer_options.border', 'e7e7e7'); ?>;
        <?php endif; ?>
    }
</style>
