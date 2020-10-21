function initAudio(id, src, autoplay, color) {
    var progressColor,
        waveColor,
        cursorColor,
        object,
        wavesurfer;

    object = jQuery('#' + id);

    if (color == 'black') {
        waveColor = '#dedede';
        progressColor = '#7f7f7f'
        cursorColor = '#333';
    } else {
        waveColor = '#cccccc';
        progressColor = '#4f4f4f';
        cursorColor = '#d3d9df';
    }

    var options = {
        container     : object.find('.wpm-audio-player')[0],
        waveColor     : waveColor,
        progressColor : progressColor,
        height        : 100,
        cursorColor   : cursorColor,
        pixelRatio    : 1,
        normalize     : true
    };
    wavesurfer = WaveSurfer.create(options);

    wavesurfer.load(src);

    wavesurfer.on('ready', function () {
        if (autoplay) {
            wavesurfer.play();
        }
        object.find('.wpm-audio-loader').remove();
    });


    jQuery(window).resize(function () {
        wavesurfer.drawer.containerWidth = wavesurfer.drawer.container.clientWidth;
        wavesurfer.drawBuffer();
    });

    var timeblock = jQuery(object).find('.wpm-audio-time');
    var duration = jQuery(object).find('.wpm-audio-duration');

    // Controls Definition
    var buttonPlay = jQuery(object).find('button.wpm-audio-play');
    var buttonStop = jQuery(object).find('button.wpm-audio-stop');
    var buttonMute = jQuery(object).find('button.wpm-audio-mute');
    var buttonDownload = jQuery(object).find('button.wpm-audio-download');
    var buttonLoop = jQuery(object).find('button.wpm-audio-loop');
    var progressBar = jQuery(object).find('progress');

    wavesurfer.on('error', function () {
        progressBar.hide();
    });

    // Timecode during Play
    wavesurfer.on('audioprocess', function () {
        var current_time = wavesurfer.getCurrentTime();
        timeblock.html(secondsTimeSpanToMS(current_time));
    });

    // Timecode and duration at Ready
    wavesurfer.on('ready', function () {
        progressBar.hide();
        var audio_duration = wavesurfer.getDuration();
        duration.html(secondsTimeSpanToMS(audio_duration));
        var current_time = wavesurfer.getCurrentTime();
        timeblock.html(secondsTimeSpanToMS(current_time));
    });

    // Timecode during pause + seek
    wavesurfer.on('seek', function () {
        var current_time = wavesurfer.getCurrentTime();
        timeblock.html(secondsTimeSpanToMS(current_time));
    });

    // Add Active class on all stop button at init stage
    buttonStop.addClass('wpm-audio-active-button');

    // Controls Functions
    buttonPlay.click(function () {

        wavesurfer.playPause();

        // IF PLAYING -> TO PAUSE
        if (jQuery(this).hasClass('wpm-audio-active-button')) {

            SetPauseButton(this);

        } else {
            // Add an active class
            jQuery(this).addClass('wpm-audio-active-button');

            // Remove active class from the other buttons
            jQuery(this).parent().children('button.wpm-audio-play').removeClass('wpm-audio-paused-button');
            jQuery(this).parent().children('button.wpm-audio-stop').removeClass('wpm-audio-active-button');
        }

    });
    buttonStop.click(function () {
        wavesurfer.stop();

        if (!jQuery(this).hasClass('wpm-audio-active-button')) {

            jQuery(this).addClass('wpm-audio-active-button');
            jQuery(this).parent().children('button.wpm-audio-play').removeClass('wpm-audio-active-button');
            jQuery(this).parent().children('button.wpm-audio-play').removeClass('wpm-audio-paused-button');
            var current_time = wavesurfer.getCurrentTime();
            timeblock.html(secondsTimeSpanToMS(current_time));
        }
    });

    // Button Mute
    buttonMute.click(function () {
        wavesurfer.toggleMute();

        // IF ACTIVE
        if (jQuery(this).hasClass('wpm-audio-active-button')) {
            jQuery(this).removeClass('wpm-audio-active-button');
        } else {
            jQuery(this).addClass('wpm-audio-active-button');
        }

    });

    // Define Stop button
    buttonDownload.click(function () {
        var audio = jQuery(this).parent().parent('.wpm-audio-block').children('.wpm-audio-player');

        var download_url = audio.data('url');
        // Get FileName from URL
        var index = download_url.lastIndexOf("/") + 1;
        var file_name = download_url.substr(index);
        jQuery(this).children('a').attr('href', download_url);
        jQuery(this).children('a').attr('download', file_name);

        // then download
        download(download_url);
    });

    // On finish, remove active class on play
    wavesurfer.on('finish', function () {
        if (playlist === false) {
            if (buttonLoop.hasClass('wpm-audio-active-button') === false) {
                buttonPlay.removeClass('wpm-audio-active-button');
                buttonStop.addClass('wpm-audio-active-button');
            }
        }
    });

    // Button Loop
    buttonLoop.click(function () { // NOTE: seamless loop need WebAudio backend
        // IF LOOP
        if (jQuery(this).hasClass('wpm-audio-active-button')) {
            jQuery(this).removeClass('wpm-audio-active-button');
            wavesurfer.on('finish', function () {
                wavesurfer.pause();
            });
        } else {
            jQuery(this).addClass('wpm-audio-active-button');
            wavesurfer.on('finish', function () {
                wavesurfer.play();
            });
        }
    });

    // Convert seconds into MS
    function secondsTimeSpanToMS(s) {
        var m = Math.floor(s / 60); //Get remaining minutes
        s -= m * 60;
        s = Math.floor(s);
        return (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s); //zero padding on minutes and seconds
    } // End secondsTimeSpanToMS

    // Set Button to Pause
    function SetPauseButton(object) {
        jQuery(object).removeClass('wpm-audio-active-button');

        jQuery(object).addClass('wpm-audio-paused-button');

        jQuery(object).parent().children('button.wpm-audio-play').removeClass('wpm-audio-active-button');
        jQuery(object).parent().children('button.wpm-audio-stop').removeClass('wpm-audio-active-button');
    }
}