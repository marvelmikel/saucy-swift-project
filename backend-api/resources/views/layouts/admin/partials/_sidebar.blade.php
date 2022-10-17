<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container text-capitalize">
            <div class="navbar-vertical-footer-offset">
                <div class="navbar-brand-wrapper justify-content-between">
                    <!-- Logo -->

                    @php($restaurant_logo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value)
                    <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="Front">
                        <img class="navbar-brand-logo"
                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                             src="{{asset('storage/app/public/restaurant/'.$restaurant_logo)}}"
                             alt="Logo">
                        <img class="navbar-brand-logo-mini"
                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                             src="{{asset('storage/app/public/restaurant/'.$restaurant_logo)}}" alt="Logo">
                    </a>

                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">

                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.dashboard')}}" title="{{translate('Dashboards')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('dashboard')}}
                                    </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['pos_management']))
                            <li class="nav-item">
                                <small
                                    class="nav-subtitle">{{translate('pos')}} {{translate('system')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <!-- POS -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/pos/*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                    <i class="tio-shopping nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('POS')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/pos/*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/pos/')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.pos.index')}}"
                                           title="{{translate('pos')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('pos')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/pos/orders')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.pos.orders')}}" title="{{translate('orders')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('orders')}}
                                            <span class="badge badge-info badge-pill ml-1">
                                                {{\App\Model\Order::Pos()->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- End POS -->
                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['order_management']))
                            <li class="nav-item">
                                <small
                                    class="nav-subtitle">{{translate('order')}} {{translate('section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/orders*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                    <i class="tio-shopping-cart nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('order')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/order*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/orders/list/all')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.orders.list',['all'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('all')}}
                                                <span class="badge badge-info badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/pending')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['pending'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('pending')}}
                                                <span class="badge badge-soft-info badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'pending'])->notSchedule()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/confirmed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['confirmed'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('confirmed')}}
                                                    <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'confirmed'])->notSchedule()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/processing')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['processing'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('processing')}}
                                                    <span class="badge badge-warning badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'processing'])->notSchedule()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/out_for_delivery')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['out_for_delivery'])}}"
                                           title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('out_for_delivery')}}
                                                    <span class="badge badge-warning badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'out_for_delivery'])->notSchedule()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/delivered')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['delivered'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('delivered')}}
                                                    <span class="badge badge-success badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'delivered'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/returned')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['returned'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('returned')}}
                                                    <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'returned'])->notSchedule()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/failed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['failed'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('failed')}}
                                                <span class="badge badge-danger badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'failed'])->notSchedule()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/orders/list/canceled')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['canceled'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('canceled')}}
                                                    <span class="badge badge-soft-dark badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where(['order_status'=>'canceled'])->notSchedule()->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/orders/list/schedule')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['schedule'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{translate('scheduled')}}
                                                    <span class="badge badge-soft-info badge-pill ml-1">
                                                    {{\App\Model\Order::notPos()->where('delivery_date','>',\Carbon\Carbon::now()->format('Y-m-d'))->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Pages -->
                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['product_management']))
                            <li class="nav-item">
                                <small
                                    class="nav-subtitle">{{translate('product')}} {{translate('section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>


                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/category*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                >
                                    <i class="tio-category nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('category')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/category*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/category/add')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.category.add')}}"
                                           title="{{translate('add new category')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('category')}}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/category/add-sub-category')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.category.add-sub-category')}}"
                                           title="{{translate('add new sub category')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('sub_category')}}</span>
                                        </a>
                                    </li>

                                    {{--<li class="nav-item {{Request::is('admin/category/add-sub-sub-category')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.category.add-sub-sub-category')}}"
                                           title="add new sub sub category">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Sub-Sub-Category</span>
                                        </a>
                                    </li>--}}
                                </ul>
                            </li>
                            <!-- End Pages -->


                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/attribute*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.attribute.add-new')}}"
                                >
                                    <i class="tio-apps nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('attribute')}}
                                    </span>
                                </a>
                            </li>
                            <!-- End Pages -->

                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/addon*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.addon.add-new')}}"
                                >
                                    <i class="tio-add-circle-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('addon')}}
                                    </span>
                                </a>
                            </li>
                            <!-- End Pages -->

                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/product*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                >
                                    <i class="tio-premium-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('product')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/product*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/product/add-new')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.add-new')}}"
                                           title="{{translate('add new product')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('add')}} {{translate('new')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/product/list')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.list')}}" title="{{translate('product list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('list')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/product/bulk-import')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.bulk-import')}}" title="{{translate('bulk import')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('bulk_import')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/product/bulk-export')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.bulk-export')}}" title="{{translate('bulk export')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('bulk_export')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Pages -->

                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/banner*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                >
                                    <i class="tio-image nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('banner')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/banner*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/banner/add-new')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.banner.add-new')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('add')}} {{translate('new')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/banner/list')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.banner.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('list')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Pages -->
                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['business_management']))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                       title="Layouts">{{translate('business')}} {{translate('section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <!-- BRANCH -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/branch*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.branch.add-new')}}">
                                    <i class="tio-shop nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('branch')}}
                                    </span>
                                </a>
                            </li>

                            <!-- MESSAGE -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/message*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.message.list')}}">
                                    <i class="tio-messages nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('messages')}}
                                    </span>
                                </a>
                            </li>

                            <!-- REVIEWS -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/reviews*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.reviews.list')}}">
                                    <i class="tio-star nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('product')}} {{translate('reviews')}}
                                    </span>
                                </a>
                            </li>


                            <!-- NOTIFICATION -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/notification*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.notification.add-new')}}">
                                    <i class="tio-notifications nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('send')}} {{translate('notification')}}
                                    </span>
                                </a>
                            </li>

                            <!-- COUPON -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/coupon*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.coupon.add-new')}}">
                                    <i class="tio-gift nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('coupon')}}</span>
                                </a>
                            </li>

                            <!-- Restaurant Settings -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/restaurant/')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                    <i class="tio-settings nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Restaurant Settings')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/business-settings/restaurant/*')?'block':'none'}}">
                                    <!-- restaurant-setup -->
                                    <li class="nav-item {{Request::is('admin/business-settings/restaurant/restaurant-setup')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.restaurant.restaurant-setup')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('restaurant')}} {{translate('setup')}}</span>
                                        </a>
                                    </li>
                                    <!-- time-schedule -->
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/restaurant/time-schedule')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.restaurant.time_schedule_index')}}"
                                           title="{{translate('Restaurant Time Schedule')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('Restaurant Time Schedule')}}</span>
                                        </a>
                                    </li>
                                    <!-- location-setup -->
                                    <li class="nav-item {{Request::is('admin/business-settings/restaurant/location-setup')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.restaurant.location-setup')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('location')}} {{translate('setup')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- WEB & APPS SETTINGS -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                    <i class="tio-website nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Web & Apps Settings')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/business-settings/web-app*')?'block':'none'}}">
                                    <!-- MAIL CONFIG -->
                                    <li class="nav-item {{Request::is('admin/business-settings/web-app/mail-config')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.web-app.mail-config')}}">
                                            <span class="tio-gmail-outlined nav-icon"></span>
                                            <span class="text-truncate">{{translate('mail')}} {{translate('config')}}</span>
                                        </a>
                                    </li>
                                    <!-- SMS-MODULE -->
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/sms-module')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.web-app.sms-module')}}"
                                           title="{{translate('sms')}} {{translate('module')}}">
                                            <span class="tio-sms nav-icon"></span>
                                            <span class="text-truncate">{{translate('sms')}} {{translate('module')}}</span>
                                        </a>
                                    </li>
                                    <!-- PAYMENT-MODULE -->
                                    <li class="nav-item {{Request::is('admin/business-settings/web-app/payment-method')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.web-app.payment-method')}}">
                                            <span class="tio-money nav-icon"></span>
                                            <span class="text-truncate">{{translate('payment')}} {{translate('methods')}}</span>
                                        </a>
                                    </li>
                                    <!-- SYSTEM SETTINGS -->
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/system-setup*')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                            <i class="tio-security-on-outlined nav-icon"></i>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('System Settings')}}</span>
                                        </a>
                                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/business-settings/web-app/system-setup*')?'block':'none'}}">
                                            <!-- app-setting -->
                                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/system-setup/app-setting')?'active':''}}">
                                                <a class="nav-link " href="{{route('admin.business-settings.web-app.system-setup.app_setting')}}"
                                                   title="{{translate('App Setting')}}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{translate('App Setting')}}</span>
                                                </a>
                                            </li>
                                            <!-- clean-db -->
                                            <li class="nav-item {{Request::is('admin/business-settings/web-app/system-setup/db*')?'active':''}}">
                                                <a class="nav-link " href="{{route('admin.business-settings.web-app.system-setup.db-index')}}"
                                                >
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{translate('clean')}} {{translate('database')}}</span>
                                                </a>
                                            </li>
                                            <!-- firebase-message-config -->
                                            <li class="nav-item {{Request::is('admin/business-settings/web-app/system-setup/firebase-message-config')?'active':''}}">
                                                <a class="nav-link " href="{{route('admin.business-settings.web-app.system-setup.firebase_message_config_index')}}"
                                                >
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{translate('Firebase Message Config')}}</span>
                                                </a>
                                            </li>
                                            <!-- language -->
                                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/system-setup/language*')?'active':''}}">
                                                <a class="nav-link " href="{{route('admin.business-settings.web-app.system-setup.language.index')}}"
                                                   title="{{translate('languages')}}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{translate('languages')}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <!-- 3RD PARTY SETTINGS -->
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/third-party*')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                            <i class="tio-settings-vs nav-icon"></i>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('3rd Party Settings')}}</span>
                                        </a>
                                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/business-settings/web-app/third-party*')?'block':'none'}}">
                                            <!-- map-api-settings -->
                                            <li class="nav-item {{Request::is('admin/business-settings/web-app/third-party/map-api-settings')?'active':''}}">
                                                <a class="nav-link "
                                                   href="{{route('admin.business-settings.web-app.third-party.map_api_settings')}}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{translate('map_api_setting')}}</span>
                                                </a>
                                            </li>
                                            <!-- social-media -->
                                            <li class="nav-item {{Request::is('admin/business-settings/web-app/third-party/social-media')?'active':''}}">
                                                <a class="nav-link "
                                                   href="{{route('admin.business-settings.web-app.third-party.social-media')}}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{translate('Social Media')}}</span>
                                                </a>
                                            </li>
                                            <!-- recaptcha -->
                                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/third-party/recaptcha*')?'active':''}}">
                                                <a class="nav-link " href="{{route('admin.business-settings.web-app.third-party.recaptcha_index')}}"
                                                   title="{{translate('reCaptcha')}}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span class="text-truncate">{{translate('reCaptcha')}}</span>
                                                </a>
                                            </li>
                                            <!-- fcm-index -->
                                            <li class="nav-item {{Request::is('admin/business-settings/web-app/third-party/fcm-index')?'active':''}}">
                                                <a class="nav-link " href="{{route('admin.business-settings.web-app.third-party.fcm-index')}}"
                                                   title="{{translate('push notification')}}">
                                                    <span class="tio-circle nav-indicator-icon"></span>
                                                    <span
                                                        class="text-truncate">{{translate('push')}} {{translate('notification')}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </li>

                            <!-- PAGE SETUP -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/page-setup/*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                    <i class="tio-pages nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Page Setup')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/business-settings/page-setup*')?'block':'none'}}">
                                    <!-- about-us -->
                                    <li class="nav-item {{Request::is('admin/business-settings/page-setup/about-us')?'active':''}}">
                                        <a class="nav-link "
                                           href="{{route('admin.business-settings.page-setup.about-us')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('about_us')}}</span>
                                        </a>
                                    </li>
                                    <!-- terms-and-conditions -->
                                    <li class="nav-item {{Request::is('admin/business-settings/page-setup/terms-and-conditions')?'active':''}}">
                                        <a class="nav-link "
                                           href="{{route('admin.business-settings.page-setup.terms-and-conditions')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('terms_and_condition')}}</span>
                                        </a>
                                    </li>
                                    <!-- privacy-policy -->
                                    <li class="nav-item {{Request::is('admin/business-settings/page-setup/privacy-policy')?'active':''}}">
                                        <a class="nav-link "
                                           href="{{route('admin.business-settings.page-setup.privacy-policy')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('privacy_policy')}}</span>
                                        </a>
                                    </li>
                                    <!-- return page -->
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/page-setup/return-page*')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.page-setup.return_page_index')}}"
                                           title="{{\App\CentralLogics\translate('Return policy')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CentralLogics\translate('Return policy')}}</span>
                                        </a>
                                    </li>
                                     <!-- refund page -->
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/page-setup/refund-page*')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.page-setup.refund_page_index')}}"
                                           title="{{\App\CentralLogics\translate('Refund policy')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CentralLogics\translate('Refund policy')}}</span>
                                        </a>
                                    </li>
                                     <!-- cancellation page -->
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/page-setup/cancellation-page*')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.business-settings.page-setup.cancellation_page_index')}}"
                                           title="{{\App\CentralLogics\translate('Cancellation policy')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CentralLogics\translate('Cancellation policy')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['employee_management']))
                            <li class="nav-item {{(Request::is('admin/employee*') || Request::is('admin/custom-role*'))?'scroll-here':''}}">
                                <small class="nav-subtitle">{{translate('employee_section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/custom-role*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.custom-role.create')}}">
                                    <i class="tio-incognito nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('employee_role')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/employee*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-user nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('employees')}}
                                        </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/employee*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/employee/add-new')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.employee.add-new')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('add_new')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/employee/list')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.employee.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('List')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['deliveryman_management']))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                       title="Layouts">{{translate('deliveryman')}} {{translate('section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man/add')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.delivery-man.add')}}"
                                >
                                    <i class="tio-running nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('register')}}
                                    </span>
                                </a>
                            </li>
                            <!-- End Pages -->

                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man/list')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.delivery-man.list')}}">
                                    <i class="tio-filter-list nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('list')}}
                                    </span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man/reviews/list')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.delivery-man.reviews.list')}}">
                                    <i class="tio-star-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('reviews')}}
                                    </span>
                                </a>
                            </li>
                            <!-- End Pages -->
                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['customer_management']))
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                       title="Documentation">{{translate('customer')}} {{translate('section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/list*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.customer.list')}}">
                                    <i class="tio-poi-user nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('customer')}} {{translate('list')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/transaction*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.customer.transaction')}}"
                                >
                                    <i class="tio-format-points nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('point')}} {{translate('history')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/subscribed-email*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.customer.subscribed_emails')}}">
                                    <i class="tio-email-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('Subscribed Emails')}}
                                    </span>
                                </a>
                            </li>
                            <!-- End Pages -->
                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['report_management']))
                            <li class="nav-item">
                                <div class="nav-divider"></div>
                            </li>

                            <li class="nav-item">
                                <small class="nav-subtitle"
                                       title="{{translate('report and analytics')}}">{{translate('report_and_analytics')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                >
                                    <i class="tio-report-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('reports')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/report*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/report/earning')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.report.earning')}}"
                                        >
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('earning')}} {{translate('report')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/report/order')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.report.order')}}"
                                        >
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('order')}} {{translate('report')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/report/deliveryman-report')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.report.deliveryman_report')}}"
                                        >
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('DeliveryMan Report')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/report/product-report')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.report.product-report')}}"
                                        >
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{translate('product')}} {{translate('report')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/report/sale-report')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.report.sale-report')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{translate('sale')}} {{translate('report')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Pages -->
                        @endif

                        <li class="nav-item" style="padding-top: 100px">
                            <div class=""></div>
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


{{--<script>
    $(document).ready(function () {
        $('.navbar-vertical-content').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 'slow');
    });
</script>--}}
