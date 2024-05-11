<?php
/**
 * Woocomerce functions.
 *
 */

/*
 * Поддержка шаблонов Вукомерса
 */
add_theme_support( 'woocommerce' );


// Иконка корзины в меню и к-во товаров в корзине
add_filter('woocommerce_add_to_cart_fragments', 'header_add_to_cart_fragment');

function header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;
    ob_start();
    ?>
    <span class="basket-btn__counter">(<?php echo sprintf($woocommerce->cart->cart_contents_count); ?>)</span>
    <?php
    $fragments['.basket-btn__counter'] = ob_get_clean();
    return $fragments;
}


// // Используем формат цены вариативного товара WC 2.0, диапазон от меньшей цены к большей)
// add_filter( 'woocommerce_variable_sale_price_html', 'wc_wc20_variation_price_format', 10, 2 );
// add_filter( 'woocommerce_variable_price_html', 'wc_wc20_variation_price_format', 10, 2 );
// function wc_wc20_variation_price_format( $price, $product ) {
// // Основная цена
// $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
// $price = $prices[0] !== $prices[1] ? sprintf( __( 'hinta alkaa %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
// // Цена со скидкой
// $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
// sort( $prices );
// $saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'hinta alkaa %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

// if ( $price !== $saleprice ) {
// $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
// }
// return $price;
// }

// // Краткое описание товара в Категории товара
// add_filter( 'woocommerce_after_shop_loop_item', 'wpspec_show_product_description', 7 );

// function wpspec_show_product_description() {
//     echo '<div class="col-sm-8woo-product-short-desc">' . get_the_excerpt() . '</div>';
// }


// Динамическая Цена вариативного товара вместо диапазона (убираем цену из-под выбора вариантов и заменяем ею цену под заголовком)
add_action( 'woocommerce_before_single_product', 'check_if_variable_first' );
function check_if_variable_first(){
    if ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product->is_type( 'variable' ) ) {
            // removing the price of variable products
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

// Change location of
add_action( 'woocommerce_single_product_summary', 'custom_wc_template_single_price', 10 );
function custom_wc_template_single_price(){
    global $product;

// Variable product only
if($product->is_type('variable')):

    // Main Price
    $prices = array( $product->get_variation_price( 'max', true ), $product->get_variation_price( 'max', true ) );
    $price = $prices[0] !== $prices[1] ? sprintf( __( 'hinta alkaa %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'max', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'hinta alkaa %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    if ( $price !== $saleprice && $product->is_on_sale() ) {
        $price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
    }

    ?>
    <style>
        div.woocommerce-variation-price,
        div.woocommerce-variation-availability,
        div.hidden-variable-price {
            height: 0px !important;
            overflow:hidden;
            position:relative;
            line-height: 0px !important;
            font-size: 0% !important;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        $('select').blur( function(){
            if( '' != $('input.variation_id').val() ){
                $('p.price').html($('div.woocommerce-variation-price > span.price').html()).append('<p class="availability">'+$('div.woocommerce-variation-availability').html()+'</p>');
                console.log($('input.variation_id').val());
            } else {
                $('p.price').html($('div.hidden-variable-price').html());
                if($('p.availability'))
                    $('p.availability').remove();
                console.log('NULL');
            }
        });
    });
    </script>
    <?php

    echo '<p class="price">'.$price.'</p>
    <div class="hidden-variable-price" >'.$price.'</div>';

endif;
}

        }
    }
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'filter_dropdown_option_html', 12, 2 );
function filter_dropdown_option_html( $html, $args ) {
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' );
    $show_option_none_html = '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

    $html = str_replace($show_option_none_html, '', $html);

    return $html;
}

// Отображение вариаций в корзине
add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );
add_filter( 'woocommerce_is_attribute_in_product_name', '__return_false' );

// Отображение цены вариации по умолчанию
add_filter('woocommerce_variable_price_html', 'custom_variation_price_default', 10, 2);
add_filter('woocommerce_variable_sale_price_html', 'custom_variation_price_default', 10, 2 );

function custom_variation_price_default( $price, $product ) {
  foreach($product->get_available_variations() as $pav){
      $def=true;
      foreach($product->get_variation_default_attributes() as $defkey=>$defval){
          if($pav['attributes']['attribute_'.$defkey]!=$defval){
              $def=false;
          }
      }
      if($def){
          $price = $pav['display_price'];
      }
  }

  return woocommerce_price($price);
}
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
 ob_start(); ?>
  <div class="cart-price"><p><span class="first-name">Товаров:</span> <span id="cart_total_amount"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?></span></p><p><span class="first-name">На сумму:</span> <span id="cart_total" class="pink-price"><?php echo WC()->cart->get_cart_total(); ?></span></p></div> 
  <?php    $fragments['div.cart-price'] = ob_get_clean(); // селектор блока обертки
  return $fragments;
}