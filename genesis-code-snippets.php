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

// Load plugin.
require_once __DIR__ . '/src/class-plugin.php';

// Run plugin.
$genesis_code_snippets = new Plugin( __FILE__ );
$genesis_code_snippets->run();
