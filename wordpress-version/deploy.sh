#!/bin/bash

# TN MedConnect WordPress Deployment Script
# This script helps deploy the TN MedConnect platform to a WordPress installation

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
WORDPRESS_ROOT=""
THEME_NAME="tn-medconnect"
PLUGIN_NAME="tn-medconnect"

echo -e "${BLUE}🏥 TN MedConnect WordPress Deployment Script${NC}"
echo "=================================================="

# Function to print colored output
print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# Check if WordPress is installed
check_wordpress() {
    if [ -z "$WORDPRESS_ROOT" ]; then
        echo -e "${YELLOW}Please enter your WordPress installation path:${NC}"
        read -p "WordPress root directory: " WORDPRESS_ROOT
    fi
    
    if [ ! -f "$WORDPRESS_ROOT/wp-config.php" ]; then
        print_error "WordPress not found at $WORDPRESS_ROOT"
        exit 1
    fi
    
    print_status "WordPress found at $WORDPRESS_ROOT"
}

# Check PHP version
check_php() {
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    PHP_MAJOR=$(echo $PHP_VERSION | cut -d. -f1)
    PHP_MINOR=$(echo $PHP_VERSION | cut -d. -f2)
    
    if [ "$PHP_MAJOR" -lt 7 ] || ([ "$PHP_MAJOR" -eq 7 ] && [ "$PHP_MINOR" -lt 4 ]); then
        print_error "PHP 7.4 or higher is required. Current version: $PHP_VERSION"
        exit 1
    fi
    
    print_status "PHP version $PHP_VERSION is compatible"
}

# Check MySQL/MariaDB
check_database() {
    if ! command -v mysql &> /dev/null; then
        print_warning "MySQL client not found. Database checks will be skipped."
        return
    fi
    
    print_status "MySQL client found"
}

# Create backup
create_backup() {
    echo -e "${YELLOW}Creating backup of existing installation...${NC}"
    
    BACKUP_DIR="backup-$(date +%Y%m%d-%H%M%S)"
    mkdir -p "$BACKUP_DIR"
    
    if [ -d "$WORDPRESS_ROOT/wp-content/themes/$THEME_NAME" ]; then
        cp -r "$WORDPRESS_ROOT/wp-content/themes/$THEME_NAME" "$BACKUP_DIR/"
        print_status "Backed up existing theme"
    fi
    
    if [ -d "$WORDPRESS_ROOT/wp-content/plugins/$PLUGIN_NAME" ]; then
        cp -r "$WORDPRESS_ROOT/wp-content/plugins/$PLUGIN_NAME" "$BACKUP_DIR/"
        print_status "Backed up existing plugin"
    fi
    
    print_status "Backup created in $BACKUP_DIR"
}

# Deploy theme
deploy_theme() {
    echo -e "${YELLOW}Deploying TN MedConnect theme...${NC}"
    
    THEME_SOURCE="tn-medconnect-theme"
    THEME_DEST="$WORDPRESS_ROOT/wp-content/themes/$THEME_NAME"
    
    if [ ! -d "$THEME_SOURCE" ]; then
        print_error "Theme source directory not found: $THEME_SOURCE"
        exit 1
    fi
    
    # Remove existing theme if it exists
    if [ -d "$THEME_DEST" ]; then
        rm -rf "$THEME_DEST"
        print_status "Removed existing theme"
    fi
    
    # Copy new theme
    cp -r "$THEME_SOURCE" "$THEME_DEST"
    print_status "Theme deployed successfully"
    
    # Set proper permissions
    chmod -R 755 "$THEME_DEST"
    find "$THEME_DEST" -type f -exec chmod 644 {} \;
    print_status "Theme permissions set"
}

