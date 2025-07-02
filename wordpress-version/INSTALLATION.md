# TN MedConnect WordPress Installation Guide

## 🚀 Quick Start Installation

### Prerequisites
- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **SSL Certificate**: Recommended for medical data security
- **Server**: Apache or Nginx with mod_rewrite enabled

### Step 1: Download and Extract
```bash
# Download the TN MedConnect WordPress package
wget https://github.com/tnmedconnect/wordpress-version/archive/main.zip
unzip main.zip
cd wordpress-version-main
```

### Step 2: Run Deployment Script
```bash
# Make the script executable
chmod +x deploy.sh

# Run the deployment script
./deploy.sh
```

### Step 3: WordPress Configuration
1. Log in to WordPress Admin
2. Go to **Appearance → Themes** → Activate "TN MedConnect"
3. Go to **Plugins** → Activate "TN MedConnect - Medical Professional Platform"

### Step 4: Initial Setup
1. Go to **TN MedConnect → Settings**
2. Configure platform settings
3. Set up user roles and permissions
4. Configure email notifications

## 📋 Detailed Installation Steps

### 1. Server Requirements Check

#### PHP Requirements
```bash
# Check PHP version
php -v

# Required PHP extensions
php -m | grep -E "(mysqli|pdo_mysql|json|mbstring|xml|curl|gd|zip)"
```

#### MySQL Requirements
```bash
# Check MySQL version
mysql --version

# Test database connection
mysql -u username -p database_name -e "SELECT VERSION();"
```

#### WordPress Requirements
- WordPress 5.0+ installed and configured
- Permalinks set to "Post name" (Settings → Permalinks)
- File uploads enabled
- Memory limit: 256MB or higher

### 2. Manual Installation

#### Upload Theme
```bash
# Copy theme to WordPress themes directory
cp -r tn-medconnect-theme/ /path/to/wordpress/wp-content/themes/tn-medconnect/

# Set proper permissions
chmod -R 755 /path/to/wordpress/wp-content/themes/tn-medconnect/
find /path/to/wordpress/wp-content/themes/tn-medconnect/ -type f -exec chmod 644 {} \;
```

#### Upload Plugin
```bash
# Copy plugin to WordPress plugins directory
cp -r tn-medconnect-plugin/ /path/to/wordpress/wp-content/plugins/tn-medconnect/

# Set proper permissions
chmod -R 755 /path/to/wordpress/wp-content/plugins/tn-medconnect/
find /path/to/wordpress/wp-content/plugins/tn-medconnect/ -type f -exec chmod 644 {} \;
```

#### Install Dependencies
```bash
# Navigate to theme directory
cd /path/to/wordpress/wp-content/themes/tn-medconnect/

# Install Node.js dependencies (if available)
npm install

# Build CSS
npm run build:css
```

### 3. Database Setup

#### Automatic Setup
The plugin will automatically create all necessary database tables when activated.

#### Manual Database Setup (if needed)
```sql
-- Create database tables manually if automatic setup fails
-- (See sql/setup.sql for complete schema)
```

### 4. Configuration

#### WordPress Configuration
Add to `wp-config.php`:
```php
// TN MedConnect Configuration
define('TN_MEDCONNECT_ENABLE_DEBUG', false);
define('TN_MEDCONNECT_ENABLE_ANALYTICS', true);
define('TN_MEDCONNECT_CME_ANNUAL_REQUIREMENT', 30);
define('TN_MEDCONNECT_ENABLE_NETWORKING', true);
define('TN_MEDCONNECT_ENABLE_CASE_STUDIES', true);
```

#### Theme Configuration
1. Go to **Appearance → Customize**
2. Configure:
   - Site Identity (logo, title)
   - Colors and Typography
   - Header and Footer
   - Homepage Settings

#### Plugin Configuration
1. Go to **TN MedConnect → Settings**
2. Configure:
   - Platform Statistics
   - User Roles and Permissions
   - CME Requirements
   - Email Notifications
   - Security Settings

### 5. Content Migration

#### From Static HTML Site
1. Use the migration tool in **TN MedConnect → Migration**
2. Upload your existing HTML files
3. Map old URLs to new WordPress URLs
4. Import user data (if available)

#### Manual Migration
```php
// Example: Import case studies
$case_studies = [
    [
        'title' => 'Complex Cardiac Case',
        'content' => 'Case study content...',
        'specialty' => 'cardiology',
        'author' => 'Dr. Rajesh Kumar'
    ]
    // ... more case studies
];

foreach ($case_studies as $case) {
    wp_insert_post([
        'post_title' => $case['title'],
        'post_content' => $case['content'],
        'post_type' => 'medical_case_study',
        'post_status' => 'publish',
        'meta_input' => [
            'medical_specialty' => $case['specialty']
        ]
    ]);
}
```

### 6. User Management Setup

