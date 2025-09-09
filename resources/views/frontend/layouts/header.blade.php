@php
    use App\Helpers\Helper;
    $settings = DB::table('settings')->first();
    $headerCategories = Helper::getHeaderCategory();
@endphp

<header class="header shop">
    <!-- Topbar -->
    <div class="topbar py-2 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="top-left">
                        <ul class="list-main mb-0">
                            <li><i class="ti-headphone-alt"></i> {{ $settings->phone ?? '' }}</li>
                            <li><i class="ti-email"></i> {{ $settings->email ?? '' }}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 text-end">
                    <div class="right-content">
                        <ul class="list-main mb-0">
                            @auth
                                <li><i class="fa fa-truck"></i>
                                    <a href="{{ route('order.track') }}">Track Order</a>
                                </li>
                                <li><i class="ti-user"></i>
                                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                                        target="_blank">
                                        Dashboard
                                    </a>
                                </li>
                                <li><i class="ti-power-off"></i> <a href="{{ route('user.logout') }}">Logout</a></li>
                            @else
                                <li><i class="fa fa-sign-in"></i>
                                    <a href="{{ route('login') }}">Login</a> /
                                    <a href="{{ route('register') }}">Register</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Topbar -->

    <!-- Middle Header -->
    <div class="middle-inner py-3">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset($settings->logo ?? '') }}" alt="logo"
                                style="height:70px;width:80px;">
                        </a>
                    </div>

                    <!-- Mobile Nav & Search -->
                    <div class="search-top mt-3">
                        <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
                        <div class="search-top mt-2">
                            <form class="search-form">
                                <input type="text" placeholder="Search here..." name="search">
                                <button type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                        <div class="mobile-nav"></div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="search-bar-top">
                        <div class="search-bar d-flex align-items-center">
                            <select class="form-select me-2">
                                <option>All Category</option>
                                @foreach (Helper::getAllCategory() as $cat)
                                    <option>{{ $cat->title }}</option>
                                @endforeach
                            </select>

                            <form method="POST" action="{{ route('product.search') }}" class="flex-grow-1 d-flex">
                                @csrf
                                <input name="search" placeholder="Search Products Here....." type="search"
                                    class="form-control">
                                <button class="btn btn-primary ms-2" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Wishlist & Cart -->
                <div class="col-lg-2 col-md-3 col-12">
                    <div class="right-bar d-flex justify-content-end gap-2">
                        <!-- Wishlist -->
                        <div class="single-bar shopping position-relative">
                            <a href="{{ route('wishlist') }}" class="single-icon">
                                <i class="fa fa-heart-o"></i>
                                <span class="total-count">{{ Helper::wishlistCount() }}</span>
                            </a>

                            @auth
                                <div class="shopping-item shadow p-3 rounded">
                                    <div class="dropdown-cart-header d-flex justify-content-between mb-2">
                                        <span>{{ count(Helper::getAllProductFromWishlist()) }} Items</span>
                                        <a href="{{ route('wishlist') }}">View Wishlist</a>
                                    </div>
                                    <ul class="shopping-list list-unstyled mb-2">
                                        @foreach (Helper::getAllProductFromWishlist() as $data)
                                            @php $photo = explode(',', $data->product['photo']); @endphp
                                            <li class="d-flex align-items-center mb-2">
                                                <a href="{{ route('wishlist-delete', $data->id) }}" class="remove me-2"><i
                                                        class="fa fa-remove"></i></a>
                                                <a class="cart-img me-2" href="#"><img src="{{ $photo[0] }}"
                                                        alt="{{ $data->product['title'] }}"
                                                        style="width:50px;height:50px;"></a>
                                                <div>
                                                    <h4><a href="{{ route('product-detail', $data->product['slug']) }}"
                                                            target="_blank">{{ $data->product['title'] }}</a></h4>
                                                    <p class="quantity">{{ $data->quantity }} x <span
                                                            class="amount">${{ number_format($data->price, 2) }}</span></p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="bottom d-flex justify-content-between align-items-center">
                                        <div class="total"><span>Total</span> <span
                                                class="total-amount">${{ number_format(Helper::totalWishlistPrice(), 2) }}</span>
                                        </div>
                                        <a href="{{ route('cart') }}" class="btn btn-primary btn-sm">Cart</a>
                                    </div>
                                </div>
                            @endauth
                        </div>

                        <!-- Cart -->
                        <div class="single-bar shopping position-relative">
                            <a href="{{ route('cart') }}" class="single-icon">
                                <i class="ti-bag"></i>
                                <span class="total-count">{{ Helper::cartCount() }}</span>
                            </a>

                            @auth
                                <div class="shopping-item shadow p-3 rounded">
                                    <div class="dropdown-cart-header d-flex justify-content-between mb-2">
                                        <span>{{ count(Helper::getAllProductFromCart()) }} Items</span>
                                        <a href="{{ route('cart') }}">View Cart</a>
                                    </div>
                                    <ul class="shopping-list list-unstyled mb-2">
                                        @foreach (Helper::getAllProductFromCart() as $data)
                                            @php $photo = explode(',', $data->product['photo']); @endphp
                                            <li class="d-flex align-items-center mb-2">
                                                <a href="{{ route('cart-delete', $data->id) }}" class="remove me-2"><i
                                                        class="fa fa-remove"></i></a>
                                                <a class="cart-img me-2" href="#"><img src="{{ $photo[0] }}"
                                                        alt="{{ $data->product['title'] }}"
                                                        style="width:50px;height:50px;"></a>
                                                <div>
                                                    <h4><a href="{{ route('product-detail', $data->product['slug']) }}"
                                                            target="_blank">{{ $data->product['title'] }}</a></h4>
                                                    <p class="quantity">{{ $data->quantity }} x <span
                                                            class="amount">${{ number_format($data->price, 2) }}</span>
                                                    </p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="bottom d-flex justify-content-between align-items-center">
                                        <div class="total"><span>Total</span> <span
                                                class="total-amount">${{ number_format(Helper::totalCartPrice(), 2) }}</span>
                                        </div>
                                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-sm">Checkout</a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Middle Header -->

    <!-- Navigation -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <nav class="navbar navbar-expand-lg">
                    <div class="navbar-collapse">
                        <ul class="nav main-menu menu navbar-nav">
                            <li class="{{ Request::path() == 'home' ? 'active' : '' }}"><a
                                    href="{{ route('home') }}">Home</a></li>
                            <li class="{{ Request::path() == 'about-us' ? 'active' : '' }}"><a
                                    href="{{ route('about-us') }}">About Us</a></li>
                            <li class="@if (Request::path() == 'product-grids' || Request::path() == 'product-lists') active @endif">
                                <a href="{{ route('product-grids') }}">Products</a><span class="new">New</span>
                            </li>
                            @if ($headerCategories->count() > 0)
                                <li>
                                    <a href="javascript:void(0);">Category <i class="ti-angle-down"></i></a>
                                    <ul class="dropdown border-0 shadow">
                                        @foreach ($headerCategories as $cat)
                                            <li>
                                                <a
                                                    href="{{ route('product-cat', $cat->slug) }}">{{ $cat->name }}</a>
                                                @if ($cat->children && $cat->children->count() > 0)
                                                    <ul class="dropdown sub-dropdown border-0 shadow">
                                                        @foreach ($cat->children as $child)
                                                            <li><a
                                                                    href="{{ route('product-sub-cat', [$cat->slug, $child->slug]) }}">{{ $child->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif

                            <li class="{{ Request::path() == 'blog' ? 'active' : '' }}"><a
                                    href="{{ route('blog') }}">Blog</a></li>
                            <li class="{{ Request::path() == 'contact' ? 'active' : '' }}"><a
                                    href="{{ route('contact') }}">Contact Us</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!--/ End Navigation -->
</header>
