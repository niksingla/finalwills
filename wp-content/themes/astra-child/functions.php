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
	?>
	<script>console.log(<?php echo json_encode(get_post(get_the_ID())) ?>)</script>
	<?php
}
add_action('wp_footer','test_script');

