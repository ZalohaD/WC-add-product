<?php
/**
 * Plugin Name: WP Add Product WooCommerce
 * Description: Тестове завдання для Webspark
 * Version: 1.0.0
 * Author: Zaloha Denys
 * Text Domain: wp-add-product-woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-product-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-my-account-pages.php';

class WP_Add_Product_WooCommerce {
    public function __construct() {
        add_action('plugins_loaded', [$this, 'check_woocommerce']);
        add_action('init', [$this, 'initialize']);
    }

    public function check_woocommerce() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p>' . __('WP Add Product Woocommerce require active Woocommerce!', 'wp-add-product-woocommerce') . '</p></div>';
            });
            deactivate_plugins(plugin_basename(__FILE__));
        }
    }

    public function initialize() {
        My_Account_Pages::get_instance();
    }
}

new WP_Add_Product_WooCommerce();

register_activation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});
