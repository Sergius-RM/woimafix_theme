<?php
/**
 * The template for displaying the header
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$expires = 60 * 60 * 24 * 30; // 30 дней в секундах
header("Cache-Control: max-age=$expires, must-revalidate");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expires) . " GMT");
?>

<!DOCTYPE html>
<html
    <?php language_attributes(); ?>
    class="no-js"
>

    <head>

        <meta charset="<?php bloginfo('charset'); ?>">
        <?php $viewport_content = apply_filters('hello_elementor_viewport_content', 'width=device-width, initial-scale=1'); ?>
        <meta
            name="viewport"
            content="<?php echo esc_attr($viewport_content); ?>"
        >
        <!-- favicon here -->

        <link rel="profile" href="https://gmpg.org/xfn/11">
        <?php wp_head(); ?>

        <?php if (  get_field( 'google_analytics', 'option') ) :?>
            <?php $headcode = get_field('google_analytics', 'option');
                print $headcode;?>
        <?php endif ?>

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
        /// echo $paymentStatus;
        ?>

        <?php if ($paymentStatus == 'Active') : ?>
            <style>
                #wpadminbar .quicklinks ul #wp-admin-bar-new-content {
                    display: block;
                }
            </style>
        <?php else:?>
            <style>
                #wpadminbar .quicklinks ul #wp-admin-bar-new-content {
                    display: none;
                }
            </style>
        <?php endif;?>
    <?php endif;?>

    </head>

    <body <?php body_class();?>>

        <?php if (  get_field( 'google_analytics_body', 'option') ) :?>
            <?php $bodycode = get_field('google_analytics_body', 'option');
                print $bodycode; ?>
        <?php endif ?>

        <?php wp_body_open();
            get_template_part('template-parts/header');
        ?>
