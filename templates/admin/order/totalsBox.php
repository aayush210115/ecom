<?php
use Jigoshop\Admin\Helper\Forms;
use Jigoshop\Helper\Currency;
use Jigoshop\Helper\Product;
use Jigoshop\Helper\Render;

/**
 * @var $order \Jigoshop\Entity\Order The order.
 * @var $tax array Tax data for the order.
 * @var $shippingMethods array List of available shipping methods.
 */
$orderTax = $order->getTax();
?>
<div class="jigoshop jigoshop-totals" data-order="<?= $order->getId(); ?>">
	<div class="form-horizontal">
		<div class="form-group<?= $order->isShippingRequired() ? '' : ' not-active'; ?>">
			<label for="order_shipping" class="col-sm-2 control-label">
				<?= __('Shipping', 'jigoshop'); ?>
			</label>
			<div class="col-sm-9">
				<ul class="list-group" id="shipping-methods">
					<?php foreach($shippingMethods as $method): /** @var $method \Jigoshop\Shipping\Method */ ?>
						<?php if ($method instanceof \Jigoshop\Shipping\MultipleMethod): ?>
							<?php Render::output('admin/order/totals/shipping/multiple_method', ['method' => $method, 'order' => $order]); ?>
						<?php else: ?>
							<?php Render::output('admin/order/totals/shipping/method', ['method' => $method, 'order' => $order]); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php Forms::constant([
			'name' => 'order[subtotal]',
			'id' => 'subtotal',
			'label' => __('Subtotal', 'jigoshop'),
			'placeholder' => 0.0,
			'size' => 12,
			'value' => Product::formatPrice($order->getSubtotal()),
        ]); ?>
        <?php if($order->getDiscount() > 0) : ?>
            <?php Forms::constant([
                'name' => 'order[discount]',
                'label' => __('Discount', 'jigoshop'),
                'placeholder' => 0.0,
                'size' => 12,
                'value' => Product::formatPrice($order->getDiscount()),
            ]); ?>
        <?php endif; ?>
		<?php foreach($tax as $class => $option): ?>
			<?php Forms::constant([
				'name' => 'order[tax]['.$class.']',
				'label' => $option['label'],
				'placeholder' => 0.0,
				'value' => $option['value'],
				'size' => 12,
				'classes' => [$orderTax[$class] > 0 ? '' : 'not-active'],
            ]); ?>
		<?php endforeach; ?>
		<?php Forms::constant([
			'name' => 'order[total]',
			'id' => 'total',
			'label' => __('Total', 'jigoshop'),
			'placeholder' => 0.0,
			'size' => 12,
			'value' => Product::formatPrice($order->getTotal())
        ]); ?>
		<?php do_action('jigoshop\admin\order\totalsBox\after_total', $order); ?>
	</div>
</div>
