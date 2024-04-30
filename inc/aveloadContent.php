<?php
/*
@AVE(advanced video embed)
Adding the stylesheet and javascript file.
*/
function ave_loadContent(){
  wp_enqueue_script(
		'ave_js_load',
		plugins_url( 'js/ave_load.js' , __FILE__ )
	);
  wp_enqueue_style(
		'ave_css_load',
		plugins_url( '/css/ave.css' , __FILE__ )
	);
}
add_action('admin_head','ave_loadContent');
