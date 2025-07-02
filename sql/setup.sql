-- Enable Row Level Security
ALTER TABLE auth.users ENABLE ROW LEVEL SECURITY;

-- Create user_profiles table to store additional user information
CREATE TABLE public.user_profiles (
    id UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
    user_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    full_name TEXT,
    specialty TEXT,
    career_stage TEXT,
    institution TEXT,
    license_number TEXT,
    bio TEXT,
    profile_image_url TEXT,
    is_verified BOOLEAN DEFAULT FALSE,
    cme_credits INTEGER DEFAULT 0,
    peer_rating DECIMAL(3,2) DEFAULT 0.0,
    total_connections INTEGER DEFAULT 0,
    case_contributions INTEGER DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS on user_profiles
ALTER TABLE public.user_profiles ENABLE ROW LEVEL SECURITY;

-- Create policy for user_profiles (users can only see and edit their own profile)
CREATE POLICY "Users can view own profile" ON public.user_profiles
    FOR SELECT USING (auth.uid() = user_id);

CREATE POLICY "Users can update own profile" ON public.user_profiles
    FOR UPDATE USING (auth.uid() = user_id);

CREATE POLICY "Users can insert own profile" ON public.user_profiles
    FOR INSERT WITH CHECK (auth.uid() = user_id);

-- Create medical_specialties table for reference
CREATE TABLE public.medical_specialties (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Insert common medical specialties
INSERT INTO public.medical_specialties (name, code, description) VALUES
('Cardiology', 'cardiology', 'Heart and cardiovascular system'),
('Orthopedics', 'orthopedics', 'Bones, joints, and musculoskeletal system'),
('Pediatrics', 'pediatrics', 'Medical care of infants, children, and adolescents'),
('Internal Medicine', 'internal-medicine', 'Prevention, diagnosis, and treatment of adult diseases'),
('Surgery', 'surgery', 'Operative treatment of diseases and injuries'),
('Radiology', 'radiology', 'Medical imaging and image-guided procedures'),
('Anesthesiology', 'anesthesiology', 'Perioperative care and pain management'),
('Dermatology', 'dermatology', 'Skin, hair, nails, and related conditions'),
('Neurology', 'neurology', 'Nervous system disorders'),
('Psychiatry', 'psychiatry', 'Mental health and behavioral disorders'),
('Gynecology & Obstetrics', 'gynecology', 'Female reproductive health and childbirth'),
('Emergency Medicine', 'emergency-medicine', 'Acute care and emergency medical services'),
('Pathology', 'pathology', 'Laboratory medicine and disease diagnosis'),
('Oncology', 'oncology', 'Cancer treatment and care');

-- Create career_stages table for reference
CREATE TABLE public.career_stages (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Insert career stages
INSERT INTO public.career_stages (name, code, description) VALUES
('Medical Student', 'medical-student', 'Currently pursuing medical degree'),
('Intern', 'intern', 'First year after medical school'),
('Resident', 'resident', 'Specialized training period'),
('Fellow', 'fellow', 'Subspecialty training'),
('Practicing Physician', 'practicing-physician', 'Established medical practice'),
('Consultant', 'consultant', 'Senior practicing physician'),
('Academic/Professor', 'academic', 'Teaching and research position'),
('Healthcare Administrator', 'administrator', 'Healthcare management role');

-- Create institutions table for reference
CREATE TABLE public.institutions (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    type TEXT, -- hospital, clinic, academic, etc.
    city TEXT,
    state TEXT DEFAULT 'Tamil Nadu',
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Insert major Tamil Nadu medical institutions
INSERT INTO public.institutions (name, type, city, is_verified) VALUES
('All Institute of Medical Sciences (AIIMS), Madurai', 'academic', 'Madurai', true),
('Christian Medical College (CMC), Vellore', 'academic', 'Vellore', true),
('Stanley Medical College', 'academic', 'Chennai', true),
('Madras Medical College', 'academic', 'Chennai', true),
('Government General Hospital, Chennai', 'hospital', 'Chennai', true),
('Apollo Hospitals', 'hospital', 'Chennai', true),
('Fortis Malar Hospital', 'hospital', 'Chennai', true),
('JIPMER, Puducherry', 'academic', 'Puducherry', true),
('PSG Institute of Medical Sciences', 'academic', 'Coimbatore', true),
('Sri Ramachandra Institute of Higher Education', 'academic', 'Chennai', true);

-- Create function to handle user profile creation on signup
CREATE OR REPLACE FUNCTION public.handle_new_user()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO public.user_profiles (
        id,
        user_id,
        full_name,
        specialty,
        career_stage,
        institution,
        license_number
    )
    VALUES (
        NEW.id,
        NEW.id,
        NEW.raw_user_meta_data->>'full_name',
        NEW.raw_user_meta_data->>'specialty',
        NEW.raw_user_meta_data->>'career_stage',
        NEW.raw_user_meta_data->>'institution',
        NEW.raw_user_meta_data->>'license_number'
    );
    RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Create trigger to automatically create profile on user signup
CREATE TRIGGER on_auth_user_created
    AFTER INSERT ON auth.users
    FOR EACH ROW EXECUTE FUNCTION public.handle_new_user();

-- Create updated_at trigger function
CREATE OR REPLACE FUNCTION public.handle_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create trigger for updated_at on user_profiles
CREATE TRIGGER handle_user_profiles_updated_at
    BEFORE UPDATE ON public.user_profiles
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();

-- Create CME activities table
CREATE TABLE public.cme_activities (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    activity_title TEXT NOT NULL,
    activity_type TEXT NOT NULL, -- webinar, conference, course, etc.
    credits_earned DECIMAL(3,1) NOT NULL,
    completion_date DATE NOT NULL,
    provider TEXT,
    certificate_url TEXT,
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS on cme_activities
ALTER TABLE public.cme_activities ENABLE ROW LEVEL SECURITY;

-- Create policies for cme_activities
CREATE POLICY "Users can view own cme activities" ON public.cme_activities
    FOR SELECT USING (auth.uid() = user_id);

CREATE POLICY "Users can insert own cme activities" ON public.cme_activities
    FOR INSERT WITH CHECK (auth.uid() = user_id);

CREATE POLICY "Users can update own cme activities" ON public.cme_activities
    FOR UPDATE USING (auth.uid() = user_id);

-- Create connections table for professional networking
CREATE TABLE public.connections (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    requester_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    addressee_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    status TEXT DEFAULT 'pending' CHECK (status IN ('pending', 'accepted', 'declined')),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    UNIQUE(requester_id, addressee_id)
);

-- Enable RLS on connections
ALTER TABLE public.connections ENABLE ROW LEVEL SECURITY;

-- Create policies for connections
CREATE POLICY "Users can view their connections" ON public.connections
    FOR SELECT USING (auth.uid() = requester_id OR auth.uid() = addressee_id);

CREATE POLICY "Users can create connection requests" ON public.connections
    FOR INSERT WITH CHECK (auth.uid() = requester_id);

CREATE POLICY "Users can update connection status" ON public.connections
    FOR UPDATE USING (auth.uid() = addressee_id OR auth.uid() = requester_id);

-- Create case_studies table
CREATE TABLE public.case_studies (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    author_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    title TEXT NOT NULL,
    specialty TEXT NOT NULL,
    content TEXT NOT NULL,
    tags TEXT[],
    is_published BOOLEAN DEFAULT FALSE,
    peer_reviewed BOOLEAN DEFAULT FALSE,
    views_count INTEGER DEFAULT 0,
    likes_count INTEGER DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS on case_studies
ALTER TABLE public.case_studies ENABLE ROW LEVEL SECURITY;

-- Create policies for case_studies
CREATE POLICY "Published case studies are viewable by all authenticated users" ON public.case_studies
    FOR SELECT USING (is_published = true AND auth.role() = 'authenticated');

CREATE POLICY "Users can view their own case studies" ON public.case_studies
    FOR SELECT USING (auth.uid() = author_id);

CREATE POLICY "Users can create case studies" ON public.case_studies
    FOR INSERT WITH CHECK (auth.uid() = author_id);

CREATE POLICY "Users can update their own case studies" ON public.case_studies
    FOR UPDATE USING (auth.uid() = author_id);

-- Add updated_at triggers
CREATE TRIGGER handle_cme_activities_updated_at
    BEFORE UPDATE ON public.cme_activities
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();

CREATE TRIGGER handle_connections_updated_at
    BEFORE UPDATE ON public.connections
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();

CREATE TRIGGER handle_case_studies_updated_at
    BEFORE UPDATE ON public.case_studies
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();

-- Create indexes for better performance
CREATE INDEX idx_user_profiles_user_id ON public.user_profiles(user_id);
CREATE INDEX idx_user_profiles_specialty ON public.user_profiles(specialty);
CREATE INDEX idx_cme_activities_user_id ON public.cme_activities(user_id);
CREATE INDEX idx_connections_requester_id ON public.connections(requester_id);
CREATE INDEX idx_connections_addressee_id ON public.connections(addressee_id);
CREATE INDEX idx_case_studies_author_id ON public.case_studies(author_id);
CREATE INDEX idx_case_studies_specialty ON public.case_studies(specialty);
CREATE INDEX idx_case_studies_published ON public.case_studies(is_published);

-- Create function to increment case study views
CREATE OR REPLACE FUNCTION increment_case_views(case_id UUID)
RETURNS void AS $$
BEGIN
    UPDATE public.case_studies 
    SET views_count = views_count + 1 
    WHERE id = case_id;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Create function to update CME credits in user profile
CREATE OR REPLACE FUNCTION update_user_cme_credits()
RETURNS TRIGGER AS $$
BEGIN
    -- Update user's total CME credits
    UPDATE public.user_profiles 
    SET cme_credits = (
        SELECT COALESCE(SUM(credits_earned), 0)
        FROM public.cme_activities 
        WHERE user_id = NEW.user_id
    )
    WHERE user_id = NEW.user_id;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Create trigger to update CME credits when activities are added/updated
CREATE TRIGGER update_cme_credits_trigger
    AFTER INSERT OR UPDATE OR DELETE ON public.cme_activities
    FOR EACH ROW EXECUTE FUNCTION update_user_cme_credits();

-- Create notifications table for future use
CREATE TABLE public.notifications (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    type TEXT NOT NULL,
    title TEXT NOT NULL,
    message TEXT NOT NULL,
    read BOOLEAN DEFAULT FALSE,
    data JSONB,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS on notifications
ALTER TABLE public.notifications ENABLE ROW LEVEL SECURITY;

-- Create policies for notifications
CREATE POLICY "Users can view own notifications" ON public.notifications
    FOR SELECT USING (auth.uid() = user_id);

CREATE POLICY "Users can update own notifications" ON public.notifications
    FOR UPDATE USING (auth.uid() = user_id);

-- Add updated_at trigger for notifications
CREATE TRIGGER handle_notifications_updated_at
    BEFORE UPDATE ON public.notifications
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();

-- Create indexes for better performance on new tables
CREATE INDEX idx_notifications_user_id ON public.notifications(user_id);
CREATE INDEX idx_notifications_read ON public.notifications(read);
CREATE INDEX idx_notifications_type ON public.notifications(type);