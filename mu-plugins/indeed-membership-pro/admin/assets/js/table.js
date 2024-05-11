/*
* Ultimate Membership Pro - Table functionality
*/
"use strict";
var IumpDataTable = {
    tableId               : '#iump-dashboard-table',
    translatedLabels      : null,
    tableButtons          : null,
    tableObject           : null,
    columns               : null,

    init                        : function(){
        var object = this;
        var labels = JSON.parse( window.iump_datatable_labels );
        object.columns = JSON.parse( window.iump_datatable_cols );

        // default settings for datatable
        object.translatedLabels = {
                      processing    : "<div class='iump-table-custom-loading'></div>",
                      search				: '',
                      lengthMenu		: labels.lengthMenu,
                      info					: labels.info,
                      infoEmpty			: labels.infoEmpty,
                      infoFiltered	: labels.infoFiltered,
                      loadingRecords: labels.loadingRecords,
                      zeroRecords		: labels.zeroRecords,
                      emptyTable		: labels.emptyTable,
                      paginate			: {
                                    first					: labels.paginate.first,
                                    previous			: labels.paginate.previous,
                                    next					: labels.paginate.next,
                                    last					: labels.paginate.last,
                      },
                      aria					: {
                                    sortAscending		: labels.sortAscending,
                                    sortDescending	: labels.sortDescending,
                      },
                      searchPlaceholder			: labels.searchPlaceholder,
                      search								: "",
                      scroller              : {
                                    loadingIndicator : true
                      }
        };

        // default buttons
        object.tableButtons = [
            {
                extend      : 'colvis',// show/hide columns button
                text        : labels.show_hide_cols_label,// action buttons
                className   : 'ihc-show-hide-cols ihc-dashboard-table-show-hide-cols'
            },
        ];

        // select items checkbox
        jQuery( '.iump-js-select-all-checkboxes' ).on('click', function(){
            object.selectUnselectAll( object );
        });

        // create the table
        if ( typeof window.iump_datatable_type == 'undefined' || window.iump_datatable_type === '' ){
            return;
        }

        switch ( window.iump_datatable_type ){
            case 'affiliates':
              object.loadTableForAffiliates( object );
              /// event on custom filter - do reload
              jQuery( '.iump-datatable-filter-show-only-role' ).on('change', function () {
                   object.tableObject.destroy();
                   object.loadTableForAffiliates( object );
              });
              // event on custom search - do reload
              jQuery( '.iump-js-search-phrase' ).on('keyup', function () {
                   object.tableObject.destroy();
                   object.loadTableForAffiliates( object );
              });
              jQuery('.iump-js-datatable-is-affiliate').on('change', function () {
                   object.tableObject.destroy();
                   object.loadTableForAffiliates( object );
              });
              break;
            case 'memberships':
              object.loadTableForMemberships( object );
              /// event on custom filter - do reload
              jQuery( '.iump-datatable-filter-show-only-role' ).on('change', function () {
                   object.tableObject.destroy();
                   object.loadTableForMemberships( object );
              });
              // event on custom search - do reload
              jQuery( '.iump-js-search-phrase' ).on('keyup', function () {
                   object.tableObject.destroy();
                   object.loadTableForMemberships( object );
              });
              break;
            case 'notifications':
              object.loadTableForNotifications( object );
              break;
            case 'orders':
              object.loadTableForOrders( object );
              jQuery( '.iump-js-admin-orders-submit-filters-bttn' ).on( 'click', function(){
                  object.tableObject.destroy();
                  object.loadTableForOrders( object );
              });
              break;
            case 'coupons':
              object.loadTableForCoupons( object );
              break;
            case 'inside_locker':
              object.loadTableForInsideLocker( object );
              break;
            case 'car_posts':
              object.loadTableForCarPosts( object ); // content access rules - all posts
              break;
            case 'car_cats':
              object.loadTableForCarCats( object ); // content access rules - categories
              break;
            case 'car_files':
              object.loadTableForCarFiles( object );
              break;
            case 'car_entire_url':
              object.loadTableForCarEntireUrl( object );
              break;
            case 'car_url_word':
              object.loadTableForCarUrlWord( object );
              break;
        }
    },

    loadTableForAffiliates: function( object ){
          object.tableObject = new DataTable( object.tableId, {
            ajax           : {
                    type      : "post",
                    url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                    data      : {
                               action             : "ihc_ajax_get_affiliates", // ajax method
                               role               : jQuery('.iump-datatable-filter-show-only-role').val(), // extra param for search
                               search_phrase      : jQuery('.iump-js-search-phrase' ).val(), // extra param for search
                               user_type          : jQuery('.iump-js-datatable-is-affiliate').val(), // extra param for search
                    },
                    dataSrc   : 'data'
            },
            //retrieve       : true,
            serverSide     : true,
            dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BP>r<"iump-datatable-wrapp"t>ipl', // '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl'
            lengthChange   : true,
            pageLength     : 25,
            drawCallback   : function(){
                  jQuery("body").removeClass("loading"); // remove loading
                  jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                  jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                  jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                  // make many users affiliates / make many affiliates just users
                  if ( jQuery( '.iump-js-affiliates-apply-bttn' ).length ){
                      jQuery( '.iump-js-affiliates-apply-bttn' ).on( 'click', function(evt){
                            evt.preventDefault();
                            if ( jQuery('.iump-js-bulk-action-select').val() === 'remove' ){
                                // make many affiliates just users
                                ihcSwal({
                                  title: jQuery('.ihc-affiliates-lists-wrapper').attr('data-remove_many_affiliates'),
                                  text: "",
                                  type: "warning",
                                  showCancelButton: true,
                                  confirmButtonClass: "btn-danger",
                                  confirmButtonText: "OK",
                                  closeOnConfirm: true
                                },
                                function(){
                                    var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                        return this.value;
                                    }).get();
                                    var valsToSend = checkedVals.join(",");
                                    jQuery.ajax({
                                            type 			: 'post',
                                            url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                            data 			: {
                                                       action		: 'ihc_admin_remove_affiliates',
                                                       ids			: valsToSend,
                                            },
                                            success		: function (response) {
                                                object.tableObject.destroy();
                                                object.loadTableForAffiliates( object );
                                            }
                                    });
                               });
                            } else if ( jQuery('.iump-js-bulk-action-select').val() === 'add' ){
                                // do complete
                                ihcSwal({
                                  title: jQuery('.ihc-affiliates-lists-wrapper').attr('data-add_many_affiliates'),
                                  text: "",
                                  type: "warning",
                                  showCancelButton: true,
                                  confirmButtonClass: "btn-danger",
                                  confirmButtonText: "OK",
                                  closeOnConfirm: true
                                },
                                function(){
                                    var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                        return this.value;
                                    }).get();
                                    var valsToSend = checkedVals.join(",");
                                    jQuery.ajax({
                                            type 			: 'post',
                                            url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                            data 			: {
                                                       action		: 'ihc_admin_add_many_affiliates',
                                                       ids			: valsToSend,
                                            },
                                            success		: function (response) {
                                                object.tableObject.destroy();
                                                object.loadTableForAffiliates( object );
                                            }
                                    });
                               });
                            }

                      });
                  }
            },
            buttons        : object.tableButtons,
            language       : object.translatedLabels,
            columns        : object.columns,
            processing     : true,
            orderCellsTop  : true,
            fixedHeader    : false,
            order          : [[ 1, "asc" ]],
            scrollToTop    : true
        });
    },

    loadTableForMemberships: function( object ){
        object.tableObject = new DataTable( object.tableId, {
            ajax           : {
                    type      : "post",
                    url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                    data      : {
                               action             : "ihc_ajax_get_memberships", // ajax method
                               search_phrase      : jQuery('.iump-js-search-phrase' ).val(), // extra param for search
                    },
                    dataSrc   : 'data'
            },
            //retrieve       : true,
            serverSide     : true,
            dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
            lengthChange   : true,
            pageLength     : 25,
            drawCallback   : function(){
                  jQuery("body").removeClass("loading"); // remove loading
                  jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                  jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                  jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                  /// memberships - delete many
                  if ( jQuery( '.iump-js-memberships-apply-bttn' ).length ){
                      jQuery( '.iump-js-memberships-apply-bttn' ).on( 'click', function(evt){
                            evt.preventDefault();
                            if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                return false;
                            }
                            ihcSwal({
                              title: jQuery('.ihc-memberships-lists-wrapper').attr('data-delete_many_levels'),
                              text: "",
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonClass: "btn-danger",
                              confirmButtonText: "OK",
                              closeOnConfirm: true
                            },
                            function(){
                                var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                    return this.value;
                                }).get();
                                var valsToSend = checkedVals.join(",");
                                jQuery.ajax({
                                        type 			: 'post',
                                        url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                        data 			: {
                                                   action		: 'ihc_admin_delete_many_memberships',
                                                   ids			: valsToSend,
                                        },
                                        success		: function (response) {
                                            object.tableObject.destroy();
                                            object.loadTableForMemberships( object );
                                        }
                                });
                           });
                      });
                  }

                  // delete
                  jQuery( '.ihc-js-delete-level' ).on( 'click', function(){
                    var levelId = jQuery( this ).attr( 'data-id' );
                    ihcSwal({
                      title: jQuery('.ihc-js-admin-messages').attr('data-delete_level'),
                      text: "",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "OK",
                      closeOnConfirm: true
                    },
                    function(){
                        jQuery.ajax({
                            type 			: 'post',
                            url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                            data 			: {
                                       action		: 'ihc_admin_delete_level',
                                       lid			: levelId,
                            },
                            success		: function (response) {
                                object.tableObject.destroy();
                                object.loadTableForMemberships( object );
                            }
                       });
                   });
                });
            },
            buttons        : object.tableButtons,
            language       : object.translatedLabels,
            columns        : object.columns,
            processing     : true,
            orderCellsTop  : true,
            fixedHeader    : false,
            order          : [ [ 1, "asc" ] ],
            scrollToTop    : true,
            createdRow: function( row, data, dataIndex ) {
                jQuery(row).attr('onmouseover', "ihcDhSelector('#level_tr_"+ data.id.value +"', 1);");
                jQuery(row).attr('onmouseout', "ihcDhSelector('#level_tr_"+ data.id.value +"', 0);");
                jQuery(row).attr('id', 'iump_membership_table_tr_' + data.id.value );
            },
        });
    },

    loadTableForNotifications : function( object ){
          object.tableObject = new DataTable( object.tableId, {
              ajax           : {
                      type      : "post",
                      url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                      data      : {
                                 action             : "ihc_ajax_get_notifications", // ajax method
                      },
                      dataSrc   : 'data'
              },
              //retrieve       : true,
              serverSide     : true,
              dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
              lengthChange   : true,
              pageLength     : 25,
              drawCallback   : function(){
                    jQuery("body").removeClass("loading"); // remove loading
                    jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                    jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                    jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                    // delete one notification
                    jQuery( '.ihc-js-admin-notifications-delete-notification' ).on( 'click', function(){
                      var notificationId = jQuery( this ).attr( 'data-id' );
                      ihcSwal({
                        title: jQuery( '.iump-js-notifications-table' ).attr('data-delete_message'),
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                      },
                      function(){
                          jQuery.ajax({
                              type 			: 'post',
                              url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                              data 			: {
                                         action		: 'ihc_admin_delete_notification',
                                         id			: notificationId,
                              },
                              success		: function (response) {
                                  object.tableObject.destroy();
                                  object.loadTableForNotifications( object );
                              }
                         });
                     });
                  });
              },
              buttons        : object.tableButtons,
              language       : object.translatedLabels,
              columns        : object.columns,
              processing     : true,
              orderCellsTop  : true,
              fixedHeader    : false,
              order          : [ [ 0, "asc" ] ],
              scrollToTop    : true,
              createdRow: function( row, data, dataIndex ) {
                  jQuery(row).attr('onmouseover', "ihcDhSelector('#notify_tr_"+ data.id +"', 1);");
                  jQuery(row).attr('onmouseout', "ihcDhSelector('#notify_tr_"+ data.id +"', 0);");
              },
          });

    },

    loadTableForOrders    : function( object ){
        object.tableObject = new DataTable( object.tableId, {
            ajax           : {
                    type      : "post",
                    url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                    data      : {
                               action               : "ihc_ajax_get_orders", // ajax method
                               search_phrase        : jQuery('.iump-js-search-phrase' ).val(), // extra param for search
                               order_status         : jQuery('.iump-datatable-filter-orders-status').val(), // extra param for search
                               subscription_type    : jQuery('.iump-datatable-filter-orders-subscription-type').val(), // extra param for search
                               payment_gateway      : jQuery('.iump-datatable-filter-orders-payment-gateway').val(), // extra param for search
                               start_time           : jQuery('.iump-js-orders-start-date').val(), // extra param for search
                               end_time             : jQuery('.iump-js-orders-end-date').val(), // extra param for search
                               uid                  : window.iump_orders_uid,// since version 12.1, 0 if its not set
                    },
                    dataSrc   : 'data'
            },
            //retrieve       : true,
            serverSide     : true,
            dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BP>r<"iump-datatable-wrapp"t>ipl',
            lengthChange   : true,
            pageLength     : 25,
            drawCallback   : function(){
                  jQuery("body").removeClass("loading"); // remove loading
                  jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                  jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                  jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                  // many orders
                  if ( jQuery( '.iump-js-orders-apply-bttn' ).length ){
                      jQuery( '.iump-js-orders-apply-bttn' ).on( 'click', function(evt){
                            evt.preventDefault();
                            if ( jQuery('.iump-js-bulk-action-select').val() === 'remove' ){
                                // do delete
                                ihcSwal({
                                  title: jQuery('.ihc-orders-lists-wrapper').attr('data-delete_many_orders'),
                                  text: "",
                                  type: "warning",
                                  showCancelButton: true,
                                  confirmButtonClass: "btn-danger",
                                  confirmButtonText: "OK",
                                  closeOnConfirm: true
                                },
                                function(){
                                    var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                        return this.value;
                                    }).get();
                                    var valsToSend = checkedVals.join(",");
                                    jQuery.ajax({
                                            type 			: 'post',
                                            url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                            data 			: {
                                                       action		: 'ihc_admin_delete_many_orders',
                                                       ids			: valsToSend,
                                            },
                                            success		: function (response) {
                                                object.tableObject.destroy();
                                                object.loadTableForOrders( object );
                                            }
                                    });
                               });
                            } else if ( jQuery('.iump-js-bulk-action-select').val() === 'make_completed' ){
                                // do complete
                                ihcSwal({
                                  title: jQuery('.ihc-orders-lists-wrapper').attr('data-complete_many_orders'),
                                  text: "",
                                  type: "warning",
                                  showCancelButton: true,
                                  confirmButtonClass: "btn-danger",
                                  confirmButtonText: "OK",
                                  closeOnConfirm: true
                                },
                                function(){
                                    var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                        return this.value;
                                    }).get();
                                    var valsToSend = checkedVals.join(",");
                                    jQuery.ajax({
                                            type 			: 'post',
                                            url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                            data 			: {
                                                       action		: 'ihc_admin_complete_many_orders',
                                                       ids			: valsToSend,
                                            },
                                            success		: function (response) {
                                                object.tableObject.destroy();
                                                object.loadTableForOrders( object );
                                            }
                                    });
                               });
                            }

                      });
                  }

                  // single order - make  completed
                  jQuery( '.ihc-js-make-order-completed' ).on( 'click', function(){
                      var orderId = jQuery( this ).attr( 'data-id' );
                      jQuery.ajax({
                          type : 'post',
                          url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                          data : {
                                     action: 'ihc_admin_make_order_completed',
                                     id:			orderId,
                                 },
                          success: function (response) {
                              //location.reload();
                              object.tableObject.destroy();
                              object.loadTableForOrders( object );
                          }
                     });
                  });

                  // single order - delete
                  jQuery( '.ihc-js-delete-order' ).on( 'click', function(){
                      var orderId = jQuery( this ).attr( 'data-id' );
                      ihcSwal({
                          title									: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_order' ),
                          text									: "",
                          type									: "warning",
                          showCancelButton			: true,
                          confirmButtonClass		: "btn-danger",
                          confirmButtonText			: "OK",
                          closeOnConfirm				: true
                      },
                      function(){
                          jQuery.ajax({
                              type 				: 'post',
                              url 				: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                              data 				: {
                                         action: 'ihc_admin_delete_order',
                                         id:			orderId,
                              },
                              success			: function (response) {
                                  //location.reload();
                                  object.tableObject.destroy();
                                  object.loadTableForOrders( object );
                              }
                         });
                     });
                  });
            },
            buttons        : object.tableButtons,
            language       : object.translatedLabels,
            columns        : object.columns,
            processing     : true,
            orderCellsTop  : true,
            fixedHeader    : false,
            order          : [ [ 1, "desc" ] ],
            scrollToTop    : true,
            createdRow: function( row, data, dataIndex ) {

            },
            autoWidth     : false,
        });
    },

    loadTableForCoupons   : function( object ){
        object.tableObject = new DataTable( object.tableId, {
            ajax           : {
                    type      : "post",
                    url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                    data      : {
                               action             : "ihc_ajax_get_coupons", // ajax method
                    },
                    dataSrc   : 'data'
            },
            //retrieve       : true,
            serverSide     : true,
            dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
            lengthChange   : true,
            pageLength     : 25,
            drawCallback   : function(){
                  jQuery("body").removeClass("loading"); // remove loading
                  jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                  jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                  jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                  /// coupons - delete many
                  if ( jQuery( '.iump-js-coupons-apply-bttn' ).length ){
                      jQuery( '.iump-js-coupons-apply-bttn' ).on( 'click', function(evt){
                            evt.preventDefault();
                            if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                return false;
                            }
                            ihcSwal({
                              title: jQuery('.ihc-coupons-lists-wrapper').attr('data-delete_many_coupons'),
                              text: "",
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonClass: "btn-danger",
                              confirmButtonText: "OK",
                              closeOnConfirm: true
                            },
                            function(){
                                var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                    return this.value;
                                }).get();
                                var valsToSend = checkedVals.join(",");
                                jQuery.ajax({
                                        type 			: 'post',
                                        url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                        data 			: {
                                                   action		: 'ihc_admin_delete_many_coupons',
                                                   ids			: valsToSend,
                                        },
                                        success		: function (response) {
                                            object.tableObject.destroy();
                                            object.loadTableForCoupons( object );
                                        }
                                });
                           });
                      });
                  }

                  // delete order
                  jQuery( '.ihc-js-admin-coupons-delete-coupon' ).on( 'click', function(){
                      var couponID = jQuery( this ).attr( 'data-id' );
                      ihcSwal({
                          title									: jQuery( this ).attr( 'data-delete_message' ),
                          text									: "",
                          type									: "warning",
                          showCancelButton			: true,
                          confirmButtonClass		: "btn-danger",
                          confirmButtonText			: "OK",
                          closeOnConfirm				: true
                      },
                      function(){
                          jQuery.ajax({
                              type 				: 'post',
                              url 				: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                              data 				: {
                                          action: 'ihc_delete_coupon_ajax',
                                          id: couponID
                              },
                              success			: function (response) {
                                  object.tableObject.destroy();
                                  object.loadTableForCoupons( object );
                              }
                         });
                     });
                  });

            },
            buttons        : object.tableButtons,
            language       : object.translatedLabels,
            columns        : object.columns,
            processing     : true,
            orderCellsTop  : true,
            fixedHeader    : false,
            order          : [ [ 1, "asc" ] ],
            scrollToTop    : true,
            createdRow: function( row, data, dataIndex ) {
                jQuery(row).attr('onmouseover', "ihcDhSelector('#coupon_tr_"+ data.id +"', 1);");
                jQuery(row).attr('onmouseout', "ihcDhSelector('#coupon_tr_"+ data.id +"', 0);");
            },
        });
    },

    loadTableForInsideLocker : function( object ){
          object.tableObject = new DataTable( object.tableId, {
              ajax           : {
                      type      : "post",
                      url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                      data      : {
                                 action             : "ihc_ajax_get_inside_locker_items", // ajax method
                      },
                      dataSrc   : 'data'
              },
              //retrieve       : true,
              serverSide     : true,
              dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
              lengthChange   : true,
              pageLength     : 25,
              drawCallback   : function(){
                    jQuery("body").removeClass("loading"); // remove loading
                    jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                    jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                    jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                    // delete many inside lockers
                    if ( jQuery( '.iump-js-lockers-apply-bttn' ).length ){
                        jQuery( '.iump-js-lockers-apply-bttn' ).on( 'click', function(evt){
                              evt.preventDefault();
                              if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                  return false;
                              }
                              ihcSwal({
                                title: jQuery('.ihc-lockers-lists-wrapper').attr('data-delete_many_lockers'),
                                text: "",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                              },
                              function(){
                                  var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                      return this.value;
                                  }).get();
                                  var valsToSend = checkedVals.join(",");
                                  jQuery.ajax({
                                          type 			: 'post',
                                          url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                          data 			: {
                                                     action		: 'ihc_admin_delete_many_lockers',
                                                     ids			: valsToSend,
                                          },
                                          success		: function (response) {
                                              object.tableObject.destroy();
                                              object.loadTableForInsideLocker( object );
                                          }
                                  });
                             });
                        });
                    }

                    // delete one inside locker
                    jQuery( '.ihc-js-admin-delete-locker' ).on( 'click', function(){
                        var lockerId = jQuery( this ).attr( 'data-id' );
                        ihcSwal({
                          title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_item' ),
                          text: "",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonClass: "btn-danger",
                          confirmButtonText: "OK",
                          closeOnConfirm: true
                        },
                        function(){
                            jQuery.ajax({
                                type : 'post',
                                url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                data : {
                                           action : 'ihc_admin_delete_locker',
                                           id     :	lockerId,
                                       },
                                success: function (response) {
                                    object.tableObject.destroy();
                                    object.loadTableForInsideLocker( object );
                                }
                           });
                       });
                    });
              },
              buttons        : object.tableButtons,
              language       : object.translatedLabels,
              columns        : object.columns,
              processing     : true,
              orderCellsTop  : true,
              fixedHeader    : false,
              order          : [ [ 1, "asc" ] ],
              scrollToTop    : true,
              createdRow: function( row, data, dataIndex ) {
                  jQuery(row).attr('onmouseover', "ihcDhSelector('#iump_inside_locker_tr_"+ data.id +"', 1);");
                  jQuery(row).attr('onmouseout', "ihcDhSelector('#iump_inside_locker_tr_"+ data.id +"', 0);");
              },
              autoWidth     : false,
          });

    },

    loadTableForCarPosts : function( object ){
          object.tableObject = new DataTable( object.tableId, {
              ajax           : {
                      type      : "post",
                      url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                      data      : {
                                 action             : "ihc_ajax_get_car_posts_items", // ajax method
                      },
                      dataSrc   : 'data'
              },
              serverSide     : true,
              dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
              lengthChange   : true,
              pageLength     : 25,
              drawCallback   : function(){
                    jQuery("body").removeClass("loading"); // remove loading
                    jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                    jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                    jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                    // delete many car-post
                    if ( jQuery( '.iump-js-car-apply-bttn' ).length ){
                        jQuery( '.iump-js-car-apply-bttn' ).on( 'click', function(evt){
                              evt.preventDefault();
                              if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                  return false;
                              }
                              ihcSwal({
                                title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_items' ),
                                text: "",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                              },
                              function(){
                                  var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                      return this.value;
                                  }).get();
                                  var valsToSend = checkedVals.join(",");
                                  jQuery.ajax({
                                          type 			: 'post',
                                          url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                          data 			: {
                                                     action		: 'ihc_admin_delete_many_car_post',
                                                     ids			: valsToSend,
                                          },
                                          success		: function (response) {
                                              object.tableObject.destroy();
                                              object.loadTableForCarPosts( object );
                                          }
                                  });
                             });
                        });
                    }

                    // delete one car-post
                    jQuery( '.ihc-js-admin-delete-block-url-block' ).on( 'click', function( evt ){
                        var itemId = jQuery( this ).attr( 'data-id' );
                        ihcSwal({
                          title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_item' ),
                          text: "",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonClass: "btn-danger",
                          confirmButtonText: "OK",
                          closeOnConfirm: true
                        },
                        function(){
                            jQuery.ajax({
                                type : 'post',
                                url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                data : {
                                           action : 'ihc_admin_delete_one_car_post',
                                           id     :	itemId,
                                       },
                                success: function (response) {
                                    object.tableObject.destroy();
                                    object.loadTableForCarPosts( object );
                                }
                           });
                       });
                    });
                    // end of drawCallback
              },
              buttons        : object.tableButtons,
              language       : object.translatedLabels,
              columns        : object.columns,
              processing     : true,
              orderCellsTop  : true,
              fixedHeader    : false,
              order          : [ [ 1, "asc" ] ],
              scrollToTop    : true,
              createdRow: function( row, data, dataIndex ) {
              },
              autoWidth     : false,
          });
    },

    loadTableForCarCats : function( object ){
          object.tableObject = new DataTable( object.tableId, {
              ajax           : {
                      type      : "post",
                      url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                      data      : {
                                 action             : "ihc_ajax_get_car_cats", // ajax method
                      },
                      dataSrc   : 'data'
              },
              serverSide     : true,
              dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
              lengthChange   : true,
              pageLength     : 25,
              drawCallback   : function(){
                    jQuery("body").removeClass("loading"); // remove loading
                    jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                    jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                    jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                    // delete many car-cats
                    if ( jQuery( '.iump-js-car-apply-bttn' ).length ){
                        jQuery( '.iump-js-car-apply-bttn' ).on( 'click', function(evt){
                              evt.preventDefault();
                              if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                  return false;
                              }
                              ihcSwal({
                                title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_items' ),
                                text: "",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                              },
                              function(){
                                  var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                      return this.value;
                                  }).get();
                                  var valsToSend = checkedVals.join(",");
                                  jQuery.ajax({
                                          type 			: 'post',
                                          url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                          data 			: {
                                                     action		: 'ihc_admin_delete_many_car_cat',
                                                     ids			: valsToSend,
                                          },
                                          success		: function (response) {
                                              object.tableObject.destroy();
                                              object.loadTableForCarCats( object );
                                          }
                                  });
                             });
                        });
                    }

                    // delete one car-cats
                    jQuery( '.ihc-js-admin-delete-block-url-block' ).on( 'click', function( evt ){
                        var itemId = jQuery( this ).attr( 'data-id' );
                        ihcSwal({
                          title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_item' ),
                          text: "",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonClass: "btn-danger",
                          confirmButtonText: "OK",
                          closeOnConfirm: true
                        },
                        function(){
                            jQuery.ajax({
                                type : 'post',
                                url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                data : {
                                           action : 'ihc_admin_delete_one_car_cat',
                                           id     :	itemId,
                                       },
                                success: function (response) {
                                    object.tableObject.destroy();
                                    object.loadTableForCarCats( object );
                                }
                           });
                       });
                    });
                    // end of drawCallback
              },
              buttons        : object.tableButtons,
              language       : object.translatedLabels,
              columns        : object.columns,
              processing     : true,
              orderCellsTop  : true,
              fixedHeader    : false,
              order          : [ [ 1, "asc" ] ],
              scrollToTop    : true,
              createdRow: function( row, data, dataIndex ) {
              },
              autoWidth     : false,
          });
    },

    loadTableForCarFiles : function( object ){
          object.tableObject = new DataTable( object.tableId, {
              ajax           : {
                      type      : "post",
                      url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                      data      : {
                                 action             : "ihc_ajax_get_car_files", // ajax method
                      },
                      dataSrc   : 'data'
              },
              serverSide     : true,
              dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
              lengthChange   : true,
              pageLength     : 25,
              drawCallback   : function(){
                    jQuery("body").removeClass("loading"); // remove loading
                    jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                    jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                    jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                    // delete many car-file
                    if ( jQuery( '.iump-js-car-apply-bttn' ).length ){
                        jQuery( '.iump-js-car-apply-bttn' ).on( 'click', function(evt){
                              evt.preventDefault();
                              if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                  return false;
                              }
                              ihcSwal({
                                title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_items' ),
                                text: "",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                              },
                              function(){
                                  var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                      return this.value;
                                  }).get();
                                  var valsToSend = checkedVals.join(",");
                                  jQuery.ajax({
                                          type 			: 'post',
                                          url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                          data 			: {
                                                     action		: 'ihc_admin_delete_many_car_file',
                                                     ids			: valsToSend,
                                          },
                                          success		: function (response) {
                                              object.tableObject.destroy();
                                              object.loadTableForCarFiles( object );
                                          }
                                  });
                             });
                        });
                    }

                    // delete one car-file
                    jQuery( '.ihc-js-admin-delete-block-url-block' ).on( 'click', function( evt ){
                        var itemId = jQuery( this ).attr( 'data-id' );
                        ihcSwal({
                          title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_item' ),
                          text: "",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonClass: "btn-danger",
                          confirmButtonText: "OK",
                          closeOnConfirm: true
                        },
                        function(){
                            jQuery.ajax({
                                type : 'post',
                                url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                data : {
                                           action : 'ihc_admin_delete_one_car_file',
                                           id     :	itemId,
                                       },
                                success: function (response) {
                                    object.tableObject.destroy();
                                    object.loadTableForCarFiles( object );
                                }
                           });
                       });
                    });
                    // end of drawCallback
              },
              buttons        : object.tableButtons,
              language       : object.translatedLabels,
              columns        : object.columns,
              processing     : true,
              orderCellsTop  : true,
              fixedHeader    : false,
              order          : [ [ 1, "asc" ] ],
              scrollToTop    : true,
              createdRow: function( row, data, dataIndex ) {
              },
              autoWidth     : false,
          });
    },

    loadTableForCarEntireUrl : function( object ){
          object.tableObject = new DataTable( object.tableId, {
              ajax           : {
                      type      : "post",
                      url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                      data      : {
                                 action             : "ihc_ajax_get_car_entire_url", // ajax method
                      },
                      dataSrc   : 'data'
              },
              serverSide     : true,
              dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
              lengthChange   : true,
              pageLength     : 25,
              drawCallback   : function(){
                    jQuery("body").removeClass("loading"); // remove loading
                    jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                    jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                    jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                    // delete many car-file
                    if ( jQuery( '.iump-js-car-apply-bttn' ).length ){
                        jQuery( '.iump-js-car-apply-bttn' ).on( 'click', function(evt){
                              evt.preventDefault();
                              if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                  return false;
                              }
                              ihcSwal({
                                title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_items' ),
                                text: "",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                              },
                              function(){
                                  var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                      return this.value;
                                  }).get();
                                  var valsToSend = checkedVals.join(",");
                                  jQuery.ajax({
                                          type 			: 'post',
                                          url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                          data 			: {
                                                     action		: 'ihc_admin_delete_many_car_entire_url',
                                                     ids			: valsToSend,
                                          },
                                          success		: function (response) {
                                              object.tableObject.destroy();
                                              object.loadTableForCarEntireUrl( object );
                                          }
                                  });
                             });
                        });
                    }

                    // delete one car-file
                    jQuery( '.ihc-js-admin-delete-block-url-block' ).on( 'click', function( evt ){
                        var itemId = jQuery( this ).attr( 'data-id' );
                        ihcSwal({
                          title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_item' ),
                          text: "",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonClass: "btn-danger",
                          confirmButtonText: "OK",
                          closeOnConfirm: true
                        },
                        function(){
                            jQuery.ajax({
                                type : 'post',
                                url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                data : {
                                           action : 'ihc_admin_delete_one_car_entire_url',
                                           id     :	itemId,
                                       },
                                success: function (response) {
                                    object.tableObject.destroy();
                                    object.loadTableForCarEntireUrl( object );
                                }
                           });
                       });
                    });
                    // end of drawCallback
              },
              buttons        : object.tableButtons,
              language       : object.translatedLabels,
              columns        : object.columns,
              processing     : true,
              orderCellsTop  : true,
              fixedHeader    : false,
              order          : [ [ 1, "asc" ] ],
              scrollToTop    : true,
              createdRow: function( row, data, dataIndex ) {
              },
              autoWidth     : false,
          });
    },

    loadTableForCarUrlWord: function( object ){
        object.tableObject = new DataTable( object.tableId, {
            ajax           : {
                    type      : "post",
                    url       : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                    data      : {
                               action             : "ihc_ajax_get_car_url_word", // ajax method
                    },
                    dataSrc   : 'data'
            },
            serverSide     : true,
            dom            : '<"iump-datatable-top"<"iump-datatable-actions-wrapp">BPf>r<"iump-datatable-wrapp"t>ipl',
            lengthChange   : true,
            pageLength     : 25,
            drawCallback   : function(){
                  jQuery("body").removeClass("loading"); // remove loading
                  jQuery( '#iump-dashboard-table' ).removeClass( 'ihc-display-none' );
                  jQuery( '.iump-js-select-all-checkboxes' ).attr( 'checked', false );
                  jQuery( '.iump-datatable-actions-wrapp' ).html( jQuery('.iump-datatable-actions-wrapp-copy').html() );

                  // delete many url-word
                  if ( jQuery( '.iump-js-car-apply-bttn' ).length ){
                      jQuery( '.iump-js-car-apply-bttn' ).on( 'click', function(evt){
                            evt.preventDefault();
                            if ( jQuery('.iump-js-bulk-action-select').val() !== 'remove' ){
                                return false;
                            }
                            ihcSwal({
                              title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_items' ),
                              text: "",
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonClass: "btn-danger",
                              confirmButtonText: "OK",
                              closeOnConfirm: true
                            },
                            function(){
                                var checkedVals = jQuery('.iump-js-table-select-item:checkbox:checked').map(function() {
                                    return this.value;
                                }).get();
                                var valsToSend = checkedVals.join(",");
                                jQuery.ajax({
                                        type 			: 'post',
                                        url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                                        data 			: {
                                                   action		: 'ihc_admin_delete_many_car_url_word',
                                                   ids			: valsToSend,
                                        },
                                        success		: function (response) {
                                            object.tableObject.destroy();
                                            object.loadTableForCarUrlWord( object );
                                        }
                                });
                           });
                      });
                  }

                  // delete one url-word
                  jQuery( '.ihc-js-admin-delete-url-word' ).on( 'click', function( evt ){
                      var itemId = jQuery( this ).attr( 'data-id' );
                      ihcSwal({
                        title: jQuery( '.ihc-js-admin-messages' ).attr( 'data-delete_item' ),
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                      },
                      function(){
                          jQuery.ajax({
                              type : 'post',
                              url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                              data : {
                                         action : 'ihc_admin_delete_one_car_url_word',
                                         id     :	itemId,
                                     },
                              success: function (response) {
                                  object.tableObject.destroy();
                                  object.loadTableForCarUrlWord( object );
                              }
                         });
                     });
                  });
                  // end of drawCallback
            },
            buttons        : object.tableButtons,
            language       : object.translatedLabels,
            columns        : object.columns,
            processing     : true,
            orderCellsTop  : true,
            fixedHeader    : false,
            order          : [ [ 1, "asc" ] ],
            scrollToTop    : true,
            createdRow: function( row, data, dataIndex ) {
            },
            autoWidth     : false,
        });
    },

    selectUnselectAll     : function(){
        let doSelect = jQuery( '.iump-js-select-all-checkboxes' ).is(':checked');
        if ( doSelect ){
            jQuery( '.iump-js-table-select-item' ).each( function(){
                jQuery( this ).attr( 'checked', true );
            });
        } else {
          jQuery( '.iump-js-table-select-item' ).each( function(){
              jQuery( this ).attr('checked', false);
          })
        }
    }
};