# Deploy plugin
deploy_plugin() {
    echo -e "${YELLOW}Deploying TN MedConnect plugin...${NC}"
    
    PLUGIN_SOURCE="tn-medconnect-plugin"
    PLUGIN_DEST="$WORDPRESS_ROOT/wp-content/plugins/$PLUGIN_NAME"
    
    if [ ! -d "$PLUGIN_SOURCE" ]; then
        print_error "Plugin source directory not found: $PLUGIN_SOURCE"
        exit 1
    fi
    
    # Remove existing plugin if it exists
    if [ -d "$PLUGIN_DEST" ]; then
        rm -rf "$PLUGIN_DEST"
        print_status "Removed existing plugin"
    fi
    
    # Copy new plugin
    cp -r "$PLUGIN_SOURCE" "$PLUGIN_DEST"
    print_status "Plugin deployed successfully"
    
    # Set proper permissions
    chmod -R 755 "$PLUGIN_DEST"
    find "$PLUGIN_DEST" -type f -exec chmod 644 {} \;
    print_status "Plugin permissions set"
}

# Install dependencies
install_dependencies() {
    echo -e "${YELLOW}Installing dependencies...${NC}"
    
    # Check if npm is available
    if command -v npm &> /dev/null; then
        cd "$WORDPRESS_ROOT/wp-content/themes/$THEME_NAME"
        
        if [ -f "package.json" ]; then
            npm install
            print_status "Node.js dependencies installed"
        fi
        
        # Build CSS if needed
        if [ -f "package.json" ] && grep -q "build:css" package.json; then
            npm run build:css
            print_status "CSS built successfully"
        fi
        
        cd - > /dev/null
    else
        print_warning "npm not found. Skipping Node.js dependencies."
    fi
}

# Create configuration file
create_config() {
    echo -e "${YELLOW}Creating configuration file...${NC}"
    
    CONFIG_FILE="tn-medconnect-config.php"
    
    cat > "$CONFIG_FILE" << EOF
<?php
/**
 * TN MedConnect Configuration
 * 
 * This file contains configuration settings for the TN MedConnect platform.
 * Copy this file to your WordPress root directory and include it in wp-config.php
 */

// Platform Settings
define('TN_MEDCONNECT_ENABLE_DEBUG', false);
define('TN_MEDCONNECT_ENABLE_ANALYTICS', true);
define('TN_MEDCONNECT_ENABLE_NOTIFICATIONS', true);

// CME Settings
define('TN_MEDCONNECT_CME_ANNUAL_REQUIREMENT', 30);
define('TN_MEDCONNECT_CME_AUTO_APPROVE', false);

// Networking Settings
define('TN_MEDCONNECT_ENABLE_NETWORKING', true);
define('TN_MEDCONNECT_MAX_CONNECTIONS', 1000);

// Case Studies Settings
define('TN_MEDCONNECT_ENABLE_CASE_STUDIES', true);
define('TN_MEDCONNECT_AUTO_APPROVE_CASE_STUDIES', false);

// Email Settings
define('TN_MEDCONNECT_NOTIFICATION_EMAIL', 'support@tnmedconnect.com');
define('TN_MEDCONNECT_SYSTEM_EMAIL', 'noreply@tnmedconnect.com');

// Security Settings
define('TN_MEDCONNECT_ENABLE_2FA', false);
define('TN_MEDCONNECT_SESSION_TIMEOUT', 3600);

// Performance Settings
define('TN_MEDCONNECT_ENABLE_CACHING', true);
define('TN_MEDCONNECT_CACHE_DURATION', 3600);

// External Integrations
define('TN_MEDCONNECT_SUPABASE_URL', '');
define('TN_MEDCONNECT_SUPABASE_KEY', '');
define('TN_MEDCONNECT_GOOGLE_ANALYTICS_ID', '');
EOF
    
    print_status "Configuration file created: $CONFIG_FILE"
    print_warning "Please review and customize the configuration file"
}

