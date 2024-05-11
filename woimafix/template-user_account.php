<?php
/**
 * Template name: User Account
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 */

get_header();
?>

    <section class="user_account_section">
        <div class="container">
            <div class="row">

            <div class="col-12 user_account_area">
                <?php the_content(); ?>

                <?php $current_user = wp_get_current_user(); ?>
                <?php if ( in_array( 'provider', (array) $current_user->roles ) ): ?>

                <?php
                    global $current_user, $wpdb;
                    $uid = isset( $current_user->ID ) ? $current_user->ID : 0;
                    $data['subscriptions'] = \Indeed\Ihc\UserSubscriptions::getAllForUser( $uid );

                    foreach ( $data['subscriptions'] as $subscriptionData ){
                        $subscriptionStatus = \Indeed\Ihc\UserSubscriptions::getStatus( $subscriptionData['user_id'], $subscriptionData['level_id'], $subscriptionData['id'] );
                    }
                ?>

                <?php $paymentStatus = esc_html($subscriptionStatus['label']);
                 echo $paymentStatus; ?>

                <?php if ($paymentStatus == 'Active') : ?>
                    <!-- <?php echo do_shortcode('[ihc-login-form]');?> -->
                <?php else:?>
                    <?php echo do_shortcode('[ihc-purchase-link id=1]<div class="um-button purchase-button">Maksa jäsenyydestä</div>[/ihc-purchase-link]');?>
                <?php endif;?>

                <?php endif;?>
            </div>

            </div>
        </div>
    </section>

<?php
get_footer();