<?php

class MBLHeader
{
    private $options;


    /**
     * MBLHeader constructor.
     */
    public function __construct()
    {
        if ($this->showHeader()) {
            $levels_ids = wpm_get_all_user_accesible_levels(get_current_user_id());
            $headers = explode(',', wpm_get_option('header_bg.priority', ''));
            $current = array();
            foreach ($headers as $item) {
                if (in_array($item, $levels_ids) && !wpm_option_is('header_bg.' . $item . '.disabled', 'disabled')) {
                    $current = wpm_get_option('header_bg.' . $item);
                    break;
                }
            }
            if (empty($current)) {
                $current = wpm_get_option('header_bg.default');
            }

            $this->options = $current;
        }
    }

    public function isVisible()
    {
        return wpm_option_is('header.visible', 'on');
    }

    public function getLogo()
    {
        return wpm_remove_protocol(wpm_get_option('logo.url', plugins_url('/member-luxe/2_0/images/memberlux_logo.svg')));
    }

    public function hasLogo()
    {
        return ($this->getOption('hide_logo') != 'on' || !$this->isVisible()) && wpm_get_option('logo.url', plugins_url('/member-luxe/2_0/images/memberlux_logo.svg'));
    }

    public function showHeader()
    {
        return $this->isVisible() || $this->getLogo();
    }

    public function getHeaderImage()
    {
        return wpm_remove_protocol($this->getOption('url', plugins_url('/member-luxe/2_0/images/assets/cover-image.jpg')));
    }

    public function getHeaderLink()
    {
        return $this->getOption('link');
    }

    public function getHeaderLinkTarget()
    {
        return $this->getOption('link_target', 'blank');
    }

    public function getOption($key = null, $default = null)
    {
        return wpm_array_get($this->options, $key, $default);
    }
}