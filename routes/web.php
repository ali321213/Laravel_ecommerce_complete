<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

// Auth routes with registration disabled
Auth::routes(['register' => false]);

// Frontend Auth Routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('login', [FrontendController::class, 'login'])->name('login.form');
    Route::post('login', [FrontendController::class, 'loginSubmit'])->name('login.submit');
    Route::get('logout', [FrontendController::class, 'logout'])->name('logout');

    Route::get('register', [FrontendController::class, 'register'])->name('register');
    Route::post('register', [FrontendController::class, 'registerSubmit'])->name('register.submit');

    // Password reset form (POST)
    Route::post('password-reset', [FrontendController::class, 'showResetForm'])->name('password.reset');
});

// Social login via Socialite
Route::prefix('login')->name('login.')->group(function () {
    Route::get('{provider}', [LoginController::class, 'redirect'])->name('redirect');
    Route::get('{provider}/callback', [LoginController::class, 'callback'])->name('callback');
});

// Public frontend routes
Route::get('/', [FrontendController::class, 'home'])->name('home');

Route::get('/home', [FrontendController::class, 'index']);
Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact/message', [MessageController::class, 'store'])->name('contact.store');

Route::get('product-detail/{slug}', [FrontendController::class, 'productDetail'])->name('product-detail');
Route::post('/product/search', [FrontendController::class, 'productSearch'])->name('product.search');
Route::get('/product-cat/{slug}', [FrontendController::class, 'productCat'])->name('product-cat');
Route::get('/product-sub-cat/{slug}/{sub_slug}', [FrontendController::class, 'productSubCat'])->name('product-sub-cat');
Route::get('/product-brand/{slug}', [FrontendController::class, 'productBrand'])->name('product-brand');

// Cart routes with 'user' middleware
Route::middleware('user')->group(function () {
    Route::get('/add-to-cart/{slug}', [CartController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/add-to-cart', [CartController::class, 'singleAddToCart'])->name('single-add-to-cart');
    Route::get('cart-delete/{id}', [CartController::class, 'cartDelete'])->name('cart-delete');
    Route::post('cart-update', [CartController::class, 'cartUpdate'])->name('cart.update');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Wishlist
    Route::get('/wishlist/{slug}', [WishlistController::class, 'wishlist'])->name('add-to-wishlist');
});

// Public cart and wishlist views
Route::view('/cart', 'frontend.pages.cart')->name('cart');
Route::view('/wishlist', 'frontend.pages.wishlist')->name('wishlist');

// Order and PDF
Route::post('cart/order', [OrderController::class, 'store'])->name('cart.order');
Route::get('order/pdf/{id}', [OrderController::class, 'pdf'])->name('order.pdf');
Route::get('/income', [OrderController::class, 'incomeChart'])->name('product.order.income');

// Product views
Route::get('/product-grids', [FrontendController::class, 'productGrids'])->name('product-grids');
Route::get('/product-lists', [FrontendController::class, 'productLists'])->name('product-lists');
Route::match(['get', 'post'], '/filter', [FrontendController::class, 'productFilter'])->name('shop.filter');

// Order Tracking
Route::get('/product/track', [OrderController::class, 'orderTrack'])->name('order.track');
Route::post('product/track/order', [OrderController::class, 'productTrackOrder'])->name('product.track.order');

// Blog
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog-detail/{slug}', [FrontendController::class, 'blogDetail'])->name('blog.detail');
Route::get('/blog/search', [FrontendController::class, 'blogSearch'])->name('blog.search');
Route::post('/blog/filter', [FrontendController::class, 'blogFilter'])->name('blog.filter');
Route::get('blog-cat/{slug}', [FrontendController::class, 'blogByCategory'])->name('blog.category');
Route::get('blog-tag/{slug}', [FrontendController::class, 'blogByTag'])->name('blog.tag');

// Newsletter subscription
Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');

// Product Review
Route::resource('review', ProductReviewController::class);
Route::post('product/{slug}/review', [ProductReviewController::class, 'store'])->name('review.store');

// Post Comments
Route::post('post/{slug}/comment', [PostCommentController::class, 'store'])->name('post-comment.store');
Route::resource('comment', PostCommentController::class);

// Coupon
Route::post('/coupon-store', [CouponController::class, 'couponStore'])->name('coupon-store');

// Payment routes
Route::get('payment', [PayPalController::class, 'payment'])->name('payment');
Route::get('cancel', [PayPalController::class, 'cancel'])->name('payment.cancel');
Route::get('payment/success', [PayPalController::class, 'success'])->name('payment.success');


// Backend routes - Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::view('/file-manager', 'backend.layouts.file-manager')->name('file-manager');

    Route::resources([
        'users' => UsersController::class,
        'banner' => BannerController::class,
        'brand' => BrandController::class,
        'category' => CategoryController::class,
        'product' => ProductController::class,
        'post-category' => PostCategoryController::class,
        'post-tag' => PostTagController::class,
        'post' => PostController::class,
        'message' => MessageController::class,
        'order' => OrderController::class,
        'shipping' => ShippingController::class,
        'coupon' => CouponController::class,
    ]);

    // Ajax for subcategory
    Route::post('/category/{id}/child', [CategoryController::class, 'getChildByParent']);

    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('profile/{id}', [AdminController::class, 'profileUpdate'])->name('profile-update');

    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('setting/update', [AdminController::class, 'settingsUpdate'])->name('settings.update');

    // Notifications
    Route::get('notification/{id}', [NotificationController::class, 'show'])->name('notification.show');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('notification/{id}', [NotificationController::class, 'delete'])->name('notification.delete');

    // Password Change
    Route::get('change-password', [AdminController::class, 'changePassword'])->name('change.password.form');
    Route::post('change-password', [AdminController::class, 'changPasswordStore'])->name('change.password');
});


// User routes
Route::prefix('user')->middleware('user')->name('user.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('profile/{id}', [HomeController::class, 'profileUpdate'])->name('profile-update');

    // Orders
    Route::get('order', [HomeController::class, 'orderIndex'])->name('order.index');
    Route::get('order/show/{id}', [HomeController::class, 'orderShow'])->name('order.show');
    Route::delete('order/delete/{id}', [HomeController::class, 'userOrderDelete'])->name('order.delete');

    // Product Reviews
    Route::get('user-review', [HomeController::class, 'productReviewIndex'])->name('productreview.index');
    Route::delete('user-review/delete/{id}', [HomeController::class, 'productReviewDelete'])->name('productreview.delete');
    Route::get('user-review/edit/{id}', [HomeController::class, 'productReviewEdit'])->name('productreview.edit');
    Route::patch('user-review/update/{id}', [HomeController::class, 'productReviewUpdate'])->name('productreview.update');

    // Post Comments
    Route::get('user-post/comment', [HomeController::class, 'userComment'])->name('post-comment.index');
    Route::delete('user-post/comment/delete/{id}', [HomeController::class, 'userCommentDelete'])->name('post-comment.delete');
    Route::get('user-post/comment/edit/{id}', [HomeController::class, 'userCommentEdit'])->name('post-comment.edit');
    Route::patch('user-post/comment/update/{id}', [HomeController::class, 'userCommentUpdate'])->name('post-comment.update');

    // Password Change
    Route::get('change-password', [HomeController::class, 'changePassword'])->name('change.password.form');
    Route::post('change-password', [HomeController::class, 'changPasswordStore'])->name('change.password');
});

// Laravel File Manager package routes
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
