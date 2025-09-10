<?php

namespace App\Http\Controllers;

use App\Models\{Banner, Product, Category, PostTag, PostCategory, Post, Brand, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Session};
use DrewM\MailChimp\MailChimp;

class FrontendController extends Controller
{
    /* Redirect based on role */
    public function index(Request $request)
    {
        return redirect()->route($request->user()->role . '.dashboard');
    }

    /* Home page */
    public function home()
    {
        return view('frontend.index', [
            'featured'       => Product::active()->featured()->latest('price')->take(2)->get(),
            'posts'          => Post::active()->latest()->take(3)->get(),
            'banners'        => Banner::active()->latest()->take(3)->get(),
            'product_lists'  => Product::active()->latest()->take(8)->get(),
            'category_lists' => Category::active()->parents()->orderBy('title')->get(),
        ]);
    }

    /* Static pages */
    public function aboutUs()
    {
        return view('frontend.pages.about-us');
    }
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    /* Product detail */
    public function productDetail($slug)
    {
        return view('frontend.pages.product_detail', [
            'product_detail' => Product::getProductBySlug($slug),
        ]);
    }

    /* Product listing (grid or list) */
    public function productGrids()
    {
        $products = Product::where('status', 'active')->paginate(9);
        $recent_products = \App\Models\Product::where('status', 'active')->orderBy('created_at', 'desc')->limit(3)->get();
        $categories = Category::with('children')->where('is_parent', 1)->whereNull('parent_id')->where('status', 'active')->orderBy('title', 'asc')->get();
        return view('frontend.pages.product-grids', compact('products', 'categories', 'recent_products'));
    }


    public function productLists(Request $request)
    {
        return $this->productListing($request, 'frontend.pages.product-lists', 6);
    }

    /* Reusable product filter method */
    private function productListing(Request $request, string $view, int $perPage)
    {
        $products = Product::query()->active();

        if ($request->filled('category')) {
            $catIds = Category::whereIn('slug', explode(',', $request->category))->pluck('id');
            $products->whereIn('cat_id', $catIds);
        }

        if ($request->filled('brand')) {
            $brandIds = Brand::whereIn('slug', explode(',', $request->brand))->pluck('id');
            $products->whereIn('brand_id', $brandIds);
        }

        if ($request->sortBy === 'title') $products->orderBy('title');
        if ($request->sortBy === 'price') $products->orderBy('price');

        if ($request->filled('price')) {
            $price = explode('-', $request->price);
            $products->whereBetween('price', $price);
        }

        return view($view, [
            'products'        => $products->paginate($request->get('show', $perPage)),
            'recent_products' => Product::active()->latest()->take(3)->get(),
        ]);
    }

    /* Search product */
    public function productSearch(Request $request)
    {
        $products = Product::active()
            ->whereAny(['title', 'slug', 'description', 'summary', 'price'], 'like', "%{$request->search}%")
            ->latest()->paginate(9);

        return view('frontend.pages.product-grids', [
            'products'        => $products,
            'recent_products' => Product::active()->latest()->take(3)->get(),
        ]);
    }

    /* Products by brand/category/subcategory */
    public function productBrand($slug, Request $request)
    {
        return $this->productByRelation(Brand::getProductByBrand($slug)->products, $request);
    }

    public function productCat($slug, Request $request)
    {
        return $this->productByRelation(Category::getProductByCat($slug)->products, $request);
    }

    public function productSubCat($subSlug, Request $request)
    {
        return $this->productByRelation(Category::getProductBySubCat($subSlug)->sub_products, $request);
    }

    private function productByRelation($products, Request $request)
    {
        $view = $request->is('e-shop.loc/product-grids')
            ? 'frontend.pages.product-grids'
            : 'frontend.pages.product-lists';

        return view($view, [
            'products'        => $products,
            'recent_products' => Product::active()->latest()->take(3)->get(),
        ]);
    }

    /* Blog */
    public function blog(Request $request)
    {
        $posts = Post::active();

        if ($request->filled('category')) {
            $catIds = PostCategory::whereIn('slug', explode(',', $request->category))->pluck('id');
            $posts->whereIn('post_cat_id', $catIds);
        }

        if ($request->filled('tag')) {
            $tagIds = PostTag::whereIn('slug', explode(',', $request->tag))->pluck('id');
            $posts->whereIn('post_tag_id', $tagIds);
        }

        return view('frontend.pages.blog', [
            'posts'        => $posts->latest()->paginate($request->get('show', 9)),
            'recent_posts' => Post::active()->latest()->take(3)->get(),
        ]);
    }

    public function blogDetail($slug)
    {
        return view('frontend.pages.blog-detail', [
            'post'         => Post::getPostBySlug($slug),
            'recent_posts' => Post::active()->latest()->take(3)->get(),
        ]);
    }

    public function blogSearch(Request $request)
    {
        $posts = Post::active()
            ->whereAny(['title', 'quote', 'summary', 'description', 'slug'], 'like', "%{$request->search}%")
            ->latest()->paginate(8);

        return view('frontend.pages.blog', [
            'posts'        => $posts,
            'recent_posts' => Post::active()->latest()->take(3)->get(),
        ]);
    }

    public function blogByCategory($slug)
    {
        return view('frontend.pages.blog', [
            'posts'        => PostCategory::getBlogByCategory($slug)->post,
            'recent_posts' => Post::active()->latest()->take(3)->get(),
        ]);
    }

    public function blogByTag($slug)
    {
        return view('frontend.pages.blog', [
            'posts'        => Post::getBlogByTag($slug),
            'recent_posts' => Post::active()->latest()->take(3)->get(),
        ]);
    }

    /* Auth */
    public function login()
    {
        return view('frontend.pages.login');
    }

    public function loginSubmit(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password') + ['status' => 'active'])) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Logged in successfully!');
        }
        return back()->with('error', 'Invalid credentials.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }

    public function register()
    {
        return view('frontend.pages.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:2',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => 'active',
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Registered successfully!');
    }

    /* Password reset (old form) */
    public function showResetForm()
    {
        return view('auth.passwords.old-reset');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $mailchimp = new MailChimp(env('MAILCHIMP_APIKEY'));
        $listId = env('MAILCHIMP_LIST_ID');
        $result = $mailchimp->post("lists/$listId/members", [
            'email_address' => $request->email,
            'status'        => 'subscribed',
        ]);
        if ($mailchimp->success()) {
            return back()->with('success', 'You have been subscribed!');
        } else {
            dd([
                'error'     => $mailchimp->getLastError(),
                'response'  => $mailchimp->getLastResponse(),
                'request'   => $mailchimp->getLastRequest(),
            ]);
        }
    }
}
