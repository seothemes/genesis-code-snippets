<?php

namespace SeoThemes\GenesisCodeSnippets;

/**
 * Class Plugin
 *
 * @package SeoThemes\GenesisCodeSnippets
 */
class Plugin {

	/**
	 * @var
	 */
	protected $file;

	/**
	 * @var string
	 */
	protected $dir;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var array|null
	 */
	protected $data;

	/**
	 * @var mixed
	 */
	protected $name;

	/**
	 * @var mixed
	 */
	protected $version;

	/**
	 * @var mixed
	 */
	protected $handle;

	/**
	 * @var array
	 */
	protected $types;

	/**
	 * @var mixed|void
	 */
	protected $php;

	/**
	 * @var mixed|void
	 */
	protected $css;

	/**
	 * @var mixed|void
	 */
	protected $js;

	/**
	 * @var string
	 */
	protected $cache;

	/**
	 * @var Admin
	 */
	protected $admin;

	/**
	 * @var Frontend
	 */
	protected $frontend;

	/**
	 * Plugin constructor.
	 *
	 * @param $file
	 */
	public function __construct( $file ) {
		$this->file    = $file;
		$this->dir     = dirname( $file );
		$this->url     = plugin_dir_url( $file );
		$this->data    = $this->get_data( $file );
		$this->name    = $this->data['name'];
		$this->version = $this->data['version'];
		$this->handle  = $this->data['handle'];
		$this->types   = [ 'php', 'css', 'js' ];
		$this->php     = get_option( $this->prefix( 'php' ) );
		$this->css     = get_option( $this->prefix( 'css' ) );
		$this->js      = get_option( $this->prefix( 'js' ) );
		$this->cache   = WP_CONTENT_DIR . '/cache/';
	}

	/**
	 * Initialize plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
		add_action( 'plugins_loaded', [ $this, 'load_admin' ] );
		add_action( 'plugins_loaded', [ $this, 'load_frontend' ] );
	}

	/**
	 * Load plugin textdomain
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( $this->handle, false, basename( $this->dir ) . '/assets/' );
	}

	/**
	 * Load admin functionality.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_admin() {
		if ( is_admin() ) {
			$this->admin = new Admin( $this->file );
			$this->admin->run();
		}
	}

	/**
	 * Load frontend functionality.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_frontend() {
		if ( ! is_admin() ) {
			$this->frontend = new Frontend( $this->file );
			$this->frontend->run();
		}
	}

	/**
	 * Get data from plugin file header comment.
	 *
	 * @since 1.0.0
	 *
	 * @param $file
	 *
	 * @return array|null
	 */
	private function get_data( $file ) {
		static $data = null;

		if ( is_null( $data ) ) {
			$data = get_file_data( $file, [
				'name'    => 'Plugin Name',
				'version' => 'Version',
				'handle'  => 'Text Domain',
			] );
		}

		return $data;
	}

	/**
	 * Utility method to prefix a given string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string String to prefix.
	 *
	 * @return string
	 */
	protected function prefix( $string = '' ) {
		return $this->handle . '-' . $string;
	}
}
