<?php
if (!defined('ABSPATH')) {
    exit;
}

$current_user_id = get_current_user_id();

$args = [
    'post_type' => 'product',
    'author' => $current_user_id,
    'posts_per_page' => -1,
    'post_status' => ['pending','published','private']
];

$products = new WP_Query($args);
?>

<h2><?php _e('My Products', 'wp-add-product-woocommerce'); ?></h2>

<?php if ($products->have_posts()): ?>
    <table>
        <tr>
            <th><?php _e('Name', 'wp-add-product-woocommerce'); ?></th>
            <th><?php _e('Stock', 'wp-add-product-woocommerce'); ?></th>
            <th><?php _e('Price', 'wp-add-product-woocommerce'); ?></th>
            <th><?php _e('Actions', 'wp-add-product-woocommerce'); ?></th>
        </tr>
        <?php while ($products->have_posts()): $products->the_post(); ?>
            <tr>
                <td><?php the_title(); ?></td>
                <td><?php echo get_post_meta(get_the_ID(), '_stock', true); ?></td>
                <td><?php echo get_post_meta(get_the_ID(), '_price', true); ?> $</td>
                <td>
                    <button class="delete-product" data-id="<?php the_ID(); ?>">
                        <?php _e('Delete', 'wp-add-product-woocommerce'); ?>
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p><?php _e('No products found.', 'wp-add-product-woocommerce'); ?></p>
<?php endif; ?>

<script>
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('<?php _e('Are you sure?', 'wp-add-product-woocommerce'); ?>')) return;

            let productId = this.getAttribute('data-id');

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'delete_product',
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
        });
    });
</script>
