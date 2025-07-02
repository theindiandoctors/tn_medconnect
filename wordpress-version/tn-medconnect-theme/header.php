<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-background font-source-sans'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php _e('Skip to content', 'tn-medconnect'); ?></a>

    <header id="masthead" class="site-header bg-white shadow-clinical border-b border-border sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo and Site Title -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <svg class="h-10 w-10 text-primary" viewBox="0 0 40 40" fill="currentColor">
                                <path d="M20 2C10.06 2 2 10.06 2 20s8.06 18 18 18 18-8.06 18-18S29.94 2 20 2zm0 32c-7.73 0-14-6.27-14-14S12.27 6 20 6s14 6.27 14 14-6.27 14-14 14z"/>
                                <path d="M20 10c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2s2-.9 2-2v-6c0-1.1-.9-2-2-2zm0 12c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                <circle cx="20" cy="15" r="3" fill="white"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-inter font-bold text-primary">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                        <p class="text-xs text-text-secondary"><?php _e('Medical Excellence Network', 'tn-medconnect'); ?></p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class' => 'flex items-baseline space-x-4',
                            'container' => false,
                            'fallback_cb' => false,
                            'items_wrap' => '%3$s',
                            'walker' => new TN_MedConnect_Nav_Walker(),
                        ));
                        ?>
                        
                        <!-- Medical Specialties Menu -->
                        <?php
                        $specialties = get_terms(array(
                            'taxonomy' => 'medical_specialty',
                            'hide_empty' => false,
                            'number' => 5,
                        ));
                        
                        if (!empty($specialties) && !is_wp_error($specialties)):
                        ?>
                            <div class="relative group">
                                <button class="text-text-secondary hover:text-primary px-3 py-2 rounded-md text-sm transition-clinical flex items-center">
                                    <?php _e('Specialties', 'tn-medconnect'); ?>
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                </button>
                                <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-clinical-lg border border-border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <div class="py-1">
                                        <?php foreach ($specialties as $specialty): ?>
                                            <a href="<?php echo esc_url(get_term_link($specialty)); ?>" 
                                               class="block px-4 py-2 text-sm text-text-secondary hover:text-primary hover:bg-primary-50 transition-colors duration-200">
                                                <?php echo esc_html($specialty->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                        <a href="<?php echo esc_url(home_url('/medical-specialties/')); ?>" 
                                           class="block px-4 py-2 text-sm text-primary font-semibold hover:bg-primary-50 transition-colors duration-200">
                                            <?php _e('View All Specialties', 'tn-medconnect'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Authentication Buttons -->
                        <div class="flex items-center space-x-2">
                            <?php if (is_user_logged_in()): ?>
                                <?php
                                $current_user = wp_get_current_user();
                                $user_specialty = get_user_meta($current_user->ID, 'medical_specialty', true);
                                ?>
                                <div class="relative group">
                                    <button class="flex items-center space-x-2 text-text-secondary hover:text-primary px-3 py-2 rounded-md text-sm transition-clinical">
                                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                            <span class="text-primary font-bold text-sm">
                                                <?php echo esc_html(strtoupper(substr($current_user->display_name, 0, 1))); ?>
                                            </span>
                                        </div>
                                        <span><?php echo esc_html($current_user->display_name); ?></span>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                        </svg>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-clinical-lg border border-border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <div class="py-1">
                                            <a href="<?php echo esc_url(home_url('/professional-dashboard/')); ?>" 
                                               class="block px-4 py-2 text-sm text-text-secondary hover:text-primary hover:bg-primary-50 transition-colors duration-200">
                                                <?php _e('Professional Dashboard', 'tn-medconnect'); ?>
                                            </a>
                                            <a href="<?php echo esc_url(home_url('/my-profile/')); ?>" 
                                               class="block px-4 py-2 text-sm text-text-secondary hover:text-primary hover:bg-primary-50 transition-colors duration-200">
                                                <?php _e('My Profile', 'tn-medconnect'); ?>
                                            </a>
                                            <a href="<?php echo esc_url(home_url('/my-cme/')); ?>" 
                                               class="block px-4 py-2 text-sm text-text-secondary hover:text-primary hover:bg-primary-50 transition-colors duration-200">
                                                <?php _e('My CME', 'tn-medconnect'); ?>
                                            </a>
                                            <a href="<?php echo esc_url(home_url('/my-connections/')); ?>" 
                                               class="block px-4 py-2 text-sm text-text-secondary hover:text-primary hover:bg-primary-50 transition-colors duration-200">
                                                <?php _e('My Connections', 'tn-medconnect'); ?>
                                            </a>
                                            <hr class="my-1">
                                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" 
                                               class="block px-4 py-2 text-sm text-error hover:text-error-dark hover:bg-error-50 transition-colors duration-200">
                                                <?php _e('Sign Out', 'tn-medconnect'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="<?php echo esc_url(home_url('/login/')); ?>" 
                                   class="text-text-secondary hover:text-primary px-3 py-2 rounded-md text-sm transition-clinical">
                                    <?php _e('Sign In', 'tn-medconnect'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/signup/')); ?>" 
                                   class="btn-primary text-sm px-4 py-2">
                                    <?php _e('Join Platform', 'tn-medconnect'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-text-secondary hover:text-primary p-2" id="mobile-menu-toggle">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'space-y-1',
                        'container' => false,
                        'fallback_cb' => false,
                        'items_wrap' => '%3$s',
                        'walker' => new TN_MedConnect_Mobile_Nav_Walker(),
                    ));
                    ?>
                    
                    <!-- Mobile Authentication -->
                    <div class="pt-4 border-t border-border">
                        <?php if (is_user_logged_in()): ?>
                            <a href="<?php echo esc_url(home_url('/professional-dashboard/')); ?>" 
                               class="block px-3 py-2 text-base font-medium text-text-secondary hover:text-primary hover:bg-primary-50 rounded-md transition-colors duration-200">
                                <?php _e('Professional Dashboard', 'tn-medconnect'); ?>
                            </a>
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" 
                               class="block px-3 py-2 text-base font-medium text-error hover:text-error-dark hover:bg-error-50 rounded-md transition-colors duration-200">
                                <?php _e('Sign Out', 'tn-medconnect'); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url(home_url('/login/')); ?>" 
                               class="block px-3 py-2 text-base font-medium text-text-secondary hover:text-primary hover:bg-primary-50 rounded-md transition-colors duration-200">
                                <?php _e('Sign In', 'tn-medconnect'); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/signup/')); ?>" 
                               class="block px-3 py-2 text-base font-medium bg-primary text-white rounded-md hover:bg-primary-dark transition-colors duration-200">
                                <?php _e('Join Platform', 'tn-medconnect'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumbs -->
    <?php if (!is_front_page() && !is_404()): ?>
        <nav class="bg-white border-b border-border py-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-2 text-sm text-text-secondary">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary transition-colors duration-200">
                        <?php _e('Home', 'tn-medconnect'); ?>
                    </a>
                    <?php if (is_category() || is_single()): ?>
                        <span>→</span>
                        <a href="<?php echo esc_url(home_url('/knowledge-hub/')); ?>" class="hover:text-primary transition-colors duration-200">
                            <?php _e('Knowledge Hub', 'tn-medconnect'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if (is_single()): ?>
                        <span>→</span>
                        <span class="text-text-primary"><?php the_title(); ?></span>
                    <?php endif; ?>
                    <?php if (is_category()): ?>
                        <span>→</span>
                        <span class="text-text-primary"><?php single_cat_title(); ?></span>
                    <?php endif; ?>
                    <?php if (is_page()): ?>
                        <span>→</span>
                        <span class="text-text-primary"><?php the_title(); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>

<?php
/**
 * Custom Navigation Walker for Desktop
 */
class TN_MedConnect_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= '<div' . $id . $class_names .'>';
        
        $attributes = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .' class="text-text-secondary hover:text-primary px-3 py-2 rounded-md text-sm transition-clinical">';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</div>\n";
    }
}

/**
 * Custom Navigation Walker for Mobile
 */
class TN_MedConnect_Mobile_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= '<div' . $id . $class_names .'>';
        
        $attributes = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .' class="block px-3 py-2 text-base font-medium text-text-secondary hover:text-primary hover:bg-primary-50 rounded-md transition-colors duration-200">';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</div>\n";
    }
}
?> 