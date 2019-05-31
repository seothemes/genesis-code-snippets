<?php

namespace SeoThemes\GenesisCodeSnippets;

class Frontend {

	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Frontend constructor.
	 *
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run() {
		if ( is_admin() ) {
			return;
		}

		add_action( 'after_setup_theme', [ $this, 'load_php' ], 15 );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_css' ] );
		add_action( 'wp_footer', [ $this, 'load_js' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_php() {
		if ( is_admin() || ! $this->plugin->php ) {
			return;
		}

		$file = $this->plugin->cache . $this->plugin->handle . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;

			return;
		}

		eval( $this->plugin->php );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_css() {
		if ( ! $this->plugin->css ) {
			return;
		}

		$path = $this->plugin->cache . $this->plugin->handle . '.css';
		$url  = content_url( '/cache/' . $this->plugin->handle . '.css' );

		wp_enqueue_style(
			$this->plugin->handle,
			file_exists( $path ) ? $url : '',
			[],
			false,
			'all'
		);

		if ( ! file_exists( $path ) ) {

			// Add inline style for child theme.
			wp_add_inline_style( genesis_get_theme_handle(), $this->plugin->css );
		}

	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_js() {
		if ( '' === $this->plugin->js ) {
			return;
		}

		$path = $this->plugin->cache . $this->plugin->handle . '.js';
		$url  = content_url( '/cache/' . $this->plugin->handle . '.js' );

		wp_enqueue_script(
			$this->plugin->handle,
			file_exists( $path ) ? $url : '',
			[ 'jquery' ],
			false,
			true
		);

		if ( ! file_exists( $path ) ) {
			?>
			<script type="text/javascript">
                jQuery(function ($) {
					<?php echo $this->plugin->js; ?>
                });
			</script>
			<?php
		}
	}
}
