# ACF Gallery Support for Car Post Type

## Overview
This modification adds support for displaying ACF (Advanced Custom Fields) gallery slider on the `Car` custom post type, using the same slider functionality as WooCommerce products.

## Changes Made

### 1. New Files Created

#### `/public/partials/class/class-public-acf-gallery.php`
- **Purpose**: Handle ACF gallery field detection and retrieval
- **Key Methods**:
  - `is_acf_active()`: Check if ACF plugin is installed
  - `get_gallery_images($post_id, $field_name)`: Retrieve ACF gallery images
  - `has_gallery($post_id, $field_name)`: Check if post has gallery
  - `is_supported_post_type($post_type)`: Validate supported post types
  - `get_field_name_for_post_type($post_type)`: Get field name for specific post type

### 2. Modified Files

#### `/public/partials/product-images.php` (WCGS_Product_Gallery class)
**Constructor Changes**:
- Now supports both WooCommerce products and custom post types
- Checks if `$product` exists before accessing WooCommerce methods
- Falls back to `get_the_ID()` and `get_post_type()` for custom post types

**New Method**:
- `add_acf_gallery_images()`: Retrieves images from ACF gallery field and formats them using the existing `wcgs_image_meta()` helper

**Modified Method**:
- `build_gallery()`: 
  - Detects if post type is supported for ACF gallery
  - Routes to ACF gallery processing for Car post type
  - Routes to WooCommerce gallery for products
  - Fallback to featured image if gallery is empty

#### `/public/class-woo-gallery-slider-public.php` (Woo_Gallery_Slider_Public class)
**Constructor Changes**:
- Added `the_content` filter hook to display gallery on Car single posts

**New Method**:
- `wcgs_display_car_gallery($content)`: 
  - Checks if viewing single Car post
  - Verifies ACF gallery exists
  - Prepends gallery HTML before post content

**Modified Methods**:
- `wcgs_body_class($classes)`: Now adds 'wcgs-gallery-slider' class for both products and Car posts
- `enqueue_scripts()`: 
  - Updated to load scripts on `is_singular('car')` in addition to products
  - Only calls `wcgs_json_data()` for WooCommerce products (Car posts don't have variations)

## Configuration

### ACF Field Setup
The implementation expects an ACF Gallery field with the following configuration:
- **Field Name**: `car_gallery`
- **Field Type**: Gallery
- **Post Type**: Car
- **Return Format**: Image Array (ID, url, alt, etc.)

### Supported Post Types
Currently configured for:
- **Post Type**: `car`
- **ACF Field**: `car_gallery`

To add more post types, use the filters:
```php
// Add more supported post types
add_filter( 'wcgs_acf_supported_post_types', function( $post_types ) {
    $post_types[] = 'portfolio';
    $post_types[] = 'project';
    return $post_types;
});

// Map field names to post types
add_filter( 'wcgs_acf_field_names', function( $field_names ) {
    $field_names['portfolio'] = 'portfolio_gallery';
    $field_names['project'] = 'project_images';
    return $field_names;
});
```

## Features

### Included Features (Same as WooCommerce Products)
✅ Swiper.js slider
✅ Lightbox (Fancybox)
✅ Thumbnail navigation
✅ Zoom functionality
✅ Lazy loading
✅ Video support (if video URLs are attached to images)
✅ RTL support
✅ Responsive design
✅ All existing slider settings

### How It Works

1. **Detection**: When viewing a single Car post, the plugin checks if ACF is active and if the `car_gallery` field has images

2. **Gallery Build**: The ACF gallery images are retrieved and converted to the plugin's internal gallery format

3. **Display**: The gallery slider is automatically prepended to the post content with all the same features as WooCommerce products

4. **Assets**: All necessary CSS and JavaScript files are enqueued automatically on Car single pages

## Testing Checklist

- [ ] Create a Car post with ACF gallery field populated
- [ ] View the Car single page
- [ ] Verify gallery displays above content
- [ ] Test slider navigation (prev/next arrows)
- [ ] Test thumbnail navigation (if enabled)
- [ ] Test lightbox functionality
- [ ] Test zoom feature (if enabled)
- [ ] Test on mobile devices
- [ ] Verify lazy loading works
- [ ] Check browser console for errors

## Requirements

- **Required**: Advanced Custom Fields (ACF) plugin - Free or Pro version
- **Required**: ACF Gallery field named `car_gallery` on Car post type
- **Optional**: WooCommerce (for product functionality, not required for Car posts)

## Compatibility

- Maintains full backward compatibility with existing WooCommerce product galleries
- Does not affect any WooCommerce functionality
- Can coexist with both features running simultaneously

## Troubleshooting

### Gallery Not Showing
1. Verify ACF plugin is active
2. Check that `car_gallery` field exists and has images
3. Confirm post type is exactly `car` (case-sensitive)
4. Clear any caching plugins

### Styles Not Loading
1. Check browser console for 404 errors
2. Verify CSS/JS files exist in `public/css/` and `public/js/`
3. Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)

### Images Not Displaying
1. Verify ACF field return format is set to "Image Array"
2. Check that images are properly uploaded to WordPress media library
3. Confirm image IDs are valid

## Future Enhancements

Possible additions:
- Admin settings panel for ACF gallery configuration
- Shortcode support: `[woogallery post_id="123"]` for custom placement
- Widget support for displaying galleries in sidebars
- Gutenberg block for gallery display
- Multiple gallery fields per post type
- Custom gallery templates per post type
