<?php
/**
 * Advanced Debug for Car Gallery
 * 
 * এটা temporarily use করো exact problem খুঁজে বের করার জন্য
 */

// Add to functions.php
function advanced_car_gallery_debug() {
    if (!is_singular('car')) {
        return;
    }
    
    $post_id = get_the_ID();
    
    echo '<div style="background: #fff3cd; padding: 20px; margin: 20px; border: 3px solid #856404;">';
    echo '<h2 style="color: #856404;">🔍 Advanced Car Gallery Debug</h2>';
    
    // Check 1: ACF Data
    echo '<h3>1️⃣ ACF Gallery Data:</h3>';
    if (function_exists('get_field')) {
        $acf_images = get_field('car_gallery', $post_id);
        if ($acf_images) {
            echo '<p style="color: green;">✅ ACF has ' . count($acf_images) . ' images</p>';
            echo '<p><strong>Image IDs:</strong> ';
            $ids = array();
            foreach ($acf_images as $img) {
                $ids[] = isset($img['ID']) ? $img['ID'] : $img;
            }
            echo implode(', ', $ids) . '</p>';
        } else {
            echo '<p style="color: red;">❌ No ACF images found</p>';
        }
    } else {
        echo '<p style="color: red;">❌ ACF not active</p>';
    }
    
    // Check 2: WCGS_Public_Acf class
    echo '<h3>2️⃣ WCGS_Public_Acf Class:</h3>';
    if (class_exists('WCGS_Public_Acf')) {
        echo '<p style="color: green;">✅ WCGS_Public_Acf class exists</p>';
        
        $is_supported = WCGS_Public_Acf::is_supported_post_type('car');
        echo '<p>Car post type supported: ' . ($is_supported ? '✅ Yes' : '❌ No') . '</p>';
        
        $field_name = WCGS_Public_Acf::get_field_name_for_post_type('car');
        echo '<p>Field name: ' . ($field_name ? '<strong>' . $field_name . '</strong>' : '❌ Not set') . '</p>';
        
        $extracted_ids = WCGS_Public_Acf::get_gallery_images($post_id, 'car_gallery');
        echo '<p>Extracted IDs: ';
        if (!empty($extracted_ids)) {
            echo '<span style="color: green;">✅ ' . implode(', ', $extracted_ids) . '</span>';
        } else {
            echo '<span style="color: red;">❌ Empty</span>';
        }
        echo '</p>';
    } else {
        echo '<p style="color: red;">❌ WCGS_Public_Acf class NOT found</p>';
    }
    
    // Check 3: Helper class and wcgs_image_meta
    echo '<h3>3️⃣ Image Meta Processing:</h3>';
    if (class_exists('WCGS_Public_Helper')) {
        echo '<p style="color: green;">✅ WCGS_Public_Helper exists</p>';
        
        $helper = new WCGS_Public_Helper();
        $settings = get_option('wcgs_settings', array());
        
        // Test with first image
        if (!empty($ids) && isset($ids[0])) {
            $test_id = $ids[0];
            echo '<p><strong>Testing Image ID ' . $test_id . ':</strong></p>';
            
            $meta = $helper->wcgs_image_meta($test_id, $settings);
            
            if ($meta) {
                echo '<pre style="background: white; padding: 10px; overflow: auto;">';
                print_r($meta);
                echo '</pre>';
                
                if (isset($meta['url']) && !empty($meta['url'])) {
                    echo '<p style="color: green;">✅ Image URL generated: ' . esc_url($meta['url']) . '</p>';
                    echo '<img src="' . esc_url($meta['url']) . '" style="max-width: 200px; border: 2px solid green;" />';
                } else {
                    echo '<p style="color: red;">❌ No URL in meta</p>';
                }
            } else {
                echo '<p style="color: red;">❌ wcgs_image_meta returned NULL</p>';
                
                // Manual test
                $manual_url = wp_get_attachment_url($test_id);
                echo '<p>Manual URL test: ';
                if ($manual_url) {
                    echo '<span style="color: green;">✅ ' . esc_url($manual_url) . '</span>';
                    echo '<br><img src="' . esc_url($manual_url) . '" style="max-width: 200px;" />';
                } else {
                    echo '<span style="color: red;">❌ Failed</span>';
                }
                echo '</p>';
            }
        }
    } else {
        echo '<p style="color: red;">❌ WCGS_Public_Helper NOT found</p>';
    }
    
    // Check 4: Gallery Class
    echo '<h3>4️⃣ Gallery Class Check:</h3>';
    if (class_exists('WCGS_Product_Gallery')) {
        echo '<p style="color: green;">✅ WCGS_Product_Gallery class exists</p>';
    } else {
        echo '<p style="color: red;">❌ WCGS_Product_Gallery NOT found</p>';
    }
    
    // Check 5: Scripts and Styles
    echo '<h3>5️⃣ Scripts & Styles:</h3>';
    global $wp_scripts, $wp_styles;
    
	// Correct handles based on plugin code
	$required_scripts = array('gallery-slider-for-woocommerce', 'wcgs-swiper');
	$required_styles = array('gallery-slider-for-woocommerce', 'wcgs-swiper', 'wcgs-fancybox');
	
	echo '<p><strong>Scripts:</strong></p><ul>';
	foreach ($required_scripts as $script) {
		$registered = isset($wp_scripts->registered[$script]);
		$enqueued = wp_script_is($script, 'enqueued');
		if ($enqueued) {
			echo '<li style="color: green;">✅ ' . $script . ' (enqueued)</li>';
		} elseif ($registered) {
			echo '<li style="color: orange;">⚠️ ' . $script . ' (registered but not enqueued)</li>';
		} else {
			echo '<li style="color: red;">❌ ' . $script . ' (NOT loaded)</li>';
		}
	}
	echo '</ul>';
	
	echo '<p><strong>Styles:</strong></p><ul>';
	foreach ($required_styles as $style) {
		$registered = isset($wp_styles->registered[$style]);
		$enqueued = wp_style_is($style, 'enqueued');
		if ($enqueued) {
			echo '<li style="color: green;">✅ ' . $style . ' (enqueued)</li>';
		} elseif ($registered) {
			echo '<li style="color: orange;">⚠️ ' . $style . ' (registered but not enqueued)</li>';
    echo '</ul>';
    
    // Check 6: Check if gallery HTML is in page
    echo '<h3>6️⃣ Gallery HTML Check:</h3>';
    echo '<p>Look for <code>#wpgs-gallery</code> element in page source</p>';
    echo '<p>If you see it, gallery is rendering. If images are broken, it\'s a data problem.</p>';
    
    echo '</div>';
}
add_action('wp_footer', 'advanced_car_gallery_debug', 999);

// Also add error logging
function log_car_gallery_errors($message) {
    if (WP_DEBUG && WP_DEBUG_LOG) {
        error_log('CAR_GALLERY_DEBUG: ' . $message);
    }
}