jQuery( window ).on('load', function(){
    IumpDataTable.init([]);

    if ( jQuery('.iump-datatable-filter-show-only-role').length ){
      jQuery('.iump-datatable-filter-show-only-role').multiselect({
          selectAll: true,
          placeholder: jQuery('.iump-datatable-filter-show-only-role').attr( 'data-placeholder' ),
      });
    }

    // orders
    if ( jQuery('.iump-datatable-filter-orders-payment-gateway').length ){
        jQuery('.iump-datatable-filter-orders-payment-gateway').multiselect({
            selectAll: true,
            placeholder: jQuery('.iump-datatable-filter-orders-payment-gateway').attr( 'data-placeholder' ),
        });
    }

    if ( jQuery('.iump-datatable-filter-orders-subscription-type').length ){
        jQuery('.iump-datatable-filter-orders-subscription-type').multiselect({
            selectAll: true,
            placeholder: jQuery('.iump-datatable-filter-orders-subscription-type').attr( 'data-placeholder' ),
        });
    }

    if ( jQuery('.iump-datatable-filter-orders-status').length ){
        jQuery('.iump-datatable-filter-orders-status').multiselect({
            selectAll: true,
            placeholder: jQuery('.iump-datatable-filter-orders-status').attr( 'data-placeholder' ),
        });
    }

    if ( jQuery( '.iump-js-orders-start-date' ).length ){
        jQuery( '.iump-js-orders-start-date' ).datepicker({
    				dateFormat : 'dd-mm-yy',
            beforeShow : function(){
                 if (!jQuery('.iump-admin-datepicker-wrapper').length){
                      jQuery('#ui-datepicker-div').wrap('<span class="iump-admin-datepicker-wrapper"></span>');
                 }
            }
    		});
    }

    if ( jQuery( '.iump-js-orders-end-date' ).length ){
        jQuery( '.iump-js-orders-end-date' ).datepicker({
            dateFormat : 'dd-mm-yy',
            beforeShow : function(){
              if (!jQuery('.iump-admin-datepicker-wrapper').length){
                   jQuery('#ui-datepicker-div').wrap('<span class="iump-admin-datepicker-wrapper"></span>');
              }
            }
        });
    }

});

