<?php
/**
 * Plugin Name: TN MedConnect - Medical Professional Platform
 * Plugin URI: https://tnmedconnect.com
 * Description: A comprehensive WordPress plugin for medical professionals in Tamil Nadu. Features include user management, CME tracking, case studies, professional networking, and knowledge sharing.
 * Version: 1.0.0
 * Author: TN MedConnect Team
 * Author URI: https://tnmedconnect.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tn-medconnect
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TN_MEDCONNECT_VERSION', '1.0.0');
define('TN_MEDCONNECT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TN_MEDCONNECT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TN_MEDCONNECT_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main TN MedConnect Plugin Class
 */
class TN_MedConnect_Plugin {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_tn_medconnect_ajax', array($this, 'handle_ajax_requests'));
        add_action('wp_ajax_nopriv_tn_medconnect_ajax', array($this, 'handle_ajax_requests'));
    }
    
    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'includes/class-tn-medconnect-database.php';
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'includes/class-tn-medconnect-user-management.php';
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'includes/class-tn-medconnect-cme-tracking.php';
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'includes/class-tn-medconnect-networking.php';
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'includes/class-tn-medconnect-case-studies.php';
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'includes/class-tn-medconnect-shortcodes.php';
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'includes/class-tn-medconnect-widgets.php';
        require_once TN_MEDCONNECT_PLUGIN_DIR . 'admin/class-tn-medconnect-admin.php';
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables
        TN_MedConnect_Database::create_tables();
        
        // Set default options
        $this->set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Create default pages
        $this->create_default_pages();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain
        load_plugin_textdomain('tn-medconnect', false, dirname(TN_MEDCONNECT_PLUGIN_BASENAME) . '/languages');
        
        // Initialize components
        new TN_MedConnect_User_Management();
        new TN_MedConnect_CME_Tracking();
        new TN_MedConnect_Networking();
        new TN_MedConnect_Case_Studies();
        new TN_MedConnect_Shortcodes();
        new TN_MedConnect_Widgets();
        
        // Initialize admin
        if (is_admin()) {
            new TN_MedConnect_Admin();
        }
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style('tn-medconnect-frontend', TN_MEDCONNECT_PLUGIN_URL . 'assets/css/frontend.css', array(), TN_MEDCONNECT_VERSION);
        wp_enqueue_script('tn-medconnect-frontend', TN_MEDCONNECT_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), TN_MEDCONNECT_VERSION, true);
        
        wp_localize_script('tn-medconnect-frontend', 'tn_medconnect_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tn_medconnect_nonce'),
            'is_logged_in' => is_user_logged_in(),
            'user_id' => get_current_user_id(),
            'strings' => array(
                'loading' => __('Loading...', 'tn-medconnect'),
                'error' => __('An error occurred. Please try again.', 'tn-medconnect'),
                'success' => __('Operation completed successfully.', 'tn-medconnect'),
            ),
        ));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'tn-medconnect') !== false) {
            wp_enqueue_style('tn-medconnect-admin', TN_MEDCONNECT_PLUGIN_URL . 'assets/css/admin.css', array(), TN_MEDCONNECT_VERSION);
            wp_enqueue_script('tn-medconnect-admin', TN_MEDCONNECT_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), TN_MEDCONNECT_VERSION, true);
        }
    }
    
    /**
     * Handle AJAX requests
     */
    public function handle_ajax_requests() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'tn_medconnect_nonce')) {
            wp_die(__('Security check failed.', 'tn-medconnect'));
        }
        
        $action = sanitize_text_field($_POST['action_type']);
        
        switch ($action) {
            case 'update_profile':
                $this->handle_update_profile();
                break;
            case 'track_cme':
                $this->handle_track_cme();
                break;
            case 'send_connection_request':
                $this->handle_send_connection_request();
                break;
            case 'respond_to_connection':
                $this->handle_respond_to_connection();
                break;
            case 'create_case_study':
                $this->handle_create_case_study();
                break;
            default:
                wp_send_json_error(__('Invalid action.', 'tn-medconnect'));
        }
    }
    
    /**
     * Handle profile update
     */
    private function handle_update_profile() {
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to update your profile.', 'tn-medconnect'));
        }
        
        $user_id = get_current_user_id();
        $profile_data = array(
            'medical_license' => sanitize_text_field($_POST['medical_license']),
            'medical_specialty' => sanitize_text_field($_POST['medical_specialty']),
            'institution' => sanitize_text_field($_POST['institution']),
            'years_experience' => intval($_POST['years_experience']),
            'bio' => sanitize_textarea_field($_POST['bio']),
        );
        
        foreach ($profile_data as $key => $value) {
            update_user_meta($user_id, $key, $value);
        }
        
        wp_send_json_success(__('Profile updated successfully.', 'tn-medconnect'));
    }
    
    /**
     * Handle CME tracking
     */
    private function handle_track_cme() {
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to track CME.', 'tn-medconnect'));
        }
        
        $user_id = get_current_user_id();
        $cme_data = array(
            'course_title' => sanitize_text_field($_POST['course_title']),
            'course_provider' => sanitize_text_field($_POST['course_provider']),
            'hours_earned' => floatval($_POST['hours_earned']),
            'completion_date' => sanitize_text_field($_POST['completion_date']),
            'certificate_url' => esc_url_raw($_POST['certificate_url']),
        );
        
        $cme_tracking = new TN_MedConnect_CME_Tracking();
        $result = $cme_tracking->add_cme_record($user_id, $cme_data);
        
        if ($result) {
            wp_send_json_success(__('CME hours tracked successfully.', 'tn-medconnect'));
        } else {
            wp_send_json_error(__('Failed to track CME hours.', 'tn-medconnect'));
        }
    }
    
    /**
     * Handle connection request
     */
    private function handle_send_connection_request() {
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to send connection requests.', 'tn-medconnect'));
        }
        
        $sender_id = get_current_user_id();
        $receiver_id = intval($_POST['receiver_id']);
        
        if ($sender_id === $receiver_id) {
            wp_send_json_error(__('You cannot connect with yourself.', 'tn-medconnect'));
        }
        
        $networking = new TN_MedConnect_Networking();
        $result = $networking->send_connection_request($sender_id, $receiver_id);
        
        if ($result) {
            wp_send_json_success(__('Connection request sent successfully.', 'tn-medconnect'));
        } else {
            wp_send_json_error(__('Failed to send connection request.', 'tn-medconnect'));
        }
    }
    
    /**
     * Handle connection response
     */
    private function handle_respond_to_connection() {
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to respond to connection requests.', 'tn-medconnect'));
        }
        
        $user_id = get_current_user_id();
        $connection_id = intval($_POST['connection_id']);
        $status = sanitize_text_field($_POST['status']); // 'accepted' or 'declined'
        
        $networking = new TN_MedConnect_Networking();
        $result = $networking->respond_to_connection_request($connection_id, $user_id, $status);
        
        if ($result) {
            $message = $status === 'accepted' ? __('Connection accepted successfully.', 'tn-medconnect') : __('Connection declined.', 'tn-medconnect');
            wp_send_json_success($message);
        } else {
            wp_send_json_error(__('Failed to respond to connection request.', 'tn-medconnect'));
        }
    }
    
    /**
     * Handle case study creation
     */
    private function handle_create_case_study() {
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to create case studies.', 'tn-medconnect'));
        }
        
        $user_id = get_current_user_id();
        $case_data = array(
            'title' => sanitize_text_field($_POST['title']),
            'content' => wp_kses_post($_POST['content']),
            'patient_age' => intval($_POST['patient_age']),
            'diagnosis' => sanitize_text_field($_POST['diagnosis']),
            'treatment' => sanitize_textarea_field($_POST['treatment']),
            'outcome' => sanitize_textarea_field($_POST['outcome']),
            'cme_hours' => floatval($_POST['cme_hours']),
            'specialty' => sanitize_text_field($_POST['specialty']),
        );
        
        $case_studies = new TN_MedConnect_Case_Studies();
        $result = $case_studies->create_case_study($user_id, $case_data);
        
        if ($result) {
            wp_send_json_success(__('Case study created successfully.', 'tn-medconnect'));
        } else {
            wp_send_json_error(__('Failed to create case study.', 'tn-medconnect'));
        }
    }
    
    /**
     * Set default plugin options
     */
    private function set_default_options() {
        $default_options = array(
            'cme_annual_requirement' => 30,
            'enable_networking' => true,
            'enable_case_studies' => true,
            'enable_cme_tracking' => true,
            'auto_approve_case_studies' => false,
            'notification_email' => get_option('admin_email'),
            'platform_statistics' => array(
                'total_professionals' => 15000,
                'total_articles' => 2500,
                'total_cme_hours' => 50000,
                'total_specialties' => 25,
            ),
        );
        
        foreach ($default_options as $key => $value) {
            if (!get_option('tn_medconnect_' . $key)) {
                update_option('tn_medconnect_' . $key, $value);
            }
        }
    }
    
    /**
     * Create default pages
     */
    private function create_default_pages() {
        $pages = array(
            'professional-dashboard' => array(
                'title' => __('Professional Dashboard', 'tn-medconnect'),
                'content' => '[tn_medconnect_dashboard]',
                'template' => 'page-dashboard.php',
            ),
            'knowledge-hub' => array(
                'title' => __('Knowledge Hub', 'tn-medconnect'),
                'content' => '[tn_medconnect_knowledge_hub]',
                'template' => 'page-knowledge-hub.php',
            ),
            'case-studies' => array(
                'title' => __('Case Studies', 'tn-medconnect'),
                'content' => '[tn_medconnect_case_studies]',
                'template' => 'page-case-studies.php',
            ),
            'cme-courses' => array(
                'title' => __('CME Courses', 'tn-medconnect'),
                'content' => '[tn_medconnect_cme_courses]',
                'template' => 'page-cme-courses.php',
            ),
            'professional-networking' => array(
                'title' => __('Professional Networking', 'tn-medconnect'),
                'content' => '[tn_medconnect_networking]',
                'template' => 'page-networking.php',
            ),
            'my-profile' => array(
                'title' => __('My Profile', 'tn-medconnect'),
                'content' => '[tn_medconnect_profile]',
                'template' => 'page-profile.php',
            ),
            'my-cme' => array(
                'title' => __('My CME', 'tn-medconnect'),
                'content' => '[tn_medconnect_my_cme]',
                'template' => 'page-my-cme.php',
            ),
            'my-connections' => array(
                'title' => __('My Connections', 'tn-medconnect'),
                'content' => '[tn_medconnect_my_connections]',
                'template' => 'page-my-connections.php',
            ),
        );
        
        foreach ($pages as $slug => $page_data) {
            $existing_page = get_page_by_path($slug);
            if (!$existing_page) {
                $page_id = wp_insert_post(array(
                    'post_title' => $page_data['title'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug,
                ));
                
                if ($page_id && !is_wp_error($page_id)) {
                    update_post_meta($page_id, '_wp_page_template', $page_data['template']);
                }
            }
        }
    }
}

