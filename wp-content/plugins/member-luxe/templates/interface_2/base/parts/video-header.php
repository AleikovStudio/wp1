<script type="text/javascript">
    function cc(data) {
        var val = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, p, i = 0, res = '';

        do {
            h1 = val.indexOf(data.charAt(i++));
            h2 = val.indexOf(data.charAt(i++));
            h3 = val.indexOf(data.charAt(i++));
            h4 = val.indexOf(data.charAt(i++));
            p = h1 << 18 | h2 << 12 | h3 << 6 | h4;
            o1 = p >> 16 & 0xff;
            o2 = p >> 8 & 0xff;
            o3 = p & 0xff;
            if (h3 == 64) res += String.fromCharCode(o1);
            else if (h4 == 64) res += String.fromCharCode(o1, o2);
            else               res += String.fromCharCode(o1, o2, o3);
        } while (i < data.length);

        return res;
    }
</script>
<?php if (wpm_yt_protection_is_enabled()): ?>
    <?php wpm_enqueue_script('jwplayer', plugins_url('/member-luxe/js/jwplayer/' . wpm_get_option('protection.jwplayer_version', '6') . '/jwplayer.js')); ?>
    <script>jwplayer.key = "<?php echo wpm_jw_player_code(); ?>"</script>
<?php else : ?>
    <?php wpm_enqueue_script('plyr', plugins_url('/member-luxe/js/plyr/plyr.js')); ?>
<?php endif; ?>
<?php wpm_enqueue_script('wmp-video', plugins_url('/member-luxe/js/video.js')); ?>