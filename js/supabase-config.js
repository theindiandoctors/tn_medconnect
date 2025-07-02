// Supabase configuration - Replace with your actual Supabase URL and anon key
// 
// 📋 STEP-BY-STEP GUIDE TO GET YOUR CREDENTIALS:
// 
// 1. Go to https://app.supabase.com and sign in
// 2. Select your project (or create a new one if you haven't already)
// 3. In the left sidebar, click on "Settings" (gear icon at bottom)
// 4. Click on "API" in the settings menu
// 5. You'll see two important values:
//    
//    📍 PROJECT URL:
//    - Look for "Project URL" section
//    - Copy the URL that looks like: https://abcdefghijklmnop.supabase.co
//    - Replace 'YOUR_SUPABASE_URL_HERE' below with this URL
//    
//    🔑 ANON KEY:
//    - Look for "Project API keys" section
//    - Find the "anon" key (also called "public" key)
//    - Copy the long JWT token that starts with "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
//    - Replace 'YOUR_SUPABASE_ANON_KEY_HERE' below with this token
//    
// ⚠️  IMPORTANT: 
// - The anon/public key is safe to use in client-side code
// - Never use the "service_role" key in client-side code
// - Keep your "service_role" key secret and only use it server-side
//
const supabaseUrl = 'YOUR_SUPABASE_URL_HERE';
const supabaseAnonKey = 'YOUR_SUPABASE_ANON_KEY_HERE';

// Example of what your configured values should look like:
// const supabaseUrl = 'https://abcdefghijklmnop.supabase.co';
// const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFiY2RlZmdoaWprbG1ub3AiLCJyb2xlIjoiYW5vbiIsImlhdCI6MTYzMDUwNDczMywiZXhwIjoxOTQ2MDgwNzMzfQ.example_signature_here';

// Configuration validation helper
function validateSupabaseConfig(url, key) {
    const errors = [];
    
    if (!url || url === 'YOUR_SUPABASE_URL' || url === 'YOUR_SUPABASE_URL_HERE' || url.includes('YOUR_SUPABASE_URL') || url.includes('your-project-ref')) {
        errors.push('Supabase URL is not configured. Please set your actual Supabase project URL.');
    }
    
    if (!key || key === 'YOUR_SUPABASE_ANON_KEY' || key === 'YOUR_SUPABASE_ANON_KEY_HERE' || key.includes('YOUR_SUPABASE_ANON_KEY') || key.includes('your_actual_anon_key_here')) {
        errors.push('Supabase Anon Key is not configured. Please set your actual Supabase anon key.');
    }
    
    // Basic URL validation
    if (url && !url.includes('your-project-ref') && !url.includes('YOUR_SUPABASE_URL') && url !== 'YOUR_SUPABASE_URL_HERE') {
        try {
            const urlObj = new URL(url);
            if (!urlObj.hostname.includes('supabase.co')) {
                errors.push('Invalid Supabase URL format. URL should end with .supabase.co');
            }
        } catch (e) {
            errors.push('Invalid Supabase URL format. Please ensure it starts with https:// and is a valid URL.');
        }
    }
    
    return errors;
}

// Enhanced error handler for network issues
function handleNetworkError(error, operation = 'operation') {
    console.error(`Network error during ${operation}:`, error);
    
    if (error.message.includes('Failed to fetch')) {
        return {
            success: false,
            error: 'Unable to connect to the server. Please check your internet connection and try again.',
            code: 'NETWORK_ERROR'
        };
    }
    
    if (error.message.includes('CORS')) {
        return {
            success: false,
            error: 'Connection blocked by browser security. Please try refreshing the page.',
            code: 'CORS_ERROR'
        };
    }
    
    if (error.message.includes('timeout')) {
        return {
            success: false,
            error: 'Request timed out. Please check your internet connection and try again.',
            code: 'TIMEOUT_ERROR'
        };
    }
    
    return {
        success: false,
        error: `Connection failed during ${operation}. Please try again in a few moments.`,
        code: 'UNKNOWN_NETWORK_ERROR'
    };
}

