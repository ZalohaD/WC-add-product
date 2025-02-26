<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$args = [
    'post_type'   => 'product',
    'post_status' => 'any',
    'meta_query'  => [
        [
            'key'   => '_created_by_webspark',
            'value' => '1',
        ],
    ],
    'fields' => 'ids',
];
$products = get_posts($args);
foreach ($products as $product_id) {
    wp_delete_post($product_id, true);
}
