<?php
/**
 * TN MedConnect Theme Functions
 * 
 * @package TN_MedConnect
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function tn_medconnect_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tn-medconnect'),
        'footer' => __('Footer Menu', 'tn-medconnect'),
        'medical-specialties' => __('Medical Specialties', 'tn-medconnect'),
    ));
    
    // Add image sizes for medical content
    add_image_size('medical-case-study', 800, 600, true);
    add_image_size('professional-avatar', 300, 300, true);
    add_image_size('knowledge-thumbnail', 400, 300, true);
}
add_action('after_setup_theme', 'tn_medconnect_setup');

/**
 * Enqueue Scripts and Styles
 */
function tn_medconnect_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style('tn-medconnect-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Source+Sans+Pro:wght@300;400;600;700&display=swap', array(), null);
    
    // Enqueue Tailwind CSS
    wp_enqueue_style('tn-medconnect-tailwind', get_template_directory_uri() . '/assets/css/tailwind.css', array(), '1.0.0');
    
    // Enqueue main stylesheet
    wp_enqueue_style('tn-medconnect-style', get_stylesheet_uri(), array('tn-medconnect-tailwind'), '1.0.0');
    
    // Enqueue JavaScript
    wp_enqueue_script('tn-medconnect-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('tn-medconnect-main', 'tn_medconnect_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tn_medconnect_nonce'),
        'is_logged_in' => is_user_logged_in(),
        'user_id' => get_current_user_id(),
    ));
}
add_action('wp_enqueue_scripts', 'tn_medconnect_scripts');

/**
 * Register Widget Areas
 */
function tn_medconnect_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'tn-medconnect'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'tn-medconnect'),
        'before_widget' => '<section id="%1$s" class="widget %2$s card mb-6">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title text-lg font-inter font-semibold text-secondary mb-4">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Medical Resources Sidebar', 'tn-medconnect'),
        'id' => 'medical-resources-sidebar',
        'description' => __('Widgets for medical resources and knowledge hub.', 'tn-medconnect'),
        'before_widget' => '<section id="%1$s" class="widget %2$s card mb-6">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title text-lg font-inter font-semibold text-secondary mb-4">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Professional Dashboard Sidebar', 'tn-medconnect'),
        'id' => 'dashboard-sidebar',
        'description' => __('Widgets for professional dashboard.', 'tn-medconnect'),
        'before_widget' => '<section id="%1$s" class="widget %2$s card mb-6">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title text-lg font-inter font-semibold text-secondary mb-4">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'tn_medconnect_widgets_init');

/**
 * Custom Post Types for Medical Content
 */