// Validate configuration before creating client
const configErrors = validateSupabaseConfig(supabaseUrl, supabaseAnonKey);

let supabase;
let configurationValid = false;

if (configErrors.length > 0) {
    console.error('❌ Supabase Configuration Error:');
    configErrors.forEach(error => console.error(`  - ${error}`));
    console.error('\n📝 Setup Instructions:');
    console.error('  1. Go to https://app.supabase.com');
    console.error('  2. Create a new project or select existing one');
    console.error('  3. Go to Settings > API');
    console.error('  4. Copy your Project URL and anon/public key');
    console.error('  5. Replace the placeholder values in js/supabase-config.js');
    console.error('\n🔗 Your URL should look like: https://abcdefg.supabase.co');
    
    // Create a dummy client to prevent further errors
    window.supabaseConfigError = configErrors;
    configurationValid = false;
} else {
    // Create Supabase client only if configuration is valid
    supabase = createClient(supabaseUrl, supabaseAnonKey);
    configurationValid = true;
}

// Export the client (might be undefined if config is invalid)
export { supabase };

// Auth helper functions with enhanced error handling
export const auth = {
    // Sign up new user
    async signUp(email, password, userData = {}) {
        if (!configurationValid) {
            return {
                success: false,
                error: 'Supabase is not properly configured. Please check the console for setup instructions.',
                code: 'CONFIG_ERROR'
            };
        }

        try {
            const { data, error } = await supabase.auth.signUp({
                email,
                password,
                options: {
                    data: {
                        specialty: userData.specialty || '',
                        career_stage: userData.careerStage || '',
                        institution: userData.institution || '',
                        full_name: userData.fullName || '',
                        license_number: userData.licenseNumber || ''
                    }
                }
            });
            
            if (error) {
                // Handle specific Supabase auth errors
                if (error.message.includes('Invalid login credentials')) {
                    throw new Error('Invalid email or password. Please check your credentials.');
                }
                if (error.message.includes('Email already registered')) {
                    throw new Error('An account with this email already exists. Please try signing in instead.');
                }
                if (error.message.includes('Password should be at least')) {
                    throw new Error('Password is too weak. Please use at least 6 characters.');
                }
                throw error;
            }
            
            return { success: true, data };
        } catch (error) {
            console.error('Signup error:', error);
            
            // Handle network errors
            if (error.message.includes('Failed to fetch') || error.name === 'TypeError') {
                return handleNetworkError(error, 'sign up');
            }
            
            return { 
                success: false, 
                error: error.message || 'Failed to create account. Please try again.',
                code: 'SIGNUP_ERROR'
            };
        }
    },

    // Sign in user with enhanced error handling
    async signIn(email, password) {
        if (!configurationValid) {
            return {
                success: false,
                error: 'Supabase is not properly configured. Please check the console for setup instructions.',
                code: 'CONFIG_ERROR'
            };
        }

        try {
            const { data, error } = await supabase.auth.signInWithPassword({
                email,
                password
            });
            
            if (error) {
                if (error.message.includes('Invalid login credentials')) {
                    throw new Error('Invalid email or password. Please check your credentials.');
                }
                if (error.message.includes('Email not confirmed')) {
                    throw new Error('Please check your email and click the confirmation link before signing in.');
                }
                throw error;
            }
            
            return { success: true, data };
        } catch (error) {
            console.error('Signin error:', error);
            
            // Handle network errors
            if (error.message.includes('Failed to fetch') || error.name === 'TypeError') {
                return handleNetworkError(error, 'sign in');
            }
            
            return { 
                success: false, 
                error: error.message || 'Failed to sign in. Please try again.',
                code: 'SIGNIN_ERROR'
            };
        }
    },

    // Sign out user
    async signOut() {
        if (!configurationValid) {
            return { success: true }; // Allow logout even if config is invalid
        }

        try {
            const { error } = await supabase.auth.signOut();
            if (error) throw error;
            return { success: true };
        } catch (error) {
            console.error('Signout error:', error);
            
            if (error.message.includes('Failed to fetch') || error.name === 'TypeError') {
                return handleNetworkError(error, 'sign out');
            }
            
            return { success: false, error: error.message };
        }
    },

    // Reset password
    async resetPassword(email) {
        if (!configurationValid) {
            return {
                success: false,
                error: 'Supabase is not properly configured. Please check the console for setup instructions.',
                code: 'CONFIG_ERROR'
            };
        }

        try {
            const { error } = await supabase.auth.resetPasswordForEmail(email, {
                redirectTo: `${window.location.origin}/pages/reset-password.html`
            });
            
            if (error) throw error;
            return { success: true };
        } catch (error) {
            console.error('Reset password error:', error);
            
            if (error.message.includes('Failed to fetch') || error.name === 'TypeError') {
                return handleNetworkError(error, 'reset password');
            }
            
            return { success: false, error: error.message };
        }
    },

    // Get current user with error handling
    async getCurrentUser() {
        if (!configurationValid) {
            return { success: true, user: null };
        }

        try {
            const { data: { user }, error } = await supabase.auth.getUser();
            if (error) throw error;
            return { success: true, user };
        } catch (error) {
            console.error('Get current user error:', error);
            
            if (error.message.includes('Failed to fetch') || error.name === 'TypeError') {
                const networkError = handleNetworkError(error, 'get user');
                return { success: true, user: null, networkError: true }; // Allow app to continue
            }
            
            return { success: false, error: error.message };
        }
    },

    // Check if user is authenticated
    async isAuthenticated() {
        const result = await this.getCurrentUser();
        return result.user !== null;
    }
};

