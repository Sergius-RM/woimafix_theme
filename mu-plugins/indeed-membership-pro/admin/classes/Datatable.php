<?php
namespace Indeed\Ihc\Admin;

class Datatable
{
    public function __construct()
    {
        // datatable data for tables
        add_action('wp_ajax_ihc_ajax_get_affiliates', [ $this, 'getAffiliates']); // affiliates
        add_action('wp_ajax_ihc_ajax_get_memberships', [ $this, 'getMembershipsForTable']); // memberships
        add_action('wp_ajax_ihc_ajax_get_notifications', [ $this, 'getNotifications']); // notification
        add_action('wp_ajax_ihc_ajax_get_orders', [ $this, 'getOrders']); // orders
        add_action('wp_ajax_ihc_ajax_get_coupons', [ $this, 'getCoupons'] ); // coupons
        add_action('wp_ajax_ihc_ajax_get_inside_locker_items', [ $this, 'getInsideLockerItems'] ); // inside lockers
        add_action('wp_ajax_ihc_ajax_get_car_posts_items', [ $this, 'ihc_ajax_get_car_posts_items'] ); // content access rules - posts
        add_action('wp_ajax_ihc_ajax_get_car_cats', [ $this, 'ihc_ajax_get_car_cats'] ); // content access rules - categories
        add_action('wp_ajax_ihc_ajax_get_car_files', [ $this, 'ihc_ajax_get_car_files'] ); // content access rules - files
        add_action('wp_ajax_ihc_ajax_get_car_entire_url', [ $this, 'ihc_ajax_get_car_entire_url'] ); // content access rules - entire url
        add_action('wp_ajax_ihc_ajax_get_car_url_word', [ $this, 'ihc_ajax_get_car_url_word'] );

        // actions
        add_action('wp_ajax_ihc_admin_remove_affiliates', [ $this, 'ihc_admin_remove_affiliates' ] );
        add_action('wp_ajax_ihc_admin_add_many_affiliates', [ $this, 'ihc_admin_add_many_affiliates' ] );
        add_action('wp_ajax_ihc_admin_delete_locker', [ $this, 'ihc_admin_delete_locker' ] );
        add_action('wp_ajax_ihc_admin_delete_many_lockers', [ $this, 'ihc_admin_delete_many_lockers' ] );
        add_action('wp_ajax_ihc_admin_delete_many_orders', [ $this, 'ihc_admin_delete_many_orders' ] );
        add_action('wp_ajax_ihc_admin_complete_many_orders', [ $this, 'ihc_admin_complete_many_orders' ] );
        add_action('wp_ajax_ihc_admin_delete_order', [ $this, 'ihc_admin_delete_order' ] );
        add_action('wp_ajax_ihc_admin_delete_payment_transaction', [$this, 'ihc_admin_delete_payment_transaction']);
        add_action('wp_ajax_ihc_admin_delete_notification', [$this, 'ihc_admin_delete_notification']);
        add_action('wp_ajax_ihc_admin_delete_many_coupons', [$this, 'ihc_admin_delete_many_coupons'] );
        add_action('wp_ajax_ihc_admin_delete_level', [$this, 'ihc_admin_delete_level']);
        add_action('wp_ajax_ihc_admin_delete_many_memberships', [$this, 'ihc_admin_delete_many_memberships']);
        add_action('wp_ajax_ihc_reorder_levels',[ $this, 'ihc_reorder_levels'] );
        add_action('wp_ajax_ihc_delete_coupon_ajax', [ $this, 'ihc_delete_coupon_ajax' ] );
        add_action('wp_ajax_ihc_make_user_affiliate', [ $this, 'ihc_make_user_affiliate'] );
        add_action('wp_ajax_ihc_admin_delete_one_car_post', [ $this, 'ihc_admin_delete_one_car_post'] );
        add_action('wp_ajax_ihc_admin_delete_many_car_post', [ $this, 'ihc_admin_delete_many_car_post'] );
        add_action('wp_ajax_ihc_admin_delete_one_car_cat', [ $this, 'ihc_admin_delete_one_car_cat'] );
        add_action('wp_ajax_ihc_admin_delete_many_car_cat', [ $this, 'ihc_admin_delete_many_car_cat'] );
        add_action('wp_ajax_ihc_admin_delete_one_car_file', [ $this, 'ihc_admin_delete_one_car_file'] );
        add_action('wp_ajax_ihc_admin_delete_many_car_file', [ $this, 'ihc_admin_delete_many_car_file'] );
        add_action('wp_ajax_ihc_admin_delete_one_car_entire_url', [ $this, 'ihc_admin_delete_one_car_entire_url'] );
        add_action('wp_ajax_ihc_admin_delete_many_car_entire_url', [ $this, 'ihc_admin_delete_many_car_entire_url'] );
        add_action('wp_ajax_ihc_admin_delete_one_car_url_word', [ $this, 'ihc_admin_delete_one_car_url_word'] );
        add_action('wp_ajax_ihc_admin_delete_many_car_url_word', [ $this, 'ihc_admin_delete_many_car_url_word'] );
    }

    public function getAffiliates()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        global $indeed_db, $wpdb;

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
            $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
            $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
            $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : false;
        if ( isset( $_POST['search_phrase'] ) && $_POST['search_phrase'] !== '' ){
            $searchValue = sanitize_text_field( $_POST['search_phrase'] );
        }

        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $role = isset( $_POST['role'] ) ? indeed_sanitize_array( $_POST['role'] ) : false;
        $userType = isset( $_POST['user_type'] ) ? sanitize_text_field( $_POST['user_type'] ) : false;


        if ( $role ){
            if ( count( $role ) > 1 ){
              $roleCondition['relation'] = 'OR';
              foreach ( $role as $roleKey ){
                  $roleCondition[] = [
                                  'key' 		=> $wpdb->get_blog_prefix() . 'capabilities',
                                  'value' 	=> $roleKey,
                                  'compare' => 'LIKE'
                  ];
              }
            } else {
                $roleCondition = [
                                'key' 		=> $wpdb->get_blog_prefix() . 'capabilities',
                                'value' 	=> $role[0],
                                'compare' => 'LIKE'
                ];
            }
        } else {
            $roleCondition = [
                              'key' 		=> $wpdb->get_blog_prefix() . 'capabilities',
                              'value' 	=> 'administrator',
                              'compare' => 'NOT LIKE'
            ];
        }
        $metaQuery = [ $roleCondition ];

        if ( $searchValue !== false && $searchValue !== '' ){
          $metaQuery = [
                'relation' => 'AND',
                $roleCondition,
                [
                  'relation' => 'OR',
                  [
                    'key'     => 'first_name',
                    'value'   => $searchValue,
                    'compare' => 'LIKE'
                  ],
                  [
                    'key'     => 'last_name',
                    'value'   => $searchValue,
                    'compare' => 'LIKE'
                  ],
                  [
                    'key' => 'nickname',
                    'value' => $searchValue ,
                    'compare' => 'LIKE'
                  ]
                ]
            ];
        }

        $params = [
                          'offset' 			=> $offset,
                          'number' 			=> $limit,
                          'meta_query' 	=> $metaQuery,
        ];
        if ( $orderBy && $ascOrDesc ){
          $params['orderby'] = $orderBy;
          $params['order'] = $ascOrDesc;
        }

        if ( $userType !== false && $userType === 'users' ){
            add_action( 'pre_user_query', function($user_query ) {
               global $wpdb;
               $user_query->query_where .= " AND ID NOT IN (SELECT uid from {$wpdb->prefix}uap_affiliates) "; // additional joins here
               return $user_query;
            } );
        } else if ( $userType !== false && $userType === 'affiliates' ){
            add_action( 'pre_user_query', function($user_query ) {
               global $wpdb;
               $user_query->query_where .= " AND ID IN (SELECT uid from {$wpdb->prefix}uap_affiliates) "; // additional joins here
               return $user_query;
            } );
        }

        $users_obj = new \WP_User_Query( $params );
        $all_users = $users_obj->results;
        $totalUsers = $users_obj->get_total();

        $userData = false;

