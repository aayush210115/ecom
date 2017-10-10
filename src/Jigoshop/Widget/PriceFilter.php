<?php

namespace Jigoshop\Widget;

use Jigoshop\Core;
use Jigoshop\Core\Types;
use Jigoshop\Entity\Product;
use Jigoshop\Frontend\Pages;
use Jigoshop\Helper\Render;
use Jigoshop\Helper\Styles;
use WPAL\Wordpress;

class PriceFilter extends \WP_Widget
{
	const ID = 'jigoshop_price_filter';

	/**
	 * Constructor
	 * Setup the widget with the available options
	 * Add actions to clear the cache whenever a post is saved|deleted or a theme is switched
	 */
	public function __construct()
	{
		$options = [
			'classname' => self::ID,
			'description' => __('Outputs a price filter slider', 'jigoshop-ecommerce')
        ];

		// Create the widget
		parent::__construct(self::ID, __('Jigoshop: Price Filter', 'jigoshop-ecommerce'), $options);

		// Add price filter init to init hook
		add_action('wp_enqueue_scripts', [$this, 'assets']);

		// Add own hidden fields to filter
		add_filter('jigoshop\get_fields', [$this, 'hiddenFields']);
	}

	public function assets()
	{
		// if price filter in use on front end, load jquery-ui slider (WP loads in footer)
		if (is_active_widget(false, false, self::ID) && !is_admin()) {
			wp_enqueue_script('jquery-ui-slider');
		}

		Styles::add('jigoshop.widget.price_filter', \JigoshopInit::getUrl().'/assets/css/widget/price_filter.css');
	}

	public function hiddenFields($fields)
	{
		if (isset($_GET['max_price'])) {
			$fields['max_price'] = $_GET['max_price'];
		}

		if (isset($_GET['min_price'])) {
			$fields['min_price'] = $_GET['min_price'];
		}

		return $fields;
	}

	/**
	 * Displays the widget in the sidebar.
	 *
	 * @param array $args     Sidebar arguments.
	 * @param array $instance The instance.
	 *
	 * @return bool|void
	 */
	public function widget($args, $instance)
	{
		global $wpdb;

		if (!Pages::isProductList()) {
			return;
		}

		// Set the widget title
		$title = apply_filters(
			'widget_title',
			($instance['title']) ? $instance['title'] : __('Filter by Price', 'jigoshop-ecommerce'),
			$instance,
			$this->id_base
		);

		$fields = apply_filters('jigoshop\get_fields', []);

		$maxPrice = 0.0;
		$row = $wpdb->get_row("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'regular_price' ORDER BY meta_value DESC LIMIT 1");
		$maxPrice = $row->meta_value;

		if(isset($_GET['min_price']) && isset($_GET['max_price'])) {
			$currentMinPrice = $_GET['min_price'];
			$currentMaxPrice = $_GET['max_price'];
		}
		else {
			$currentMinPrice = 0;
			$currentMaxPrice = $maxPrice;
		}

		Render::output('widget/price_filter/widget', array_merge($args, [
			'title' => $title,
			'max' => $maxPrice,
			'fields' => $fields,
			'currentMinPrice' => $currentMinPrice,
			'currentMaxPrice' => $currentMaxPrice
        ]));
	}

	/**
	 * Handles the processing of information entered in the wordpress admin
	 * Flushes the cache & removes entry from options array
	 *
	 * @param array $new_instance new instance
	 * @param array $old_instance old instance
	 *
	 * @return array instance
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		// Save the new values
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	/**
	 * Displays the form for the wordpress admin.
	 *
	 * @param array $instance Instance data.
	 *
	 * @return string|void
	 */
	public function form($instance)
	{
		// Get instance data
		$title = isset($instance['title']) ? esc_attr($instance['title']) : null;

		Render::output('widget/price_filter/form', [
			'title_id' => $this->get_field_id('title'),
			'title_name' => $this->get_field_name('title'),
			'title' => $title,
        ]);
	}
}

function filter($query)
{
	if (isset($_GET['max_price']) && isset($_GET['min_price'])) {
		if (!isset($query['meta_query'])) {
			$query['meta_query'] = [];
		}

		// TODO: How to support filtering using jigoshop_price() DB function?
		// TODO: Support for variable products
		$query['meta_query'][] = [
			'key' => 'regular_price',
			'value' => [$_GET['min_price'], $_GET['max_price']],
			'type' => 'NUMERIC',
			'compare' => 'BETWEEN'
        ];
	}

	return $query;
}

add_filter('jigoshop\query\product_list_base', '\Jigoshop\Widget\filter');