function ihcDatatableSortableOnOff(i, selector){
		if (window.ihc_sortable){
			//disable
			jQuery( selector ).sortable( { disabled: true } );
			jQuery( i ).attr('class', 'ihc-sortable-off');
			jQuery(selector).css('cursor', '');
			jQuery(selector).css('opacity', '1');
			jQuery('#ihc-reorder-msg').fadeOut(200);
			window.ihc_sortable = 0;
		} else {
			//enable
			jQuery( selector ).sortable({
					helper: function(e, ui) {
			        ui.children().each(function() {
			            jQuery(this).width(jQuery(this).width());
			        });
			        return ui;
			    },
					disabled: false,
          update: function(e, ui) {
             var arr = new Array();
             var i = 0;
             jQuery(selector + ' tr').each(function (i, row) {
               var id = jQuery(this).attr('id');
               if ( id ){
                     var level_id = jQuery('#'+id+' .ihc-hidden-level-id').val();
                     arr.push(level_id);
                   }
                   i++;
               });
               var j = false;
               var j = JSON.stringify(arr);
               if (j){
                     jQuery.ajax({
                         type : 'post',
                         url : decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
                         data : {
                                    action			: 'ihc_reorder_levels',
                                    json_data	: j,
                                },
                         success: function (response) {

                         }
                    });
                }
             }
			} );
			jQuery( i ).attr('class', 'ihc-sortable-on');
			jQuery(selector).css('cursor', 'move');
			jQuery(selector).css('opacity', '0.7');
			jQuery('#ihc-reorder-msg').fadeIn(200);
			window.ihc_sortable = 1;
		}
}

function iumpSendTestNotification(notificationId)
{
    jQuery.ajax({
        type 			: 'post',
        url 			: decodeURI(window.ihc_site_url)+'/wp-admin/admin-ajax.php',
        data 			: {
                   action : 'ihc_ajax_notification_send_test_email',
                   id			: notificationId
        },
        success		: function (data) {
            jQuery(data).hide().appendTo('body').fadeIn('normal');
        }
   });
}