        if ( $all_users ){
          foreach ( $all_users as $user ){
              $first_name = get_user_meta($user->data->ID, 'first_name', true);
              $last_name = get_user_meta($user->data->ID, 'last_name', true);
              if ($first_name || $last_name){
                $name = esc_html($first_name) .' '.esc_html($last_name);
              } else {
                $name =  esc_html($user->data->user_nicename);
              }
              $checked = (!empty($indeed_db) && $indeed_db->is_user_affiliate_by_uid($user->data->ID)) ? 'checked' : '';

              $userData[] = [
                              'id'							=> "<input type='checkbox' name='users[]' value='{$user->data->ID}' class='iump-js-table-select-item' />",
                              'user_login'						=> [
                                            'display' => esc_attr($user->data->user_login),
                                            'value' => esc_attr($user->data->user_login)
                              ],
                              'name'								=> $name,
                              'user_email'					=> esc_html($user->user_email),
                              'affiliate'						=> '<label class="iump_label_shiwtch-uap-affiliate">
                                                              <input type="checkbox" class="iump-switch" id="uap_checkbox_' . $user->data->ID . '" onClick="ihcChangeUapAffiliate(' . $user->data->ID . ');" '.$checked.'/>
                                                              <div class="switch ihc-display-inline"></div>
                                                            </label>',
                              'registered'			=> [
                                'display' =>  ihc_convert_date_to_us_format( $user->user_registered ),
                                'value' 	=>  strtotime( $user->user_registered ),
                              ],

              ];
          }
        }
        //

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $userData, 'recordsTotal' => $totalUsers, 'recordsFiltered' => $totalUsers ] );
        die;
    }


    public function getMembershipsForTable()
    {
      // input : start, length, search[value], order[i][column], columns[i][orderable]

      // order by
      $ascOrDesc = 'ASC';
      $orderBy = 'the_order';
      if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
          $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
          $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
          $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
      }

      // search value
      $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : false;
      if ( isset( $_POST['search_phrase'] ) && $_POST['search_phrase'] !== '' ){
          $searchValue = sanitize_text_field( $_POST['search_phrase'] );
      }

      // offset and limit
      $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
      $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

      $memberships = \Indeed\Ihc\Db\Memberships::getMany( $searchValue, $offset, $limit, $orderBy, $ascOrDesc );
      $data = [];
      $accessTypes = [
              'unlimited'       => esc_html__( 'LifeTime', 'ihc' ),
              'limited'         => esc_html__( 'Limited', 'ihc' ),
              'date_interval'   => esc_html__( 'Date Range', 'ihc' ),
              'regular_period'  => esc_html__( 'Recurring Subscription', 'ihc' ),
      ];
      $currency = get_option('ihc_currency');
      if ( $currency === false ){
          $currency = 'USD';
      }
      include_once IHC_PATH . 'admin/includes/functions.php';
      $membershipsCounts = \Indeed\Ihc\UserSubscriptions::getCountsMembersPerSubscription();
      $url = get_admin_url() . 'admin.php?page=ihc_manage';
      $wooIsActive = ihc_is_magic_feat_active('woo_payment');

      if ( $memberships ){
        foreach ( $memberships as $id => $membership ){
            // access type
            if ( !empty($membership['access_type']) && !empty($membership['access_type']) ){
                $accessType = esc_html($accessTypes[$membership['access_type']]);
            } else {
                $accessType = esc_html__('Not set', 'ihc');
            }

            // user per membership
            if(isset($membershipsCounts[$id])){
                $userCounts = esc_ump_content('<a href="'.$url.'&tab=users&ihc_limit=25&levels=' . $id . '"  class="iump-simple-link">' . $membershipsCounts[$id] . '</a>');
            } else {
                $userCounts = '';
            }

            //
            if (isset($membership['show_on']) && $membership['show_on'] == 0){
                $status = esc_ump_content('<div class="ihc_item_status_nonactive"></div>');
            } else {
                $status = esc_ump_content('<div class="ihc_item_status_active"></div>');
            }
            $status .= '<input type="hidden" class="ihc-hidden-level-id" value="'.esc_attr($id).'" />';

            $array = [
                            'id_checkbox'							 => "<input type='checkbox' name='memberhips[]' value='{$id}' class='iump-js-table-select-item' />",
                            'the_order'							       => [
                                              'display'   => $status,
                                              'value'     => $membership['the_order'],
                            ],
                            'id'			                 => [
                                              'display'   => esc_attr($id),
                                              'value'     => esc_attr($id)
                            ],
                            'name'                      => [
                                              'display'   => $membership['name'],
                                              'value'     => $membership['name'],
                            ],
                            'label'                     => '<span class="ihc-membership-name">' . $membership['label'] . '</span>',
                            'type'                      => '<span class="subcr-type-list">'.$accessType . '</span>',
                            'price'                     => '<div class="ihc-membership-price-wrapper">' . ihc_return_membership_plan( $membership, $currency ) . '</div>',
                            'users_per_membership'      => $membership['users_per_membership'],
                            'purchase_link_shortcode'   => "<div>[ihc-purchase-link id=" . esc_attr($id) . "]" . esc_html__('SignUp', 'ihc') . "[/ihc-purchase-link]</div>",
                            'restriction_shortcode'     => "<div>[ihc-hide-content membership=" . esc_attr($id) . "]...[/ihc-hide-content]</div>",

            ];

            $array['name']['display'] .= '<div class="ihc-buttons-rsp ihc-visibility-hidden" id="level_tr_'. esc_attr($id) . '" style="visibility: hidden;">
            <a class="iump-btns" href="' . esc_url( $url . '&tab=levels&edit_level=' . esc_attr($id) ) . '">' . esc_html__('Edit', 'ihc') . '</a>
            <div class="iump-btns ihc-js-delete-level ihc-delete-link" data-id="' . esc_attr($id) . '">' . esc_html__('Delete', 'ihc') . '</div>
            </div>';

            if ( $wooIsActive ){
                // woo product
                $productId = \Ihc_Db::get_woo_product_id_for_lid($id);
                if ($productId){
                    $productName = get_the_title($productId);
                    $wooProduct = '<a href="'. admin_url('post.php?post=' . $productId . '&action=edit') .'" target="_blank" class="iump-simple-link">' . esc_html( $productName ) . '</a>';
                } else {
                    $wooProduct = '-';
                }
                $array['woo_product'] = $wooProduct;
            }
            $data[] = $array;

        }
      }

      $total = \Indeed\Ihc\Db\Memberships::getCount( $searchValue );

      // output data, recordsTotal, recordsFiltered
      echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
      die;
    }

    public function getNotifications()
    {
          // input : start, length, search[value], order[i][column], columns[i][orderable]

          // order by
          $ascOrDesc = '';
          $orderBy = '';
          if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
              $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
              $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
              $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
          }

          // search value
          $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';
          if ( isset( $_POST['search_phrase'] ) && $_POST['search_phrase'] !== '' ){
              $searchValue = sanitize_text_field( $_POST['search_phrase'] );
          }

          // offset and limit
          $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
          $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

          $notificationObject = new \Indeed\Ihc\Notifications();
          $notification_arr = $notificationObject->getAllNotificationNames();
          $admin_actions = $notificationObject->getAdminCases();
          $notification_arr = apply_filters( 'ihc_admin_list_notifications_types', $notification_arr );

          $notifications = $notificationObject->getMany( $searchValue, $offset, $limit, $orderBy, $ascOrDesc );
          $exclude = apply_filters( 'ihc_admin_remove_notification_from_listing_by_type', [] );
          $runtime = $notificationObject->getNotificationRuntime();
          $doPushover = ihc_is_magic_feat_active('pushover');
          $url = admin_url( 'admin.php?page=ihc_manage&tab=notifications' );

          if ( $notifications ){
            foreach ( $notifications as $item ){
                if ( $exclude && in_array( $item->notification_type, $exclude ) ) {
                    continue;
                }
                $array = [];
                $array['id'] = $item->id;
                if (strlen($item->subject)>100){
                    $array['subject'] = esc_html(substr($item->subject, 0, 100) . ' ...');
                } else {
                    $array['subject'] = esc_html($item->subject);
                }
                $array['subject'] = '<div class="iump-dashboard-table-highlight-text">'.$array['subject'].'</div>';
                $array['subject'] .= '<div class ="ihc-buttons-rsp ihc-visibility-hidden" id="notify_tr_'.esc_attr($item->id).'">
    <a class ="iump-btns" href="' . esc_url($url.'&tab=notifications&edit_notification='.$item->id) . '">' . esc_html__('Edit', 'ihc') . '</a> |
    <span class ="iump-btns ihc-delete-link ihc-js-admin-notifications-delete-notification" data-id="' . esc_attr($item->id) . '" >' . esc_html__('Delete', 'ihc') . '</span>
  </div>';

                $array['action'] = isset( $notification_arr[$item->notification_type] ) ? esc_html($notification_arr[$item->notification_type]) : '';
                if (in_array($item->notification_type, $admin_actions)){
                    $array['goes_to'] = esc_html__('Administrators', 'ihc');
                } else {
                    $array['goes_to'] = esc_html__('Member', 'ihc');
                }
                $array['runtime']           = isset( $runtime[$item->notification_type] ) ? esc_html($runtime[$item->notification_type]) : '';;
                if ($item->level_id==-1){
                    $array['membership_target'] = esc_html__('All', 'ihc');
                } else {
                    $level_data = ihc_get_level_by_id($item->level_id);
                    $array['membership_target'] = esc_html($level_data['name']);
                }
                if ( $doPushover && !empty($item->pushover_status)){
                    $array['pushover'] = '<i class="fa-ihc fa-pushover-on-ihc"></i>';
                }

                $array['options_act'] = '<div class="ihc-notifications-list-send iump-second-button"
                              data-notification_id="' . esc_attr( $item->id ) . '"
                              data-email="' . esc_url( get_option( 'admin_email' ) ) . '"
                              onClick="iumpSendTestNotification(' . esc_attr( $item->id ) . ');"
                        >' . esc_html__('Send Test Email', 'ihc') . '</div>';

                $data[] = $array;
            }
          }

          $total = $notificationObject->countWithFilter( $searchValue );

          // output data, recordsTotal, recordsFiltered
          echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
          die;
    }


    public function getOrders()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]
        /*
        extra params via post variable
        search_phrase
        order_status
        subscription_type
        payment_gateway
        start_time
        end_time
        */

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
            $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
            $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
            $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';
        if ( isset( $_POST['search_phrase'] ) && $_POST['search_phrase'] !== '' ){
            $searchValue = sanitize_text_field( $_POST['search_phrase'] );
        }

        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        // getting data
        $ordersObject = new \Indeed\Ihc\Db\Orders();
        $params = [
          'status'		  => false,
          'q'				    => $searchValue,//u.user_login LIKE %s OR u.user_nicename LIKE %s OR u.user_email LIKE %s OR o.amount_value
          'start_time'	=> false,
          'end_time'		=> false,
          'order_by'		=> $orderBy,
          'order_type'	=> $ascOrDesc,
          'limit'			  => $limit,
          'offset'		  => $offset,
        ];
        if ( isset( $_POST['start_time'] ) && $_POST['start_time'] !== '' ){
            $params['start_time'] = sanitize_text_field( $_POST['start_time'] );
        }
        if ( isset( $_POST['end_time'] ) && $_POST['end_time'] !== '' ){
            $params['end_time'] = sanitize_text_field( $_POST['end_time'] );
        }
        if ( isset( $_POST['order_status'] ) && $_POST['order_status'] !== '' ){
            $params['status_in'] = indeed_sanitize_array( $_POST['order_status'] );
        }
        if ( isset( $_POST['subscription_type'] ) && $_POST['subscription_type'] !== '' ){
            $params['subscription_type'] = indeed_sanitize_array( $_POST['subscription_type'] );
        }
        if ( isset( $_POST['payment_gateway'] ) && $_POST['payment_gateway'] !== '' ){
            $params['payment_gateway'] = indeed_sanitize_array( $_POST['payment_gateway'] );
        }

        $uid = isset( $_POST['uid'] ) ? sanitize_text_field( $_POST['uid'] ) : 0;// since version 12.01
        $uid = (int)$uid;// since version 12.01
        include_once IHC_PATH . 'admin/includes/functions.php';

        $total = $ordersObject->countWithFilter( $uid, $params );
        if ( !$total ){
            echo json_encode( [ 'data' => [], 'recordsTotal' => 0, 'recordsFiltered' => 0 ] );
            die;
        }

        $orders = $ordersObject->getMany( $uid, $params );
        if ( !$orders ){
            echo json_encode( [ 'data' => [], 'recordsTotal' => 0, 'recordsFiltered' => 0 ] );
            die;
        }
        $columns = [
              'select_item',
              'id',
              'code',
              'customer',
              'membership',
              'net_amount',
              'taxes',
              'total_amount',
              'payment_method',
              'date',
              'coupon',
              'transaction',
              'invoices',
              'status',
              'action',
        ];
        $payment_gateways = ihc_list_all_payments();
        $payment_gateways['woocommerce'] = esc_html__( 'WooCommerce', 'ihc' );
        $orderMeta = new \Indeed\Ihc\Db\OrderMeta();
        $taxesOn = ihc_is_magic_feat_active( 'taxes' );
        // invoices
        $onlyCompletedInvoices = get_option( 'ihc_invoices_only_completed_payments', 0 );
        $onlyCompletedInvoices = (int)$onlyCompletedInvoices;
        $showInvoices = get_option( 'ihc_invoices_on', 0 );
        $showInvoices = (int)$showInvoices;

        if ( $orders ){
          foreach ( $orders as $order ){
              $array = [];
              $array['select_item'] = '<input type="checkbox" name="order_id[]" value="' . $order['id'] . '"  class="iump-js-table-select-item" />';
              $array['id'] = $order['id'];
              // code
              if (!empty($order['metas']['code'])){
                $array['code'] = esc_ump_content('<a href="' . admin_url( '/admin.php?page=ihc_manage&tab=order-edit&order_id=' . esc_attr($order['id']) ) . '" target="_blank" >' . esc_html($order['metas']['code']) . '</a>');
              } else {
                $array['code'] = esc_html('-');
              }
              //customer
              $array['user'] = '<span class="ihc-order-user"><a target="_blank" href="' . esc_url(ihcAdminUserDetailsPage( $order['uid'] )) . '">' . esc_html($order['user']) . '</a></span>';
              $array['membership'] = $order['level'];// get the name of membership

              if ( $taxesOn ){
                  // amount and taxes
                  if(isset($order['metas']['first_amount']) && $order['metas']['first_amount'] == $order['amount_value']){
                    $firstCharge = true;
                  }
                  if ( !isset($order['metas']['taxes_amount']) || $order['metas']['taxes_amount'] == null ){
                      $taxes = isset( $order['metas']['tax_value'] ) ? $order['metas']['tax_value'] : false;
                  }
                  if ( !isset($taxes) || $taxes === false ){
                      if ( isset($firstCharge) && $firstCharge == true){
                        $taxes = isset( $order['metas']['first_amount_taxes'] ) ? $order['metas']['first_amount_taxes'] : false;
                      } else {
                        $taxes = isset( $order['metas']['taxes'] ) ? $order['metas']['taxes'] : false;
                      }
                  }

                  if (isset($firstChage) && $firstChage == true && isset($firstAmount)){
                      $netAmount = $firstAmount;
                      if ( $taxes != false ){
                        $netAmount = $firstAmount - $taxes;
                      }
                      $array['net_amount'] = esc_html($netAmount) . ' ' . esc_html($order['amount_type']);
                  } else {
                      $base_price = $orderMeta->get( $order['id'], 'base_price' );
                      if ( $base_price !== null ){
                        $array['net_amount'] = esc_html($base_price) . ' ' . esc_html($order['amount_type']);
                      } else if ( $taxes != false ){
                        $netAmount = $order['amount_value'] - $taxes;
                        $array['net_amount'] = esc_html($netAmount) . ' ' . esc_html($order['amount_type']);
                      } else {
                        $array['net_amount'] = esc_html($order['amount_value']) . ' ' . esc_html($order['amount_type']);
                      }
                  }

                  // taxes

                  // taxes amount
                  if ( $taxes === false || $taxes === '' ){
                      $array['taxes'] = '';
                  } else {
                      $array['taxes'] = $taxes . ' ' . esc_html($order['amount_type']);//
                  }

              }

              // total amount
              $array['amount_value'] = '<span class="order-total-amount">' . esc_html($order['amount_value']) . ' ' . esc_html($order['amount_type']) . '</span>';

              $array['payment_method'] = $order['metas']['ihc_payment_type'];//
              if (empty($order['metas']['ihc_payment_type'])){
                  $array['payment_method'] = esc_html('-');
              } else {
                if (!empty($order['metas']['ihc_payment_type'])){
                  $gateway_key = $order['metas']['ihc_payment_type'];
                  $array['payment_method'] = isset( $payment_gateways[$gateway_key] ) ? $payment_gateways[$gateway_key] : '-';
                }
              }

              $array['create_date'] = ihc_convert_date_time_to_us_format($order['create_date']);
              $array['coupon'] = (isset( $order['metas']['coupon_used'] ) && $order['metas']['coupon_used'] !== '') ? $order['metas']['coupon_used'] : '-';//
              $array['transaction'] = '';
              if ( $showInvoices === 1 ){
                  if ( $showInvoices === 1 && $order['status'] !== 'Completed' ){
                      $array['invoices'] = '-';
                  } else {
                      $array['invoices'] = '<i class="fa-ihc fa-invoice-preview-ihc iump-pointer" onClick="iumpGenerateInvoice(' . esc_attr($order['id']) . ');"></i>';
                  }
              } //else {
                //  $array['invoices'] = '-';
              //}

              $array['status'] = '';
              $array['action'] = '';

              if ( isset($order['metas']['transaction_id']) && $order['metas']['transaction_id'] !== '' && isset( $order['metas']['ihc_payment_type'] ) ){
                  $transactionLink = iumpGetTransactionLink( $order['metas']['transaction_id'], $order['metas']['ihc_payment_type'] );
                  $array['transaction'] = '<a target="_blank" title="' . esc_html__('Check Transaction on ', 'ihc') . esc_attr( $order['metas']['ihc_payment_type'] ) . '" href="' . esc_url($transactionLink) . '">' . esc_html( $order['metas']['transaction_id'] ) . '</a>';
              }

              // status
              switch ( $order['status'] ){
                case 'Completed':
                  $array['status'] = esc_html__('Completed', 'ihc');
                  break;
                case 'pending':
                  $array['status'] =  esc_ump_content('<div>' . esc_html__('Pending', 'ihc') . '</div>');
                  break;
                case 'fail':
                case 'failed':
                  $array['status'] = esc_html__('Fail', 'ihc');
                  break;
                case 'error':
                  $array['status'] = esc_html__('Error', 'ihc');
                  break;
                default:
                  $array['status'] =  esc_html( $array['status'] );
                  break;
              }
              $array['status'] = '<strong>' . $array['status'] . '</strong>';

              // actions
              if ( $order['status'] == 'pending' ){
                  $array['action'] .= '<span class="ihc-js-make-order-completed ihc-pointer" data-id="' . esc_attr($order['id']) . '" ><i title="' . esc_html__( 'Make Completed', 'ihc' ) . '" class="fa-ihc ihc-icon-completed-e"></i></span>';
              }

              $array['action'] .= '<a title="' . esc_html__( 'Edit', 'ihc' ) . '" href="' . admin_url( 'admin.php?page=ihc_manage&tab=order-edit&order_id=' . esc_attr( $order['id'] ) ) . '" >
                <i class="fa-ihc ihc-icon-edit-e"></i>
              </a>';

              if ( isset( $order['metas']['ihc_payment_type'] )
                    && in_array( $order['metas']['ihc_payment_type'], [ 'stripe', 'paypal', 'paypal_express_checkout', 'stripe_checkout_v2', 'stripe_connect', 'mollie', 'twocheckout','braintree' , 'authorize' ] ) ){
                  // gettings special links for transactions
                  $links = iumpAdminGetOrderActionsLinks( $order['id'], $order['metas']['ihc_payment_type'], '' );
                  if ( isset( $links['refundLink'] ) && $links['refundLink'] != '' ){
                        $array['action'] .= '<a title="' . esc_html__( 'Refund', 'ihc' ) . '" href="' . esc_url( $links['refundLink'] ) . '" target="_blank" ><i class="fa-ihc ihc-icon-refund-e"></i></a>';
                  }
                  if ( isset( $links['chargingPlan'] ) && $links['chargingPlan'] != '' ){
                      $array['action'] .= '<a title="' . esc_html__( 'Check Charging plan on', 'ihc' ) . esc_attr($array['payment_method']) . '" href="' . esc_url( $links['chargingPlan'] ) . '" target="_blank" ><i class="fa-ihc ihc-icon-plan-e"></i></a>';
                  }
              }

              $array['action'] .= '<span class="ihc-pointer ihc-js-delete-order" data-id="' . esc_attr($order['id']) . '" title="' . esc_html__( 'Remove', 'ihc' ) . '" ><i class="fa-ihc ihc-icon-remove-e"></i></span>';

              $data[] = $array;
          }
        }

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }


    public function getCoupons()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
            $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
            $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
            $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';
        if ( isset( $_POST['search_phrase'] ) && $_POST['search_phrase'] !== '' ){
            $searchValue = sanitize_text_field( $_POST['search_phrase'] );
        }

        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $params = [
          'search_phrase'		 => $searchValue,//u.user_login LIKE %s OR u.user_nicename LIKE %s OR u.user_email LIKE %s OR o.amount_value
          'order_by'		     => $orderBy,
          'order_type'	     => $ascOrDesc,
          'limit'			       => $limit,
          'offset'		       => $offset,
        ];

        $coupons = \Ihc_Db::couponsGetMany( $params );
        $url = admin_url('admin.php?page=ihc_manage&tab=coupons&subtab=add_edit');

        if ( $coupons ){
          foreach ( $coupons as $coupon ){

              $array = [];

              $array['select_item'] = '<input type="checkbox" name="coupon_id[]" value="' . $coupon['id'] . '"  class="iump-js-table-select-item" />';

              if ($coupon['settings']['target_level']==-1){
                $array['target_membership'] = '<div class="second-box-element">'.esc_html__("All", "ihc").'</div>';
              } else if ( strpos( $coupon['settings']['target_level'], ',') !== false ){
                $coupon['settings']['target_level'] = explode( ',', $coupon['settings']['target_level'] );
                foreach ( $coupon['settings']['target_level'] as $lid ){
                  $membershipLabels[] = '<div class="second-box-element">'.\Indeed\Ihc\Db\Memberships::getMembershipLabel( $lid ).'</div>';
                }
                  $array['target_membership'] = implode( '', $membershipLabels );
              } else {
                  $array['target_membership'] = '<div class="second-box-element">'.\Indeed\Ihc\Db\Memberships::getMembershipLabel( $coupon['settings']['target_level'] ).'</div>';
              }

              $array['code'] = '<div class="iump-dashboard-table-highlight-text">'.$coupon['code'].'</div>';
              $array['code'] .= '<div class ="ihc-buttons-rsp ihc-visibility-hidden" id="coupon_tr_'.esc_attr($coupon['id']).'">
    <a class ="iump-btns" href="' . esc_url( $url . '&id=' . $coupon['id'] ) . '">' . esc_html__('Edit', 'ihc') . '</a> |
    <span class ="iump-btns ihc-delete-link ihc-js-admin-coupons-delete-coupon" data-delete_message="' . esc_html__( 'Are You sure You wish to delete this coupon?', 'ihc' ) . '" data-id="' . esc_attr($coupon['id']) . '" >' . esc_html__('Delete', 'ihc') . '</span>
    </div>';

              if ($coupon['settings']['period_type']=='unlimited'){
                $array['period_type'] = esc_ump_content('<span>'). esc_html__("No Date range", 'ihc').esc_ump_content('</span>');
              }else if (!empty($coupon['settings']['start_time']) && !empty($coupon['settings']['end_time'])) {
                $date_format = get_option('date_format');
                $coupon['settings']['start_time'] = date_i18n( $date_format, strtotime($coupon['settings']['start_time']) );
                $coupon['settings']['end_time'] = date_i18n( $date_format, strtotime( $coupon['settings']['end_time']) );
                $array['period_type'] = esc_html__("From ", "ihc") .'<span>'. esc_html( $coupon['settings']['start_time'] ) . "</span><br/> " .  esc_html__("to ", "ihc") .'<span>' . esc_html( $coupon['settings']['end_time'] ) . '</span>';
              } else {
                $array['period_type'] = esc_ump_content('-');
              }

              $array['submited_coupons'] .= esc_ump_content(' <strong>') . esc_html( $coupon['submited_coupons_count'] );
              if (!empty($coupon['settings']['repeat'])){
                 $array['submited_coupons'] .= esc_html("/") . esc_html($coupon['settings']['repeat']);
              }
              $array['submited_coupons'] .= esc_ump_content('</strong>');

              $array['discount'] = esc_html($coupon['settings']['discount_value']);
              if ($coupon['settings']['discount_type']=='percentage'){
                $array['discount'] .= esc_html('%');
              } else {
                $array['discount'] .= esc_ump_content(' ' ). esc_html( get_option( 'ihc_currency' ) );
              }
              $array['id'] = $coupon['id'];
              //$array['short_description'] = isset( $array['settings']['description'] ) ? $array['settings']['description'] : '-';
              $recurringTypes = [0 => esc_html__("Just for Initial Payment", 'ihc'), 1 => esc_html__("Entire Billing Period", 'ihc')];
              $array['recurring_behavior'] = isset( $recurringTypes[$coupon['settings']['reccuring']] ) ? $recurringTypes[$coupon['settings']['reccuring']] : '-';

              $data[] = $array;
          }
        }


        $total = \Ihc_Db::couponsCountWithFilter( $params );

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }

    public function getInsideLockerItems()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
            $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
            $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
            $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';


        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $params = [
          'search_phrase'		 => $searchValue,
          'order_by'		     => $orderBy,
          'order_type'	     => $ascOrDesc,
          'limit'			       => $limit,
          'offset'		       => $offset,
        ];

        $url = admin_url('admin.php?page=ihc_manage&tab=locker&subtab=add_new');
        $lockers = ihc_return_meta('ihc_lockers');
        $templates = [1=>'Default', 2=>'Basic', 3=>'Zipped', 4=>'Zone', 5=>'Majic Transparent', 6=>'Star', 7=>'Clouddy', 8=>'Darks'];
        $data = [];

        if ( $lockers ){
            foreach ( $lockers as $id => $lockerData ){
                if ( $searchValue !== '' && strpos( $lockerData['ihc_locker_name'], $searchValue ) === false ){
                    continue;
                }
                $array = [
                          'select_item'         => '<input type="checkbox" name="locker_id[]" value="' . $id . '"  class="iump-js-table-select-item" />',
                          'id'                  => $id,
                          'name'                => esc_html($lockerData['ihc_locker_name']),
                          'theme'               => '<span class="second-box-element">' . esc_html($templates [$lockerData['ihc_locker_template']]) . '</span>',
                          'edit'                => '<a href="' . esc_url( $url.'&ihc_edit_id='.$id ) . '">
                            <i class="fa-ihc ihc-icon-edit-e"></i>
                          </a>',
                          'preview'             => '<a href="javascript:void(0)" onClick="ihcLockerPreviewWi(' . esc_attr( $id ) . ', 1);">
                            <i class="fa-ihc ihc-icon-preview"></i>
                          </a>',
                          'remove'              => '<span class="ihc-js-admin-delete-locker ihc-delete-link" data-id="' . esc_attr( $id ) . '" >
                            <i class="fa-ihc ihc-icon-remove-e"></i>
                          </span>',
                          'name_strtolower'     => strtolower($lockerData['ihc_locker_name'])
                ];
                $data[] = $array;
            }
            if ( $orderBy === 'name' ){
                $keys = array_column( $data, 'name_strtolower' );
            } else {
                $keys = array_column( $data, 'id' );
            }

            if ( $ascOrDesc === 'asc' ){
                array_multisort($keys, SORT_ASC, $data);
            } else {
                array_multisort($keys, SORT_DESC, $data);
            }

            $data = array_slice( $data, $offset, $limit );
        }

        $total = count( $lockers );

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }

    public function ihc_ajax_get_car_posts_items()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
           $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
           $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
           $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';


        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $params = [
                   'search_phrase'		 => $searchValue,
                   'order_by'		       => $orderBy,
                   'order_type'	       => $ascOrDesc,
                   'limit'			       => $limit,
                   'offset'		         => $offset,
        ];

        $carPosts = get_option( 'ihc_block_posts_by_type' );
        $data = [];

        if ( $carPosts ){
           foreach ( $carPosts as $id => $carPost ){
               if ( $searchValue !== '' && strpos( $carPost['post_type'], $searchValue ) === false ){
                    continue;
               }
               $printType = isset($carPost['block_or_show']) ? $carPost['block_or_show'] : 'block';

               $array = [
                         'id'                => '<input type="checkbox" name="car_posts[]" value="' . $id . '"  class="iump-js-table-select-item" />',
                         'target_post_type'  => '<div class="iump-dashboard-table-highlight-text">' . esc_html( $carPost['post_type'] ) . '</div>',
                         'restriction_type'  => ucfirst( $printType ),
                         'target_members'    => '',
                         'except'            => '',
                         'redirect'          => '',
                         'actions'           => '',
               ];

               // user type
               if ($carPost['target_users']){
                 $levels = explode(',', $carPost['target_users']);
                 if ($levels && count($levels)){
                   $extra_class = ($printType=='block') ? 'ihc-expired-level' : '';
                   foreach ($levels as $val){
                     $print_type_user = '';
                     if ($val!='reg' && $val!='unreg' && $val!='all'){
                       $temp_data = ihc_get_level_by_id($val);
                       if (!empty($temp_data['name'])){
                         $print_type_user = $temp_data['name'];
                       }
                     } else {
                       $print_type_user = $val;
                     }
                     if (empty($print_type_user)){
                       $print_type_user = esc_html__('Removed Membership', 'ihc');
                     }
                     $array['target_members'] .= '<div class="level-type-list ' . esc_attr($extra_class) . '">' . esc_html($print_type_user) . '</div>';
                   }
                 }
               }

               // except
               if (empty($carPost['except'])){
                   $array['except'] = esc_html('-');
               } else {
                   $array['except'] = esc_html($carPost['except']);
               }

               // redirect
               if ($carPost['redirect']!=-1){
                   $redirect_link = ihc_get_redirect_link_by_label($carPost['redirect']);
                   if ($redirect_link){
                        $array['redirect'] = '<a href="' . get_permalink($carPost['redirect']) . '" target="_blank">' . esc_url($redirect_link) . '</a>';
                   } else {
                        $array['redirect'] = '<a href="' . get_permalink($carPost['redirect']) . '" target="_blank">' . get_the_title( $carPost['redirect'] ) . '</a>';
                   }
               } else {
                    $array['redirect'] = esc_html('-');
               }

               // actions
               $array['actions'] = '<i class="fa-ihc ihc-icon-remove-e ihc-js-admin-delete-block-url-block" data-id="' . esc_attr( $id ) . '" ></i>';

               $data[] = $array;
           }
           if ( $orderBy === 'name' ){
               $keys = array_column( $data, 'name_strtolower' );
           } else {
               $keys = array_column( $data, 'id' );
           }

           if ( $ascOrDesc === 'asc' ){
               array_multisort($keys, SORT_ASC, $data);
           } else {
               array_multisort($keys, SORT_DESC, $data);
           }

           $data = array_slice( $data, $offset, $limit );
        }

        $total = count( $carPosts );

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }

    public function ihc_ajax_get_car_cats()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
           $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
           $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
           $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';


        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $params = [
                   'search_phrase'		 => $searchValue,
                   'order_by'		       => $orderBy,
                   'order_type'	       => $ascOrDesc,
                   'limit'			       => $limit,
                   'offset'		         => $offset,
        ];

        $carCats = get_option('ihc_block_cats_by_name');
        $data = [];

        if ( $carCats ){
           $terms = ihc_get_all_terms_with_names();
           foreach ( $carCats as $id => $carCat ){
               $k = $carCat['cat_id'];
               if ( $searchValue !== '' && !empty( $terms[$k] ) && strpos( $terms[$k], $searchValue ) === false ){
                    continue;
               }
               $printType = isset($carCat['block_or_show']) ? $carCat['block_or_show'] : 'block';

               $array = [
                         'id'                => '<input type="checkbox" name="car_posts[]" value="' . $id . '"  class="iump-js-table-select-item" />',
                         'target_cats'       => '',
                         'restriction_type'  => ucfirst( $printType ),
                         'target_members'    => '',
                         'except'            => '',
                         'redirect'          => '',
                         'actions'           => '',
               ];
               if (!empty($terms[$k])){
                 $array['target_cats'] = '<div class="iump-dashboard-table-highlight-text">'.esc_html($terms[$k]).'</div>';
               }

               // user type
               if ($carCat['target_users']){
                 $levels = explode(',', $carCat['target_users']);
                 if ($levels && count($levels)){
                   $extra_class = ($printType=='block') ? 'ihc-expired-level' : '';
                   foreach ($levels as $val){
                     $print_type_user = '';
                     if ($val!='reg' && $val!='unreg' && $val!='all'){
                       $temp_data = ihc_get_level_by_id($val);
                       if (!empty($temp_data['name'])){
                         $print_type_user = $temp_data['name'];
                       }
                     } else {
                       $print_type_user = $val;
                     }
                     if (empty($print_type_user)){
                       $print_type_user = esc_html__('Removed Membership', 'ihc');
                     }
                     $array['target_members'] .= '<div class="level-type-list ' . esc_attr($extra_class) . '">' . esc_html($print_type_user) . '</div>';
                   }
                 }
               }

               // except
               if (empty($carCat['except'])){
                   $array['except'] = esc_html('-');
               } else {
                   $array['except'] = esc_html($carCat['except']);
               }

               // redirect
               if ($carCat['redirect']!=-1){
                   $redirect_link = ihc_get_redirect_link_by_label($carCat['redirect']);
                   if ($redirect_link){
                        $array['redirect'] = '<a href="' . get_permalink($carCat['redirect']) . '" target="_blank">' . esc_url($redirect_link) . '</a>';
                   } else {
                        $array['redirect'] = '<a href="' . get_permalink($carCat['redirect']) . '" target="_blank">' . get_the_title( $carCat['redirect'] ) . '</a>';
                   }
               } else {
                    $array['redirect'] = esc_html('-');
               }

               // actions
               $array['actions'] = '<i class="fa-ihc ihc-icon-remove-e ihc-js-admin-delete-block-url-block" data-id="' . esc_attr( $id ) . '" ></i>';

               $data[] = $array;
           }
           if ( $orderBy === 'name' ){
               $keys = array_column( $data, 'name_strtolower' );
           } else {
               $keys = array_column( $data, 'id' );
           }

           if ( $ascOrDesc === 'asc' ){
               array_multisort($keys, SORT_ASC, $data);
           } else {
               array_multisort($keys, SORT_DESC, $data);
           }

           $data = array_slice( $data, $offset, $limit );
        }

        $total = count( $data );

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }

    public function ihc_ajax_get_car_files()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
           $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
           $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
           $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';


        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $params = [
                   'search_phrase'		 => $searchValue,
                   'order_by'		       => $orderBy,
                   'order_type'	       => $ascOrDesc,
                   'limit'			       => $limit,
                   'offset'		         => $offset,
        ];

        $carFiles = get_option('ihc_block_files_by_url');
        $data = [];

        if ( $carFiles ){
           foreach ( $carFiles as $id => $carFile ){
               if ( $searchValue !== '' && strpos( $carFile['file_url'], $searchValue ) === false ){
                    continue;
               }
               $printType = isset($carFile['block_or_show']) ? $carFile['block_or_show'] : 'block';

               $array = [
                         'id'                => '<input type="checkbox" name="car_posts[]" value="' . $id . '"  class="iump-js-table-select-item" />',
                         'target_files'      => '<div class="iump-dashboard-table-highlight-text">' . esc_html( $carFile['file_url'] ) . '</div>',
                         'restriction_type'  => ucfirst( $printType ),
                         'target_members'    => '',
                         'redirect'          => '',
                         'actions'           => '',
               ];

               // user type
               if ($carFile['target_users']){
                 $levels = explode(',', $carFile['target_users']);
                 if ($levels && count($levels)){
                   $extra_class = ($printType=='block') ? 'ihc-expired-level' : '';
                   foreach ($levels as $val){
                     $print_type_user = '';
                     if ($val!='reg' && $val!='unreg' && $val!='all'){
                       $temp_data = ihc_get_level_by_id($val);
                       if (!empty($temp_data['name'])){
                         $print_type_user = $temp_data['name'];
                       }
                     } else {
                       $print_type_user = $val;
                     }
                     if (empty($print_type_user)){
                       $print_type_user = esc_html__('Removed Membership', 'ihc');
                     }
                     $array['target_members'] .= '<div class="level-type-list ' . esc_attr($extra_class) . '">' . esc_html($print_type_user) . '</div>';
                   }
                 }
               }

               // redirect
               if ($carFile['redirect']!=-1){
                   $redirect_link = ihc_get_redirect_link_by_label($carFile['redirect']);

                   if ($redirect_link){
                        $array['redirect'] = '<a href="' . get_permalink($carFile['redirect']) . '" target="_blank">' . esc_url($redirect_link) . '</a>';
                   } else {
                        $array['redirect'] = '<a href="' . get_permalink($carFile['redirect']) . '" target="_blank">' . get_the_title( $carFile['redirect'] ) . '</a>';
                   }
               } else {
                    $array['redirect'] = esc_html('-');
               }

               // actions
               $array['actions'] = '<i class="fa-ihc ihc-icon-remove-e ihc-js-admin-delete-block-url-block" data-id="' . esc_attr( $id ) . '" ></i>';

               $data[] = $array;
           }
           if ( $orderBy === 'name' ){
               $keys = array_column( $data, 'name_strtolower' );
           } else {
               $keys = array_column( $data, 'id' );
           }

           if ( $ascOrDesc === 'asc' ){
               array_multisort($keys, SORT_ASC, $data);
           } else {
               array_multisort($keys, SORT_DESC, $data);
           }

           $data = array_slice( $data, $offset, $limit );
        }

        $total = count( $data );

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }

    public function ihc_ajax_get_car_entire_url()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
           $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
           $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
           $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';


        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $params = [
                   'search_phrase'		 => $searchValue,
                   'order_by'		       => $orderBy,
                   'order_type'	       => $ascOrDesc,
                   'limit'			       => $limit,
                   'offset'		         => $offset,
        ];

        $carItems = get_option('ihc_block_url_entire');
        $data = [];

        if ( $carItems ){
           foreach ( $carItems as $id => $carItem ){
               if ( $searchValue !== '' && strpos( $carItem['url'], $searchValue ) === false ){
                    continue;
               }
               $printType = isset($carItem['block_or_show']) ? $carItem['block_or_show'] : 'block';

               $array = [
                         'id'                => '<input type="checkbox" name="car_target_url[]" value="' . $id . '"  class="iump-js-table-select-item" />',
                         'entire_url'        => '<div class="iump-dashboard-table-highlight-text">' . esc_html( $carItem['url'] ) . '</div>',
                         'restriction_type'  => ucfirst( $printType ),
                         'target_members'    => '',
                         'redirect'          => '',
                         'actions'           => '',
               ];

               // user type
               if ($carItem['target_users']){
                 $levels = explode(',', $carItem['target_users']);
                 if ($levels && count($levels)){
                   $extra_class = ($printType=='block') ? 'ihc-expired-level' : '';
                   foreach ($levels as $val){
                     $print_type_user = '';
                     if ($val!='reg' && $val!='unreg' && $val!='all'){
                       $temp_data = ihc_get_level_by_id($val);
                       if (!empty($temp_data['name'])){
                         $print_type_user = $temp_data['name'];
                       }
                     } else {
                       $print_type_user = $val;
                     }
                     if (empty($print_type_user)){
                       $print_type_user = esc_html__('Removed Membership', 'ihc');
                     }
                     $array['target_members'] .= '<div class="level-type-list ' . esc_attr($extra_class) . '">' . esc_html($print_type_user) . '</div>';
                   }
                 }
               }

               // redirect
               if ($carItem['redirect']!=-1){
                   $redirect_link = ihc_get_redirect_link_by_label($carItem['redirect']);

                   if ($redirect_link){
                        $array['redirect'] = '<a href="' . get_permalink($carItem['redirect']) . '" target="_blank">' . esc_url($redirect_link) . '</a>';
                   } else {
                        $array['redirect'] = '<a href="' . get_permalink($carItem['redirect']) . '" target="_blank">' . get_the_title( $carItem['redirect'] ) . '</a>';
                   }
               } else {
                    $array['redirect'] = esc_html('-');
               }

               // actions
               $array['actions'] = '<i class="fa-ihc ihc-icon-remove-e ihc-js-admin-delete-block-url-block" data-id="' . esc_attr( $id ) . '" ></i>';

               $data[] = $array;
           }
           if ( $orderBy === 'name' ){
               $keys = array_column( $data, 'name_strtolower' );
           } else {
               $keys = array_column( $data, 'id' );
           }

           if ( $ascOrDesc === 'asc' ){
               array_multisort($keys, SORT_ASC, $data);
           } else {
               array_multisort($keys, SORT_DESC, $data);
           }

           $data = array_slice( $data, $offset, $limit );
        }

        $total = count( $data );

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }

    public function ihc_ajax_get_car_url_word()
    {
        // input : start, length, search[value], order[i][column], columns[i][orderable]

        // order by
        $ascOrDesc = '';
        $orderBy = '';
        if ( isset( $_POST['order'][0]['column'] ) && $_POST['order'][0]['column'] !== '' ){
           $columnId = sanitize_text_field( $_POST['order'][0]['column'] );
           $ascOrDesc = sanitize_text_field( $_POST['order'][0]['dir'] );
           $orderBy = isset(	$_POST['columns'][$columnId]['data'] ) ? sanitize_text_field($_POST['columns'][$columnId]['data']) : false;
        }

        // search value
        $searchValue = isset( $_POST['search']['value'] ) ? sanitize_text_field( $_POST['search']['value'] ) : '';


        // offset and limit
        $offset = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : 0;
        $limit = isset( $_POST['length'] ) ? sanitize_text_field( $_POST['length'] ) : 30;

        $params = [
                   'search_phrase'		 => $searchValue,
                   'order_by'		       => $orderBy,
                   'order_type'	       => $ascOrDesc,
                   'limit'			       => $limit,
                   'offset'		         => $offset,
        ];

        $carItems = get_option( 'ihc_block_url_word' );
        $data = [];

        if ( $carItems ){
           foreach ( $carItems as $id => $carItem ){
               if ( $searchValue !== '' && strpos( $carItem['url'], $searchValue ) === false ){
                    continue;
               }
               $printType = isset($carItem['block_or_show']) ? $carItem['block_or_show'] : 'block';

               $array = [
                         'id'                => '<input type="checkbox" name="car_target_url[]" value="' . $id . '"  class="iump-js-table-select-item" />',
                         'url'               => '<div class="iump-dashboard-table-highlight-text">' . esc_html( $carItem['url'] ) . '</div>',
                         'restriction_type'  => ucfirst( $printType ),
                         'target_members'    => '',
                         'redirect'          => '',
                         'actions'           => '',
               ];

               // user type
               if ($carItem['target_users']){
                 $levels = explode(',', $carItem['target_users']);
                 if ($levels && count($levels)){
                   $extra_class = ($printType=='block') ? 'ihc-expired-level' : '';
                   foreach ($levels as $val){
                     $print_type_user = '';
                     if ($val!='reg' && $val!='unreg' && $val!='all'){
                       $temp_data = ihc_get_level_by_id($val);
                       if (!empty($temp_data['name'])){
                         $print_type_user = $temp_data['name'];
                       }
                     } else {
                       $print_type_user = $val;
                     }
                     if (empty($print_type_user)){
                       $print_type_user = esc_html__('Removed Membership', 'ihc');
                     }
                     $array['target_members'] .= '<div class="level-type-list ' . esc_attr($extra_class) . '">' . esc_html($print_type_user) . '</div>';
                   }
                 }
               }

               // redirect
               if ($carItem['redirect']!=-1){
                   $redirect_link = ihc_get_redirect_link_by_label($carItem['redirect']);
                   if ($redirect_link){
                        $array['redirect'] = '<a href="' . get_permalink($carItem['redirect']) . '" target="_blank">' . esc_url($redirect_link) . '</a>';
                   } else {
                        $array['redirect'] = '<a href="' . get_permalink($carItem['redirect']) . '" target="_blank">' . get_the_title( $carItem['redirect'] ) . '</a>';
                   }
               } else {
                    $array['redirect'] = esc_html('-');
               }

               // actions
               $array['actions'] = '<i class="fa-ihc ihc-icon-remove-e ihc-js-admin-delete-url-word" data-id="' . esc_attr( $id ) . '" ></i>';

               $data[] = $array;
           }
           if ( $orderBy === 'name' ){
               $keys = array_column( $data, 'name_strtolower' );
           } else {
               $keys = array_column( $data, 'id' );
           }

           if ( $ascOrDesc === 'asc' ){
               array_multisort($keys, SORT_ASC, $data);
           } else {
               array_multisort($keys, SORT_DESC, $data);
           }

           $data = array_slice( $data, $offset, $limit );
        }

        $total = count( $data );

        // output data, recordsTotal, recordsFiltered
        echo json_encode( [ 'data' => $data, 'recordsTotal' => $total, 'recordsFiltered' => $total ] );
        die;
    }

    public function ihc_admin_remove_affiliates()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
    		if ( !$ids ){
    				die;
    		}
    		$users = explode( ',', $ids );
    		global $indeed_db;
    		foreach ( $users as $uid ){
    				// remove from affiliate list
    				$indeed_db->remove_user_from_affiliate( $uid );
    		}
    		die;
    }

    public function ihc_admin_add_many_affiliates()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
    		if ( !$ids ){
    				die;
    		}
    		$users = explode( ',', $ids );
    		global $indeed_db;
    		$default_rank = get_option('uap_register_new_user_rank');
    		foreach ( $users as $uid ){
    				//
    				$inserted = $indeed_db->save_affiliate( $uid );
    				if ( $inserted ){
    						/// put default rank on this new affiliate
    						$indeed_db->update_affiliate_rank_by_uid( $uid, $default_rank);
    				}
    		}
    		die;
    }

    public function ihc_admin_delete_locker()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
        $id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
        if ( !$id ){
            die;
        }
        \Ihc_Db::deleteLocker( $id );
        die;
    }


    public function ihc_admin_delete_many_lockers()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
    		if ( !$ids ){
    				die;
    		}
    		$lockers = explode( ',', $ids );
    		foreach ( $lockers as $locker ){
    				\Ihc_Db::deleteLocker( $locker );
    		}
    		die;
    }

    public function ihc_admin_delete_many_orders()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
    		if ( !$ids ){
    				die;
    		}
    		$orders = explode( ',', $ids );
    		foreach ( $orders as $orderId ){
    				// delete each order
    				\Ihc_Db::delete_order( $orderId );
    		}
    		die;
    }

    public function ihc_admin_complete_many_orders()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
    		if ( !$ids ){
    				die;
    		}
    		$orders = explode( ',', $ids );
    		foreach ( $orders as $orderId ){
    				$orderObject = new \Indeed\Ihc\Db\Orders();
    				$orderData = $orderObject->setId( $orderId )->fetch()->get();
    				if ( isset( $orderData->status ) && $orderData->status === 'Completed' ){
    						continue;
    				}
    				$orderObject->setId( $orderId )->update( 'status', 'Completed' );
    				$orderData = $orderObject->fetch()->get();
    				if ( !$orderData ){
    						continue;
    				}
    				$orderMeta = new \Indeed\Ihc\Db\OrderMeta();
    				$paymentGateway = $orderMeta->get( $orderId, 'ihc_payment_type' );
    				$levelData = \Indeed\Ihc\Db\Memberships::getOne( $orderData->lid );
    				if (isset($levelData['access_trial_time_value']) && $levelData['access_trial_time_value'] > 0 && \Indeed\Ihc\UserSubscriptions::isFirstTime( $orderData->uid, $orderData->lid )){
    					/// CHECK FOR TRIAL
    						\Indeed\Ihc\UserSubscriptions::makeComplete( $orderData->uid, $orderData->lid, true, [ 'manual' => true, 'payment_gateway' => $paymentGateway ] );
    				} else {
    						\Indeed\Ihc\UserSubscriptions::makeComplete( $orderData->uid, $orderData->lid, false, [ 'manual' => true, 'payment_gateway' => $paymentGateway ] );
    				}
    				if ( $paymentGateway === 'bank_transfer' ){
    					// create a transaction_id for this entry
    					$transactionId = $orderData->uid . '_' . $orderData->lid . '_' . time();
    					$orderMeta->save( $orderId, 'transaction_id', $transactionId );
    					do_action( 'ihc_payment_completed', $orderData->uid, $orderData->lid );
    				}
    		}
    		die;
    }

    public function ihc_admin_delete_order()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
                    $id = isset($_POST['id']) ? sanitize_text_field( $_POST['id'] ) : false;
        if ( !$id ){
            die;
        }
        \Ihc_Db::delete_order( $id );
        die;
    }

    public function ihc_admin_delete_payment_transaction()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
        $id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
    		if ( !$id ){
    				die;
    		}
    		require_once IHC_PATH . 'admin/includes/functions.php';
    		ihc_delete_payment_entry( $id );
    		die;
    }

    public function ihc_admin_delete_notification()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
    		if ( !$id ){
    				die;
    		}
    		// delete notification
    		$notificationObject = new \Indeed\Ihc\Notifications();
    		$notificationObject->deleteOne( $id );
    		die;
    }

    public function ihc_admin_delete_many_coupons()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
    		if ( !$ids ){
    				die;
    		}
    		$coupons = explode( ',', $ids );
    		foreach ( $coupons as $couponId ){
    				// delete each coupons
    				ihc_delete_coupon( $couponId );
    		}
    		die;
    }


    public function ihc_admin_delete_level()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$lid = isset( $_POST['lid'] ) ? sanitize_text_field( $_POST['lid'] ) : false;
    		if ( !$lid ){
    				die;
    		}
    		\Indeed\Ihc\Db\Memberships::deleteOne( $lid );

    		\Indeed\Ihc\UserSubscriptions::deleteAllForSubscription( $lid );

    		\Ihc_Db::deletePostMetaRestrictionsForMembership( $lid );
    	  do_action( 'ihc_delete_level_action', $lid );
    		die;
    }

    public function ihc_admin_delete_many_memberships()
    {
    		if ( !ihcIsAdmin() ){
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				die;
    		}
    		$ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
    		if ( !$ids ){
    				die;
    		}
    		$lids = explode( ',', $ids );
    		foreach ( $lids as $lid ){
    				\Indeed\Ihc\Db\Memberships::deleteOne( $lid );
    				\Indeed\Ihc\UserSubscriptions::deleteAllForSubscription( $lid );
    				\Ihc_Db::deletePostMetaRestrictionsForMembership( $lid );
    			  do_action( 'ihc_delete_level_action', $lid );
    		}

    		die;
    }

    public function ihc_reorder_levels()
    {
    		if ( !ihcIsAdmin() ){
    				echo 0;
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				echo 0;
    				die;
    		}
    		$json = stripslashes( sanitize_text_field( $_REQUEST['json_data'] ) );
    		$json_arr = json_decode($json);
    		$i = 0;
    		foreach ($json_arr as $k){
    			\Indeed\Ihc\Db\Memberships::setOrderForMembership( $k, $i );

    			$i++;
    		}
    		die;
    }

    public function ihc_delete_coupon_ajax()
    {
    		if ( !ihcIsAdmin() ){
    				echo 0;
    				die;
    		}
    		if ( !ihcAdminVerifyNonce() ){
    				echo 0;
    				die;
    		}
    		ihc_delete_coupon( sanitize_text_field( $_REQUEST['id'] ) );
    		echo 1;
    		die;
    }

    /**
     * @param none
     * @return none
     */
    public function ihc_make_user_affiliate()
    {
    	  if ( !ihcIsAdmin() ){
    			  echo 0;
    			  die;
    	  }
    	  if ( !ihcAdminVerifyNonce() ){
    			  echo 0;
    			  die;
    	  }
                    $uid = isset( $_REQUEST['uid'] ) ? sanitize_text_field( $_REQUEST['uid'] ) : false;
                    $act = isset( $_REQUEST['act'] ) ? sanitize_text_field( $_REQUEST['act'] ) : false;

    		if ($uid && $act!==false && defined('UAP_PATH')){
    				if (!class_exists('UapDb')){
    						require_once UAP_PATH . 'classes/UapDb.class.php';
    						$indeed_db = new UapDb;
    				} else {
    						global $indeed_db;
    				}

    				if ($act==0){
    					  // remove from affiliates
    					  $indeed_db->remove_user_from_affiliate( $uid );
    				} else {
    					  /// add to affiliates
    					  $inserted = $indeed_db->save_affiliate( $uid );
    					  if ($inserted){
    								/// put default rank on this new affiliate
    								$default_rank = get_option('uap_register_new_user_rank');
    								$indeed_db->update_affiliate_rank_by_uid( $uid, $default_rank);
    								echo esc_html($inserted);
    					  }
    				}
    		}
    	  die;
    }

    public function ihc_admin_delete_one_car_post()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
        require_once IHC_PATH . 'admin/includes/functions.php';
        $item = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
        ihc_delete_block_group( 'ihc_block_posts_by_type', $item );
        die;
    }

    public function ihc_admin_delete_many_car_post()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
        require_once IHC_PATH . 'admin/includes/functions.php';
        $ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
        if ( !$ids ){
            die;
        }
        $items = explode( ',', $ids );
        foreach ( $items as $item ){
            ihc_delete_block_group('ihc_block_posts_by_type', $item );
        }

        die;
    }

    public function ihc_admin_delete_one_car_cat()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
        require_once IHC_PATH . 'admin/includes/functions.php';
        $item = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
        ihc_delete_block_group( 'ihc_block_cats_by_name', $item );
        die;
    }

    public function ihc_admin_delete_many_car_cat()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
        require_once IHC_PATH . 'admin/includes/functions.php';
        $ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
        if ( !$ids ){
            die;
        }
        $items = explode( ',', $ids );
        foreach ( $items as $item ){
            ihc_delete_block_group('ihc_block_cats_by_name', $item );
        }

        die;
    }

    public function ihc_admin_delete_one_car_file()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
    		require_once IHC_PATH . 'admin/includes/functions.php';
        $item = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
        ihc_delete_block_group( 'ihc_block_files_by_url', $item );
        die;
    }

    public function ihc_admin_delete_many_car_file()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
    		require_once IHC_PATH . 'admin/includes/functions.php';
        $ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
        if ( !$ids ){
            die;
        }
        $items = explode( ',', $ids );
        foreach ( $items as $item ){
            ihc_delete_block_group('ihc_block_files_by_url', $item );
        }

        die;
    }

    public function ihc_admin_delete_one_car_entire_url()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
    		require_once IHC_PATH . 'admin/includes/functions.php';
        $item = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
        ihc_delete_block_group( 'ihc_block_url_entire', $item );
        die;
    }

    public function ihc_admin_delete_many_car_entire_url()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
    		require_once IHC_PATH . 'admin/includes/functions.php';
        $ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
        if ( !$ids ){
            die;
        }
        $items = explode( ',', $ids );
        foreach ( $items as $item ){
            ihc_delete_block_group('ihc_block_url_entire', $item );
        }

        die;
    }

    public function ihc_admin_delete_one_car_url_word()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
    		require_once IHC_PATH . 'admin/includes/functions.php';
        $item = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : false;
        ihc_delete_block_group( 'ihc_block_url_word', $item );
        die;
    }

    public function ihc_admin_delete_many_car_url_word()
    {
        if ( !ihcIsAdmin() ){
            die;
        }
        if ( !ihcAdminVerifyNonce() ){
            die;
        }
    		require_once IHC_PATH . 'admin/includes/functions.php';
        $ids = isset( $_POST['ids'] ) ? sanitize_text_field( $_POST['ids'] ) : false;
        if ( !$ids ){
            die;
        }
        $items = explode( ',', $ids );
        foreach ( $items as $item ){
            ihc_delete_block_group('ihc_block_url_word', $item );
        }

        die;
    }

}
