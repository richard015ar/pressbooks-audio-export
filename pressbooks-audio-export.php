<?php
/**
 * Plugin Name: Pressbooks Audio Export
 * Plugin URI: https://pressbooks.org
 * Description: Export audio files from Pressbooks through AI.
 * x-release-please-start-version
 *  Version: 1.0.0
 * x-release-please-end
 * Requires at least: 6.5
 * Requires Plugins: pressbooks
 * Requires PHP: 8.1
 * Author: Ricardo Aragon
 * Author URI: https://pressbooks.org
 * Text Domain: pressbooks-audio-export
 * License: GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Network: True
 */

use PressbooksAudioExport\Bootstrap;

require __DIR__.'/vendor/autoload.php';

add_action('plugins_loaded', [Bootstrap::class, 'run']);
