<?php
if (!defined('ABSPATH')) {
    exit;
}

class Product_Handler {
    public function __construct() {
        add_action('admin_post_add_new_product', [$this, 'add_new_product']);
        add_action('admin_post_nopriv_add_new_product', [$this, 'add_new_product']);
        add_action('wp_ajax_delete_product', [$this, 'delete_product']);
    }

    public function add_new_product() {
        if (!is_user_logged_in()) {
            wp_die(__('You should log in!', 'wp-add-product-woocommerce'));
        }

        if (!isset($_POST['wp_my_product_nonce']) || !wp_verify_nonce($_POST['wp_my_product_nonce'], 'save_product')) {
            wp_die(__('Wrong request!', 'wp-add-product-woocommerce'));
        }

        $product_id = !empty($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $product_name = sanitize_text_field($_POST['product_name']);
        $price = floatval($_POST['product_price']);
        $stock = intval($_POST['product_quantity']);
        $description = wp_kses_post($_POST['product_description']);
        $image_id = !empty($_POST['product_image']) ? intval($_POST['product_image']) : 0;

        if (empty($product_name) || $price <= 0 || $stock < 0) {
            wp_die(__('Wrong data', 'wp-add-product-woocommerce'));
        }

        if ($product_id) {
            $product = wc_get_product($product_id);
            if (!$product || $product->get_author() !== get_current_user_id()) {
                wp_die(__('Wrong product!', 'wp-add-product-woocommerce'));
            }
        } else {
            $product = new WC_Product_Simple();
        }

        $product->set_name($product_name);
        $product->set_price($price);
        $product->set_regular_price($price);
        $product->set_stock_quantity($stock);
        $product->set_manage_stock(true);
        $product->set_status('pending');

        $product->set_description($description);

        if ($image_id) {
            $product->set_image_id($image_id);
        }

        $product_id = $product->save();

        if ($product_id) {
            wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')) . 'my-products');
            exit;
        } else {
            wp_die(__('Error while we adding product', 'wp-add-product-woocommerce'));
        }
    }

    public function delete_product() {
        if (!is_user_logged_in()) {
            wp_send_json(['success' => false, 'message' => __('You should log in!', 'wp-add-product-woocommerce')]);
        }

        $product_id = intval($_POST['product_id']);
        $product = get_post($product_id);

        if (!$product || $product->post_type !== 'product' || $product->post_author != get_current_user_id()) {
            wp_send_json(['success' => false, 'message' => __('Wrong product!', 'wp-add-product-woocommerce')]);
        }

        wp_delete_post($product_id, true);
        wp_send_json(['success' => true, 'message' => __('Product added successfully!', 'wp-add-product-woocommerce')]);
    }
}

new Product_Handler();
