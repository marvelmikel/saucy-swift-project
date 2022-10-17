<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('lang/{locale}', 'LanguageController@lang')->name('lang');

    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit')->middleware('actch');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });
    /*authentication*/

    Route::group(['middleware' => ['admin']], function () {
        Route::get('/fcm/{id}', 'DashboardController@fcm')->name('dashboard');     //test route
        Route::get('/', 'DashboardController@dashboard')->name('dashboard');
        Route::post('order-stats', 'DashboardController@order_stats')->name('order-stats');
        Route::get('settings', 'SystemController@settings')->name('settings');
        Route::post('settings', 'SystemController@settings_update');
        Route::post('settings-password', 'SystemController@settings_password_update')->name('settings-password');
        Route::get('/get-restaurant-data', 'SystemController@restaurant_data')->name('get-restaurant-data');

        Route::group(['prefix' => 'custom-role', 'as' => 'custom-role.','middleware'=>['module:employee_section']], function () {
            Route::get('create', 'CustomRoleController@create')->name('create');
            Route::post('create', 'CustomRoleController@store')->name('store');
            Route::get('update/{id}', 'CustomRoleController@edit')->name('update');
            Route::post('update/{id}', 'CustomRoleController@update');
        });

        Route::group(['prefix' => 'employee', 'as' => 'employee.','middleware'=>['module:employee_section']], function () {
            Route::get('add-new', 'EmployeeController@add_new')->name('add-new');
            Route::post('add-new', 'EmployeeController@store');
            Route::get('list', 'EmployeeController@list')->name('list');
            Route::get('update/{id}', 'EmployeeController@edit')->name('update');
            Route::post('update/{id}', 'EmployeeController@update');
            Route::get('status/{id}/{status}', 'EmployeeController@status')->name('status');
        });
//        Route::post('/image-upload', 'FileManagerController@upload')->name('image-upload');

        Route::group(['prefix' => 'pos', 'as' => 'pos.','middleware'=>['module:pos_management']], function () {
            Route::get('/', 'POSController@index')->name('index');
            Route::get('quick-view', 'POSController@quick_view')->name('quick-view');
            Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
            Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
            Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
            Route::post('cart-items', 'POSController@cart_items')->name('cart_items');
            Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
            Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
            Route::post('tax', 'POSController@update_tax')->name('tax');
            Route::post('discount', 'POSController@update_discount')->name('discount');
            Route::get('customers', 'POSController@get_customers')->name('customers');
            Route::post('order', 'POSController@place_order')->name('order');
            Route::get('orders', 'POSController@order_list')->name('orders');
            Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
            Route::get('invoice/{id}', 'POSController@generate_invoice');
            Route::any('store-keys', 'POSController@store_keys')->name('store-keys');
        });

        Route::group(['prefix' => 'banner', 'as' => 'banner.','middleware'=>['module:product_management']], function () {
            Route::get('add-new', 'BannerController@index')->name('add-new');
            Route::post('store', 'BannerController@store')->name('store');
            Route::get('edit/{id}', 'BannerController@edit')->name('edit');
            Route::put('update/{id}', 'BannerController@update')->name('update');
            Route::get('list', 'BannerController@list')->name('list');
            Route::get('status/{id}/{status}', 'BannerController@status')->name('status');
            Route::delete('delete/{id}', 'BannerController@delete')->name('delete');
        });

        Route::group(['prefix' => 'attribute', 'as' => 'attribute.','middleware'=>['module:product_management']], function () {
            Route::get('add-new', 'AttributeController@index')->name('add-new');
            Route::post('store', 'AttributeController@store')->name('store');
            Route::get('edit/{id}', 'AttributeController@edit')->name('edit');
            Route::post('update/{id}', 'AttributeController@update')->name('update');
            Route::delete('delete/{id}', 'AttributeController@delete')->name('delete');
        });

        Route::group(['prefix' => 'branch', 'as' => 'branch.','middleware'=>['module:business_management']], function () {
            Route::get('add-new', 'BranchController@index')->name('add-new');
            Route::post('store', 'BranchController@store')->name('store');
            Route::get('edit/{id}', 'BranchController@edit')->name('edit');
            Route::post('update/{id}', 'BranchController@update')->name('update');
            Route::delete('delete/{id}', 'BranchController@delete')->name('delete');
        });

        Route::group(['prefix' => 'addon', 'as' => 'addon.','middleware'=>['module:product_management']], function () {
            Route::get('add-new', 'AddonController@index')->name('add-new');
            Route::post('store', 'AddonController@store')->name('store');
            Route::get('edit/{id}', 'AddonController@edit')->name('edit');
            Route::post('update/{id}', 'AddonController@update')->name('update');
            Route::delete('delete/{id}', 'AddonController@delete')->name('delete');
        });

        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.','middleware'=>['module:deliveryman_management']], function () {
            Route::get('add', 'DeliveryManController@index')->name('add');
            Route::post('store', 'DeliveryManController@store')->name('store');
            Route::get('list', 'DeliveryManController@list')->name('list');
            Route::get('preview/{id}', 'DeliveryManController@preview')->name('preview');
            Route::get('edit/{id}', 'DeliveryManController@edit')->name('edit');
            Route::post('update/{id}', 'DeliveryManController@update')->name('update');
            Route::delete('delete/{id}', 'DeliveryManController@delete')->name('delete');
            Route::post('search', 'DeliveryManController@search')->name('search');

            Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
                Route::get('list', 'DeliveryManController@reviews_list')->name('list');
            });
        });

        Route::group(['prefix' => 'notification', 'as' => 'notification.','middleware'=>['module:business_management']], function () {
            Route::get('add-new', 'NotificationController@index')->name('add-new');
            Route::post('store', 'NotificationController@store')->name('store');
            Route::get('edit/{id}', 'NotificationController@edit')->name('edit');
            Route::post('update/{id}', 'NotificationController@update')->name('update');
            Route::get('status/{id}/{status}', 'NotificationController@status')->name('status');
            Route::delete('delete/{id}', 'NotificationController@delete')->name('delete');
        });

        Route::group(['prefix' => 'product', 'as' => 'product.','middleware'=>['module:product_management']], function () {
            Route::get('add-new', 'ProductController@index')->name('add-new');
            Route::post('variant-combination', 'ProductController@variant_combination')->name('variant-combination');
            Route::post('store', 'ProductController@store')->name('store');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('update/{id}', 'ProductController@update')->name('update');
            Route::get('list', 'ProductController@list')->name('list');
            Route::delete('delete/{id}', 'ProductController@delete')->name('delete');
            Route::get('status/{id}/{status}', 'ProductController@status')->name('status');
            Route::post('search', 'ProductController@search')->name('search');
            Route::get('bulk-import', 'ProductController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'ProductController@bulk_import_data');
            Route::get('bulk-export', 'ProductController@bulk_export_data')->name('bulk-export');

            Route::get('view/{id}', 'ProductController@view')->name('view');
            //ajax request
            Route::get('get-categories', 'ProductController@get_categories')->name('get-categories');
        });

        Route::group(['prefix' => 'orders', 'as' => 'orders.','middleware'=>['module:order_management']], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::post('increase-preparation-time/{id}', 'OrderController@preparation_time')->name('increase-preparation-time');
            Route::get('status', 'OrderController@status')->name('status');
            Route::get('add-delivery-man/{order_id}/{delivery_man_id}', 'OrderController@add_delivery_man')->name('add-delivery-man');
            Route::get('payment-status', 'OrderController@payment_status')->name('payment-status');
            Route::post('productStatus', 'OrderController@productStatus')->name('productStatus');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice')->withoutMiddleware(['module:order_management']);
            Route::post('add-payment-ref-code/{id}', 'OrderController@add_payment_ref_code')->name('add-payment-ref-code');
            Route::get('branch-filter/{branch_id}', 'OrderController@branch_filter')->name('branch-filter');
            Route::post('search', 'OrderController@search')->name('search');
            Route::post('update-shipping/{id}', 'OrderController@update_shipping')->name('update-shipping');
            Route::delete('delete/{id}', 'OrderController@delete')->name('delete');
            Route::get('export', 'OrderController@export_data')->name('export');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.','middleware'=>['module:product_management']], function () {
            Route::get('add', 'CategoryController@index')->name('add');
            Route::get('add-sub-category', 'CategoryController@sub_index')->name('add-sub-category');
            Route::get('add-sub-sub-category', 'CategoryController@sub_sub_index')->name('add-sub-sub-category');
            Route::post('store', 'CategoryController@store')->name('store');
            Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
            Route::post('update/{id}', 'CategoryController@update')->name('update');
            Route::post('store', 'CategoryController@store')->name('store');
            Route::get('status/{id}/{status}', 'CategoryController@status')->name('status');
            Route::delete('delete/{id}', 'CategoryController@delete')->name('delete');
            Route::post('search', 'CategoryController@search')->name('search');
        });

        Route::group(['prefix' => 'message', 'as' => 'message.','middleware'=>['module:business_management']], function () {
            Route::get('list', 'ConversationController@list')->name('list');
            Route::post('update-fcm-token', 'ConversationController@update_fcm_token')->name('update_fcm_token');
            Route::get('get-firebase-config', 'ConversationController@get_firebase_config')->name('get_firebase_config');
            Route::get('get-conversations', 'ConversationController@get_conversations')->name('get_conversations');
            Route::post('store/{user_id}', 'ConversationController@store')->name('store');
            Route::get('view/{user_id}', 'ConversationController@view')->name('view');
        });

        Route::group(['prefix' => 'reviews', 'as' => 'reviews.','middleware'=>['module:deliveryman_management']], function () {
            Route::get('list', 'ReviewsController@list')->name('list');
            Route::post('search', 'ReviewsController@search')->name('search');
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.','middleware'=>['module:business_management']], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new');
            Route::post('store', 'CouponController@store')->name('store');
            Route::get('update/{id}', 'CouponController@edit')->name('update');
            Route::post('update/{id}', 'CouponController@update');
            Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
            Route::delete('delete/{id}', 'CouponController@delete')->name('delete');
        });

        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.','middleware'=>['module:business_management']], function () {
            //restaurant-settings
            Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.'], function () {
                Route::get('restaurant-setup', 'BusinessSettingsController@restaurant_index')->name('restaurant-setup')->middleware('actch');
                Route::post('update-setup', 'BusinessSettingsController@restaurant_setup')->name('update-setup')->middleware('actch');

                //app settings
                Route::get('time-schedule', 'TimeScheduleController@time_schedule_index')->name('time_schedule_index');
                Route::post('add-time-schedule', 'TimeScheduleController@add_schedule')->name('time_schedule_add');
                Route::get('time-schedule-remove', 'TimeScheduleController@remove_schedule')->name('time_schedule_remove');

                //location
                Route::get('location-setup', 'LocationSettingsController@location_index')->name('location-setup')->middleware('actch');
                Route::post('update-location', 'LocationSettingsController@location_setup')->name('update-location')->middleware('actch');;
            });

            //web-app
            Route::group(['prefix' => 'web-app', 'as' => 'web-app.','middleware'=>['module:business_management']], function () {
                Route::get('mail-config', 'BusinessSettingsController@mail_index')->name('mail-config')->middleware('actch');
                Route::post('mail-config', 'BusinessSettingsController@mail_config')->middleware('actch');
                Route::post('mail-send', 'BusinessSettingsController@mail_send')->name('mail-send');

                Route::get('sms-module', 'SMSModuleController@sms_index')->name('sms-module');
                Route::post('sms-module-update/{sms_module}', 'SMSModuleController@sms_update')->name('sms-module-update');

                Route::get('payment-method', 'BusinessSettingsController@payment_index')->name('payment-method')->middleware('actch');
                Route::post('payment-method-update/{payment_method}', 'BusinessSettingsController@payment_update')->name('payment-method-update')->middleware('actch');

                //system-setup
                Route::group(['prefix' => 'system-setup', 'as' => 'system-setup.'], function () {
                    //app settings
                    Route::get('app-setting', 'BusinessSettingsController@app_setting_index')->name('app_setting');
                    Route::post('app-setting', 'BusinessSettingsController@app_setting_update');

                    //clean db
                    Route::get('db-index', 'DatabaseSettingsController@db_index')->name('db-index');
                    Route::post('db-clean', 'DatabaseSettingsController@clean_db')->name('clean-db');

                    //firebase message
                    Route::get('firebase-message-config', 'BusinessSettingsController@firebase_message_config_index')->name('firebase_message_config_index');
                    Route::post('firebase-message-config', 'BusinessSettingsController@firebase_message_config')->name('firebase_message_config');

                    //language
                    Route::group(['prefix' => 'language', 'as' => 'language.', 'middleware' => []], function () {
                        Route::get('', 'LanguageController@index')->name('index');
                        Route::post('add-new', 'LanguageController@store')->name('add-new');
                        Route::get('update-status', 'LanguageController@update_status')->name('update-status');
                        Route::get('update-default-status', 'LanguageController@update_default_status')->name('update-default-status');
                        Route::post('update', 'LanguageController@update')->name('update');
                        Route::get('translate/{lang}', 'LanguageController@translate')->name('translate');
                        Route::post('translate-submit/{lang}', 'LanguageController@translate_submit')->name('translate-submit');
                        Route::post('remove-key/{lang}', 'LanguageController@translate_key_remove')->name('remove-key');
                        Route::get('delete/{lang}', 'LanguageController@delete')->name('delete');
                    });
                });

                //third-party
                Route::group(['prefix' => 'third-party', 'as' => 'third-party.','middleware'=>['module:business_management']], function () {
                    //map api
                    Route::get('map-api-settings', 'BusinessSettingsController@map_api_settings')->name('map_api_settings');
                    Route::post('map-api-settings', 'BusinessSettingsController@update_map_api');
                    //Social Icon
                    Route::get('social-media', 'BusinessSettingsController@social_media')->name('social-media');
                    Route::get('fetch', 'BusinessSettingsController@fetch')->name('fetch');
                    Route::post('social-media-store', 'BusinessSettingsController@social_media_store')->name('social-media-store');
                    Route::post('social-media-edit', 'BusinessSettingsController@social_media_edit')->name('social-media-edit');
                    Route::post('social-media-update', 'BusinessSettingsController@social_media_update')->name('social-media-update');
                    Route::post('social-media-delete', 'BusinessSettingsController@social_media_delete')->name('social-media-delete');
                    Route::post('social-media-status-update', 'BusinessSettingsController@social_media_status_update')->name('social-media-status-update');
                    //recaptcha
                    Route::get('recaptcha', 'BusinessSettingsController@recaptcha_index')->name('recaptcha_index');
                    Route::post('recaptcha-update', 'BusinessSettingsController@recaptcha_update')->name('recaptcha_update');

                    //fcm-index
                    Route::get('fcm-index', 'BusinessSettingsController@fcm_index')->name('fcm-index')->middleware('actch');
                    Route::post('update-fcm', 'BusinessSettingsController@update_fcm')->name('update-fcm')->middleware('actch');

                });

            });




            Route::post('update-fcm-messages', 'BusinessSettingsController@update_fcm_messages')->name('update-fcm-messages');





            /*Route::get('currency-add', 'BusinessSettingsController@currency_index')->name('currency-add');
            Route::post('currency-add', 'BusinessSettingsController@currency_store');
            Route::get('currency-update/{id}', 'BusinessSettingsController@currency_edit')->name('currency-update');
            Route::put('currency-update/{id}', 'BusinessSettingsController@currency_update');
            Route::delete('currency-delete/{id}', 'BusinessSettingsController@currency_delete')->name('currency-delete');*/



