<div class="product-card card wrapper" onclick="quickView('{{ $product->id }}')">
    <?php $offers = Helpers::getWhProductOffers($product->id); ?>
    @if (count($offers) > 0)
        <div class="ribbon-wrapper-green">
            <div class="ribbon-green"> Offer</div>
        </div>
    @endif
    <?php
    //dd( $product);
    $category_id = $product['category_id'];
    /* foreach (json_decode($product['category_id'], true) as $cat) {
            if ($cat['position'] == 1){
                $category_id = ($cat['id']);
            }
        } */
    $category_discount = \App\CentralLogics\Helpers::category_discount_calculate($category_id, $product['price']);
    $product_discount = \App\CentralLogics\Helpers::discount_calculate($product, $product['price']);
    
    if ($category_discount >= $product['price']) {
        $discount = $product_discount;
    } else {
        $discount = max($category_discount, $product_discount);
    }
    ?>

    <div class="card-header inline_product clickable p-0">
        @if (!empty(json_decode($product['image'], true)))
            <img src="{{ asset('storage/app/public/product') }}/{{ json_decode($product['image'], true)[0] }}"
                onerror="this.src='{{ asset('public/assets/admin/img/160x160/2.png') }}'"
                class="w-100 h-100 object-cover aspect-ratio-80">
        @else
            <img src="{{ asset('public/assets/admin/img/160x160/2.png') }}"
                class="w-100 h-100 object-cover aspect-ratio-80">
        @endif
    </div>
    @php($whProduct = Helpers::warehouseProductData($product['id']))
    <div class="card-body inline_product text-center p-1 clickable">
        <div class="product-title1 text-dark font-weight-bold">
            {{ Str::limit(@$product['name'], 12) }}
            @if (@$whProduct->discount_upto > 0)
                <div class="off_bg">
                    Up to {{ $whProduct->discount_upto }}% Off
                </div>
            @endif
        </div>
        <div class="justify-content-between text-center">
            <div class="product-price text-center">
                @if ($whProduct->market_price && $whProduct->market_price > $whProduct->customer_price)
                    <strike style="font-size: 12px!important;">
                        {{ Helpers::set_symbol(@$whProduct->market_price) }} / {{ @$whProduct->unit->title }}
                    </strike><br>
                @endif
                {{ Helpers::set_symbol(@$whProduct->customer_price - $discount) }} / {{ @$whProduct->unit->title }}
            </div>
        </div>
    </div>
</div>