// Initialize the plugin
new TN_MedConnect_Plugin();

/**
 * Plugin activation hook
 */
function tn_medconnect_activate() {
    // Create database tables
    TN_MedConnect_Database::create_tables();
    
    // Set default options
    $plugin = new TN_MedConnect_Plugin();
    $plugin->set_default_options();
    $plugin->create_default_pages();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Plugin deactivation hook
 */
function tn_medconnect_deactivate() {
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'tn_medconnect_activate');
register_deactivation_hook(__FILE__, 'tn_medconnect_deactivate');

/**
 * Load plugin text domain
 */
function tn_medconnect_load_textdomain() {
    load_plugin_textdomain('tn-medconnect', false, dirname(TN_MEDCONNECT_PLUGIN_BASENAME) . '/languages');
}
add_action('plugins_loaded', 'tn_medconnect_load_textdomain');

/**
 * Add plugin action links
 */
function tn_medconnect_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=tn-medconnect-settings') . '">' . __('Settings', 'tn-medconnect') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . TN_MEDCONNECT_PLUGIN_BASENAME, 'tn_medconnect_plugin_action_links');

/**
 * Add plugin meta links
 */
function tn_medconnect_plugin_meta_links($links, $file) {
    if ($file === TN_MEDCONNECT_PLUGIN_BASENAME) {
        $links[] = '<a href="' . admin_url('admin.php?page=tn-medconnect-documentation') . '">' . __('Documentation', 'tn-medconnect') . '</a>';
        $links[] = '<a href="https://tnmedconnect.com/support">' . __('Support', 'tn-medconnect') . '</a>';
    }
    return $links;
}
add_filter('plugin_row_meta', 'tn_medconnect_plugin_meta_links', 10, 2); 