<?php
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="memberarea-header" class="memberarea-navigation">
    <div class="nav-container">
        <div class="nav-logo">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/wordpress-logo.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                </a>
            <?php endif; ?>
        </div>

        <nav class="nav-main" role="navigation">
            <?php
            wp_nav_menu( array(
                'theme_location'  => 'memberarea',
                'menu_class'      => 'memberarea-menu',
                'container'       => false,
                'fallback_cb'     => false,
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'walker'          => new Custom_Nav_Walker(),
            ) );
            ?>
        </nav>

        <div class="nav-user-section">
            <?php if ( is_user_logged_in() ) :
                $current_user = wp_get_current_user();
                $user_display_name = $current_user->display_name;
                $user_avatar = get_avatar_url( $current_user->ID, array( 'size' => 40 ) );
            ?>

                <div class="nav-user-profile">
                    <button class="user-profile-toggle" aria-expanded="false" aria-haspopup="true">
                        <img src="<?php echo esc_url( $user_avatar ); ?>" alt="<?php echo esc_attr( $user_display_name ); ?>" class="user-avatar">
                        <span class="user-greeting">Hallo <?php echo esc_html( $user_display_name ); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="user-dropdown-menu" aria-hidden="true">
                        <ul>
                            <?php if ( class_exists( 'WooCommerce' ) ) :
                                $cart_count = WC()->cart->get_cart_contents_count();
                            ?>
                                <li>
                                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                        <?php esc_html_e( 'Warenkorb', 'custom-theme' ); ?>
                                        <?php if ( $cart_count > 0 ) : ?>
                                            <span class="menu-cart-count">(<?php echo esc_html( $cart_count ); ?>)</span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
                                        <i class="fas fa-user"></i>
                                        <?php esc_html_e( 'Mein Konto', 'custom-theme' ); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
                                        <i class="fas fa-box"></i>
                                        <?php esc_html_e( 'Bestellungen', 'custom-theme' ); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>">
                                        <i class="fas fa-cog"></i>
                                        <?php esc_html_e( 'Einstellungen', 'custom-theme' ); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                            <?php endif; ?>
                            <li>
                                <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <?php esc_html_e( 'Abmelden', 'custom-theme' ); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            <?php else : ?>
                <div class="nav-login">
                    <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="login-link">
                        <i class="fas fa-sign-in-alt"></i>
                        <?php esc_html_e( 'Anmelden', 'custom-theme' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
