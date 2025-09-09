@extends('frontend.layouts.master')

@section('meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='copyright' content=''>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="online shop, purchase, cart, ecommerce site, best online shopping">
    <meta name="description" content="{{ $product_detail->summary }}">
    <meta property="og:url" content="{{ route('product-detail', $product_detail->slug) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $product_detail->title }}">
    <meta property="og:image" content="{{ $product_detail->photo }}">
    <meta property="og:description" content="{{ $product_detail->description }}">
@endsection

@section('title', 'Ecommerce Laravel || PRODUCT DETAIL')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="">Shop Details</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Shop Single -->
    <section class="shop single section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <!-- Product Images -->
                        <div class="col-lg-6 col-12">
                            <div class="product-gallery">
                                <div class="flexslider-thumbnails">
                                    <ul class="slides">
                                        @php
                                            $photos = explode(',', $product_detail->photo);
                                        @endphp
                                        @foreach ($photos as $photo)
                                            <li data-thumb="{{ $photo }}" rel="adjustX:10, adjustY:">
                                                <img src="{{ $photo }}" alt="{{ $product_detail->title }}">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="col-lg-6 col-12">
                            <div class="product-des">
                                <div class="short">
                                    <h4>{{ $product_detail->title ?? 'Product not found' }}</h4>
                                    <div class="rating-main">
                                        <ul class="rating">
                                            @php
                                                $avgRate = $product_detail->reviews->avg('rate') ?? 0;
                                                $avgRate = ceil($avgRate);
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($avgRate >= $i)
                                                    <li><i class="fa fa-star"></i></li>
                                                @else
                                                    <li><i class="fa fa-star-o"></i></li>
                                                @endif
                                            @endfor
                                        </ul>
                                        <a href="#reviews" class="total-review">({{ $product_detail->reviews->count() }})
                                            Reviews</a>
                                    </div>
                                    @php
                                        $after_discount =
                                            $product_detail->price -
                                            ($product_detail->price * $product_detail->discount) / 100;
                                    @endphp
                                    <p class="price"><span class="discount">${{ number_format($after_discount, 2) }}</span>
                                        <s>${{ number_format($product_detail->price, 2) }}</s></p>
                                    <p class="description">{!! $product_detail->summary !!}</p>
                                </div>

                                <!-- Product Size -->
                                @if ($product_detail->size)
                                    <div class="size mt-4">
                                        <h4>Size</h4>
                                        <ul>
                                            @php $sizes = explode(',', $product_detail->size); @endphp
                                            @foreach ($sizes as $size)
                                                <li><a href="#" class="one">{{ $size }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Add to Cart -->
                                <div class="product-buy">
                                    <form action="{{ route('single-add-to-cart') }}" method="POST">
                                        @csrf
                                        <div class="quantity">
                                            <h6>Quantity :</h6>
                                            <div class="input-group">
                                                <div class="button minus">
                                                    <button type="button" class="btn btn-primary btn-number"
                                                        disabled="disabled" data-type="minus" data-field="quant[1]"><i
                                                            class="ti-minus"></i></button>
                                                </div>
                                                <input type="hidden" name="slug" value="{{ $product_detail->slug }}">
                                                <input type="text" name="quant[1]" class="input-number" data-min="1"
                                                    data-max="1000" value="1" id="quantity">
                                                <div class="button plus">
                                                    <button type="button" class="btn btn-primary btn-number"
                                                        data-type="plus" data-field="quant[1]"><i
                                                            class="ti-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add-to-cart mt-4">
                                            <button type="submit" class="btn">Add to cart</button>
                                            <a href="{{ route('add-to-wishlist', $product_detail->slug) }}"
                                                class="btn min"><i class="ti-heart"></i></a>
                                        </div>
                                    </form>
                                    <p class="cat">
                                        Category:
                                        @if ($product_detail->category)
                                            <a href="{{ route('product-cat', $product_detail->category->slug) }}">
                                                {{ $product_detail->category->title }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </p>

                                    @if ($product_detail->subCategory)
                                        <p class="cat mt-1">Sub Category: <a
                                                href="{{ route('product-sub-cat', [$product_detail->category->slug ?? '', $product_detail->subCategory->slug ?? '']) }}">{{ $product_detail->subCategory->title ?? '' }}</a>
                                        </p>
                                    @endif
                                    <p class="availability">Stock:
                                        @if ($product_detail->stock > 0)
                                            @if ($product_detail->stock < 5)
                                                <span class="badge badge-warning">Low in stock</span>
                                            @else
                                                <span class="badge badge-success">Available</span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Out of stock</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="row">
                        <div class="col-12">
                            <div class="product-info">
                                <div class="nav-main">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                href="#description" role="tab">Description</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reviews"
                                                role="tab">Reviews</a></li>
                                    </ul>
                                </div>

                                <div class="tab-content" id="myTabContent">
                                    <!-- Description Tab -->
                                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                                        <div class="tab-single">
                                            <div class="single-des">
                                                <p>{!! $product_detail->description !!}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reviews Tab -->
                                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                                        <div class="tab-single review-panel">
                                            <div class="add-review mb-4">
                                                <h5>Add A Review</h5>
                                                <p>Your email address will not be published. Required fields are marked *
                                                </p>
                                            </div>

                                            @auth
                                                <form method="POST"
                                                    action="{{ route('review.store', $product_detail->slug) }}">
                                                    @csrf
                                                    <div class="rating_box mb-3">
                                                        <label>Your Rating *</label>
                                                        <div class="star-rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <input type="radio" name="rate"
                                                                    value="{{ $i }}"
                                                                    id="star-{{ $i }}">
                                                                <label for="star-{{ $i }}"
                                                                    class="fa fa-star-o"></label>
                                                            @endfor
                                                            @error('rate')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Write a review</label>
                                                        <textarea name="review" rows="4" class="form-control"></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                            @else
                                                <p class="text-center p-5">
                                                    You need to <a href="{{ route('login') }}" class="text-primary">Login</a>
                                                    OR <a href="{{ route('register') }}" class="text-primary">Register</a>
                                                </p>
                                            @endauth

                                            <!-- Existing Reviews -->
                                            <div class="ratting-main mt-5">
                                                <div class="avg-ratting mb-3">
                                                    <h4>{{ ceil($product_detail->reviews->avg('rate')) }}
                                                        <span>(Overall)</span></h4>
                                                    <span>Based on {{ $product_detail->reviews->count() }} Comments</span>
                                                </div>

                                                @foreach ($product_detail->reviews as $review)
                                                    <div class="single-rating mb-3">
                                                        <div class="rating-author">
                                                            <img src="{{ $review->user_info->photo ?? asset('backend/img/avatar.png') }}"
                                                                alt="User" class="rounded-circle" width="50">
                                                        </div>
                                                        <div class="rating-des">
                                                            <h6>{{ $review->user_info->name ?? 'Anonymous' }}</h6>
                                                            <ul class="rating">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($review->rate >= $i)
                                                                        <li><i class="fa fa-star"></i></li>
                                                                    @else
                                                                        <li><i class="fa fa-star-o"></i></li>
                                                                    @endif
                                                                @endfor
                                                            </ul>
                                                            <p>{{ $review->review }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- End Reviews -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Products Section -->
                    <div class="product-area most-popular related-product section mt-5">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="section-title">
                                        <h2>Related Products</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="owl-carousel popular-slider">
                                        @foreach ($product_detail->relatedProducts as $data)
                                            @if ($data->id !== $product_detail->id)
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="{{ route('product-detail', $data->slug) }}">
                                                            @php $photos = explode(',', $data->photo); @endphp
                                                            <img class="default-img" src="{{ $photos[0] }}"
                                                                alt="{{ $data->title }}">
                                                            <span class="price-dec">{{ $data->discount }}% Off</span>
                                                        </a>
                                                        <div class="button-head">
                                                            <div class="product-action">
                                                                <a title="Wishlist" href="#"><i
                                                                        class="ti-heart"></i><span>Add to
                                                                        Wishlist</span></a>
                                                            </div>
                                                            <div class="product-action-2">
                                                                <a title="Add to cart" href="#">Add to cart</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-content">
                                                        <h3><a
                                                                href="{{ route('product-detail', $data->slug) }}">{{ $data->title }}</a>
                                                        </h3>
                                                        <div class="product-price">
                                                            @php $after_discount = $data->price - ($data->discount * $data->price)/100; @endphp
                                                            <span
                                                                class="old">${{ number_format($data->price, 2) }}</span>
                                                            <span>${{ number_format($after_discount, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Related Products -->
                </div>
            </div>
        </div>
    </section>
@endsection


@push('styles')
    <style>
        /* Rating */
        .rating_box {
            display: inline-flex;
        }

        .star-rating {
            font-size: 0;
            padding-left: 10px;
            padding-right: 10px;
        }

        .star-rating__wrap {
            display: inline-block;
            font-size: 1rem;
        }

        .star-rating__wrap:after {
            content: "";
            display: table;
            clear: both;
        }

        .star-rating__ico {
            float: right;
            padding-left: 2px;
            cursor: pointer;
            color: #F7941D;
            font-size: 16px;
            margin-top: 5px;
        }

        .star-rating__ico:last-child {
            padding-left: 0;
        }

        .star-rating__input {
            display: none;
        }

        .star-rating__ico:hover:before,
        .star-rating__ico:hover~.star-rating__ico:before,
        .star-rating__input:checked~.star-rating__ico:before {
            content: "\F005";
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    {{-- <script>
        $('.cart').click(function(){
            var quantity=$('#quantity').val();
            var pro_id=$(this).data('id');
            // alert(quantity);
            $.ajax({
                url:"{{route('add-to-cart')}}",
                type:"POST",
                data:{
                    _token:"{{csrf_token()}}",
                    quantity:quantity,
                    pro_id:pro_id
                },
                success:function(response){
                    console.log(response);
					if(typeof(response)!='object'){
						response=$.parseJSON(response);
					}
					if(response.status){
						swal('success',response.msg,'success').then(function(){
							document.location.href=document.location.href;
						});
					}
					else{
                        swal('error',response.msg,'error').then(function(){
							document.location.href=document.location.href;
						});
                    }
                }
            })
        });
    </script> --}}
@endpush
