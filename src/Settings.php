<?php

namespace SeoThemes\GenesisCodeSnippets;

/**
 * Class Settings
 *
 * @package SeoThemes\GenesisCodeSnippets
 */
class Settings extends Plugin {

	/**
	 * Initialize settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'add_menu_item' ] );
	}

	/**
	 * Add settings page to admin menu.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_menu_item() {
		add_menu_page(
			$this->name,
			str_replace( 'Genesis ', '', $this->name ),
			'manage_options',
			$this->handle,
			[ $this, 'settings_page' ],
			'dashicons-editor-code',
			59
		);
	}

	/**
	 * Display settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function settings_page() {
		$tab = $this->get_tab();
		$id  = $this->prefix( $tab );

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
						esc_attr( $type === $tab ? 'nav-tab-active' : '' ),
						esc_html( strtoupper( $type ) )
					);
				}
				?>
			</h2>
			<form method="post" action="options.php" enctype="multipart/form-data" class="<?php esc_attr_e( $this->handle ); ?>">
				<?php
				settings_fields( $id );
				do_settings_sections( $this->handle );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_settings() {
		$tab = $this->get_tab();
		$id  = $this->prefix( $tab );

		// Add all settings sections (options.php workaround).
		foreach ( $this->types as $type ) {
			add_settings_section(
				$this->prefix( $type ),
				'',
				'',
				$this->handle
			);
		}

		// Only register the current tab settings.
		register_setting(
			$id,
			$id
		);

		// Only add the current tab settings field.
		add_settings_field(
			$id,
			'',
			[ $this, 'display_field' ],
			$this->handle,
			$id,
			$id
		);
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
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'php';

		if ( ! in_array( $tab, $this->types ) ) {
			return 'php';
		}

		return $tab;
	}
}
