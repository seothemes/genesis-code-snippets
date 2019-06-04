<?php

namespace SeoThemes\GenesisCodeSnippets;

/**
 * Class Admin
 *
 * @package SeoThemes\GenesisCodeSnippets
 */
class Admin extends Plugin {

	/**
	 * Run admin hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'admin_menu', [ $this, 'add_menu_item' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_init', [ $this, 'write_to_file' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ), [ $this, 'action_links' ] );
	}

	/**
	 * Add settings page to admin menu.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_menu_item() {
		$menu = add_menu_page(
			$this->name,
			str_replace( 'Genesis ', '', $this->name ),
			'manage_options',
			$this->handle,
			[ $this, 'settings_page' ],
			'dashicons-editor-code',
			59
		);

		add_action( 'admin_print_styles-' . $menu, [ $this, 'enqueue_styles' ] );
		add_action( 'admin_print_scripts-' . $menu, [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Load admin styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->prefix( 'admin' ),
			$this->url . 'assets/admin.css'
		);
	}

	/**
	 * Load admin scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_code_editor( [
			'type'       => 'text/x-php',
			'codemirror' => [
				'scrollbarStyle' => 'null',
			],
		] );

		wp_enqueue_script(
			$this->prefix( 'admin' ),
			$this->url . 'assets/admin.js', [ 'jquery' ]
		);
	}

	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_settings() {
		$tab  = $this->get_tab();
		$id   = $this->prefix( $tab );
		$page = $this->handle;

		add_settings_section( $id, $tab, '', $page );
		add_settings_field( $id, $id, [ $this, 'display_field' ], $page, $id, $id );
		register_setting( $page, $id );
	}

	/**
	 * Display settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( $this->name ); ?></h1>
			<h2 class="nav-tab-wrapper wide-tabs">
				<?php
				foreach ( $this->types as $type ) {
					printf(
						'<a href="?page=%s&tab=%s" class="nav-tab %s">%s</a>',
						esc_attr( $this->handle ),
						esc_attr( $type ),
						esc_attr( $type === $this->get_tab() ? 'nav-tab-active' : '' ),
						esc_html( strtoupper( $type ) )
					);
				}
				?>
			</h2>
			<form method="post" action="options.php" enctype="multipart/form-data" class="<?php esc_attr_e( $this->handle ); ?>">
				<input type="hidden" name="tab" value="<?php esc_attr_e( $this->get_tab() ); ?>">
				<?php
				settings_fields( $this->handle );
				do_settings_sections( $this->handle );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Display single textarea field.
	 *
	 * @since 1.0.0
	 *
	 * @param $id
	 *
	 * @return void
	 */
	public function display_field( $id ) {
		printf(
			'<textarea id="%s" name="%s">%s</textarea>',
			esc_attr( $id ),
			esc_attr( $id ),
			get_option( $id )
		);
	}

	/**
	 * Validates and sanitizes tab.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_tab() {
		if ( isset( $_POST['tab'] ) && in_array( $_POST['tab'], $this->types ) ) {
			$tab = $_POST['tab'];

		} elseif ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $this->types ) ) {
			$tab = $_GET['tab'];

		} else {
			$tab = 'php';
		}

		return sanitize_text_field( $tab );
	}

	/**
	 * Generate static files.
	 *
	 * @since 1.0.0
	 *
	 * @uses  \WP_Filesystem()
	 *
	 * @return void
	 */
	public function write_to_file() {
		include_once ABSPATH . 'wp-admin/includes/file.php';
		\WP_Filesystem();
		global $wp_filesystem;

		// Create cache directory.
		if ( ! is_dir( $this->cache ) ) {
			wp_mkdir_p( $this->cache );
		}

		// Create custom code files.
		foreach ( $this->types as $type ) {
			$file = $this->cache . $this->handle . '.' . $type;
			$code = $this->$type;

			// Skip if code has not changed.
			if ( $wp_filesystem->get_contents( $file ) === $code ) {
				continue;
			}

			// Add opening PHP tag.
			if ( 'php' === $type ) {
				$code = "<?php\n" . $code;
			}

			// Wrap JS code in jQuery function.
			if ( 'js' === $type ) {
				$code = 'jQuery(function ($) {' . $code . '});';
			}

			$wp_filesystem->put_contents( $file, $code );
		}
	}

	/**
	 * Add settings link to plugins list.
	 *
	 * @since 1.0.0
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function action_links( $links ) {
		return array_merge( $links, [
			sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=' . $this->handle ),
				__( 'Settings', 'genesis-code-snippets' ) ),
		] );
	}
}
