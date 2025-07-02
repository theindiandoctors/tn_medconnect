<?php
/**
 * TN MedConnect User Management Class
 * 
 * Handles user registration, profiles, and medical professional data
 * 
 * @package TN_MedConnect
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TN_MedConnect_User_Management {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_ajax_tn_medconnect_update_profile', array($this, 'ajax_update_profile'));
        add_action('wp_ajax_tn_medconnect_get_profile', array($this, 'ajax_get_profile'));
        add_action('wp_ajax_tn_medconnect_search_professionals', array($this, 'ajax_search_professionals'));
        add_action('wp_ajax_tn_medconnect_get_professional_stats', array($this, 'ajax_get_professional_stats'));
    }
    
    /**
     * Initialize user management
     */
    public function init() {
        // Add custom user meta fields
        add_action('show_user_profile', array($this, 'add_custom_user_fields'));
        add_action('edit_user_profile', array($this, 'add_custom_user_fields'));
        add_action('personal_options_update', array($this, 'save_custom_user_fields'));
        add_action('edit_user_profile_update', array($this, 'save_custom_user_fields'));
        
        // Add custom user roles
        add_action('init', array($this, 'add_custom_user_roles'));
        
        // Add profile completion check
        add_action('wp_login', array($this, 'check_profile_completion'), 10, 2);
        
        // Add profile completion widget
        add_action('wp_dashboard_setup', array($this, 'add_profile_completion_widget'));
    }
    
    /**
     * Add custom user roles
     */
    public function add_custom_user_roles() {
        // Medical Student Role
        add_role('medical_student', __('Medical Student', 'tn-medconnect'), array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'publish_posts' => false,
            'upload_files' => true,
            'read_medical_content' => true,
            'participate_in_forums' => true,
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
            'participate_in_forums' => true,
            'mentor_students' => true,
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
            'participate_in_forums' => true,
            'mentor_students' => true,
            'mentor_residents' => true,
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
            'participate_in_forums' => true,
            'mentor_students' => true,
            'mentor_residents' => true,
            'mentor_physicians' => true,
            'moderate_content' => true,
        ));
        
        // Administrator Role
        add_role('medical_administrator', __('Medical Administrator', 'tn-medconnect'), array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
            'publish_posts' => true,
            'upload_files' => true,
            'read_medical_content' => true,
            'publish_medical_case_studies' => true,
            'publish_cme_courses' => true,
            'publish_medical_resources' => true,
            'participate_in_forums' => true,
            'mentor_students' => true,
            'mentor_residents' => true,
            'mentor_physicians' => true,
            'moderate_content' => true,
            'manage_platform' => true,
        ));
    }
    
    /**
     * Add custom user fields to profile
     */
    public function add_custom_user_fields($user) {
        ?>
        <h3><?php _e('Medical Professional Information', 'tn-medconnect'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="medical_license"><?php _e('Medical License Number', 'tn-medconnect'); ?></label></th>
                <td>
                    <input type="text" name="medical_license" id="medical_license" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'medical_license', true)); ?>" 
                           class="regular-text" />
                    <p class="description"><?php _e('Your official medical license number', 'tn-medconnect'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="medical_specialty"><?php _e('Primary Medical Specialty', 'tn-medconnect'); ?></label></th>
                <td>
                    <select name="medical_specialty" id="medical_specialty">
                        <option value=""><?php _e('Select Specialty', 'tn-medconnect'); ?></option>
                        <?php
                        $specialties = $this->get_medical_specialties();
                        $current_specialty = get_user_meta($user->ID, 'medical_specialty', true);
                        foreach ($specialties as $key => $specialty) {
                            echo '<option value="' . esc_attr($key) . '" ' . selected($current_specialty, $key, false) . '>' . esc_html($specialty) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="secondary_specialties"><?php _e('Secondary Specialties', 'tn-medconnect'); ?></label></th>
                <td>
                    <select name="secondary_specialties[]" id="secondary_specialties" multiple class="regular-text">
                        <?php
                        $current_secondary = get_user_meta($user->ID, 'secondary_specialties', true);
                        if (!is_array($current_secondary)) {
                            $current_secondary = array();
                        }
                        foreach ($specialties as $key => $specialty) {
                            echo '<option value="' . esc_attr($key) . '" ' . (in_array($key, $current_secondary) ? 'selected' : '') . '>' . esc_html($specialty) . '</option>';
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple specialties', 'tn-medconnect'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="institution"><?php _e('Current Institution/Hospital', 'tn-medconnect'); ?></label></th>
                <td>
                    <input type="text" name="institution" id="institution" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'institution', true)); ?>" 
                           class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="position"><?php _e('Current Position', 'tn-medconnect'); ?></label></th>
                <td>
                    <input type="text" name="position" id="position" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'position', true)); ?>" 
                           class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="years_experience"><?php _e('Years of Experience', 'tn-medconnect'); ?></label></th>
                <td>
                    <input type="number" name="years_experience" id="years_experience" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'years_experience', true)); ?>" 
                           class="small-text" min="0" max="50" />
                </td>
            </tr>
            <tr>
                <th><label for="cme_hours"><?php _e('CME Hours Completed (This Year)', 'tn-medconnect'); ?></label></th>
                <td>
                    <input type="number" name="cme_hours" id="cme_hours" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'cme_hours', true)); ?>" 
                           class="small-text" min="0" step="0.5" />
                </td>
            </tr>
            <tr>
                <th><label for="medical_bio"><?php _e('Professional Bio', 'tn-medconnect'); ?></label></th>
                <td>
                    <textarea name="medical_bio" id="medical_bio" rows="5" class="large-text"><?php echo esc_textarea(get_user_meta($user->ID, 'medical_bio', true)); ?></textarea>
                    <p class="description"><?php _e('Brief professional biography and areas of expertise', 'tn-medconnect'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="research_interests"><?php _e('Research Interests', 'tn-medconnect'); ?></label></th>
                <td>
                    <textarea name="research_interests" id="research_interests" rows="3" class="large-text"><?php echo esc_textarea(get_user_meta($user->ID, 'research_interests', true)); ?></textarea>
                    <p class="description"><?php _e('Your current research interests and areas of study', 'tn-medconnect'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="languages"><?php _e('Languages Spoken', 'tn-medconnect'); ?></label></th>
                <td>
                    <input type="text" name="languages" id="languages" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'languages', true)); ?>" 
                           class="regular-text" />
                    <p class="description"><?php _e('Languages you can communicate in (e.g., English, Tamil, Hindi)', 'tn-medconnect'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="availability_mentoring"><?php _e('Available for Mentoring', 'tn-medconnect'); ?></label></th>
                <td>
                    <input type="checkbox" name="availability_mentoring" id="availability_mentoring" 
                           value="1" <?php checked(get_user_meta($user->ID, 'availability_mentoring', true), '1'); ?> />
                    <label for="availability_mentoring"><?php _e('I am available to mentor other medical professionals', 'tn-medconnect'); ?></label>
                </td>
            </tr>
            <tr>
                <th><label for="preferred_contact"><?php _e('Preferred Contact Method', 'tn-medconnect'); ?></label></th>
                <td>
                    <select name="preferred_contact" id="preferred_contact">
                        <option value="email" <?php selected(get_user_meta($user->ID, 'preferred_contact', true), 'email'); ?>><?php _e('Email', 'tn-medconnect'); ?></option>
                        <option value="phone" <?php selected(get_user_meta($user->ID, 'preferred_contact', true), 'phone'); ?>><?php _e('Phone', 'tn-medconnect'); ?></option>
                        <option value="platform" <?php selected(get_user_meta($user->ID, 'preferred_contact', true), 'platform'); ?>><?php _e('Platform Message', 'tn-medconnect'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save custom user fields
     */
    public function save_custom_user_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        
        $fields = array(
            'medical_license',
            'medical_specialty',
            'secondary_specialties',
            'institution',
            'position',
            'years_experience',
            'cme_hours',
            'medical_bio',
            'research_interests',
            'languages',
            'availability_mentoring',
            'preferred_contact'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                if ($field === 'secondary_specialties') {
                    $value = is_array($_POST[$field]) ? $_POST[$field] : array();
                    update_user_meta($user_id, $field, array_map('sanitize_text_field', $value));
                } elseif ($field === 'availability_mentoring') {
                    update_user_meta($user_id, $field, '1');
                } elseif ($field === 'medical_bio' || $field === 'research_interests') {
                    update_user_meta($user_id, $field, sanitize_textarea_field($_POST[$field]));
                } else {
                    update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
                }
            } else {
                if ($field === 'availability_mentoring') {
                    delete_user_meta($user_id, $field);
                }
            }
        }
        
        // Update profile completion status
        $this->update_profile_completion($user_id);
    }
    
    /**
     * Get medical specialties
     */
    public function get_medical_specialties() {
        return array(
            'cardiology' => __('Cardiology', 'tn-medconnect'),
            'orthopedics' => __('Orthopedics', 'tn-medconnect'),
            'pediatrics' => __('Pediatrics', 'tn-medconnect'),
            'internal_medicine' => __('Internal Medicine', 'tn-medconnect'),
            'surgery' => __('Surgery', 'tn-medconnect'),
            'radiology' => __('Radiology', 'tn-medconnect'),
            'neurology' => __('Neurology', 'tn-medconnect'),
            'psychiatry' => __('Psychiatry', 'tn-medconnect'),
            'dermatology' => __('Dermatology', 'tn-medconnect'),
            'ophthalmology' => __('Ophthalmology', 'tn-medconnect'),
            'ent' => __('ENT (Ear, Nose, Throat)', 'tn-medconnect'),
            'gynecology' => __('Gynecology', 'tn-medconnect'),
            'urology' => __('Urology', 'tn-medconnect'),
            'oncology' => __('Oncology', 'tn-medconnect'),
            'emergency_medicine' => __('Emergency Medicine', 'tn-medconnect'),
            'anesthesiology' => __('Anesthesiology', 'tn-medconnect'),
            'pathology' => __('Pathology', 'tn-medconnect'),
            'microbiology' => __('Microbiology', 'tn-medconnect'),
            'biochemistry' => __('Biochemistry', 'tn-medconnect'),
            'physiology' => __('Physiology', 'tn-medconnect'),
            'anatomy' => __('Anatomy', 'tn-medconnect'),
            'pharmacology' => __('Pharmacology', 'tn-medconnect'),
            'community_medicine' => __('Community Medicine', 'tn-medconnect'),
            'forensic_medicine' => __('Forensic Medicine', 'tn-medconnect'),
            'other' => __('Other', 'tn-medconnect'),
        );
    }
    
    /**
     * Get career stages
     */
    public function get_career_stages() {
        return array(
            'medical_student' => __('Medical Student', 'tn-medconnect'),
            'intern' => __('Intern', 'tn-medconnect'),
            'resident' => __('Resident', 'tn-medconnect'),
            'fellow' => __('Fellow', 'tn-medconnect'),
            'practicing_physician' => __('Practicing Physician', 'tn-medconnect'),
            'consultant' => __('Consultant', 'tn-medconnect'),
            'academic' => __('Academic', 'tn-medconnect'),
            'researcher' => __('Researcher', 'tn-medconnect'),
            'administrator' => __('Administrator', 'tn-medconnect'),
            'retired' => __('Retired', 'tn-medconnect'),
        );
    }
    
    /**
     * Update profile completion percentage
     */
    public function update_profile_completion($user_id) {
        $required_fields = array(
            'medical_specialty',
            'institution',
            'position',
            'years_experience',
            'medical_bio',
        );
        
        $completed_fields = 0;
        foreach ($required_fields as $field) {
            if (get_user_meta($user_id, $field, true)) {
                $completed_fields++;
            }
        }
        
        $completion_percentage = ($completed_fields / count($required_fields)) * 100;
        update_user_meta($user_id, 'profile_completion', $completion_percentage);
        
        return $completion_percentage;
    }
    
    /**
     * Check profile completion on login
     */
    public function check_profile_completion($user_login, $user) {
        $completion = get_user_meta($user->ID, 'profile_completion', true);
        
        if ($completion < 80) {
            // Add notification for incomplete profile
            $this->add_notification($user->ID, 'profile_incomplete', 
                __('Complete Your Profile', 'tn-medconnect'),
                __('Your profile is incomplete. Complete it to unlock all platform features.', 'tn-medconnect')
            );
        }
    }
    
    /**
     * Add notification
     */
    private function add_notification($user_id, $type, $title, $message) {
        global $wpdb;
        
        $table_notifications = TN_MedConnect_Database::get_table_name('notifications');
        
        $wpdb->insert($table_notifications, array(
            'user_id' => $user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'created_at' => current_time('mysql'),
        ));
    }
    
    /**
     * Add profile completion widget to dashboard
     */
    public function add_profile_completion_widget() {
        if (current_user_can('read')) {
            wp_add_dashboard_widget(
                'tn_medconnect_profile_completion',
                __('Profile Completion', 'tn-medconnect'),
                array($this, 'profile_completion_widget')
            );
        }
    }
    
    /**
     * Profile completion widget content
     */
    public function profile_completion_widget() {
        $user_id = get_current_user_id();
        $completion = get_user_meta($user_id, 'profile_completion', true);
        
        if (!$completion) {
            $completion = $this->update_profile_completion($user_id);
        }
        
        ?>
        <div class="tn-medconnect-profile-completion">
            <div class="completion-bar">
                <div class="completion-fill" style="width: <?php echo esc_attr($completion); ?>%"></div>
            </div>
            <p><?php printf(__('Your profile is %s%% complete', 'tn-medconnect'), $completion); ?></p>
            
            <?php if ($completion < 100): ?>
                <a href="<?php echo esc_url(home_url('/my-profile/')); ?>" class="button button-primary">
                    <?php _e('Complete Profile', 'tn-medconnect'); ?>
                </a>
            <?php endif; ?>
        </div>
        
        <style>
        .tn-medconnect-profile-completion .completion-bar {
            width: 100%;
            height: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .tn-medconnect-profile-completion .completion-fill {
            height: 100%;
            background-color: #0073aa;
            transition: width 0.3s ease;
        }
        </style>
        <?php
    }
    
    /**
     * AJAX: Update profile
     */
    public function ajax_update_profile() {
        check_ajax_referer('tn_medconnect_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to update your profile.', 'tn-medconnect'));
        }
        
        $user_id = get_current_user_id();
        $this->save_custom_user_fields($user_id);
        
        wp_send_json_success(__('Profile updated successfully.', 'tn-medconnect'));
    }
    
    /**
     * AJAX: Get profile
     */
    public function ajax_get_profile() {
        check_ajax_referer('tn_medconnect_nonce', 'nonce');
        
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error(__('Invalid user ID.', 'tn-medconnect'));
        }
        
        $user = get_userdata($user_id);
        if (!$user) {
            wp_send_json_error(__('User not found.', 'tn-medconnect'));
        }
        
        $profile = array(
            'id' => $user->ID,
            'name' => $user->display_name,
            'email' => $user->user_email,
            'specialty' => get_user_meta($user_id, 'medical_specialty', true),
            'institution' => get_user_meta($user_id, 'institution', true),
            'position' => get_user_meta($user_id, 'position', true),
            'years_experience' => get_user_meta($user_id, 'years_experience', true),
            'bio' => get_user_meta($user_id, 'medical_bio', true),
            'cme_hours' => get_user_meta($user_id, 'cme_hours', true),
            'profile_completion' => get_user_meta($user_id, 'profile_completion', true),
        );
        
        wp_send_json_success($profile);
    }
    
    /**
     * AJAX: Search professionals
     */
    public function ajax_search_professionals() {
        check_ajax_referer('tn_medconnect_nonce', 'nonce');
        
        $search_term = sanitize_text_field($_POST['search_term']);
        $specialty = sanitize_text_field($_POST['specialty']);
        $institution = sanitize_text_field($_POST['institution']);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = 10;
        
        $args = array(
            'number' => $per_page,
            'paged' => $page,
            'meta_query' => array(),
        );
        
        if ($search_term) {
            $args['search'] = '*' . $search_term . '*';
            $args['search_columns'] = array('user_login', 'user_email', 'display_name');
        }
        
        if ($specialty) {
            $args['meta_query'][] = array(
                'key' => 'medical_specialty',
                'value' => $specialty,
                'compare' => '='
            );
        }
        
        if ($institution) {
            $args['meta_query'][] = array(
                'key' => 'institution',
                'value' => $institution,
                'compare' => 'LIKE'
            );
        }
        
        $user_query = new WP_User_Query($args);
        $professionals = array();
        
        foreach ($user_query->get_results() as $user) {
            $professionals[] = array(
                'id' => $user->ID,
                'name' => $user->display_name,
                'specialty' => get_user_meta($user->ID, 'medical_specialty', true),
                'institution' => get_user_meta($user->ID, 'institution', true),
                'position' => get_user_meta($user->ID, 'position', true),
                'years_experience' => get_user_meta($user->ID, 'years_experience', true),
                'profile_completion' => get_user_meta($user->ID, 'profile_completion', true),
            );
        }
        
        wp_send_json_success(array(
            'professionals' => $professionals,
            'total' => $user_query->get_total(),
            'pages' => ceil($user_query->get_total() / $per_page),
        ));
    }
    
    /**
     * AJAX: Get professional statistics
     */
    public function ajax_get_professional_stats() {
        check_ajax_referer('tn_medconnect_nonce', 'nonce');
        
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error(__('Invalid user ID.', 'tn-medconnect'));
        }
        
        // Get CME statistics
        global $wpdb;
        $table_cme = TN_MedConnect_Database::get_table_name('cme_tracking');
        $total_cme_hours = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(hours_earned) FROM $table_cme WHERE user_id = %d AND status = 'approved'",
            $user_id
        ));
        
        // Get case studies count
        $table_case_studies = TN_MedConnect_Database::get_table_name('case_studies');
        $case_studies_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_case_studies WHERE user_id = %d AND status = 'approved'",
            $user_id
        ));
        
        // Get connections count
        $table_connections = TN_MedConnect_Database::get_table_name('connections');
        $connections_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_connections WHERE (sender_id = %d OR receiver_id = %d) AND status = 'accepted'",
            $user_id, $user_id
        ));
        
        $stats = array(
            'total_cme_hours' => $total_cme_hours ?: 0,
            'case_studies_count' => $case_studies_count ?: 0,
            'connections_count' => $connections_count ?: 0,
            'profile_completion' => get_user_meta($user_id, 'profile_completion', true) ?: 0,
        );
        
        wp_send_json_success($stats);
    }
} 