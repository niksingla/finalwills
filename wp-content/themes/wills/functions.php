<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	// wp_enqueue_style( 'child-style',
	// 	get_stylesheet_uri(),
	// 	array( 'parenthandle' ),
	// 	wp_get_theme()->get( 'Version' ) // This only works if you have Version defined in the style header.
	// );
	
	$css_url = get_stylesheet_directory_uri().'/assets/css/';
	$js_url = get_stylesheet_directory_uri().'/assets/js/';
	wp_register_script( 'bootstrap', $js_url . 'bootstrap.bundle.js', array(), ASTRA_THEME_VERSION, true );
	wp_register_style( 'bootstrap', $css_url . 'bootstrap.min.css', array(), ASTRA_THEME_VERSION, 'all' );
	
}

function test_script(){

}
// add_action('wp_footer','test_script');

function update_form_data(){
	$data = $_POST['data'];
	$user_id = get_current_user_id();
	$update_data = update_user_meta($user_id,'wills_form_data',$data);
	if($update_data){
		wp_send_json_success(['data'=>$data]);
	}
	else{
		wp_send_json_error(['message'=>'Unknown Error']);
	}
	wp_die();
}
add_action('wp_ajax_update_form_data','update_form_data');

function updateNumViews(){
	$user_id = get_current_user_id();
	if($user_id){
		$current_val = get_user_meta($user_id,'numViews',true);
		if(empty($current_val) || !isset($current_val)){
			update_user_meta($user_id,'numViews',1);
		} else if($current_val >= 5){
			wp_send_json_error();
			die();	
		} else {
			update_user_meta($user_id,'numViews',++$current_val);
		}
		wp_send_json_success(['current'=>$current_val]);
		die();
	}else{
		wp_send_json_error();
		die();
	}
}
add_action('wp_ajax_updateNumViews','updateNumViews');