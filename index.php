<?php
/**
 * MyTTI Announcements
 *
 * @package   MyTTI_Announcements
 * @copyright 2024 Texas A&M Transportation Institute
 * @author    Travis Ward <t-ward@tti.tamu.edu>
 * @license   GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       MyTTI Announcements
 * Plugin URI:        https://github.com/ttitamu/mytti24-announcements
 * Description:       A plugin for the MyTTI website to manage and feed data to HQ video boards
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      8.0
 * Author:            Travis Ward
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mytti-announcements-textdomain
 * Update URI:        https://github.com/ttitamu/mytti24-announcements
 */

namespace MyTTI_Announcements;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

const PLUGIN_FILE   = __FILE__;
const PLUGIN_KEY    = 'mytti-announcements';
const POST_TYPE_KEY = 'mytti_announcements';

define( 'MyTTI_Announcements\PLUGIN_URL', plugins_url( 'src', __FILE__ ) . '/' );

//require 'src/functions.php';
require 'src/mytti-announcements-post-type.php';