function tn_medconnect_custom_post_types() {
    // Medical Case Studies
    register_post_type('medical_case_study', array(
        'labels' => array(
            'name' => __('Case Studies', 'tn-medconnect'),
            'singular_name' => __('Case Study', 'tn-medconnect'),
            'add_new' => __('Add New Case Study', 'tn-medconnect'),
            'add_new_item' => __('Add New Case Study', 'tn-medconnect'),
            'edit_item' => __('Edit Case Study', 'tn-medconnect'),
            'new_item' => __('New Case Study', 'tn-medconnect'),
            'view_item' => __('View Case Study', 'tn-medconnect'),
            'search_items' => __('Search Case Studies', 'tn-medconnect'),
            'not_found' => __('No case studies found', 'tn-medconnect'),
            'not_found_in_trash' => __('No case studies found in trash', 'tn-medconnect'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'menu_icon' => 'dashicons-clipboard',
        'rewrite' => array('slug' => 'case-studies'),
        'show_in_rest' => true,
    ));
    
    // CME Courses
    register_post_type('cme_course', array(
        'labels' => array(
            'name' => __('CME Courses', 'tn-medconnect'),
            'singular_name' => __('CME Course', 'tn-medconnect'),
            'add_new' => __('Add New Course', 'tn-medconnect'),
            'add_new_item' => __('Add New CME Course', 'tn-medconnect'),
            'edit_item' => __('Edit CME Course', 'tn-medconnect'),
            'new_item' => __('New CME Course', 'tn-medconnect'),
            'view_item' => __('View CME Course', 'tn-medconnect'),
            'search_items' => __('Search CME Courses', 'tn-medconnect'),
            'not_found' => __('No CME courses found', 'tn-medconnect'),
            'not_found_in_trash' => __('No CME courses found in trash', 'tn-medconnect'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
        'menu_icon' => 'dashicons-welcome-learn-more',
        'rewrite' => array('slug' => 'cme-courses'),
        'show_in_rest' => true,
    ));
    
    // Medical Resources
    register_post_type('medical_resource', array(
        'labels' => array(
            'name' => __('Medical Resources', 'tn-medconnect'),
            'singular_name' => __('Medical Resource', 'tn-medconnect'),
            'add_new' => __('Add New Resource', 'tn-medconnect'),
            'add_new_item' => __('Add New Medical Resource', 'tn-medconnect'),
            'edit_item' => __('Edit Medical Resource', 'tn-medconnect'),
            'new_item' => __('New Medical Resource', 'tn-medconnect'),
            'view_item' => __('View Medical Resource', 'tn-medconnect'),
            'search_items' => __('Search Medical Resources', 'tn-medconnect'),
            'not_found' => __('No medical resources found', 'tn-medconnect'),
            'not_found_in_trash' => __('No medical resources found in trash', 'tn-medconnect'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
        'menu_icon' => 'dashicons-book-alt',
        'rewrite' => array('slug' => 'medical-resources'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'tn_medconnect_custom_post_types');

/**
 * Custom Taxonomies
 */
function tn_medconnect_custom_taxonomies() {
    // Medical Specialties
    register_taxonomy('medical_specialty', array('medical_case_study', 'cme_course', 'medical_resource', 'post'), array(
        'labels' => array(
            'name' => __('Medical Specialties', 'tn-medconnect'),
            'singular_name' => __('Medical Specialty', 'tn-medconnect'),
            'search_items' => __('Search Specialties', 'tn-medconnect'),
            'all_items' => __('All Specialties', 'tn-medconnect'),
            'parent_item' => __('Parent Specialty', 'tn-medconnect'),
            'parent_item_colon' => __('Parent Specialty:', 'tn-medconnect'),
            'edit_item' => __('Edit Specialty', 'tn-medconnect'),
            'update_item' => __('Update Specialty', 'tn-medconnect'),
            'add_new_item' => __('Add New Specialty', 'tn-medconnect'),
            'new_item_name' => __('New Specialty Name', 'tn-medconnect'),
            'menu_name' => __('Specialties', 'tn-medconnect'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'specialty'),
        'show_in_rest' => true,
    ));
    
    // Career Stages
    register_taxonomy('career_stage', array('medical_case_study', 'cme_course', 'medical_resource', 'post'), array(
        'labels' => array(
            'name' => __('Career Stages', 'tn-medconnect'),
            'singular_name' => __('Career Stage', 'tn-medconnect'),
            'search_items' => __('Search Career Stages', 'tn-medconnect'),
            'all_items' => __('All Career Stages', 'tn-medconnect'),
            'edit_item' => __('Edit Career Stage', 'tn-medconnect'),
            'update_item' => __('Update Career Stage', 'tn-medconnect'),
            'add_new_item' => __('Add New Career Stage', 'tn-medconnect'),
            'new_item_name' => __('New Career Stage Name', 'tn-medconnect'),
            'menu_name' => __('Career Stages', 'tn-medconnect'),
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'career-stage'),
        'show_in_rest' => true,
    ));
    
    // Medical Institutions
    register_taxonomy('medical_institution', array('medical_case_study', 'cme_course', 'medical_resource', 'post'), array(
        'labels' => array(
            'name' => __('Medical Institutions', 'tn-medconnect'),
            'singular_name' => __('Medical Institution', 'tn-medconnect'),
            'search_items' => __('Search Institutions', 'tn-medconnect'),
            'all_items' => __('All Institutions', 'tn-medconnect'),
            'edit_item' => __('Edit Institution', 'tn-medconnect'),
            'update_item' => __('Update Institution', 'tn-medconnect'),
            'add_new_item' => __('Add New Institution', 'tn-medconnect'),
            'new_item_name' => __('New Institution Name', 'tn-medconnect'),
            'menu_name' => __('Institutions', 'tn-medconnect'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'institution'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'tn_medconnect_custom_taxonomies');

/**
 * Custom User Roles
 */
function tn_medconnect_add_user_roles() {
    // Medical Student Role
    add_role('medical_student', __('Medical Student', 'tn-medconnect'), array(
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false,
        'publish_posts' => false,
        'upload_files' => true,
        'read_medical_content' => true,
    ));
    
    // Resident Role
    add_role('resident', __('Resident', 'tn-medconnect'), array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false,
        'publish_posts' => false,
        'upload_files' => true,
        'read_medical_content' => true,
        'publish_medical_case_studies' => true,
    ));
    
    // Practicing Physician Role
    add_role('practicing_physician', __('Practicing Physician', 'tn-medconnect'), array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'publish_posts' => true,
        'upload_files' => true,
        'read_medical_content' => true,
        'publish_medical_case_studies' => true,
        'publish_cme_courses' => true,
    ));
    
    // Academic Role
    add_role('academic', __('Academic', 'tn-medconnect'), array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'publish_posts' => true,
        'upload_files' => true,
        'read_medical_content' => true,
        'publish_medical_case_studies' => true,
        'publish_cme_courses' => true,
        'publish_medical_resources' => true,
    ));
}
add_action('init', 'tn_medconnect_add_user_roles');

/**
 * Custom User Profile Fields
 */
function tn_medconnect_add_custom_user_fields($user) {
    ?>
    <h3><?php _e('Medical Professional Information', 'tn-medconnect'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="medical_license"><?php _e('Medical License Number', 'tn-medconnect'); ?></label></th>
            <td>
                <input type="text" name="medical_license" id="medical_license" value="<?php echo esc_attr(get_user_meta($user->ID, 'medical_license', true)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="medical_specialty"><?php _e('Primary Medical Specialty', 'tn-medconnect'); ?></label></th>
            <td>
                <select name="medical_specialty" id="medical_specialty">
                    <option value=""><?php _e('Select Specialty', 'tn-medconnect'); ?></option>
                    <option value="cardiology" <?php selected(get_user_meta($user->ID, 'medical_specialty', true), 'cardiology'); ?>><?php _e('Cardiology', 'tn-medconnect'); ?></option>
                    <option value="orthopedics" <?php selected(get_user_meta($user->ID, 'medical_specialty', true), 'orthopedics'); ?>><?php _e('Orthopedics', 'tn-medconnect'); ?></option>
                    <option value="pediatrics" <?php selected(get_user_meta($user->ID, 'medical_specialty', true), 'pediatrics'); ?>><?php _e('Pediatrics', 'tn-medconnect'); ?></option>
                    <option value="internal_medicine" <?php selected(get_user_meta($user->ID, 'medical_specialty', true), 'internal_medicine'); ?>><?php _e('Internal Medicine', 'tn-medconnect'); ?></option>
                    <option value="surgery" <?php selected(get_user_meta($user->ID, 'medical_specialty', true), 'surgery'); ?>><?php _e('Surgery', 'tn-medconnect'); ?></option>
                    <option value="radiology" <?php selected(get_user_meta($user->ID, 'medical_specialty', true), 'radiology'); ?>><?php _e('Radiology', 'tn-medconnect'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="institution"><?php _e('Institution/Hospital', 'tn-medconnect'); ?></label></th>
            <td>
                <input type="text" name="institution" id="institution" value="<?php echo esc_attr(get_user_meta($user->ID, 'institution', true)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="years_experience"><?php _e('Years of Experience', 'tn-medconnect'); ?></label></th>
            <td>
                <input type="number" name="years_experience" id="years_experience" value="<?php echo esc_attr(get_user_meta($user->ID, 'years_experience', true)); ?>" class="small-text" min="0" />
            </td>
        </tr>
        <tr>
            <th><label for="cme_hours"><?php _e('CME Hours Completed', 'tn-medconnect'); ?></label></th>
            <td>
                <input type="number" name="cme_hours" id="cme_hours" value="<?php echo esc_attr(get_user_meta($user->ID, 'cme_hours', true)); ?>" class="small-text" min="0" />
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'tn_medconnect_add_custom_user_fields');
add_action('edit_user_profile', 'tn_medconnect_add_custom_user_fields');

/**
 * Save Custom User Fields
 */
function tn_medconnect_save_custom_user_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    
    $fields = array('medical_license', 'medical_specialty', 'institution', 'years_experience', 'cme_hours');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('personal_options_update', 'tn_medconnect_save_custom_user_fields');
add_action('edit_user_profile_update', 'tn_medconnect_save_custom_user_fields');

/**
 * Custom Login Redirect
 */
function tn_medconnect_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('administrator', $user->roles)) {
            return admin_url();
        } else {
            return home_url('/professional-dashboard/');
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'tn_medconnect_login_redirect', 10, 3);

/**
 * Custom Excerpt Length
 */
function tn_medconnect_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'tn_medconnect_excerpt_length');

/**
 * Custom Excerpt More
 */
function tn_medconnect_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'tn_medconnect_excerpt_more');

/**
 * Add Custom Body Classes
 */
function tn_medconnect_body_classes($classes) {
    if (is_singular('medical_case_study')) {
        $classes[] = 'single-case-study';
    }
    if (is_singular('cme_course')) {
        $classes[] = 'single-cme-course';
    }
    if (is_singular('medical_resource')) {
        $classes[] = 'single-medical-resource';
    }
    return $classes;
}
add_filter('body_class', 'tn_medconnect_body_classes');

/**
 * Custom Search Filter
 */
function tn_medconnect_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search()) {
            $query->set('post_type', array('post', 'medical_case_study', 'cme_course', 'medical_resource'));
        }
    }
}
add_action('pre_get_posts', 'tn_medconnect_search_filter');

/**
 * Add Custom Meta Boxes
 */
function tn_medconnect_add_meta_boxes() {
    add_meta_box(
        'medical_case_study_details',
        __('Case Study Details', 'tn-medconnect'),
        'tn_medconnect_case_study_meta_box',
        'medical_case_study',
        'normal',
        'high'
    );
    
    add_meta_box(
        'cme_course_details',
        __('CME Course Details', 'tn-medconnect'),
        'tn_medconnect_cme_course_meta_box',
        'cme_course',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'tn_medconnect_add_meta_boxes');

/**
 * Case Study Meta Box
 */
function tn_medconnect_case_study_meta_box($post) {
    wp_nonce_field('tn_medconnect_case_study_meta_box', 'tn_medconnect_case_study_meta_box_nonce');
    
    $patient_age = get_post_meta($post->ID, '_patient_age', true);
    $diagnosis = get_post_meta($post->ID, '_diagnosis', true);
    $treatment = get_post_meta($post->ID, '_treatment', true);
    $outcome = get_post_meta($post->ID, '_outcome', true);
    $cme_hours = get_post_meta($post->ID, '_cme_hours', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="patient_age"><?php _e('Patient Age', 'tn-medconnect'); ?></label></th>
            <td><input type="number" id="patient_age" name="patient_age" value="<?php echo esc_attr($patient_age); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="diagnosis"><?php _e('Diagnosis', 'tn-medconnect'); ?></label></th>
            <td><input type="text" id="diagnosis" name="diagnosis" value="<?php echo esc_attr($diagnosis); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="treatment"><?php _e('Treatment', 'tn-medconnect'); ?></label></th>
            <td><textarea id="treatment" name="treatment" rows="3" class="large-text"><?php echo esc_textarea($treatment); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="outcome"><?php _e('Outcome', 'tn-medconnect'); ?></label></th>
            <td><textarea id="outcome" name="outcome" rows="3" class="large-text"><?php echo esc_textarea($outcome); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="cme_hours"><?php _e('CME Hours Awarded', 'tn-medconnect'); ?></label></th>
            <td><input type="number" id="cme_hours" name="cme_hours" value="<?php echo esc_attr($cme_hours); ?>" class="small-text" step="0.5" min="0" /></td>
        </tr>
    </table>
    <?php
}

/**
 * CME Course Meta Box
 */
function tn_medconnect_cme_course_meta_box($post) {
    wp_nonce_field('tn_medconnect_cme_course_meta_box', 'tn_medconnect_cme_course_meta_box_nonce');
    
    $course_duration = get_post_meta($post->ID, '_course_duration', true);
    $cme_hours = get_post_meta($post->ID, '_cme_hours', true);
    $course_fee = get_post_meta($post->ID, '_course_fee', true);
    $start_date = get_post_meta($post->ID, '_start_date', true);
    $end_date = get_post_meta($post->ID, '_end_date', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="course_duration"><?php _e('Course Duration (hours)', 'tn-medconnect'); ?></label></th>
            <td><input type="number" id="course_duration" name="course_duration" value="<?php echo esc_attr($course_duration); ?>" class="small-text" step="0.5" min="0" /></td>
        </tr>
        <tr>
            <th><label for="cme_hours"><?php _e('CME Hours Awarded', 'tn-medconnect'); ?></label></th>
            <td><input type="number" id="cme_hours" name="cme_hours" value="<?php echo esc_attr($cme_hours); ?>" class="small-text" step="0.5" min="0" /></td>
        </tr>
        <tr>
            <th><label for="course_fee"><?php _e('Course Fee (₹)', 'tn-medconnect'); ?></label></th>
            <td><input type="number" id="course_fee" name="course_fee" value="<?php echo esc_attr($course_fee); ?>" class="regular-text" min="0" /></td>
        </tr>
        <tr>
            <th><label for="start_date"><?php _e('Start Date', 'tn-medconnect'); ?></label></th>
            <td><input type="date" id="start_date" name="start_date" value="<?php echo esc_attr($start_date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="end_date"><?php _e('End Date', 'tn-medconnect'); ?></label></th>
            <td><input type="date" id="end_date" name="end_date" value="<?php echo esc_attr($end_date); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

/**
 * Save Meta Box Data
 */
function tn_medconnect_save_meta_boxes($post_id) {
    // Case Study Meta Box
    if (isset($_POST['tn_medconnect_case_study_meta_box_nonce']) && wp_verify_nonce($_POST['tn_medconnect_case_study_meta_box_nonce'], 'tn_medconnect_case_study_meta_box')) {
        $fields = array('patient_age', 'diagnosis', 'treatment', 'outcome', 'cme_hours');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    // CME Course Meta Box
    if (isset($_POST['tn_medconnect_cme_course_meta_box_nonce']) && wp_verify_nonce($_POST['tn_medconnect_cme_course_meta_box_nonce'], 'tn_medconnect_cme_course_meta_box')) {
        $fields = array('course_duration', 'cme_hours', 'course_fee', 'start_date', 'end_date');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('save_post', 'tn_medconnect_save_meta_boxes');

/**
 * Custom Dashboard Widget
 */
function tn_medconnect_dashboard_widget() {
    $current_user = wp_get_current_user();
    $user_specialty = get_user_meta($current_user->ID, 'medical_specialty', true);
    $cme_hours = get_user_meta($current_user->ID, 'cme_hours', true);
    
    ?>
    <div class="tn-medconnect-dashboard-widget">
        <h3><?php _e('Welcome to TN MedConnect', 'tn-medconnect'); ?></h3>
        <p><?php printf(__('Hello %s!', 'tn-medconnect'), $current_user->display_name); ?></p>
        
        <?php if ($user_specialty): ?>
            <p><strong><?php _e('Specialty:', 'tn-medconnect'); ?></strong> <?php echo esc_html(ucfirst(str_replace('_', ' ', $user_specialty))); ?></p>
        <?php endif; ?>
        
        <?php if ($cme_hours): ?>
            <p><strong><?php _e('CME Hours:', 'tn-medconnect'); ?></strong> <?php echo esc_html($cme_hours); ?></p>
        <?php endif; ?>
        
        <p><a href="<?php echo home_url('/professional-dashboard/'); ?>" class="button button-primary"><?php _e('View Professional Dashboard', 'tn-medconnect'); ?></a></p>
    </div>
    <?php
}

function tn_medconnect_add_dashboard_widget() {
    wp_add_dashboard_widget('tn_medconnect_dashboard_widget', __('TN MedConnect', 'tn-medconnect'), 'tn_medconnect_dashboard_widget');
}
add_action('wp_dashboard_setup', 'tn_medconnect_add_dashboard_widget');

/**
 * Theme Customizer
 */
function tn_medconnect_customize_register($wp_customize) {
    // Medical Platform Section
    $wp_customize->add_section('tn_medconnect_medical', array(
        'title' => __('Medical Platform Settings', 'tn-medconnect'),
        'priority' => 30,
    ));
    
    // Platform Statistics
    $wp_customize->add_setting('tn_medconnect_total_professionals', array(
        'default' => '15000',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('tn_medconnect_total_professionals', array(
        'label' => __('Total Medical Professionals', 'tn-medconnect'),
        'section' => 'tn_medconnect_medical',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('tn_medconnect_total_articles', array(
        'default' => '2500',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('tn_medconnect_total_articles', array(
        'label' => __('Total Knowledge Articles', 'tn-medconnect'),
        'section' => 'tn_medconnect_medical',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('tn_medconnect_total_cme_hours', array(
        'default' => '50000',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('tn_medconnect_total_cme_hours', array(
        'label' => __('Total CME Hours Completed', 'tn-medconnect'),
        'section' => 'tn_medconnect_medical',
        'type' => 'text',
    ));
}
add_action('customize_register', 'tn_medconnect_customize_register');

/**
 * Load Text Domain
 */
function tn_medconnect_load_textdomain() {
    load_theme_textdomain('tn-medconnect', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'tn_medconnect_load_textdomain'); 