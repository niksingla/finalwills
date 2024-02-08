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
	$order = wc_get_order(435);
	if($order){
		$items = $order->get_items();
		foreach ($items as $item_id => $item) {     
			$product_id = $item->get_product_id();
		}
		$product = wc_get_product($product_id);
		$duration = $product->get_attribute('duration');
		$user_id = get_current_user_id(); 
		$currentExpiry = date(get_user_meta($user_id,'acc_expires_on',true));
		$ogDateTime = DateTime::createFromFormat(EXP_DATE_FORMAT, $currentExpiry);
		$modifiedDateTime = $ogDateTime->modify('+3 years');

		$new_expiration_date = $modifiedDateTime->format(EXP_DATE_FORMAT);
		// if(date(EXP_DATE_FORMAT,time())>$currentExpiry){
		// 	$new_expiration_date = date(EXP_DATE_FORMAT, strtotime("+{$duration} years"));
		// }else{
		// 	$ogDateTime = DateTime::createFromFormat(EXP_DATE_FORMAT, $currentExpiry);
		// 	$modifiedDateTime = $ogDateTime->modify('+3 years');

		// 	$new_expiration_date = $modifiedDateTime->format(EXP_DATE_FORMAT);
		// }
	}
	?>
	<script>console.log("<?php echo $new_expiration_date; ?>")</script>
	<?php
}
// add_action('wp_footer','test_script');



