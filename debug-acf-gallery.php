<?php
/**
 * ACF Gallery Debug Helper
 * 
 * এই ফাইলটি temporarily use করো debug করার জন্য
 * Car post এ ACF gallery কি data return করছে সেটা দেখার জন্য
 */

// Add this to your Car single template or use as shortcode [debug_car_gallery]
function debug_car_gallery_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'id' => get_the_ID(),
        ),
        $atts
    );

    $post_id = $atts['id'];
    
    // Check if ACF is active
    if ( ! function_exists( 'get_field' ) ) {
        return '<div style="background: #f44336; color: white; padding: 20px; margin: 20px 0;">ACF plugin is not active!</div>';
    }

    // Get the gallery field
    $gallery = get_field( 'car_gallery', $post_id );
    
    ob_start();
    ?>
    <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border: 2px solid #333;">
        <h3>ACF Gallery Debug - Post ID: <?php echo esc_html( $post_id ); ?></h3>
        
        <h4>Post Type: <?php echo esc_html( get_post_type( $post_id ) ); ?></h4>
        
        <h4>Gallery Field Data:</h4>
        <?php if ( empty( $gallery ) ) : ?>
            <p style="color: red; font-weight: bold;">❌ Gallery is EMPTY!</p>
            <p>Possible reasons:</p>
            <ul>
                <li>ACF field 'car_gallery' doesn't exist</li>
                <li>No images uploaded to the gallery</li>
                <li>Wrong post ID</li>
            </ul>
        <?php else : ?>
            <p style="color: green; font-weight: bold;">✅ Gallery has data</p>
            <p><strong>Total Images:</strong> <?php echo count( $gallery ); ?></p>
            
            <h4>Gallery Data Structure:</h4>
            <pre style="background: white; padding: 10px; overflow: auto; max-height: 300px;"><?php print_r( $gallery ); ?></pre>
            
            <h4>First Image Details:</h4>
            <?php if ( isset( $gallery[0] ) ) : ?>
                <?php $first_image = $gallery[0]; ?>
                <?php if ( is_array( $first_image ) ) : ?>
                    <p><strong>Return Format:</strong> Image Array</p>
                    <ul>
                        <li><strong>ID:</strong> <?php echo isset( $first_image['ID'] ) ? $first_image['ID'] : 'N/A'; ?></li>
                        <li><strong>URL:</strong> <?php echo isset( $first_image['url'] ) ? esc_url( $first_image['url'] ) : 'N/A'; ?></li>
                        <li><strong>Width:</strong> <?php echo isset( $first_image['width'] ) ? $first_image['width'] : 'N/A'; ?></li>
                        <li><strong>Height:</strong> <?php echo isset( $first_image['height'] ) ? $first_image['height'] : 'N/A'; ?></li>
                    </ul>
                    <?php if ( isset( $first_image['url'] ) ) : ?>
                        <img src="<?php echo esc_url( $first_image['url'] ); ?>" style="max-width: 200px; border: 2px solid #4CAF50;" />
                    <?php endif; ?>
                <?php elseif ( is_numeric( $first_image ) ) : ?>
                    <p><strong>Return Format:</strong> Image ID</p>
                    <p><strong>ID:</strong> <?php echo $first_image; ?></p>
                    <?php $image_url = wp_get_attachment_url( $first_image ); ?>
                    <?php if ( $image_url ) : ?>
                        <p><strong>URL:</strong> <?php echo esc_url( $image_url ); ?></p>
                        <img src="<?php echo esc_url( $image_url ); ?>" style="max-width: 200px; border: 2px solid #4CAF50;" />
                    <?php else : ?>
                        <p style="color: red;">❌ Image URL not found for ID: <?php echo $first_image; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            
            <h4>Extracted Image IDs:</h4>
            <?php
            $image_ids = array();
            foreach ( $gallery as $image ) {
                if ( is_array( $image ) && isset( $image['ID'] ) ) {
                    $image_ids[] = $image['ID'];
                } elseif ( is_numeric( $image ) ) {
                    $image_ids[] = $image;
                }
            }
            ?>
            <p><?php echo implode( ', ', $image_ids ); ?></p>
            
            <h4>Image Validation:</h4>
            <?php foreach ( $image_ids as $img_id ) : ?>
                <?php $is_valid = wp_attachment_is_image( $img_id ); ?>
                <p>
                    ID <?php echo $img_id; ?>: 
                    <?php if ( $is_valid ) : ?>
                        <span style="color: green;">✅ Valid Image</span>
                    <?php else : ?>
                        <span style="color: red;">❌ Not a valid image attachment</span>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <h4>Featured Image:</h4>
        <?php $featured_id = get_post_thumbnail_id( $post_id ); ?>
        <?php if ( $featured_id ) : ?>
            <p style="color: green;">✅ Featured image exists (ID: <?php echo $featured_id; ?>)</p>
            <?php $featured_url = wp_get_attachment_url( $featured_id ); ?>
            <?php if ( $featured_url ) : ?>
                <img src="<?php echo esc_url( $featured_url ); ?>" style="max-width: 150px; border: 2px solid #2196F3;" />
            <?php endif; ?>
        <?php else : ?>
            <p style="color: red;">❌ No featured image</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'debug_car_gallery', 'debug_car_gallery_shortcode' );

/**
 * How to use:
 * 
 * 1. Add this code to your theme's functions.php
 * 2. Go to any Car post or page
 * 3. Add shortcode: [debug_car_gallery]
 * 4. Or for specific post: [debug_car_gallery id="123"]
 * 5. This will show you exactly what ACF is returning
 */
