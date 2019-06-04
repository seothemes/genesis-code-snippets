<?php

namespace SeoThemes\GenesisCodeSnippets;

/**
 * Class Frontend
 *
 * @package SeoThemes\GenesisCodeSnippets
 */
class Frontend extends Plugin {

	/**
	 * Run frontend hooks.
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
	 * Load custom PHP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_php() {
		if ( is_admin() || ! $this->php ) {
			return;
		}

		$file = $this->cache . $this->handle . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;

			return;
		}

		eval( $this->php );
	}

	/**
	 * Load custom CSS.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_css() {
		if ( ! $this->css ) {
			return;
		}

		$path = $this->cache . $this->handle . '.css';
		$url  = content_url( '/cache/' . $this->handle . '.css' );

		wp_enqueue_style(
			$this->handle,
			file_exists( $path ) ? $url : '',
			[],
			false,
			'all'
		);

		if ( ! file_exists( $path ) ) {

			// Add inline style for child theme.
			wp_add_inline_style( genesis_get_theme_handle(), $this->css );
		}

	}

	/**
	 * Load custom JS.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_js() {
		if ( '' === $this->js ) {
			return;
		}

		$path = $this->cache . $this->handle . '.js';
		$url  = content_url( '/cache/' . $this->handle . '.js' );

		wp_enqueue_script(
			$this->handle,
			file_exists( $path ) ? $url : '',
			[ 'jquery' ],
			false,
			true
		);

		if ( ! file_exists( $path ) ) {
			?>
			<script type="text/javascript">
                jQuery(function ($) {
					<?php echo $this->js; ?>
                });
			</script>
			<?php
		}
	}
}
