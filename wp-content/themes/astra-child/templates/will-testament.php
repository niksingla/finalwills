<?php 
/* Template Name: Will Testament Service  */ 

//Terminate if the Wills Helper plugin is not activated
if (!is_plugin_active( 'wills-helper/wills-helper.php')) {    
    echo 'Wills Helper Plugin is not active, activate it first.';
    exit;
}
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');

get_header();
if(is_user_logged_in()){
?>
<!-- Code here -->
<?php
}else{
    echo '<h2>This content is restricted</h2>';
}
get_footer();
?>
