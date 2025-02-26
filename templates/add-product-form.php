<?php
if (!is_user_logged_in()) {
    echo __('You must log in to add product.', 'wp-add-product-woocommerce');
    return;
}

$product_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$product = get_post($product_id);

$name = $product ? get_the_title($product) : '';
$description = $product ? $product->post_content : '';
$price = $product ? get_post_meta($product->ID, '_price', true) : '';
$quantity = $product ? get_post_meta($product->ID, '_stock', true) : '';
$image_id = $product ? get_post_thumbnail_id($product->ID) : '';
$image_url = $image_id ? wp_get_attachment_url($image_id) : '';

?>

<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: flex; flex-direction: column; gap:15px;">
    <?php wp_nonce_field('save_product', 'wp_my_product_nonce'); ?>
    <input type="hidden" name="action" value="add_new_product">
    <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">

    <input type="text" name="product_name" placeholder="Назва товару" value="<?php echo esc_attr($name); ?>" required>
    <input type="number" name="product_price" placeholder="Ціна" step="0.01" value="<?php echo esc_attr($price); ?>" required>
    <input type="number" name="product_quantity" placeholder="Кількість" value="<?php echo esc_attr($quantity); ?>" required>

    <label><?php _e('Description', 'wp-add-product-woocommerce'); ?></label>
    <?php wp_editor($description, 'product_description'); ?>

    <label><?php _e('Image', 'wp-add-product-woocommerce'); ?></label>
    <input type="hidden" name="product_image" id="product_image" value="<?php echo esc_attr($image_id); ?>">
    <img id="product_image_preview" src="<?php echo esc_url($image_url); ?>" style="max-width: 100px;">
    <button type="button" id="upload_image"><?php _e('Choose image', 'wp-add-product-woocommerce'); ?></button>

    <input type="submit" name="save_product" value="<?php _e('Save product', 'wp-add-product-woocommerce'); ?>">
</form>

<script>
    jQuery(document).ready(function($) {
        $('#upload_image').click(function(e) {
            e.preventDefault();
            let frame = wp.media({
                title: '<?php _e("Choose image", "wp-add-product-woocommerce"); ?>',
                multiple: false
            }).open().on('select', function() {
                let attachment = frame.state().get('selection').first().toJSON();
                $('#product_image').val(attachment.id);
                $('#product_image_preview').attr('src', attachment.url);
            });
        });
    });
</script>
