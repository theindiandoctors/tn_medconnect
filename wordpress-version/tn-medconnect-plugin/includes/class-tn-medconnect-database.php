<?php
/**
 * TN MedConnect Database Class
 * 
 * Handles all database operations for the medical professional platform
 * 
 * @package TN_MedConnect
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TN_MedConnect_Database {
    
    /**
     * Create all plugin tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // CME Tracking Table
        $table_cme_tracking = $wpdb->prefix . 'tn_medconnect_cme_tracking';
        $sql_cme_tracking = "CREATE TABLE $table_cme_tracking (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            course_title varchar(255) NOT NULL,
            course_provider varchar(255) NOT NULL,
            hours_earned decimal(5,2) NOT NULL,
            completion_date date NOT NULL,
            certificate_url varchar(500),
            status varchar(50) DEFAULT 'pending',
            approved_by bigint(20),
            approved_date datetime,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY completion_date (completion_date),
            KEY status (status)
        ) $charset_collate;";
        
        // Professional Connections Table
        $table_connections = $wpdb->prefix . 'tn_medconnect_connections';
        $sql_connections = "CREATE TABLE $table_connections (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            sender_id bigint(20) NOT NULL,
            receiver_id bigint(20) NOT NULL,
            status varchar(50) DEFAULT 'pending',
            message text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_connection (sender_id, receiver_id),
            KEY sender_id (sender_id),
            KEY receiver_id (receiver_id),
            KEY status (status)
        ) $charset_collate;";
        
        // Case Studies Table
        $table_case_studies = $wpdb->prefix . 'tn_medconnect_case_studies';
        $sql_case_studies = "CREATE TABLE $table_case_studies (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            title varchar(255) NOT NULL,
            content longtext NOT NULL,
            patient_age int(3),
            diagnosis varchar(500),
            treatment text,
            outcome text,
            cme_hours decimal(5,2) DEFAULT 0,
            specialty varchar(100),
            status varchar(50) DEFAULT 'pending',
            approved_by bigint(20),
            approved_date datetime,
            views_count int(11) DEFAULT 0,
            likes_count int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY specialty (specialty),
            KEY status (status),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Professional Activities Table
        $table_activities = $wpdb->prefix . 'tn_medconnect_activities';
        $sql_activities = "CREATE TABLE $table_activities (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            activity_type varchar(100) NOT NULL,
            title varchar(255) NOT NULL,
            description text,
            related_id bigint(20),
            related_type varchar(50),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY activity_type (activity_type),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Notifications Table
        $table_notifications = $wpdb->prefix . 'tn_medconnect_notifications';
        $sql_notifications = "CREATE TABLE $table_notifications (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            type varchar(100) NOT NULL,
            title varchar(255) NOT NULL,
            message text NOT NULL,
            related_id bigint(20),
            related_type varchar(50),
            is_read tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY type (type),
            KEY is_read (is_read),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Medical Resources Table
        $table_resources = $wpdb->prefix . 'tn_medconnect_resources';
        $sql_resources = "CREATE TABLE $table_resources (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            title varchar(255) NOT NULL,
            description text,
            resource_type varchar(100) NOT NULL,
            file_url varchar(500),
            external_url varchar(500),
            specialty varchar(100),
            tags text,
            downloads_count int(11) DEFAULT 0,
            status varchar(50) DEFAULT 'pending',
            approved_by bigint(20),
            approved_date datetime,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY resource_type (resource_type),
            KEY specialty (specialty),
            KEY status (status)
        ) $charset_collate;";
        
        // Professional Certifications Table
        $table_certifications = $wpdb->prefix . 'tn_medconnect_certifications';
        $sql_certifications = "CREATE TABLE $table_certifications (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            certification_name varchar(255) NOT NULL,
            issuing_organization varchar(255) NOT NULL,
            issue_date date NOT NULL,
            expiry_date date,
            certificate_number varchar(100),
            certificate_url varchar(500),
            status varchar(50) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY status (status),
            KEY expiry_date (expiry_date)
        ) $charset_collate;";
        
        // Professional Experience Table
        $table_experience = $wpdb->prefix . 'tn_medconnect_experience';
        $sql_experience = "CREATE TABLE $table_experience (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            position varchar(255) NOT NULL,
            institution varchar(255) NOT NULL,
            start_date date NOT NULL,
            end_date date,
            is_current tinyint(1) DEFAULT 0,
            description text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY is_current (is_current)
        ) $charset_collate;";
        
        // Professional Publications Table
        $table_publications = $wpdb->prefix . 'tn_medconnect_publications';
        $sql_publications = "CREATE TABLE $table_publications (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            title varchar(500) NOT NULL,
            authors text,
            journal varchar(255),
            publication_date date,
            doi varchar(100),
            url varchar(500),
            abstract text,
            citation_count int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY publication_date (publication_date)
        ) $charset_collate;";
        
        // Professional Skills Table
        $table_skills = $wpdb->prefix . 'tn_medconnect_skills';
        $sql_skills = "CREATE TABLE $table_skills (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            skill_name varchar(255) NOT NULL,
            skill_category varchar(100),
            proficiency_level varchar(50),
            years_experience int(3),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY skill_category (skill_category)
        ) $charset_collate;";
        
        // Professional Awards Table
        $table_awards = $wpdb->prefix . 'tn_medconnect_awards';
        $sql_awards = "CREATE TABLE $table_awards (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            award_name varchar(255) NOT NULL,
            awarding_organization varchar(255) NOT NULL,
            award_date date NOT NULL,
            description text,
            certificate_url varchar(500),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY award_date (award_date)
        ) $charset_collate;";
        
        // Professional Research Table
        $table_research = $wpdb->prefix . 'tn_medconnect_research';
        $sql_research = "CREATE TABLE $table_research (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            title varchar(500) NOT NULL,
            description text,
            research_type varchar(100),
            start_date date,
            end_date date,
            status varchar(50) DEFAULT 'ongoing',
            funding_source varchar(255),
            budget decimal(15,2),
            collaborators text,
            outcomes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY research_type (research_type),
            KEY status (status)
        ) $charset_collate;";
        
        // Professional Mentorship Table
        $table_mentorship = $wpdb->prefix . 'tn_medconnect_mentorship';
        $sql_mentorship = "CREATE TABLE $table_mentorship (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            mentor_id bigint(20) NOT NULL,
            mentee_id bigint(20) NOT NULL,
            specialty varchar(100),
            start_date date NOT NULL,
            end_date date,
            status varchar(50) DEFAULT 'active',
            goals text,
            progress_notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY mentor_id (mentor_id),
            KEY mentee_id (mentee_id),
            KEY status (status)
        ) $charset_collate;";
        
        // Professional Events Table
        $table_events = $wpdb->prefix . 'tn_medconnect_events';
        $sql_events = "CREATE TABLE $table_events (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            event_type varchar(100) NOT NULL,
            start_date datetime NOT NULL,
            end_date datetime NOT NULL,
            location varchar(255),
            organizer_id bigint(20),
            max_participants int(11),
            registration_deadline date,
            cme_hours decimal(5,2) DEFAULT 0,
            fee decimal(10,2) DEFAULT 0,
            status varchar(50) DEFAULT 'upcoming',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_type (event_type),
            KEY start_date (start_date),
            KEY status (status)
        ) $charset_collate;";
        
        // Event Registrations Table
        $table_event_registrations = $wpdb->prefix . 'tn_medconnect_event_registrations';
        $sql_event_registrations = "CREATE TABLE $table_event_registrations (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            event_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            registration_date datetime DEFAULT CURRENT_TIMESTAMP,
            payment_status varchar(50) DEFAULT 'pending',
            attendance_status varchar(50) DEFAULT 'registered',
            cme_hours_earned decimal(5,2) DEFAULT 0,
            feedback text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_registration (event_id, user_id),
            KEY event_id (event_id),
            KEY user_id (user_id),
            KEY payment_status (payment_status)
        ) $charset_collate;";
        
        // Execute SQL statements
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql_cme_tracking);
        dbDelta($sql_connections);
        dbDelta($sql_case_studies);
        dbDelta($sql_activities);
        dbDelta($sql_notifications);
        dbDelta($sql_resources);
        dbDelta($sql_certifications);
        dbDelta($sql_experience);
        dbDelta($sql_publications);
        dbDelta($sql_skills);
        dbDelta($sql_awards);
        dbDelta($sql_research);
        dbDelta($sql_mentorship);
        dbDelta($sql_events);
        dbDelta($sql_event_registrations);
        
        // Update database version
        update_option('tn_medconnect_db_version', TN_MEDCONNECT_VERSION);
    }
    
    /**
     * Get table name with prefix
     */
    public static function get_table_name($table) {
        global $wpdb;
        return $wpdb->prefix . 'tn_medconnect_' . $table;
    }
    
    /**
     * Check if table exists
     */
    public static function table_exists($table) {
        global $wpdb;
        $table_name = self::get_table_name($table);
        $result = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
        return $result === $table_name;
    }
    
    /**
     * Get database statistics
     */
    public static function get_statistics() {
        global $wpdb;
        
        $stats = array();
        
        // Count medical professionals
        $stats['total_professionals'] = count_users()['total_users'];
        
        // Count case studies
        $table_case_studies = self::get_table_name('case_studies');
        if (self::table_exists('case_studies')) {
            $stats['total_case_studies'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_case_studies WHERE status = 'approved'");
        }
        
        // Count CME hours
        $table_cme_tracking = self::get_table_name('cme_tracking');
        if (self::table_exists('cme_tracking')) {
            $stats['total_cme_hours'] = $wpdb->get_var("SELECT SUM(hours_earned) FROM $table_cme_tracking WHERE status = 'approved'");
        }
        
        // Count connections
        $table_connections = self::get_table_name('connections');
        if (self::table_exists('connections')) {
            $stats['total_connections'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_connections WHERE status = 'accepted'");
        }
        
        // Count medical resources
        $table_resources = self::get_table_name('resources');
        if (self::table_exists('resources')) {
            $stats['total_resources'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_resources WHERE status = 'approved'");
        }
        
        return $stats;
    }
    
    /**
     * Clean up old data
     */
    public static function cleanup_old_data($days = 30) {
        global $wpdb;
        
        $date_threshold = date('Y-m-d H:i:s', strtotime("-$days days"));
        
        // Clean up old notifications
        $table_notifications = self::get_table_name('notifications');
        if (self::table_exists('notifications')) {
            $wpdb->query($wpdb->prepare(
                "DELETE FROM $table_notifications WHERE created_at < %s AND is_read = 1",
                $date_threshold
            ));
        }
        
        // Clean up old activities
        $table_activities = self::get_table_name('activities');
        if (self::table_exists('activities')) {
            $wpdb->query($wpdb->prepare(
                "DELETE FROM $table_activities WHERE created_at < %s",
                $date_threshold
            ));
        }
    }
    
    /**
     * Backup plugin data
     */
    public static function backup_data() {
        global $wpdb;
        
        $backup = array();
        $tables = array(
            'cme_tracking',
            'connections',
            'case_studies',
            'activities',
            'notifications',
            'resources',
            'certifications',
            'experience',
            'publications',
            'skills',
            'awards',
            'research',
            'mentorship',
            'events',
            'event_registrations'
        );
        
        foreach ($tables as $table) {
            $table_name = self::get_table_name($table);
            if (self::table_exists($table)) {
                $backup[$table] = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            }
        }
        
        return $backup;
    }
    
    /**
     * Restore plugin data
     */
    public static function restore_data($backup) {
        global $wpdb;
        
        foreach ($backup as $table => $data) {
            $table_name = self::get_table_name($table);
            if (self::table_exists($table) && !empty($data)) {
                // Clear existing data
                $wpdb->query("TRUNCATE TABLE $table_name");
                
                // Insert backup data
                foreach ($data as $row) {
                    $wpdb->insert($table_name, $row);
                }
            }
        }
    }
} 