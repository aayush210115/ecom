<?php
use Jigoshop\Helper\Product;

/**
 * @var $cart \Jigoshop\Entity\Cart Cart object.
 * @var $key string Cart item key.
 * @var $item \Jigoshop\Entity\Order\Item Cart item to display.
 */
?>
<?php
$product = $item->getProduct();
$url = apply_filters('jigoshop\cart\product_url', get_permalink($product->getId()), $key);

$price = $item->getPrice();
$priceWithTax = $item->getPrice() + ($item->getTax() / $item->getQuantity());

$prices = Product::generatePrices($price, $priceWithTax, 1);
if(count($prices) == 2) {
    $pricesStr = sprintf('%s
        (%s)', $prices[0], $prices[1]);
}
else {
    $pricesStr = $prices[0];
}

$priceTotal = $item->getQuantity() * $price;
$priceTotalWithTax = $item->getQuantity() * $priceWithTax;

$pricesTotal = Product::generatePrices($priceTotal, $priceTotalWithTax, 1);
if(count($pricesTotal) == 2) {
    $pricesTotalStr = sprintf('%s
        (%s)', $pricesTotal[0], $pricesTotal[1]);
}
else {
    $pricesTotalStr = $pricesTotal[0];
}
?>
<li class="list-group-item" data-id="<?= $key; ?>" data-product="<?= $product->getId(); ?>">
    <div class="list-group-item-heading clearfix">
        <div class="buttons pull-right">
            <button type="button" class="show-product btn btn-default pull-right"
                    title="<?php _e('Expand', 'jigoshop'); ?>">
                <span class="glyphicon glyphicon-collapse-down"></span>
            </button>
        </div>
        <div class="form-group">
            <div class="pull-left">
                <a href="<?= $url; ?>"><?= apply_filters('jigoshop\template\shop\checkout\product_title', $product->getName(), $product, $item); ?></a>
                <?php do_action('jigoshop\template\shop\checkout\after_product_title', $product, $item); ?>
            </div>
            <div class="pull-right">
                <span class="product-quantity"><?= $item->getQuantity(); ?></span>
                &times;
                <span class="product-price">
                            <?= $pricesStr; ?>
                        </span>
            </div>
        </div>
    </div>
    <div class="list-group-item-text" style="display: none">
        <div class="">
            <?= \Jigoshop\Helper\Product::getFeaturedImage($product, 'shop_tiny'); ?>
        </div>
        <div class="">
            <fieldset>
                <div class="form-group">
                    <label class="margin-top-bottom-9">
                        <?php _e('Unit Price', 'jigoshop'); ?>
                    </label>
                    <div class="clearfix product-price">
                        <?= apply_filters('jigoshop\template\shop\checkout\product_price', $pricesStr, $price, $product, $item); ?>
                    </div>
                </div>
                <div class="form-group product_quantity_field padding-bottom-5">
                        <label for="product_quantity" class="margin-top-bottom-9">
                            <?php _e('Quantity', 'jigoshop'); ?>
                        </label>
                        <div class="clearfix">
                            <div class="tooltip-inline-badge">
                            </div>
                            <div class="tooltip-inline-input product-quantity">
                                <?= $item->getQuantity(); ?>
                            </div>
                        </div>
                </div>
                <div class="form-group product_regular_price_field ">
                        <label for="product_regular_price" class="margin-top-bottom-9">
                            <?php _e('Price', 'jigoshop'); ?>
                        </label>
                        <div class="clearfix product-subtotal">
                            <?= apply_filters('jigoshop\template\shop\checkout\product_subtotal', $pricesTotalStr, $item->getQuantity() * $price, $product, $item); ?>
                        </div>
                </div>
            </fieldset>
        </div>
    </div>
</li>