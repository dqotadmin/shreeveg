<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1', 'middleware' => 'localization'], function () {
    Route::get('authentication-failed', function () {
        $errors = [];
        array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthenticated.']);
        return response()->json([
            'errors' => $errors,
        ], 401);
    })->name('authentication-failed');

    Route::get('/terms', function () {
        return view('terms');
    });
    Route::get('/privacy-policy', function () {
        return view('privacy_policy');
    });
    
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('register', 'CustomerAuthController@registration');
        Route::post('login', 'CustomerAuthController@login');
        Route::post('logout', 'CustomerAuthController@logout');
        Route::post('social-customer-login', 'CustomerAuthController@social_customer_login');

        Route::post('check-phone', 'CustomerAuthController@check_phone');
        Route::post('verify-phone', 'CustomerAuthController@verify_phone');
        Route::post('login-otp', 'CustomerAuthController@login_otp'); // new api 

        Route::post('check-email', 'CustomerAuthController@check_email');
        Route::post('verify-email', 'CustomerAuthController@verify_email');

        Route::post('forgot-password', 'PasswordResetController@reset_password_request');
        Route::post('verify-token', 'PasswordResetController@verify_token');
        Route::put('reset-password', 'PasswordResetController@reset_password_submit');
        Route::post('get-location', 'LocationController@get_location');

        Route::post('login-guest-user', 'CustomerAuthController@guestUserLogin');


        Route::group(['prefix' => 'delivery-man'], function () {
            Route::post('register', 'DeliveryManLoginController@registration');
            Route::post('login', 'DeliveryManLoginController@login');
        });
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('latest', 'ProductController@get_latest_products');
        Route::get('popular', 'ProductController@get_popular_products');
        Route::get('discounted', 'ProductController@get_discounted_products');
        Route::get('search', 'ProductController@get_searched_products');
        Route::get('details/{id}', 'ProductController@get_product');
        Route::get('related-products/{product_id}', 'ProductController@get_related_products');
        Route::get('reviews/{product_id}', 'ProductController@get_product_reviews');
        Route::get('rating/{product_id}', 'ProductController@get_product_rating');
        Route::get('daily-needs', 'ProductController@get_daily_need_products');
        Route::get('group', 'ProductController@product_group');
        Route::post('reviews/submit', 'ProductController@submit_product_review')->middleware('auth:api');

        Route::group(['prefix' => 'favorite', 'middleware' => ['auth:api', 'customer_is_block']], function () {
            Route::get('/', 'ProductController@get_favorite_products');
            Route::post('/', 'ProductController@add_favorite_products');
            Route::delete('/', 'ProductController@remove_favorite_products');
        });

        Route::get('featured', 'ProductController@featured_products');
        Route::get('most-viewed', 'ProductController@get_most_viewed_products');
        Route::get('trending', 'ProductController@get_trending_products');
        Route::get('recommended', 'ProductController@get_recommended_products');
        Route::get('most-reviewed', 'ProductController@get_most_reviewed_products');
    });

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@get_notifications');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@get_categories');
        Route::get('childes/{category_id}', 'CategoryController@get_childes');
        Route::get('products/{category_id}', 'CategoryController@get_products');
        Route::get('products/{category_id}/all', 'CategoryController@get_all_products');
    });

    Route::group(['prefix' => 'customer', 'middleware' => ['auth:api', 'customer_is_block']], function () {
        Route::get('info', 'CustomerController@info');
        Route::post('update-profile', 'CustomerController@update_profile');
        Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');
        Route::delete('unsubscribe-topic', 'CustomerController@unsubscribe_topic');

        Route::delete('remove-account', 'CustomerController@remove_account');

        Route::group(['prefix' => 'address'], function () {
            Route::get('list', 'CustomerController@address_list');
            Route::post('add', 'CustomerController@add_new_address');
            Route::post('update/{id}', 'CustomerController@update_address');
            Route::delete('delete', 'CustomerController@delete_address');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('list', 'OrderController@get_order_list');
            Route::get('details', 'OrderController@get_order_details');
            Route::post('place', 'OrderController@place_order');
            Route::put('cancel', 'OrderController@cancel_order');
            Route::get('track', 'OrderController@track_order');
            Route::put('payment-method', 'OrderController@update_payment_method');
            Route::get('history', 'OrderController@order_history');
        });
        // Chatting
        Route::group(['prefix' => 'message'], function () {
            //customer-admin
            Route::get('get-admin-message', 'ConversationController@get_admin_message');
            Route::post('send-admin-message', 'ConversationController@store_admin_message');
            //customer-deliveryman
            Route::get('get-order-message', 'ConversationController@get_message_by_order');
            Route::post('send/{sender_type}', 'ConversationController@store_message_by_order');

            //            Route::get('get', 'ConversationController@messages');
            //            Route::post('send', 'ConversationController@messages_store');
            //            Route::post('chat-image', 'ConversationController@chat_image');
        });

        Route::group(['prefix' => 'wish-list'], function () {
            Route::get('/', 'WishlistController@wish_list');
            Route::post('add', 'WishlistController@add_to_wishlist');
            Route::delete('remove', 'WishlistController@remove_from_wishlist');
        });

        Route::post('transfer-point-to-wallet', 'CustomerWalletController@transfer_loyalty_point_to_wallet');
        Route::post('wallet-add-money', 'CustomerWalletController@wallet_add_money');
         Route::get('wallet-transactions', 'CustomerWalletController@wallet_transactions');
        Route::get('wallet-transactions-history', 'CustomerWalletController@wallet_transactions_history');
        Route::get('loyalty-point-transactions', 'LoyaltyPointController@point_transactions');
    });

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });

    Route::group(['prefix' => 'coupon', 'middleware' => ['auth:api', 'customer_is_block']], function () {
        Route::get('list', 'CouponController@list');
        Route::get('apply', 'CouponController@apply');
    });
    //timeSlot
    Route::group(['prefix' => 'timeSlot'], function () {
        Route::get('/', 'TimeSlotController@getTime_slot');
    });
    //map api
    Route::group(['prefix' => 'mapapi'], function () {
        Route::get('place-api-autocomplete', 'MapApiController@place_api_autocomplete');
        Route::get('distance-api', 'MapApiController@distance_api');
        Route::get('place-api-details', 'MapApiController@place_api_details');
        Route::get('geocode-api', 'MapApiController@geocode_api');
    });

    Route::group(['prefix' => 'flash-deals'], function () {
        Route::get('/', 'OfferController@get_flash_deal');
        Route::get('products/{flash_deal_id}', 'OfferController@get_flash_deal_products');
    });

    Route::post('subscribe-newsletter', 'CustomerController@subscribe_newsletter');

    Route::group(['prefix' => 'delivery-man'], function () {
        Route::group(['middleware' => 'deliveryman_is_active'], function () {
            Route::get('profile', 'DeliverymanController@get_profile');
            Route::get('current-orders', 'DeliverymanController@get_current_orders');
            Route::get('all-orders', 'DeliverymanController@get_all_orders');
            Route::post('record-location-data', 'DeliverymanController@record_location_data');
            Route::get('order-delivery-history', 'DeliverymanController@get_order_history');
            Route::put('update-order-status', 'DeliverymanController@update_order_status');
            Route::put('update-payment-status', 'DeliverymanController@order_payment_status_update');
            Route::get('order-details', 'DeliverymanController@get_order_details');
            Route::get('last-location', 'DeliverymanController@get_last_location');
            Route::put('update-fcm-token', 'DeliverymanController@update_fcm_token');
        });


        //delivery-man message
        Route::group(['prefix' => 'message'], function () {
            Route::post('get-message', 'ConversationController@get_order_message_for_dm');
            Route::post('send/{sender_type}', 'ConversationController@store_message_by_order');
        });

        Route::group(['prefix' => 'reviews', 'middleware' => ['auth:api', 'customer_is_block']], function () {
            Route::get('/{delivery_man_id}', 'DeliveryManReviewController@get_reviews');
            Route::get('rating/{delivery_man_id}', 'DeliveryManReviewController@get_rating');
            Route::post('/submit', 'DeliveryManReviewController@submit_review');
        });
    });
});
