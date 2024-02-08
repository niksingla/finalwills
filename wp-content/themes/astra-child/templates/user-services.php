<?php 
/* Template Name: User Services  */ 

//Terminate if the Wills Helper plugin is not activated
if(function_exists('is_plugin_active')){
    if (!is_plugin_active( 'wills-helper/wills-helper.php')) {    
        echo 'Wills Helper Plugin is not active, activate it first.';
        exit;
    }
}
wp_enqueue_style('bootstrap');
wp_enqueue_script('bootstrap');

get_header();
if(is_user_logged_in()){
    $user_id = get_current_user_id(); 
    $userdata = get_userdata($user_id);
    $user_meta = get_user_meta( $user_id);
    $date_display_format = "F d, Y";
    echo $user_meta['expiration_date'][0];
    $isMemberExpired = date(EXP_DATE_FORMAT,time()) > $user_meta['acc_expires_on'][0];
    
    // echo '<pre>';
    // print_r($user_meta);
    // echo '</pre>';    
?>
<div class="services-wrapper py-5">
    <div class="inner-section">
        <div class="welcome-section">
            <p>Welcome, <?= $user_meta['first_name'][0]; ?></p>
            <a class="btn btn-info logout-btn" href="<?= site_url('logout')?>">Logout</a>
        </div>    
        <div class="membership-section">
            <p>Member since: <span><?= date($date_display_format,strtotime($user_meta['acc_created_on'][0]) ) ?></span></p>
            <?php
            if(!$isMemberExpired){ ?> <p>Membership Expires: <span><?php echo date($date_display_format, strtotime($user_meta['acc_expires_on'][0])) ?></span></p>
            <?php
            }else{ ?> <p>Membership Expired: <span><?php echo date($date_display_format, strtotime($user_meta['acc_expires_on'][0])) ?></span></p> <?php } ?>
        </div>
        
        <div class="extend-memberships-btns">
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'order' => 'ASC',
            );
            $products_query = new WP_Query( $args );
            if ( $products_query->have_posts() ) {
                while ( $products_query->have_posts() ) {
                    $products_query->the_post();
                    $product_id           = get_the_ID();                    
                    $product_price        = get_post_meta( $product_id, '_price', true );
                    $product_short_desc   = get_the_excerpt($product_id)
                    ?>
                    <a class="btn btn-dark extend-plan" data-id="<?= $product_id ?>" data-price="<?= $product_price ?>" href="<?= get_the_permalink($product_id)?>"><?= $product_short_desc; ?></a>                    
                    <?php             
                }
                wp_reset_postdata();
            }
            ?>                      
        </div>
        <?php if(!$isMemberExpired){ ?>
        <div class="try-buy-service-wrapper">
            <h3>Last Will and Testament</h3>
            <p>Create a perfect, lawyer-approved legal Will from the comfort of your home.</p>
            <div class="try-buy-btns row">
            <a class="btn btn-dark extend-plan col" href="#">TRY</a>
            <a class="btn btn-dark extend-plan col" href="#">BUY ($39.95)</a>
            </div>
        </div>
        <?php } else {?>
            <h2>There is no active membership.</h2>
        <?php } ?>
    </div>
</div>
<?php
}else{
    echo '<h2>This content is restricted</h2>';
}
get_footer();
?>
