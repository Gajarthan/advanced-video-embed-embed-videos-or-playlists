<?php
/*
Plugin Name: Advanced Video Embed - embed videos or playlists
Description: Adavnced Video embed free version supports youtube video embed into your wordpress posts, with easy to use search panel along side you can also create youtube playlists within the search panel and generate its shortcode to use in posts
Author: Arsh Singh,Meenakshi Goyal
Author Uri: http://www.dscom.it
Version: 1.0

Advanced Video Embed
Copyright (C) 2015,Arsh Singh, Meenakshi Goyal

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
define( 'AVE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
/*
Including content for css and js
*/
require_once(AVE_PLUGIN_DIR.'/inc/aveloadContent.php');

/*
Including admin settings & videos page setting file
*/
require_once(AVE_PLUGIN_DIR.'/inc/classes/class.aveSettings.php');
/*
Including shortcodes file
*/
require_once(AVE_PLUGIN_DIR.'/inc/classes/class.aveShortcodes.php');
/*
Including post class file
*/
require_once(AVE_PLUGIN_DIR.'/inc/classes/class.avePost.php');
/*
Calling main admin class AveAdmin;
*/
new AveAdmin();
/*
Calling main shortcode class Shortcodes_ave
*/
new AveShortcodes();
/*
Calling main ave post saving class AvePost
*/
new AvePost();
/*
Adding settings page to the admin menu on left side.
*/
function ave_settingPanel(){
  add_submenu_page("ave_search","A.v.e Settings",
  "A.v.e Settings",
  "administrator",
  "ave_settingspanel",
  "ave_settingsPanel"
	);
  add_action('admin_init', 'ave_registerSettings');
  //call register settings function
}
add_action('admin_menu', 'ave_settingPanel');
// -- Function Name : register_rss_mysettings
// -- Params : NULL
// -- Purpose : Registering Plugin settings(inputs)
function ave_registerSettings(){
  register_setting('ave-opts', 'ave-yt-api');
}

function ave_settingsPanel(){
  require_once AVE_PLUGIN_DIR.'/inc/views/setting.php';
}
