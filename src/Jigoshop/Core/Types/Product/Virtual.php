<?php

namespace Jigoshop\Core\Types\Product;

use Jigoshop\Entity\Order\Item;
use Jigoshop\Entity\Product;
use Jigoshop\Entity\Product\Virtual as Entity;
use Jigoshop\Helper\Scripts;
use WPAL\Wordpress;

class Virtual implements Type
{
	/**
	 * Returns identifier for the type.
	 *
	 * @return string Type identifier.
	 */
	public function getId()
	{
		return Entity::TYPE;
	}

	/**
	 * Returns human-readable name for the type.
	 *
	 * @return string Type name.
	 */
	public function getName()
	{
		return __('Virtual', 'jigoshop');
	}

	/**
	 * Returns class name to use as type entity.
	 * This class MUST extend {@code \Jigoshop\Entity\Product}!
	 *
	 * @return string Fully qualified class name.
	 */
	public function getClass()
	{
		return '\Jigoshop\Entity\Product\Virtual';
	}

	/**
	 * Initializes product type.
	 *
	 * @param Wordpress $wp           WordPress Abstraction Layer
	 * @param array     $enabledTypes List of all available types.
	 */
	public function initialize(Wordpress $wp, array $enabledTypes)
	{
		$wp->addFilter('jigoshop\cart\add', [$this, 'addToCart'], 10, 2);
		$wp->addFilter('jigoshop\core\types\variable\subtypes', [$this, 'addVariableSubtype'], 10, 1);
		$wp->addFilter('jigoshop\product\get_stock', [$this, 'getStock'], 10, 2);
		$wp->addAction('jigoshop\admin\product\assets', [$this, 'addAssets'], 10, 0);
	}

	/**
	 * @param $stock bool|int Current stock value.
	 * @param $item  Item Item to check.
	 *
	 * @return bool Whether the product is out of stock.
	 */
	public function getStock($stock, $item)
	{
		if ($item->getType() == Entity::TYPE) {
			/** @var Entity $product */
			$product = $item->getProduct();

            if($product->getStock()->getManage()) {
                return $product->getStock()->getStock();
            }
		}

		return $stock;
	}

	/**
	 * Adds downloadable as proper subtype for variations.
	 *
	 * @param $subtypes array Current list of subtypes.
	 *
	 * @return array Updated list of subtypes.
	 */
	public function addVariableSubtype($subtypes)
	{
		$subtypes[] = Entity::TYPE;

		return $subtypes;
	}

	public function addToCart($value, $product)
	{
		if ($product instanceof Entity) {
			$item = new Item();
			$item->setName($product->getName());
			$item->setPrice($product->getPrice());
			$item->setQuantity(1);
			$item->setProduct($product);

			return $item;
		}

		return $value;
	}

	public function addAssets()
	{
		Scripts::add('jigoshop.admin.product.virtual', \JigoshopInit::getUrl().'/assets/js/admin/product/virtual.js', ['jquery']);
	}
}