#### Create User Roles
```php
// Add custom user roles
add_role('medical_student', 'Medical Student', [
    'read' => true,
    'participate_in_forums' => true
]);

add_role('resident', 'Resident', [
    'read' => true,
    'publish_medical_case_studies' => true,
    'mentor_students' => true
]);

add_role('practicing_physician', 'Practicing Physician', [
    'read' => true,
    'publish_medical_case_studies' => true,
    'publish_cme_courses' => true
]);
```

#### Set Default User Role
```php
// Set default role for new registrations
update_option('default_role', 'medical_student');
```

### 7. Security Configuration

#### SSL Setup
```apache
# Apache .htaccess
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

#### Security Headers
```php
// Add security headers
add_action('send_headers', function() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
});
```

#### File Permissions
```bash
# Set secure file permissions
find /path/to/wordpress -type d -exec chmod 755 {} \;
find /path/to/wordpress -type f -exec chmod 644 {} \;
chmod 440 /path/to/wordpress/wp-config.php
```

### 8. Performance Optimization

#### Caching Setup
```php
// Enable object caching
define('WP_CACHE', true);

// Configure Redis/Memcached
define('WP_REDIS_HOST', '127.0.0.1');
define('WP_REDIS_PORT', 6379);
```

#### Database Optimization
```sql
-- Optimize database tables
OPTIMIZE TABLE wp_posts;
OPTIMIZE TABLE wp_postmeta;
OPTIMIZE TABLE wp_users;
OPTIMIZE TABLE wp_usermeta;
```

#### Image Optimization
```php
// Add image sizes for medical content
add_image_size('medical-case-study', 800, 600, true);
add_image_size('professional-avatar', 300, 300, true);
add_image_size('knowledge-thumbnail', 400, 300, true);
```

### 9. Testing and Validation

#### Functionality Testing
1. **User Registration**: Test new user signup
2. **Profile Completion**: Verify profile fields
3. **CME Tracking**: Test CME hour logging
4. **Case Studies**: Test submission and approval
5. **Networking**: Test connection requests
6. **Search**: Test content search functionality

#### Performance Testing
```bash
# Test page load times
curl -w "@curl-format.txt" -o /dev/null -s "https://yoursite.com/"

# Test database queries
mysql -u username -p -e "SHOW PROCESSLIST;"
```

#### Security Testing
1. Test user permissions
2. Verify data sanitization
3. Check for SQL injection vulnerabilities
4. Test XSS protection
5. Verify CSRF protection

### 10. Go Live Checklist

#### Pre-Launch
- [ ] All functionality tested
- [ ] Security measures implemented
- [ ] Performance optimized
- [ ] Content migrated
- [ ] SEO configured
- [ ] Backup system in place
- [ ] Monitoring tools installed

#### Launch Day
- [ ] Remove maintenance mode
- [ ] Update DNS if necessary
- [ ] Monitor error logs
- [ ] Check user registrations
- [ ] Verify email notifications
- [ ] Test mobile responsiveness

#### Post-Launch
- [ ] Monitor performance
- [ ] Gather user feedback
- [ ] Address any issues
- [ ] Plan future updates
- [ ] Set up regular maintenance

## 🔧 Troubleshooting

### Common Issues

#### Theme Not Activating
```bash
# Check file permissions
ls -la wp-content/themes/tn-medconnect/

# Check PHP errors
tail -f wp-content/debug.log
```

#### Plugin Not Working
```bash
# Check plugin directory
ls -la wp-content/plugins/tn-medconnect/

# Check database tables
mysql -u username -p -e "SHOW TABLES LIKE 'wp_tn_medconnect_%';"
```

#### Database Connection Issues
```php
// Test database connection
$wpdb->get_results("SELECT 1");
if ($wpdb->last_error) {
    error_log("Database error: " . $wpdb->last_error);
}
```

#### Performance Issues
```bash
# Check server resources
htop
df -h
free -m

# Check WordPress memory usage
wp eval 'echo memory_get_peak_usage(true) / 1024 / 1024;'
```

### Debug Mode
```php
// Enable debug mode in wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Support Resources
- **Documentation**: https://tnmedconnect.com/docs
- **Support Forum**: https://community.tnmedconnect.com
- **Email Support**: support@tnmedconnect.com
- **Emergency Support**: +91-XXX-XXX-XXXX

## 📞 Getting Help

If you encounter any issues during installation:

1. **Check the logs**: Review WordPress debug logs and server error logs
2. **Search documentation**: Look for similar issues in the documentation
3. **Community forum**: Post your question in the community forum
4. **Email support**: Contact support with detailed error information
5. **Emergency support**: Call for urgent issues during business hours

### Support Information to Include
- WordPress version
- PHP version
- MySQL version
- Server type (Apache/Nginx)
- Error messages
- Steps to reproduce the issue
- Screenshots if applicable

---

**Need help? Contact us at support@tnmedconnect.com** 