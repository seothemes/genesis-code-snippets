<?php
/**
 * Plugin Name: Genesis Code Snippets
 * Plugin URI:  https://seothemes.com
 * Description: Lets you add custom code snippets to Genesis sites.
 * Author:      SEO Themes
 * Author URI:  https://seothemes.com
 * Version:     1.0.0
 * Text Domain: genesis-code-snippets
 * Domain Path: /languages/
 * License:     GPL-3.0-or-later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

namespace SeoThemes\GenesisCodeSnippets;

// Load classes.
spl_autoload_register( function ( $class ) {
	$file = __DIR__ . '/src/' . substr( strrchr( $class, '\\' ), 1 ) . '.php';

	if ( is_readable( $file ) ) {
		require_once $file;
	}
} );

// Initialize plugin.
$genesis_code_snippets = new Plugin( __FILE__ );

// Run.
$genesis_code_snippets->run();
