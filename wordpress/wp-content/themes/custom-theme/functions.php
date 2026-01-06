<?php
if (!defined('ABSPATH')) {
    exit;
}

function custom_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus(array(
        'memberarea' => esc_html__('Member Area Navigation', 'custom-theme'),
        'footer'     => esc_html__('Footer Menu', 'custom-theme'),
    ));
}
add_action('after_setup_theme', 'custom_theme_setup');

function custom_theme_enqueue_scripts() {
    wp_enqueue_style(
        'custom-theme-style',
        get_stylesheet_uri(),
        array(),
        '1.0.0'
    );

    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    wp_enqueue_style(
        'memberarea-navigation',
        get_template_directory_uri() . '/assets/css/memberarea-navigation.css',
        array('custom-theme-style'),
        '1.0.0'
    );

    if (class_exists('WooCommerce')) {
        wp_enqueue_style(
            'custom-woocommerce',
            get_template_directory_uri() . '/woocommerce.css',
            array('custom-theme-style'),
            '1.0.0'
        );
    }

    wp_enqueue_script('jquery');

    wp_enqueue_script(
        'memberarea-navigation',
        get_template_directory_uri() . '/assets/js/memberarea-navigation.js',
        array('jquery'),
        '1.0.0',
        true
    );

    if (class_exists('WooCommerce')) {
        wp_localize_script('memberarea-navigation', 'memberarea_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('memberarea_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'custom_theme_enqueue_scripts');

class Custom_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        // Get icon from menu item custom field or description
        $icon = '';
        if (!empty($item->description)) {
            $icon = '<i class="' . esc_attr($item->description) . '"></i> ';
        }

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $icon . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

function custom_get_cart_count() {
    check_ajax_referer('memberarea_nonce', 'nonce');

    if (class_exists('WooCommerce')) {
        $cart_count = WC()->cart->get_cart_contents_count();
        wp_send_json_success(array('count' => $cart_count));
    } else {
        wp_send_json_error(array('message' => 'WooCommerce not active'));
    }
}
add_action('wp_ajax_get_cart_count', 'custom_get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'custom_get_cart_count');


function custom_woocommerce_header_add_to_cart_fragment($fragments) {
    if (class_exists('WooCommerce')) {
        $cart_count = WC()->cart->get_cart_contents_count();

        ob_start();
        ?>
        <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
    }

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'custom_woocommerce_header_add_to_cart_fragment');

function custom_menu_item_custom_fields($item_id, $item, $depth, $args) {
    $icon_value = get_post_meta($item_id, '_menu_item_icon', true);
    if (empty($icon_value)) {
        $icon_value = $item->description;
    }
    ?>
    <p class="field-icon description description-wide">
        <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Font Awesome Icon Class', 'custom-theme'); ?><br />
            <input type="text"
                   id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>"
                   class="widefat code edit-menu-item-icon"
                   name="menu-item-icon[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($icon_value); ?>" />
            <span class="description"><?php esc_html_e('z.B.: fas fa-home', 'custom-theme'); ?></span>
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'custom_menu_item_custom_fields', 10, 4);

function custom_update_nav_menu_item($menu_id, $menu_item_db_id, $args) {
    // Check if our custom icon field was submitted
    if (isset($_POST['menu-item-icon'][$menu_item_db_id])) {
        $icon_value = sanitize_text_field($_POST['menu-item-icon'][$menu_item_db_id]);

        update_post_meta($menu_item_db_id, '_menu_item_icon', $icon_value);

        global $wpdb;
        $wpdb->update(
            $wpdb->posts,
            array('post_excerpt' => $icon_value),
            array('ID' => $menu_item_db_id),
            array('%s'),
            array('%d')
        );
    }
}
add_action('wp_update_nav_menu_item', 'custom_update_nav_menu_item', 10, 3);

function custom_setup_nav_menu_item($menu_item) {
    $icon = get_post_meta($menu_item->ID, '_menu_item_icon', true);
    if (!empty($icon)) {
        $menu_item->description = $icon;
    }
    return $menu_item;
}
add_filter('wp_setup_nav_menu_item', 'custom_setup_nav_menu_item');

function custom_check_login_attempts($user, $username, $password) {
    if (empty($username) || empty($password)) {
        return $user;
    }

    $transient = 'login_attempts_' . sanitize_user($username);
    $attempts = get_transient($transient);

    if ($attempts && $attempts >= 5) {
        return new WP_Error('too_many_attempts',
            __('Zu viele Anmeldeversuche. Bitte versuchen Sie es später erneut.', 'custom-theme')
        );
    }

    return $user;
}
add_filter('authenticate', 'custom_check_login_attempts', 30, 3);

function custom_track_failed_login($username) {
    $transient = 'login_attempts_' . sanitize_user($username);
    $attempts = get_transient($transient);
    $attempts = $attempts ? $attempts + 1 : 1;

    set_transient($transient, $attempts, 15 * MINUTE_IN_SECONDS);
}
add_action('wp_login_failed', 'custom_track_failed_login');

function custom_clear_login_attempts($user_login, $user) {
    $transient = 'login_attempts_' . sanitize_user($user_login);
    delete_transient($transient);
}
add_action('wp_login', 'custom_clear_login_attempts', 10, 2);
