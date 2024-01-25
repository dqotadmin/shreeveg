<div class="modal-body position-relative">
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    <?php $whProduct =  Helpers::warehouseProductData($product['id']); ?>
    
    <div class="modal--media">
        <!-- Product gallery-->
        <div class="modal--media-avatar">
            @if (!empty(json_decode($product['image'],true)))
            <img class="img-responsive" src="{{asset('storage/app/public/product')}}/{{json_decode($product['image'], true)[0]}}"
                onerror="this.src='{{asset('public/assets/admin/img/160x160/2.png')}}'"
                data-zoom="{{asset('storage/app/public/product')}}/{{json_decode($product['image'], true)[0]}}"
                alt="Product image" width="">
            @else
            <img src="{{asset('public/assets/admin/img/160x160/2.png')}}" >
            @endif
            @if(@$whProduct->discount_upto > 0)
                <div class="off_bg">
                    Up to {{$whProduct->discount_upto}}% Off
                </div>
            @endif
            <div class="cz-image-zoom-pane"></div>
        </div>
        <!-- Product details-->
        <div class="details">
            <span class="product-name" style="color: green">{{ $product->category->name }}</span><br>
            <span class="product-name"><a href="#" class="h3 mb-2 product-title">{{ Str::limit($product->name, 100) }}</a></span>
            <div class="mb-3 text-dark">
                <span class="h3 font-weight-normal text-accent mr-1">
                    @if($whProduct['market_price'] && $whProduct['market_price'] > $whProduct['customer_price'])
                <strike style="font-size: 12px!important;">
                {{ Helpers::set_symbol(@$whProduct['market_price']) }} / {{ @$whProduct->unit->title }}
                </strike><br>
                @endif
                {{ Helpers::set_symbol(($whProduct['customer_price']- $discount)) }} / {{ @$whProduct->unit->title }}
                </span>
                {{-- @if($discount > 0)
                <strike style="font-size: 12px!important;">
                {{ Helpers::set_symbol($whProduct['customer_price']) }}
                </strike>
                @endif --}}
            </div>
            @if($discount > 0)
            <div class="mb-3 text-dark">
                <strong>{{ translate('Discount') }} : </strong>
                <strong
                    id="set-discount-amount">{{ $discount }}</strong>
            </div>
            @endif
            <div style="margin-left: -1%" class="sharethis-inline-share-buttons"></div>
        </div>
    </div>
    <div class="row pt-2">
        <div class="col-12">
            <?php
                $cart = false;
                if (session()->has('cart')) {
                    foreach (session()->get('cart') as $key => $cartItem) {
                        if (is_array($cartItem) && $cartItem['id'] == $product['id']) {
                            $cart = $cartItem;
                        }
                    }
                }
                
                ?>
            <h2>{{translate('description')}}</h2>
            <div class="d-block text-break text-dark __descripiton-txt __not-first-hidden">
                <div>
                    {!! $product->description !!}
                </div>
                <div class="show-more text-info text-center">
                    <span>
                    {{translate('see more')}}
                    </span>
                </div>
            </div>
            <p>
                <?php $store_id =  auth('admin')->user()->store_id; $product_id = $whProduct->product_id;   
                    $stock = \App\Model\StoreProduct::where('store_id',$store_id)->where('product_id',$product_id)->first();
                       ?>
            <div class="d-flex justify-content-between">
                <h2>{{translate('available_stock')}}: </h2>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                Price List
                </button>
                @if(count($offers)> 0)
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseWidthOffers" aria-expanded="false" aria-controls="collapseWidthOffers">
                    Offers
                    </button>
                @endif
            </div>
            <span  class="font-20px font-weight-bold text-dark">
            {{ @$stock->total_stock? $stock->total_stock : 0; }}/ {{ @$whProduct->unit->title }} 
            </span>
            </p>
            <div  >
                <div class="collapse width" id="collapseWidthExample">
                    <?php    $product_details_array = @json_decode($whProduct->product_details, true); 
                        $i=0;
                        ?>
                    @if( $product_details_array)
                    @foreach($product_details_array as $key => $warehouse)
                    <div class="discountpricing card card-body p-2 d-block mb-2 w-75"> 
                        {{@$product_details_array[$i]['quantity']}}{{ @$whProduct->unit->title }}
                        <strong class="text-dark mx-2 d-inline-block">₹{{@$product_details_array[$i]['offer_price']}} <strike>₹{{@$product_details_array[$i]['market_price']}}</strike></strong>  
                        <span class="bg-danger discountbox px-2 py-1 rounded-sm text-white "> {{@$product_details_array[$i]['discount']}}%</span>
                        {{@$product_details_array[$i]['per_unit_price']}} /{{ @$whProduct->unit->title }}
                    </div>
                    <?php $i++ ?>
                    @endforeach
                    @endif                            
                </div>

                <div class="collapse width" id="collapseWidthOffers">
                   
                    @foreach($offers as $offer) 
                    
                    @php($offerQty =  Helpers::getWhProductOfferQty($product->id,$offer->id))
                    <div class="discountpricing card card-body p-2 d-block mb-2 w-75"> 
                       {{ @$offer->title }} 
                       <?php 
                       if($offer->offer_type == 'one_rupee'){
                            $msg =  '1 Kg in only 1₹ on minimun order amount ₹'.$offer->min_purchase_amount;
                       }else{

                            $disType = ($offer->discount_type == 'amount')?'₹':'%';
                            $msg =  $offer->discount_amount.$disType. ' off on purchase minimun '.$offerQty .' '.$whProduct->unit->title;
                        } ?>
                        <strong class="text-dark mx-2 d-inline-block"> {{$msg }}</strong>  
                        {{-- <span class="bg-danger discountbox px-2 py-1 rounded-sm text-white "> </span> 
                         --}}
                        
                    </div>
                   
                    @endforeach
                                             
                </div>
            </div>
            <form id="add-to-cart-form" class="mb-2">
                @csrf
                <input type="hidden" name="id" value="{{ @$whProduct->id }}">
                <input type="hidden" name="product_id" value="{{ @$whProduct->product_id }}">
                <!-- Quantity + Add to cart -->
                <div class="d-flex justify-content-between">
                    <div class="product-description-label mt-2 text-dark h3">{{translate('Quantity')}}:</div>
                    <div class="product-quantity d-flex align-items-center">
                        <div class="input-group input-group--style-2 pr-3"
                            style="width: 160px;">
                            <span class="input-group-btn">
                            <button class="btn btn-number text-dark" type="button"
                                data-type="minus" data-field="quantity"
                                disabled="disabled" style="padding: 10px">
                            <i class="tio-remove  font-weight-bold"></i>
                            </button>
                            </span>
                            <input type="hidden" id="check_max_qty" name="store_total_stock" value="{{ @$stock->total_stock }}">
                            <input type="text" name="quantity"  
                                class="form-control input-number text-center cart-qty-field"
                                placeholder="1" value="1" min="0.1" max="100" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');">
                            <span class="input-group-btn">
                            <button class="btn btn-number text-dark" type="button" data-type="plus"
                                data-field="quantity" style="padding: 10px">
                            <i class="tio-add  font-weight-bold"></i>
                            </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters mt-2 text-dark" id="chosen_price_div">
                    <div class="col-2">
                        <div class="product-description-label">{{translate('Total Price')}}:</div>
                    </div>
                    <div class="col-10">
                        <div class="product-price">
                            <strong id="chosen_price"></strong>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-2">
                    <button class="btn btn-primary addToCart"
                        onclick="addToCart()"
                        type="button"
                        style="width:37%; height: 45px">
                    <i class="tio-shopping-cart"></i>
                    {{translate('add')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    cartQuantityInitialize();
    getVariantPrice();
    $('#add-to-cart-form input').on('change', function () {
        getVariantPrice();
    });
</script>
<script>
    $('.show-more span').on('click', function(){
        $('.__descripiton-txt').toggleClass('__not-first-hidden')
        if($(this).hasClass('active')) {
            $('.show-more span').text('{{translate('See More')}}')
            $(this).removeClass('active')
        }else {
            $('.show-more span').text('{{translate('See Less')}}')
            $(this).addClass('active')
        }
    });
    
</script>