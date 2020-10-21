var wpmVideo = {
    initYT     : function (videoId, file, ratio, autostart, flash) {
        flash = flash || false;
        var options, player;

        options = {
            file        : location.protocol + file,
            width       : "100%",
            aspectratio : ratio,
            autostart   : autostart,
            stretching  : "fill",
            androidhls  : true
        };

        if (flash) {
            options.primary = "flash";
        } else {
            options.fallback = false;
        }

        player = jwplayer(videoId).setup(options);

        player.onPlay(function () {
            var _this = $(this.container);
            setTimeout(function () {
                _this.closest('.wpmjw').removeClass("inactive");
            }, 1000);
            $("#" + videoId).hover(function () {
                var $this = $(this),
                    interval = setInterval(function () {
                        if ($this.is(":hover")) {
                            $this.removeClass("jw-flag-user-inactive")
                        } else {
                            clearInterval(interval);
                        }
                    }, 50);
                $(this).removeClass("jw-flag-user-inactive");
            });
        });
    },
    initDirect : function (id, type, link, autoplay) {
        var players,
            isIphone,
            video,
            options,
            browserOptions,
            holder;

        browserOptions = _browserSniff();

        options = {
            source : {
                type    : type,
                sources : [{
                    src  : link,
                    type : 'video/mp4'
                }]
            },
            autoplay : autoplay
        };

        if (isIphone && type == 'video') {
            options.controls = ['play-large', 'play', 'progress', 'current-time'];
        }

        players = plyr.setup('#' + id, options);
        players[0].source({
            type     : type,
            sources  : [{
                src : link
            }],
            tooltips : {
                controls : true
            },
            captions : {
                defaultActive : false
            }
        });
        if (autoplay) {
            players[0].play();
        }

        if (browserOptions.isIphone && browserOptions.version < 10 && type == 'video') {
            jQuery(players[0].getContainer()).on('click', function () {
                if (!players[0].isFullscreen() && players[0].isPaused() !== false) {
                    players[0].play();
                    return false;
                }
            });
        } else if (browserOptions.isIphone && browserOptions.version < 10) {
            holder = jQuery(players[0].getContainer());
            holder.css('opacity', '0.01');
            holder.parent()
                .css('background', 'url(/wp-content/plugins/member-luxe/i/plyr.png)')
                .css('background-size', 'cover')
        }

        function _browserSniff() {
            var ua = navigator.userAgent,
                name = navigator.appName,
                fullVersion = '' + parseFloat(navigator.appVersion),
                majorVersion = parseInt(navigator.appVersion, 10),
                nameOffset,
                verOffset,
                ix,
                isIE = false,
                isFirefox = false,
                isChrome = false,
                isSafari = false;

            if ((navigator.appVersion.indexOf('Windows NT') !== -1) && (navigator.appVersion.indexOf('rv:11') !== -1)) {
                // MSIE 11
                isIE = true;
                name = 'IE';
                fullVersion = '11';
            } else if ((verOffset = ua.indexOf('MSIE')) !== -1) {
                // MSIE
                isIE = true;
                name = 'IE';
                fullVersion = ua.substring(verOffset + 5);
            } else if ((verOffset = ua.indexOf('Chrome')) !== -1) {
                // Chrome
                isChrome = true;
                name = 'Chrome';
                fullVersion = ua.substring(verOffset + 7);
            } else if ((verOffset = ua.indexOf('Safari')) !== -1) {
                // Safari
                isSafari = true;
                name = 'Safari';
                fullVersion = ua.substring(verOffset + 7);
                if ((verOffset = ua.indexOf('Version')) !== -1) {
                    fullVersion = ua.substring(verOffset + 8);
                }
            } else if ((verOffset = ua.indexOf('Firefox')) !== -1) {
                // Firefox
                isFirefox = true;
                name = 'Firefox';
                fullVersion = ua.substring(verOffset + 8);
            } else if ((nameOffset = ua.lastIndexOf(' ') + 1) < (verOffset = ua.lastIndexOf('/'))) {
                // In most other browsers, 'name/version' is at the end of userAgent
                name = ua.substring(nameOffset, verOffset);
                fullVersion = ua.substring(verOffset + 1);

                if (name.toLowerCase() === name.toUpperCase()) {
                    name = navigator.appName;
                }
            }

            // Trim the fullVersion string at semicolon/space if present
            if ((ix = fullVersion.indexOf(';')) !== -1) {
                fullVersion = fullVersion.substring(0, ix);
            }
            if ((ix = fullVersion.indexOf(' ')) !== -1) {
                fullVersion = fullVersion.substring(0, ix);
            }

            // Get major version
            majorVersion = parseInt('' + fullVersion, 10);
            if (isNaN(majorVersion)) {
                fullVersion = '' + parseFloat(navigator.appVersion);
                majorVersion = parseInt(navigator.appVersion, 10);
            }

            // Return data
            return {
                name      : name,
                version   : majorVersion,
                isIE      : isIE,
                isFirefox : isFirefox,
                isChrome  : isChrome,
                isSafari  : isSafari,
                isIos     : /(iPad|iPhone|iPod)/g.test(navigator.platform),
                isIphone  : /(iPhone|iPod)/g.test(navigator.userAgent),
                isTouch   : 'ontouchstart' in document.documentElement
            };
        }
    }
};