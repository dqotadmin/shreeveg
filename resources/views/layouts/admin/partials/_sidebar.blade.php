
 <div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container text-capitalize">
            <div class="navbar-vertical-footer-offset">
                <div class="navbar-brand-wrapper justify-content-between">
                    <!-- Logo -->

                    @php($restaurant_logo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value)
                    <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="Front">
                        <img class="w-100 side-logo"
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
                    <div class="navbar-nav-wrap-content-left d-none d-xl-block">
                        <!-- Navbar Vertical Toggle -->
                        <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                            <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                                data-placement="right" title="Collapse"></i>
                            <i class="tio-last-page navbar-vertical-aside-toggle-full-align"></i>
                        </button>
                        <!-- End Navbar Vertical Toggle -->
                    </div>
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content" id="navbar-vertical-content">
                    <form class="sidebar--search-form">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="form-control form--control"
                                placeholder="{{ translate('Search Menu...') }}" id="search-sidebar-menu">
                        </div>
                    </form>
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['dashboard_management']))
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin')?'show active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.dashboard')}}"
                                title="{{translate('dashboard')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->
                        @endif


                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['pos_management']))
                        <!-- POS Section -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/pos*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{translate('POS')}}">
                                <i class="tio-shopping nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('POS')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/pos*')?'block':'none'}}">
                                @if(in_array(auth('admin')->user()->admin_role_id,[6,7]))
                                <li class="nav-item {{Request::is('admin/pos')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.pos.index')}}"
                                        title="{{translate('New Sale')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('New Sale')}}</span>
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item {{Request::is('admin/pos/orders')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.pos.orders')}}"
                                        title="{{translate('orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            <span>{{translate('orders')}}</span>
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                @if(auth('admin')->user()->admin_role_id == 1)
                                                {{\App\Model\Order::Pos()->count()}}
                                                @endif
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                @if(auth('admin')->user()->admin_role_id != 7)
                                <li class="nav-item {{Request::is('admin/pos/stocks')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.pos.stocks')}}"
                                        title="{{translate('stocks')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            <span>{{translate('available_stocks')}}</span>
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </li>
                        <!-- End POS -->
                        @endif
                        @if(in_array(auth('admin')->user()->admin_role_id,[3]))
                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/purchase-warehouse-order/stock-update') || Request::is('admin/purchase-warehouse-order/stock-update*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.price-update')}}"
                                title="{{translate('customer')}} {{translate('list')}}">
                                <i class="tio-poi-user tio-money"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('assign_price')}} {{translate('Management')}}
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(in_array(auth('admin')->user()->admin_role_id,[3]))
                         <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/rate_list/index') || Request::is('admin/rate_list/index*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.rate_list.index')}}"
                                title="{{translate('customer')}} {{translate('list')}}">
                                <i class="tio-money"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('assign_rate_for_customer')}}
                                </span>
                            </a>
                        </li> 
                        @endif

                        <!-- User management start from here -->
                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['user_management']))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="Documentation">{{translate('customer management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/list') || Request::is('admin/customer/view*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.customer.list')}}"
                                title="{{translate('customer')}} {{translate('list')}}">
                                <i class="tio-poi-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('customer')}} {{translate('list')}}
                                </span>
                            </a>
                        </li>

                        <!-- <li
                                class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/settings*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.customer.settings')}}"
                                    title="{{translate('customer')}} {{translate('settings')}}">
                                    <i class="tio-settings-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('customer')}} {{translate('settings')}}
                                    </span>
                                </a>
                            </li> -->

                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/wallet/*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{translate('Customer Wallet')}}">
                                <i class="tio-wallet-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('Customer Wallet')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/customer/wallet*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('admin/customer/wallet/add-fund')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.customer.wallet.add-fund')}}"
                                        title="{{translate('add_fund')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('add_fund')}}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/customer/wallet/report')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.customer.wallet.report')}}"
                                        title="{{translate('report')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('report')}}
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                        <!-- User management end here -->

                        @if(auth('admin')->user()->admin_role_id == 5)

                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.broker-rate-list.wh_receiver_rate_list')}}"
                                title="{{translate('rate list')}}">
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('rate list')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.purchase-warehouse-order.index')}}"
                                title="{{translate('order_list')}}">
                                <i class="tio-map nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('order list')}}</span>
                            </a>
                        </li>
                        @endif

                        @if(auth('admin')->user()->admin_role_id == 1 || auth('admin')->user()->admin_role_id == 3)


                        <!-- Admin management start from here -->
                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('admin_management')}} </small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.user-management',['role_id'=>'3'])}}"
                                title="{{translate('Warehouse Admin')}}">
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Warehouse Admin')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.user-management',['role_id'=>'8'])}}"
                                title="{{translate('warehouse_worker Admin')}}">
                                <i class="tio-map nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Broker')}}</span>
                            </a>
                        </li>
                        @endif

                        @if( auth('admin')->user()->admin_role_id == 1 || auth('admin')->user()->admin_role_id == 3 )

                            <li class="navbar-vertical-aside-has-menu ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.user-management',['role_id'=>'5'])}}"
                                    title="{{translate('Warehouse Receiver Admin')}}">
                                    <i class="tio-map nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Warehouse Receiver')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.user-management',['role_id'=>'4'])}}"
                                    title="{{translate('warehouse_worker Admin')}}">
                                    <i class="tio-map nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Warehouse Worker')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man/*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                    title="{{translate('deliveryman')}}">
                                    <i class="tio-user nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('deliveryman')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/delivery-man*')?'block':'none'}}">

                                    <li class="nav-item {{Request::is('admin/delivery-man/list')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.delivery-man.list')}}"
                                            title="{{translate('list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{translate('Delivery Man List')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/delivery-man/add')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.delivery-man.add')}}"
                                            title="{{translate('register')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{translate('Add New Delivery Man')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li
                                        class="nav-item {{Request::is('admin/delivery-man/pending/list') || Request::is('admin/delivery-man/denied/list')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.delivery-man.pending')}}"
                                            title="{{translate('joining request')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{translate('New Joining Request')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/delivery-man/reviews/list')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.delivery-man.reviews.list')}}"
                                            title="{{translate('reviews')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                                {{translate('Delivery Man Reviews')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="navbar-vertical-aside-has-menu ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.user-management',['role_id'=>'6'])}}"
                                    title="{{translate('Store Admin')}}">
                                    <i class="tio-city nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Store Admin')}}</span>
                                </a>
                            </li>

                        @endif

                        @if( auth('admin')->user()->admin_role_id == 1 || auth('admin')->user()->admin_role_id == 6 )

                            <li class="navbar-vertical-aside-has-menu ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.user-management',['role_id'=>'7'])}}"
                                    title="{{translate('Warehouse Store Sales Person')}}">
                                    <i class="tio-map nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Store Sales Person')}}</span>
                                </a>
                            </li>
                        @endif

                        @if(auth('admin')->user()->admin_role_id == 1)
                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/employee*')?'active':''}}  {{Request::is('admin/custom-role*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{translate('employees')}}">
                                <i class="tio-incognito nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('Admin Role')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('admin/custom-role*')?'d-block':''}}"
                                style="display: {{Request::is('admin/employee*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/custom-role*')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.custom-role.create')}}"
                                        title="{{translate('Employee Role Setup')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{translate('Admin Role')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <!-- Admin management end from here -->



                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['store_management']))

                        <!-- Warehouse, store, location management from here -->
                            @if( auth('admin')->user()->admin_role_id == 1 || auth('admin')->user()->admin_role_id == 3)
                                <li class="nav-item">
                                    <small class="nav-subtitle">{{translate('warehouse_&_store setup')}} </small>
                                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                                </li>
                                <!-- location  -->
                                @if( auth('admin')->user()->admin_role_id == 1 )
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/area*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                        title="{{translate('Location setup')}}">
                                        <i class="tio-map nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Location')}}</span>
                                    </a>
                                    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                        style="display: {{Request::is('admin/area*')?'block':'none'}} ">

                                        <li class="nav-item {{Request::is('admin/city/add')?'active':''}}">
                                            <a class="nav-link " href="{{route('admin.city.list')}}"
                                                title="{{translate('City')}}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span class="text-truncate">{{translate('City')}}</span>
                                            </a>
                                        </li>

                                        <li class="nav-item {{Request::is('admin/area/list')?'active':''}}">
                                            <a class="nav-link " href="{{route('admin.area.list')}}"
                                                title="{{translate('categories')}}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span class="text-truncate">{{translate('Area')}}</span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <!-- location end  -->
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/warehouse.add')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.warehouse.list')}}" title="{{translate('Warehouse')}}">
                                        <i class="tio-map nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Warehouse')}}</span>
                                    </a>
                                </li>
                                @endif
                            @endif

                            @if(in_array(auth('admin')->user()->admin_role_id,[3,6,7]))
                            <li class="navbar-vertical-aside-has-menu ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.store.donations.index')}}"
                                    title="{{translate('donations')}}">
                                    <i class="tio-map nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('donations')}}</span>
                                </a>
                            </li>
                            @endif
                        @endif
                        
                       
                        {{-- <li
                            class="nav-item {{Request::is('admin/product/donations*')?'active':''}} {{Request::is('admin/product/donations')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.product.donations.index')}}" title="{{translate('donations')}}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{translate('donations')}}</span>
                            </a>
                        </li> --}}
                        

                        @if(auth('admin')->user()->admin_role_id == 8 || auth('admin')->user()->admin_role_id == 3 || auth('admin')->user()->admin_role_id == 1)
                            @if(auth('admin')->user()->admin_role_id == 8 )
                            <li class="navbar-vertical-aside-has-menu ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.broker-rate-list.index')}}" title="{{translate('rate list')}}">
                                    <i class="tio-category nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('rate list')}}</span>
                                </a>
                            </li>
                            @endif
                            <li class="navbar-vertical-aside-has-menu ">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.purchase-warehouse-order.index')}}"
                                    title="{{translate('order_list')}}">
                                    <i class="tio-map nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('warehouse_purchase_orders')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(in_array(auth('admin')->user()->admin_role_id,[1,3,6]))
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/store.index')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.store.list')}}"
                                    title="{{translate('Store')}}">
                                    <i class="tio-map nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Store')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/store.index')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{route('admin.store.purchase-store-orders.index')}}"
                                    title="{{translate('Store orders')}}">
                                    <i class="tio-map nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Store Purchase orders')}}</span>
                                </a>
                            </li>
                        <!-- Warehouse, store, location management end here -->
                        @endif



                        <!-- Product management start here -->
                        @if( in_array(auth('admin')->user()->admin_role_id, [1,3,5]) )
                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('product_management')}} </small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <!-- Only Super Admin can view -->
                        @if( auth('admin')->user()->admin_role_id == 1 )
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/category*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.category.list')}}" title="{{translate('categories')}}">
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('categories')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/unit*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.unit.list')}}"
                                title="{{translate('units')}}">
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('units')}}</span>
                            </a>
                        </li>
                        @endif

                        <!-- <li    class="navbar-vertical-aside-has-menu {{Request::is('admin/product*') || Request::is('admin/attribute*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{translate('product setup')}}">
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('product setup')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/product*') || Request::is('admin/attribute*') ? 'block' : 'none'}}"> -->

                        <!-- <li class="nav-item {{Request::is('admin/attribute*')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.attribute.add-new')}}"
                                        title="{{translate('product attribute')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('product attribute')}}</span>
                                    </a>
                                </li> -->

                        <!-- Only Super Admin can view -->
                        @if( auth('admin')->user()->admin_role_id == 1 )
                        <li class="nav-item {{Request::is('admin/product/add-new')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.product.add-new')}}"
                                title="{{translate('list')}}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{translate('add new product')}}</span>
                            </a>
                        </li>
                        @endif

                        <li
                            class="nav-item {{Request::is('admin/product/list*')?'active':''}} {{Request::is('admin/product/add-new')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.product.list')}}" title="{{translate('list')}}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                @if( auth('admin')->user()->admin_role_id == 5 )
                                <span class="text-truncate">{{translate('available_stock')}}</span>
                                @else
                                <span class="text-truncate">{{translate('product list')}}</span>
                                @endif
                            </a>
                        </li>

                        
                        
                        <!-- <li class="nav-item {{Request::is('admin/product/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.bulk-import')}}"
                                        title="{{translate('bulk_import')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('bulk_import')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/product/bulk-export-index')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.product.bulk-export-index')}}"
                                        title="{{translate('bulk_export')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('bulk_export')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/product/limited-stock')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.product.limited-stock')}}"
                                        title="{{translate('Limited Stocks')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('Limited Stocks')}}</span>
                                    </a>
                                </li> -->

                        <!-- </ul>
                        </li> -->
                        @endif


                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['order_management']))
                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('order_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/orders*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{translate('orders')}}">
                                <i class="tio-shopping-cart nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('orders')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/order*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/orders/list/all')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.orders.list',['all'])}}"
                                        title="{{translate('all_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            <span>{{translate('all')}}</span>
                                            <span class="badge badge-info badge-pill ml-1">
                                                {{\App\Model\Order::notPos()->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/pending')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['pending'])}}"
                                        title="{{translate('pending_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            <span>{{translate('pending')}}</span>
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/confirmed')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['confirmed'])}}"
                                        title="{{translate('confirmed_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            <span>{{translate('confirmed')}}</span>
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'confirmed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/processing')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['processing'])}}"
                                        title="{{translate('processing_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate  sidebar--badge-container">
                                            <span>{{translate('packaging')}}</span>
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'processing'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/out_for_delivery')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['out_for_delivery'])}}"
                                        title="{{translate('out_for_delivery_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate  sidebar--badge-container">
                                            <span>{{translate('out_for_delivery')}}</span>
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'out_for_delivery'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/delivered')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['delivered'])}}"
                                        title="{{translate('delivered_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate  sidebar--badge-container">
                                            <span>{{translate('delivered')}}</span>
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{\App\Model\Order::notPos()->where(['order_status'=>'delivered'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/returned')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['returned'])}}"
                                        title="{{translate('returned_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate  sidebar--badge-container">
                                            <span>{{translate('returned')}}</span>
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'returned'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/orders/list/failed')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['failed'])}}"
                                        title="{{translate('failed_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate  sidebar--badge-container">
                                            <span>{{translate('failed')}}</span>
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'failed'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('admin/orders/list/canceled')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.orders.list',['canceled'])}}"
                                        title="{{translate('canceled_orders')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate  sidebar--badge-container">
                                            <span>{{translate('canceled')}}</span>
                                            <span class="badge badge-soft-light badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'canceled'])->count()}}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- End Pages -->
                        @endif
                        @if(auth('admin')->user()->admin_role_id == 3)
                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['category_management']))
                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('category_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('warehouse-category*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{translate('orders')}}">
                                <i class="tio-category nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('Assign Categories')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/warehouse-category*') ? 'block' : 'none'}}">

                                <li class="nav-item {{Request::is('admin/warehouse-category/list')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.warehouse-category.list')}}"
                                        title="{{translate('all_categories')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('all Categories')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/warehouse-category/group-list')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.warehouse-category.group-list')}}"
                                        title="{{translate('all_categories')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('Group List')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @endif
                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['promotion_management']))

                        <li class="nav-item">
                            <small class="nav-subtitle">{{translate('offer_management')}} </small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <!-- <li class="navbar-vertical-aside-has-menu {{Request::is('admin/banner*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.banner.add-new')}}"
                                   title="{{translate('banner')}}"
                                >
                                    <i class="tio-image nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('banner')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/coupon*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.coupon.add-new')}}"
                                   title="{{translate('coupons')}}"
                                >
                                    <i class="tio-gift nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('coupons')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/notification*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.notification.add-new')}}"
                                   title="{{translate('send notifications')}}"
                                >
                                    <i class="tio-notifications nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('send')}} {{translate('notifications')}}
                                    </span>
                                </a>
                            </li> -->

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/offer*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.offer.flash.index')}}" title="{{translate('flash_sale')}}">
                                <i class="tio-alarm-alert nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('offers')}}
                                </span>
                            </a>
                        </li>

                        <!-- <li class="navbar-vertical-aside-has-menu {{Request::is('admin/discount*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('admin.discount.add-new')}}"
                                   title="{{translate('category_discount')}}">
                                    <i class="tio-layers-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('category_discount')}}</span>
                                </a>
                            </li> -->

                        @endif

                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['support_management']))
                        <!-- Help & Support Manegement 
                            <li class="nav-item">
                                <small class="nav-subtitle"
                                       title="Layouts">{{translate('Help & Support Section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/message*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.message.list')}}"
                                   title="{{translate('messages')}}"
                                >
                                    <i class="tio-messages nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{translate('messages')}}
                                    </span>
                                </a>
                            </li>
                            End Pages -->
                        @endif


                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['report_management']))

                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="Documentation">{{translate('report_and_analytics')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <!-- Pages -->
                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/report/sale-report')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.report.sale-report')}}"
                                title="{{translate('sale')}} {{translate('report')}}">
                                <span class="tio-chart-bar-1 nav-icon"></span>
                                <span class="text-truncate">{{translate('Sales Report')}}</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/order')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.report.order')}}"
                                title="{{translate('order')}} {{translate('report')}}">
                                <span class="tio-chart-bar-2 nav-icon"></span>
                                <span class="text-truncate">{{translate('Order Report')}}</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/earning')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.report.earning')}}"
                                title="{{translate('earning')}} {{translate('report')}}">
                                <span class="tio-chart-pie-1 nav-icon"></span>
                                <span class="text-truncate">{{translate('earning')}} {{translate('report')}}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/expense')?'active':''}}">
                            <a class="nav-link " href="{{route('admin.report.expense')}}"
                                title="{{translate('expense')}} {{translate('report')}}">
                                <span class="tio-chart-bar-4 nav-icon"></span>
                                <span class="text-truncate">{{translate('expense')}} {{translate('report')}}</span>
                            </a>
                        </li>

                        <!-- Analytics -->
                        <!-- <li class="navbar-vertical-aside-has-menu {{Request::is('admin/analytics*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="{{translate('Analytics')}}">
                                <i class="tio-chart-donut-2 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Analytics')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/analytics*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/analytics/keyword-search')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.analytics.keyword-search', ['date_range'=>'today', 'date_range_2'=>'today'])}}" title="{{translate('keyword-search')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('Keyword Search')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('admin/analytics/customer-search')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.analytics.customer-search', ['date_range'=>'today', 'date_range_2'=>'today'])}}" title="{{translate('customer-search')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('customer search')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li> -->

                        <!-- End Pages -->
                        @endif



                        @if(auth('admin')->user()->admin_role_id == 1)
                        <!-- ########### SYSTEM SETTING ###########  -->
                        @if(Helpers::module_permission_check(MANAGEMENT_SECTION['system_management']))
                        <li class="nav-item">
                            <small class="nav-subtitle" title="Layouts">{{translate('system setting')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/store*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.business-settings.store.ecom-setup')}}"
                                title="{{translate('Business Setup')}}">
                                <i class="tio-settings nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('Business Setup')}}
                                </span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/groups*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.business-settings.groups.index')}}"
                                title="{{translate('groups')}}">
                                <i class="tio-settings nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('groups')}}
                                </span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/web-app/payment-method*')
                                                || Request::is('admin/business-settings/web-app/third-party*')
                                                || Request::is('admin/business-settings/web-app/mail-config*')
                                                || Request::is('admin/business-settings/web-app/sms-module*') ?'active':''}}">
                            <a class="nav-link" href="{{route('admin.business-settings.web-app.payment-method')}}"
                                title="{{translate('Web & Apps Settings')}}">
                                <i class="tio-website nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('3rd Party')}}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/page-setup/*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{translate('Pages & Media')}}">
                                <i class="tio-pages-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Pages & Media')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/business-settings/page-setup/*')?'block':''}} {{Request::is('admin/business-settings/web-app/third-party/social-media')?'block':''}}">
                                <li
                                    class="nav-item mt-0 {{Request::is('admin/business-settings/page-setup/*')?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.business-settings.page-setup.about-us')}}"
                                        title="{{translate('Page Setup')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('Page Setup')}}</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{Request::is('admin/business-settings/web-app/third-party/social-media')?'active':''}}">
                                    <a class="nav-link "
                                        href="{{route('admin.business-settings.web-app.third-party.social-media')}}"
                                        title="{{translate('Social Media')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('Social Media')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item mt-0
                                        {{Request::is('admin/business-settings/web-app/system-setup*')?'active':''}}">
                            <a class="nav-link"
                                href="{{route('admin.business-settings.web-app.system-setup.language.index')}}"
                                title="{{translate('system_settings')}}">
                                <i class="tio-security-on-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('system_setup')}}</span>
                            </a>
                        </li>
                        @endif
                        @endif

                        <li class="nav-item">
                            <div class="nav-divider"></div>
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>


@push('script_2')
<script>
$(window).on('load', function() {
    if ($(".navbar-vertical-content li.active").length) {
        $('.navbar-vertical-content').animate({
            scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
        }, 10);
    }
});

var $rows = $('#navbar-vertical-content .navbar-nav > li');
$('#search-sidebar-menu').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});
</script>
@endpush