//            Route::group(['prefix' => '3rdparty-setup', 'as' => 'page-setup.'], function () {
//
//            });

            Route::group(['prefix' => 'page-setup', 'as' => 'page-setup.','middleware'=>['module:business_management']], function () {
                Route::get('terms-and-conditions', 'BusinessSettingsController@terms_and_conditions')->name('terms-and-conditions')->middleware('actch');
                Route::post('terms-and-conditions', 'BusinessSettingsController@terms_and_conditions_update')->middleware('actch');

                Route::get('privacy-policy', 'BusinessSettingsController@privacy_policy')->name('privacy-policy')->middleware('actch');
                Route::post('privacy-policy', 'BusinessSettingsController@privacy_policy_update')->middleware('actch');

                Route::get('about-us', 'BusinessSettingsController@about_us')->name('about-us')->middleware('actch');
                Route::post('about-us', 'BusinessSettingsController@about_us_update')->middleware('actch');

                //pages
                Route::get('return-page', 'BusinessSettingsController@return_page_index')->name('return_page_index');
                Route::post('return-page-update', 'BusinessSettingsController@return_page_update')->name('return_page_update');

                Route::get('refund-page', 'BusinessSettingsController@refund_page_index')->name('refund_page_index');
                Route::post('refund-page-update', 'BusinessSettingsController@refund_page_update')->name('refund_page_update');

                Route::get('cancellation-page', 'BusinessSettingsController@cancellation_page_index')->name('cancellation_page_index');
                Route::post('cancellation-page-update', 'BusinessSettingsController@cancellation_page_update')->name('cancellation_page_update');
            });
            Route::get('currency-position/{position}', 'BusinessSettingsController@currency_symbol_position')->name('currency-position');
            Route::get('maintenance-mode', 'BusinessSettingsController@maintenance_mode')->name('maintenance-mode');





        });

        Route::group(['prefix' => 'report', 'as' => 'report.','middleware'=>['module:report_management']], function () {
            Route::get('order', 'ReportController@order_index')->name('order');
            Route::get('earning', 'ReportController@earning_index')->name('earning');
            Route::post('set-date', 'ReportController@set_date')->name('set-date');
            Route::get('deliveryman-report', 'ReportController@deliveryman_report')->name('deliveryman_report');
            Route::post('deliveryman-filter', 'ReportController@deliveryman_filter')->name('deliveryman_filter');
            Route::get('product-report', 'ReportController@product_report')->name('product-report');
            Route::post('product-report-filter', 'ReportController@product_report_filter')->name('product-report-filter');
            Route::get('export-product-report', 'ReportController@export_product_report')->name('export-product-report');
            Route::get('sale-report', 'ReportController@sale_report')->name('sale-report');
            Route::post('sale-report-filter', 'ReportController@sale_filter')->name('sale-report-filter');
            Route::get('export-sale-report', 'ReportController@export_sale_report')->name('export-sale-report');
        });

        Route::group(['prefix' => 'customer', 'as' => 'customer.','middleware'=>['actch', 'module:customer_management']], function () {
            Route::post('add-point/{id}', 'CustomerController@add_point')->name('add-point');
            Route::get('set-point-modal-data/{id}', 'CustomerController@set_point_modal_data')->name('set-point-modal-data');
            Route::get('list', 'CustomerController@customer_list')->name('list');
            Route::get('view/{user_id}', 'CustomerController@view')->name('view');
            Route::post('search', 'CustomerController@search')->name('search');
            Route::post('AddPoint/{id}', 'CustomerController@AddPoint')->name('AddPoint');
            Route::get('transaction', 'CustomerController@transaction')->name('transaction');
            Route::get('transaction/{id}', 'CustomerController@customer_transaction')->name('customer_transaction');
            Route::get('subscribed-emails', 'CustomerController@subscribed_emails')->name('subscribed_emails');

            Route::get('chat', 'CustomerController@chat')->name('chat');
            Route::post('get-user-info', 'CustomerController@get_user_info')->name('get_user_info');
            Route::post('message-notification', 'CustomerController@message_notification')->name('message_notification');
            Route::post('chat-image-upload', 'CustomerController@chat_image_upload')->name('chat_image_upload');
        });
    });
});

