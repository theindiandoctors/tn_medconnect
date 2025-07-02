// Dashboard-specific API functions for TN MedConnect
import { 
    auth, 
    userProfile, 
    cmeActivities, 
    connections, 
    caseStudies, 
    notifications,
    realTime
} from './supabase-config.js';

// Dashboard data manager
export class DashboardManager {
    constructor() {
        this.currentUser = null;
        this.userProfile = null;
        this.subscriptions = [];
        this.updateCallbacks = new Map();
    }

    // Initialize dashboard
    async initialize() {
        try {
            // Check authentication
            const { user } = await auth.getCurrentUser();
            if (!user) {
                window.location.href = 'login.html';
                return false;
            }

            this.currentUser = user;
            await this.loadUserData();
            this.setupRealtimeSubscriptions();
            this.updateUI();
            
            return true;
        } catch (error) {
            console.error('Dashboard initialization failed:', error);
            return false;
        }
    }

    // Load user data
    async loadUserData() {
        try {
            const profileResult = await userProfile.getDetailedProfile(this.currentUser.id);
            if (profileResult.success) {
                this.userProfile = profileResult.data;
            }
        } catch (error) {
            console.error('Failed to load user data:', error);
        }
    }

    // Setup real-time subscriptions
    setupRealtimeSubscriptions() {
        // Subscribe to profile changes
        const profileSub = realTime.subscribe(
            'user_profiles',
            (payload) => this.handleProfileUpdate(payload),
            { filter: `user_id=eq.${this.currentUser.id}` }
        );
        this.subscriptions.push(profileSub);

        // Subscribe to CME activities
        const cmeSub = realTime.subscribe(
            'cme_activities',
            (payload) => this.handleCMEUpdate(payload),
            { filter: `user_id=eq.${this.currentUser.id}` }
        );
        this.subscriptions.push(cmeSub);

        // Subscribe to connections
        const connectionsSub = realTime.subscribe(
            'connections',
            (payload) => this.handleConnectionUpdate(payload),
            { filter: `or(requester_id.eq.${this.currentUser.id},addressee_id.eq.${this.currentUser.id})` }
        );
        this.subscriptions.push(connectionsSub);
    }

    // Handle profile updates
    handleProfileUpdate(payload) {
        if (payload.eventType === 'UPDATE') {
            this.userProfile = { ...this.userProfile, ...payload.new };
            this.triggerCallback('profile_update', this.userProfile);
        }
    }

    // Handle CME updates
    handleCMEUpdate(payload) {
        this.triggerCallback('cme_update', payload);
        // Refresh CME stats
        this.refreshCMEStats();
    }

    // Handle connection updates
    handleConnectionUpdate(payload) {
        this.triggerCallback('connection_update', payload);
        // Refresh connections count
        this.refreshConnectionsCount();
    }

    // Refresh CME statistics
    async refreshCMEStats() {
        try {
            const result = await cmeActivities.getStatistics(this.currentUser.id);
            if (result.success) {
                this.userProfile.cme_stats = result.data;
                this.triggerCallback('cme_stats_update', result.data);
            }
        } catch (error) {
            console.error('Failed to refresh CME stats:', error);
        }
    }

    // Refresh connections count
    async refreshConnectionsCount() {
        try {
            const result = await userProfile.getConnectionsCount(this.currentUser.id);
            if (result.success) {
                this.userProfile.connections_count = result.data;
                this.triggerCallback('connections_count_update', result.data);
            }
        } catch (error) {
            console.error('Failed to refresh connections count:', error);
        }
    }

    // Update UI with current data
    updateUI() {
        this.updateProfileSection();
        this.updateWelcomeSection();
        this.updateQuickStats();
        this.updateLastLogin();
    }

