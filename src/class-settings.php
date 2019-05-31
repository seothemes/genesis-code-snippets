<?php

namespace SeoThemes\GenesisCodeSnippets;

class Settings {

	/**
	 * The main plugin object.
	 *
	 * @var    object
	 * @access public
	 * @since  1.0.0
	 */
	public $plugin = null;

	/**
	 * Available settings for plugin.
	 *
	 * @var    array
	 * @access public
	 * @since  1.0.0
	 */
	public $settings = [];

	/**
	 * Constructor.
	 *
	 * @param Plugin $plugin Parent class.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	public function run() {
		add_action( 'admin_init', [ $this, 'register_settings' ], 11 );
		add_action( 'admin_menu', [ $this, 'add_menu_item' ] );
		add_action( 'whitelist_options', [ $this, 'whitelist_options' ], 11 );

	}

	/**
	 * Add settings page to admin menu.
	 *
	 * @return void
	 */
	public function add_menu_item() {
		add_menu_page(
			$this->plugin->name,
			str_replace( 'Genesis ', '', $this->plugin->name ),
			'manage_options',
			$this->plugin->handle,
			[ $this, 'settings_page' ],
			'dashicons-editor-code',
			59
		);
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( $this->plugin->name ); ?></h1>
			<?php $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'php'; ?>
			<?php $id = $this->plugin->handle . '-' . $tab; ?>
			<h2 class="nav-tab-wrapper wide-tabs">
				<?php
				foreach ( $this->plugin->types as $type ) {
					printf(
						'<a href="?page=%s&tab=%s" class="nav-tab %s">%s</a>',
						esc_attr( $this->plugin->handle ),
						esc_attr( $type ),
						esc_attr( $type === $tab ? 'nav-tab-active' : '' ),
						esc_html( strtoupper( $type ) )
					);
				}
				?>
			</h2>
			<form method="post" action="options.php" enctype="multipart/form-data" class="<?php esc_attr_e( $this->plugin->handle ); ?>">
				<input type="hidden" name="field" id="field" value="<?php esc_attr_e( $tab ); ?>">
				<?php
				settings_fields( $id );
				do_settings_sections( $this->plugin->handle );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_settings() {
		$tab     = isset( $_GET['tab'] ) ? $_GET['tab'] : 'php';
		$setting = $this->plugin->handle . '-' . $tab;

		foreach ( $this->plugin->types as $type ) {
			$id = $this->plugin->handle . '-' . $type;

			add_settings_section(
				$id,
				'',
				'',
				$this->plugin->handle
			);

			register_setting(
				$id,
				$id
			);
		}

		// Only add the current settings field.
		add_settings_field(
			$setting,
			'',
			[ $this, 'display_field' ],
			$this->plugin->handle,
			$setting,
			$setting
		);
	}

	/**
	 * Description of expected behavior.
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

	public function whitelist_options( $whitelist_options ) {
		foreach ( $this->plugin->types as $type ) {
			$whitelist_options[ $type ] = true;
		}

		return $whitelist_options;
	}
}
