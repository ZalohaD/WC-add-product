<?php

class My_Account_Pages {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('woocommerce_account_menu_items', [$this, 'add_my_account_menu_items']);
        add_action('init', [$this, 'register_endpoints']); // Змінив ім'я методу тут
        add_action('woocommerce_account_add-product_endpoint', [$this, 'add_product_page']);
        add_action('woocommerce_account_my-products_endpoint', [$this, 'my_products_page']);
    }

    public function register_endpoints() { // Перейменував метод
        add_rewrite_endpoint('add-product', EP_PAGES);
        add_rewrite_endpoint('my-products', EP_PAGES);
    }

    public function add_my_account_menu_items($items) {
        $items['add-product'] = __('Add Product', 'wp-add-product-woocommerce');
        $items['my-products'] = __('My Products', 'wp-add-product-woocommerce');
        return $items;
    }

    public function add_product_page() {
        include plugin_dir_path(__FILE__) . '../templates/add-product-form.php';
    }

    public function my_products_page() {
        include plugin_dir_path(__FILE__) . '../templates/my-products-list.php';
    }
}

My_Account_Pages::get_instance();
