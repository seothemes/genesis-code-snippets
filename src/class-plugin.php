<?php

namespace SeoThemes\GenesisCodeSnippets;

class Plugin {

	/**
	 * @var
	 */
	public $file;

	/**
	 * @var string
	 */
	public $dir;

	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var array|null
	 */
	public $data;

	/**
	 * @var mixed
	 */
	public $name;

	/**
	 * @var mixed
	 */
	public $version;

	/**
	 * @var mixed
	 */
	public $handle;

	/**
	 * @var array
	 */
	public $types;

	/**
	 * @var mixed|void
	 */
	public $php;

	/**
	 * @var mixed|void
	 */
	public $css;

	/**
	 * @var mixed|void
	 */
	public $js;

	/**
	 * @var string
	 */
	public $cache;

	/**
	 * @var
	 */
	protected $admin;

	/**
	 * @var
	 */
	protected $settings;

	/**
	 * @var
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
		$this->php     = get_option( $this->handle . '-php' );
		$this->css     = get_option( $this->handle . '-css' );
		$this->js      = get_option( $this->handle . '-js' );
		$this->cache   = WP_CONTENT_DIR . '/cache/';
	}

	/**
	 * Description of expected behavior.
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
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function run() {
		spl_autoload_register( [ $this, 'autoload' ] );
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
		add_action( 'plugins_loaded', [ $this, 'load_settings' ] );
		add_action( 'admin_init', [ $this, 'load_admin' ] );
		add_action( 'after_setup_theme', [ $this, 'load_frontend' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $class
	 *
	 * @return null|string
	 */
	protected function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return null;
		}

		$file_name = strtolower( str_replace( [ __NAMESPACE__, '\\' ], '', $class ) );
		$file      = $this->dir . '/src/class-' . $file_name . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}

		return $file;
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( $this->handle, false, basename( dirname( $this->file ) ) . '/assets/' );
	}

	/**
	 * Add the Theme Settings Page
	 *
	 * @since 1.0.0
	 */
	public function load_admin() {
		$this->admin = new Admin( $this );
		$this->admin->run();
	}

	/**
	 * Add the Theme Settings Page
	 *
	 * @since 1.0.0
	 */
	public function load_settings() {
		$this->settings = new Settings( $this );
		$this->settings->run();
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_frontend() {
		$this->frontend = new Frontend( $this );
		$this->frontend->run();
	}

}