    // Update profile section
    updateProfileSection() {
        const profileSection = document.getElementById('profileSection');
        if (profileSection && this.userProfile) {
            profileSection.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="text-primary font-semibold">
                            ${this.getInitials(this.userProfile.full_name || this.currentUser.email)}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-secondary">
                            ${this.userProfile.full_name || 'Medical Professional'}
                        </p>
                        <p class="text-xs text-text-secondary">
                            ${this.userProfile.specialty || 'General Practice'}
                        </p>
                    </div>
                </div>
            `;
        }
    }

    // Update welcome section
    updateWelcomeSection() {
        const welcomeSection = document.getElementById('welcomeSection');
        if (welcomeSection && this.userProfile) {
            welcomeSection.innerHTML = `
                <div>
                    <h1 class="text-3xl font-inter font-bold text-secondary mb-2">
                        Welcome back, ${this.userProfile.full_name || 'Doctor'}!
                    </h1>
                    <p class="text-text-secondary text-lg">
                        ${this.userProfile.specialty || 'Medical Professional'} • 
                        ${this.userProfile.institution || 'Healthcare Provider'}
                    </p>
                    <div class="flex items-center space-x-4 mt-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-primary rounded-full"></div>
                            <span class="text-sm text-text-secondary">CME Credits: ${this.userProfile.cme_credits || 0}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-success rounded-full"></div>
                            <span class="text-sm text-text-secondary">Network: ${this.userProfile.total_connections || 0} connections</span>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    // Update quick stats
    updateQuickStats() {
        if (!this.userProfile) return;

        // Update CME credits
        const cmeElement = document.querySelector('[data-stat="cme"]');
        if (cmeElement) {
            cmeElement.textContent = this.userProfile.cme_stats?.yearlyCredits || this.userProfile.cme_credits || 0;
        }

        // Update connections
        const connectionsElement = document.querySelector('[data-stat="connections"]');
        if (connectionsElement) {
            connectionsElement.textContent = this.userProfile.connections_count || this.userProfile.total_connections || 0;
        }

        // Update case contributions
        const casesElement = document.querySelector('[data-stat="cases"]');
        if (casesElement) {
            casesElement.textContent = this.userProfile.case_contributions || 0;
        }

        // Update peer rating
        const ratingElement = document.querySelector('[data-stat="rating"]');
        if (ratingElement) {
            ratingElement.textContent = this.userProfile.peer_rating || '4.8';
        }
    }

    // Update last login time
    updateLastLogin() {
        const lastLoginElement = document.getElementById('lastLogin');
        if (lastLoginElement) {
            const lastSign = this.currentUser.last_sign_in_at;
            if (lastSign) {
                const date = new Date(lastSign);
                lastLoginElement.textContent = this.formatDate(date);
            } else {
                lastLoginElement.textContent = 'First login';
            }
        }
    }

    // Load recent CME activities
    async loadRecentCME() {
        try {
            const result = await cmeActivities.getUserActivities(this.currentUser.id);
            if (result.success) {
                this.updateCMEProgress(result.data);
            }
        } catch (error) {
            console.error('Failed to load CME activities:', error);
        }
    }

    // Update CME progress display
    updateCMEProgress(activities) {
        // Calculate current year progress
        const currentYear = new Date().getFullYear();
        const yearlyActivities = activities.filter(activity => 
            new Date(activity.completion_date).getFullYear() === currentYear
        );
        
        const yearlyCredits = yearlyActivities.reduce((sum, activity) => 
            sum + parseFloat(activity.credits_earned), 0
        );

        // Update progress bar
        const progressBar = document.querySelector('.cme-progress-bar');
        if (progressBar) {
            const percentage = Math.min((yearlyCredits / 30) * 100, 100);
            progressBar.style.width = `${percentage}%`;
        }

        // Update recent activities list
        const activitiesList = document.querySelector('.recent-cme-activities');
        if (activitiesList && yearlyActivities.length > 0) {
            activitiesList.innerHTML = yearlyActivities.slice(0, 3).map(activity => `
                <div class="flex items-center justify-between p-3 bg-success-50 rounded-lg border border-success-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-success-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-secondary">${activity.activity_title}</p>
                            <p class="text-xs text-text-secondary">Completed ${this.formatDate(new Date(activity.completion_date))}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-success">+${activity.credits_earned} Credits</span>
                </div>
            `).join('');
        }
    }

    // Load notifications
    async loadNotifications() {
        try {
            const result = await notifications.getUserNotifications(this.currentUser.id, 5);
            if (result.success) {
                this.updateNotificationsDisplay(result.data);
            }
        } catch (error) {
            console.error('Failed to load notifications:', error);
        }
    }

    // Update notifications display
    updateNotificationsDisplay(notificationsList) {
        const container = document.querySelector('.notifications-container');
        if (container && notificationsList.length > 0) {
            container.innerHTML = notificationsList.map(notification => `
                <div class="flex items-start space-x-3 p-3 bg-${this.getNotificationColor(notification.type)}-50 rounded-lg border border-${this.getNotificationColor(notification.type)}-200">
                    <div class="w-2 h-2 bg-${this.getNotificationColor(notification.type)} rounded-full mt-2 flex-shrink-0 ${notification.read ? 'opacity-50' : ''}"></div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-secondary">${notification.title}</p>
                        <p class="text-xs text-text-secondary">${notification.message}</p>
                        <p class="text-xs text-text-secondary mt-1">${this.formatRelativeTime(new Date(notification.created_at))}</p>
                    </div>
                </div>
            `).join('');
        }
    }

    // Register update callback
    registerCallback(event, callback) {
        if (!this.updateCallbacks.has(event)) {
            this.updateCallbacks.set(event, []);
        }
        this.updateCallbacks.get(event).push(callback);
    }

    // Trigger callback
    triggerCallback(event, data) {
        const callbacks = this.updateCallbacks.get(event);
        if (callbacks) {
            callbacks.forEach(callback => callback(data));
        }
    }

    // Utility methods
    getInitials(name) {
        return name
            .split(' ')
            .map(n => n[0])
            .join('')
            .toUpperCase()
            .substring(0, 2);
    }

    formatDate(date) {
        return new Intl.DateTimeFormat('en-IN', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }

    formatRelativeTime(date) {
        const now = new Date();
        const diff = now - date;
        
        if (diff < 3600000) { // Less than 1 hour
            const minutes = Math.floor(diff / 60000);
            return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
        } else if (diff < 86400000) { // Less than 1 day
            const hours = Math.floor(diff / 3600000);
            return `${hours} ${hours === 1 ? 'hour' : 'hours'} ago`;
        } else {
            const days = Math.floor(diff / 86400000);
            return `${days} ${days === 1 ? 'day' : 'days'} ago`;
        }
    }

    getNotificationColor(type) {
        const colors = {
            'cme_update': 'accent',
            'connection_request': 'primary',
            'case_featured': 'success',
            'default': 'primary'
        };
        return colors[type] || colors.default;
    }

    // Cleanup
    destroy() {
        // Unsubscribe from all real-time subscriptions
        this.subscriptions.forEach(subKey => {
            realTime.unsubscribe(subKey);
        });
        this.subscriptions = [];
        this.updateCallbacks.clear();
    }
}

// Export singleton instance
export const dashboardManager = new DashboardManager();