# Generate setup instructions
generate_instructions() {
    echo -e "${YELLOW}Generating setup instructions...${NC}"
    
    INSTRUCTIONS_FILE="setup-instructions.md"
    
    cat > "$INSTRUCTIONS_FILE" << EOF
# TN MedConnect Setup Instructions

## Post-Deployment Steps

### 1. WordPress Configuration
1. Log in to your WordPress admin panel
2. Go to **Appearance → Themes** and activate "TN MedConnect"
3. Go to **Plugins** and activate "TN MedConnect - Medical Professional Platform"

### 2. Platform Configuration
1. Go to **TN MedConnect → Settings** in the admin menu
2. Configure the following settings:
   - Platform statistics
   - CME requirements
   - User roles and permissions
   - Email notifications
   - Security settings

### 3. Content Migration (if applicable)
1. Use the **TN MedConnect → Migration** tool
2. Import existing content from your static site
3. Set up URL redirects from old pages to new WordPress URLs

### 4. User Management
1. Create user roles for different medical professionals
2. Set up default user permissions
3. Configure profile completion requirements

### 5. Customization
1. Customize the theme using **Appearance → Customize**
2. Add your logo and branding
3. Configure colors and typography
4. Set up navigation menus

### 6. Testing
1. Test user registration and login
2. Verify CME tracking functionality
3. Test case study submission and approval
4. Check professional networking features
5. Test mobile responsiveness

### 7. SEO Configuration
1. Install and configure an SEO plugin (Yoast SEO recommended)
2. Set up XML sitemaps
3. Configure meta descriptions and titles
4. Set up Google Analytics

### 8. Security Hardening
1. Install a security plugin (Wordfence recommended)
2. Enable two-factor authentication
3. Set up regular backups
4. Configure SSL certificate

### 9. Performance Optimization
1. Install a caching plugin (WP Rocket recommended)
2. Optimize images
3. Enable GZIP compression
4. Set up CDN if needed

### 10. Go Live
1. Remove maintenance mode
2. Update DNS if necessary
3. Test all functionality
4. Monitor performance and errors

## Important URLs
- **Admin Panel**: $WORDPRESS_ROOT/wp-admin/
- **Professional Dashboard**: $WORDPRESS_ROOT/professional-dashboard/
- **Knowledge Hub**: $WORDPRESS_ROOT/knowledge-hub/
- **Case Studies**: $WORDPRESS_ROOT/case-studies/
- **Professional Networking**: $WORDPRESS_ROOT/professional-networking/

## Support
- **Documentation**: https://tnmedconnect.com/docs
- **Support Email**: support@tnmedconnect.com
- **Community Forum**: https://community.tnmedconnect.com

## Backup Information
- Backup location: $BACKUP_DIR
- Backup date: $(date)
- WordPress version: $(grep "wp_version =" $WORDPRESS_ROOT/wp-includes/version.php | cut -d"'" -f2)
EOF
    
    print_status "Setup instructions generated: $INSTRUCTIONS_FILE"
}

# Main deployment function
main() {
    echo -e "${BLUE}Starting TN MedConnect deployment...${NC}"
    
    # Check prerequisites
    check_wordpress
    check_php
    check_database
    
    # Create backup
    create_backup
    
    # Deploy components
    deploy_theme
    deploy_plugin
    
    # Install dependencies
    install_dependencies
    
    # Create configuration
    create_config
    
    # Generate instructions
    generate_instructions
    
    echo -e "${GREEN}🎉 TN MedConnect deployment completed successfully!${NC}"
    echo ""
    echo -e "${BLUE}Next steps:${NC}"
    echo "1. Review the setup instructions in setup-instructions.md"
    echo "2. Configure the platform settings in WordPress admin"
    echo "3. Test all functionality"
    echo "4. Contact support if you need assistance"
    echo ""
    echo -e "${YELLOW}Backup created in: $BACKUP_DIR${NC}"
    echo -e "${YELLOW}Configuration file: tn-medconnect-config.php${NC}"
    echo -e "${YELLOW}Setup instructions: setup-instructions.md${NC}"
}

# Run main function
main "$@" 