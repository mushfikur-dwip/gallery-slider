# Car Gallery Shortcode Usage Guide

## শর্টকোড ব্যবহার করার নিয়ম

### ১. বেসিক ব্যবহার (Current Post এর Gallery)

যদি তুমি Car পোস্টের মধ্যে থাকো এবং সেই পোস্টের গ্যালারি দেখাতে চাও:

```
[car_gallery]
```

অথবা

```
[woogallery]
```

### ২. নির্দিষ্ট Post এর Gallery দেখানো

যেকোনো Car পোস্টের ID দিয়ে সেই পোস্টের গ্যালারি দেখাতে পারো:

```
[car_gallery id="123"]
```

অথবা

```
[woogallery post_id="123"]
```

**Post ID কিভাবে খুঁজবে:**
1. WordPress Admin → Car → All Cars
2. যে Car পোস্টের গ্যালারি দেখাতে চাও তার উপর মাউস নিয়ে যাও
3. Browser এর নিচে URL দেখবে, যেমন: `post.php?post=123&action=edit`
4. এখানে `123` হলো Post ID

### ৩. কাস্টম ACF Field দিয়ে

যদি তোমার ACF field এর নাম `car_gallery` না হয়ে অন্য কিছু হয়:

```
[car_gallery id="123" field="custom_gallery_name"]
```

## ব্যবহার করার জায়গা

### ১. Post/Page Content এ
সরাসরি WordPress Editor এ গিয়ে শর্টকোড লিখে দাও।

### ২. Widget এ
Appearance → Widgets → যেকোনো widget area তে Shortcode widget যোগ করে সেখানে ব্যবহার করো।

### ৩. PHP Template File এ
```php
<?php echo do_shortcode('[car_gallery id="123"]'); ?>
```

### ৪. Gutenberg Block এ
1. Shortcode block খুঁজে নাও
2. Block এ শর্টকোড লিখে দাও

### ৫. Elementor এ (যদি ব্যবহার করো)
1. Shortcode widget drag করো
2. Shortcode লিখে দাও

## উদাহরণ

### Example 1: Homepage এ Featured Car Gallery
```
[car_gallery id="45"]
```

### Example 2: Sidebar Widget এ
```
[car_gallery id="78"]
```

### Example 3: Page Template এ
```php
<?php
// Get latest car
$latest_car = get_posts(array(
    'post_type' => 'car',
    'posts_per_page' => 1
));

if ($latest_car) {
    echo do_shortcode('[car_gallery id="' . $latest_car[0]->ID . '"]');
}
?>
```

### Example 4: Multiple Galleries on Same Page
```
<h2>Car 1</h2>
[car_gallery id="10"]

<h2>Car 2</h2>
[car_gallery id="20"]

<h2>Car 3</h2>
[car_gallery id="30"]
```

## Features যা কাজ করবে

✅ **Swiper Slider** - Smooth sliding animation
✅ **Thumbnail Navigation** - নিচে/পাশে thumbnail gallery
✅ **Lightbox** - Full screen image view
✅ **Zoom** - Image zoom functionality
✅ **Lazy Loading** - Fast page load
✅ **Previous/Next Arrows** - Navigation arrows
✅ **Keyboard Navigation** - Arrow keys দিয়ে navigate
✅ **Touch Swipe** - Mobile এ swipe করে navigate
✅ **Responsive** - সব device এ কাজ করবে
✅ **RTL Support** - Right-to-left languages support

## Styling Customization

### CSS Override করার জন্য:

তোমার থিমের `style.css` অথবা Additional CSS এ যোগ করো:

```css
/* Gallery container */
.wcgs-woocommerce-product-gallery {
    max-width: 800px;
    margin: 0 auto;
}

/* Main image height */
.wcgs-slider-image-tag {
    max-height: 500px;
}

/* Thumbnail size */
.wcgs-thumb img {
    width: 100px;
    height: 100px;
}

/* Navigation arrows */
.spswiper-button-next,
.spswiper-button-prev {
    color: #c00;
}

/* Lightbox icon */
.wcgs-slider-lightbox {
    background: rgba(0,0,0,0.5);
}
```

## Settings

সব settings WooCommerce product এর মতোই কাজ করবে:

1. WordPress Admin → WooGallery → Settings
2. এখানে সব settings configure করো:
   - Gallery Layout (Horizontal/Vertical)
   - Thumbnail Position
   - Lightbox Settings
   - Zoom Settings
   - Arrow Style
   - Slider Animation
   - এবং আরো অনেক...

## Troubleshooting

### সমস্যা: Gallery দেখাচ্ছে না
**সমাধান:**
- ACF plugin active আছে কিনা চেক করো
- `car_gallery` field এ image আছে কিনা দেখো
- Post ID সঠিক দিয়েছো কিনা verify করো
- Browser console এ error আছে কিনা দেখো

### সমস্যা: Images লোড হচ্ছে না
**সমাধান:**
- Image গুলো media library তে আছে কিনা দেখো
- ACF field return format "Image Array" set করা আছে কিনা চেক করো
- Clear browser cache করো

### সমস্যা: Slider কাজ করছে না
**সমাধান:**
- jQuery conflict চেক করো
- Console এ JavaScript error আছে কিনা দেখো
- অন্য plugin deactivate করে test করো

### সমস্যা: Styling ভাঙা
**সমাধান:**
- CSS cache clear করো
- Theme compatibility চেক করো
- `!important` দিয়ে custom CSS override করার চেষ্টা করো

## Advanced Usage

### Dynamic Post ID with PHP
```php
<?php
// Show gallery for current user's first car
$user_cars = get_posts(array(
    'post_type' => 'car',
    'author' => get_current_user_id(),
    'posts_per_page' => 1
));

if ($user_cars) {
    echo do_shortcode('[car_gallery id="' . $user_cars[0]->ID . '"]');
}
?>
```

### Conditional Display
```php
<?php
// Only show if car has gallery
$car_id = 123;
if (class_exists('WCGS_Public_Acf') && WCGS_Public_Acf::has_gallery($car_id, 'car_gallery')) {
    echo do_shortcode('[car_gallery id="' . $car_id . '"]');
} else {
    echo '<p>No gallery available for this car.</p>';
}
?>
```

### Multiple Fields Support
```php
<?php
// Show different galleries
echo do_shortcode('[car_gallery id="123" field="exterior_images"]');
echo do_shortcode('[car_gallery id="123" field="interior_images"]');
echo do_shortcode('[car_gallery id="123" field="engine_images"]');
?>
```

## Performance Tips

1. **Lazy Load Enable করো** - Settings থেকে lazy loading চালু করো
2. **Image Optimize করো** - JPEG/WebP ব্যবহার করো
3. **Thumbnail Generate করো** - Proper thumbnail size use করো
4. **Caching Use করো** - Caching plugin ব্যবহার করো
5. **CDN Use করো** - Static files এর জন্য CDN ব্যবহার করো

## Support & Documentation

আরো জানতে চাইলে:
- Main Documentation: ACF_GALLERY_IMPLEMENTATION.md
- WooGallery Settings: WordPress Admin → WooGallery
- Plugin Help Page: WordPress Admin → WooGallery → Help
