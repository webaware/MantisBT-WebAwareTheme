<?php

/**
 * WebAware Theme plugin
 */
class WebAwareThemePlugin extends MantisPlugin  {

	protected $fonts;

	/**
	 * register the plugin
	 */
	function register() {
		$this->name			= 'WebAware Theme';
		$this->description	= 'Custom styling for WebAware Mantis';
		$this->page			= '';

		$this->version		= '1.0.0';
		$this->requires		= [
			'MantisCore'	=> '2.0.0',
		];

		$this->author		= 'WebAware';
		$this->contact		= 'support@webaware.com.au';
		$this->url			= 'https://shop.webaware.com.au/';
	}

	/**
	 * initialise the plugin
	 */
	function init() {
		// register the Google webfonts we'll be loading
		global $g_font_family_choices_local;

		$this->fonts = [
			'Droid Sans'		=> 'Droid+Sans:ital,wght@0,400;0,700;1,400;1,700',
			'Fira Code'			=> 'Fira+Code:wght@400;700',
			'Literata'			=> 'Literata:ital,wght@0,400;0,700;1,400;1,700',
			'Lora'				=> 'Lora:ital,wght@0,400;0,700;1,400;1,700',
			'Roboto Slab'		=> 'Roboto+Slab:wght@400;700',
			'Source Code Pro'	=> 'Source+Code+Pro:wght@400;700',
			'Source Sans Pro'	=> 'Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700',
			'Source Serif Pro'	=> 'Source+Serif+Pro:wght@400;700',
			'Work Sans'			=> 'Work+Sans:ital,wght@0,400;0,700;1,400;1,700',
		];

		$g_font_family_choices_local = array_merge($g_font_family_choices_local, array_keys($this->fonts));
		sort($g_font_family_choices_local);
	}

	/**
	 * plugin hooks
	 * @return array
	 */
	function hooks() {
		return [
			'EVENT_CORE_HEADERS'		=> 'csp_headers',
			'EVENT_LAYOUT_RESOURCES'	=> 'resources',
		];
	}

	/**
	 * add Google Webfonts to CSP headers if not set for CDN, allowing local fonts too
	 */
	function csp_headers() {
		if (config_get_global('cdn_enabled') === OFF) {
			http_csp_add('style-src', 'fonts.googleapis.com');
			http_csp_add('font-src', "'self'");
			http_csp_add('font-src', 'fonts.gstatic.com');
		}
	}

	/**
	 * check for required webfont, load if selected
	 */
	function resources() {
		$t_font_family = config_get('font_family', null, null, ALL_PROJECTS);
		echo "\n\n<!-- $t_font_family -->\n\n";

		if (isset($this->fonts[$t_font_family])) {
			$spec	= $this->fonts[$t_font_family];
			$url	= "https://fonts.googleapis.com/css2?family={$spec}&display=swap";

			printf('<link rel="stylesheet" type="text/css" href="%s" crossorigin="anonymous" />', $url);
		}
	}

}