// User profile functions
export const userProfile = {
    // Get user profile
    async getProfile(userId) {
        try {
            const { data, error } = await supabase
                .from('user_profiles')
                .select('*')
                .eq('user_id', userId)
                .single();
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Update user profile
    async updateProfile(userId, profileData) {
        try {
            const { data, error } = await supabase
                .from('user_profiles')
                .upsert({
                    user_id: userId,
                    ...profileData,
                    updated_at: new Date().toISOString()
                });
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Get detailed profile with stats
    async getDetailedProfile(userId) {
        try {
            const { data: profile, error: profileError } = await supabase
                .from('user_profiles')
                .select('*')
                .eq('user_id', userId)
                .single();
            
            if (profileError) throw profileError;

            // Get CME stats
            const cmeStats = await cmeActivities.getStatistics(userId);
            
            // Get connections count
            const connections = await this.getConnectionsCount(userId);

            return { 
                success: true, 
                data: {
                    ...profile,
                    cme_stats: cmeStats.success ? cmeStats.data : null,
                    connections_count: connections.success ? connections.data : 0
                }
            };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Get connections count
    async getConnectionsCount(userId) {
        try {
            const { count, error } = await supabase
                .from('connections')
                .select('*', { count: 'exact', head: true })
                .or(`requester_id.eq.${userId},addressee_id.eq.${userId}`)
                .eq('status', 'accepted');
            
            if (error) throw error;
            return { success: true, data: count };
        } catch (error) {
            return { success: false, error: error.message };
        }
    }
};

// Session management
export const session = {
    // Check session and redirect if not authenticated
    async requireAuth(redirectTo = '/pages/login.html') {
        const { user } = await auth.getCurrentUser();
        if (!user) {
            window.location.href = redirectTo;
            return false;
        }
        return true;
    },

    // Handle auth state changes
    onAuthStateChange(callback) {
        return supabase.auth.onAuthStateChange(callback);
    }
};

// Real-time subscriptions manager
export const realTime = {
    subscriptions: new Map(),

    // Subscribe to table changes
    subscribe(table, callback, options = {}) {
        const subscriptionKey = `${table}_${Date.now()}`;
        
        const subscription = supabase
            .channel(`${table}_changes`)
            .on('postgres_changes', {
                event: '*',
                schema: 'public',
                table: table,
                ...options
            }, callback)
            .subscribe();

        this.subscriptions.set(subscriptionKey, subscription);
        return subscriptionKey;
    },

    // Unsubscribe from specific subscription
    unsubscribe(subscriptionKey) {
        const subscription = this.subscriptions.get(subscriptionKey);
        if (subscription) {
            subscription.unsubscribe();
            this.subscriptions.delete(subscriptionKey);
        }
    },

    // Unsubscribe from all subscriptions
    unsubscribeAll() {
        this.subscriptions.forEach(subscription => {
            subscription.unsubscribe();
        });
        this.subscriptions.clear();
    }
};

// CME Activities API
export const cmeActivities = {
    // Get user's CME activities
    async getUserActivities(userId) {
        try {
            const { data, error } = await supabase
                .from('cme_activities')
                .select('*')
                .eq('user_id', userId)
                .order('completion_date', { ascending: false });
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Add new CME activity
    async addActivity(activityData) {
        try {
            const { data: { user } } = await supabase.auth.getUser();
            if (!user) throw new Error('User not authenticated');

            const { data, error } = await supabase
                .from('cme_activities')
                .insert({
                    user_id: user.id,
                    ...activityData,
                    created_at: new Date().toISOString()
                })
                .select();
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Update CME activity
    async updateActivity(activityId, activityData) {
        try {
            const { data, error } = await supabase
                .from('cme_activities')
                .update({
                    ...activityData,
                    updated_at: new Date().toISOString()
                })
                .eq('id', activityId)
                .select();
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Get CME statistics
    async getStatistics(userId) {
        try {
            const { data, error } = await supabase
                .from('cme_activities')
                .select('credits_earned, completion_date')
                .eq('user_id', userId);
            
            if (error) throw error;

            const currentYear = new Date().getFullYear();
            const yearlyCredits = data
                .filter(activity => new Date(activity.completion_date).getFullYear() === currentYear)
                .reduce((sum, activity) => sum + parseFloat(activity.credits_earned), 0);

            const totalCredits = data.reduce((sum, activity) => sum + parseFloat(activity.credits_earned), 0);

            return { 
                success: true, 
                data: {
                    yearlyCredits,
                    totalCredits,
                    activitiesCount: data.length,
                    yearlyActivities: data.filter(activity => 
                        new Date(activity.completion_date).getFullYear() === currentYear
                    ).length
                }
            };
        } catch (error) {
            return { success: false, error: error.message };
        }
    }
};

// Professional Connections API
export const connections = {
    // Get user's connections
    async getUserConnections(userId, status = 'accepted') {
        try {
            const { data, error } = await supabase
                .from('connections')
                .select(`
                    *,
                    requester:requester_id(id, email, user_profiles(full_name, specialty, institution)),
                    addressee:addressee_id(id, email, user_profiles(full_name, specialty, institution))
                `)
                .or(`requester_id.eq.${userId},addressee_id.eq.${userId}`)
                .eq('status', status)
                .order('created_at', { ascending: false });
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Send connection request
    async sendRequest(addresseeId) {
        try {
            const { data: { user } } = await supabase.auth.getUser();
            if (!user) throw new Error('User not authenticated');

            const { data, error } = await supabase
                .from('connections')
                .insert({
                    requester_id: user.id,
                    addressee_id: addresseeId,
                    status: 'pending'
                })
                .select();
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Respond to connection request
    async respondToRequest(connectionId, status) {
        try {
            const { data, error } = await supabase
                .from('connections')
                .update({ 
                    status,
                    updated_at: new Date().toISOString()
                })
                .eq('id', connectionId)
                .select();
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Get pending requests for user
    async getPendingRequests(userId) {
        try {
            const { data, error } = await supabase
                .from('connections')
                .select(`
                    *,
                    requester:requester_id(id, email, user_profiles(full_name, specialty, institution))
                `)
                .eq('addressee_id', userId)
                .eq('status', 'pending')
                .order('created_at', { ascending: false });
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    }
};

// Case Studies API
export const caseStudies = {
    // Get published case studies
    async getPublished(options = {}) {
        try {
            let query = supabase
                .from('case_studies')
                .select(`
                    *,
                    author:author_id(id, email, user_profiles(full_name, specialty, institution))
                `)
                .eq('is_published', true);

            if (options.specialty) {
                query = query.eq('specialty', options.specialty);
            }

            if (options.limit) {
                query = query.limit(options.limit);
            }

            query = query.order('created_at', { ascending: false });

            const { data, error } = await query;
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Get user's case studies
    async getUserCases(userId) {
        try {
            const { data, error } = await supabase
                .from('case_studies')
                .select('*')
                .eq('author_id', userId)
                .order('created_at', { ascending: false });
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Create new case study
    async create(caseData) {
        try {
            const { data: { user } } = await supabase.auth.getUser();
            if (!user) throw new Error('User not authenticated');

            const { data, error } = await supabase
                .from('case_studies')
                .insert({
                    author_id: user.id,
                    ...caseData,
                    created_at: new Date().toISOString()
                })
                .select();
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Update case study
    async update(caseId, caseData) {
        try {
            const { data, error } = await supabase
                .from('case_studies')
                .update({
                    ...caseData,
                    updated_at: new Date().toISOString()
                })
                .eq('id', caseId)
                .select();
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Increment view count
    async incrementViews(caseId) {
        try {
            const { data, error } = await supabase.rpc('increment_case_views', {
                case_id: caseId
            });
            
            if (error) throw error;
            return { success: true };
        } catch (error) {
            return { success: false, error: error.message };
        }
    }
};

// Notifications API
export const notifications = {
    // Get user notifications
    async getUserNotifications(userId, limit = 10) {
        try {
            // For now, create mock notifications based on recent activities
            // In a full implementation, you'd have a notifications table
            const mockNotifications = [
                {
                    id: 1,
                    type: 'cme_update',
                    title: 'New CME Requirement Update',
                    message: 'Tamil Nadu Medical Council has updated CME requirements for 2025',
                    read: false,
                    created_at: new Date(Date.now() - 3600000).toISOString()
                },
                {
                    id: 2,
                    type: 'connection_request',
                    title: 'Mentorship Request',
                    message: 'Dr. Kavya Reddy has requested mentorship in interventional cardiology',
                    read: false,
                    created_at: new Date(Date.now() - 10800000).toISOString()
                },
                {
                    id: 3,
                    type: 'case_featured',
                    title: 'Case Study Featured',
                    message: 'Your recent case study has been featured in the Knowledge Hub',
                    read: true,
                    created_at: new Date(Date.now() - 86400000).toISOString()
                }
            ];

            return { success: true, data: mockNotifications.slice(0, limit) };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Mark notification as read
    async markAsRead(notificationId) {
        try {
            // Mock implementation - in reality, update notifications table
            return { success: true };
        } catch (error) {
            return { success: false, error: error.message };
        }
    }
};

// Reference data API
export const referenceData = {
    // Get medical specialties
    async getSpecialties() {
        try {
            const { data, error } = await supabase
                .from('medical_specialties')
                .select('*')
                .order('name');
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Get career stages
    async getCareerStages() {
        try {
            const { data, error } = await supabase
                .from('career_stages')
                .select('*')
                .order('name');
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    },

    // Get institutions
    async getInstitutions() {
        try {
            const { data, error } = await supabase
                .from('institutions')
                .select('*')
                .order('name');
            
            if (error) throw error;
            return { success: true, data };
        } catch (error) {
            return { success: false, error: error.message };
        }
    }
};