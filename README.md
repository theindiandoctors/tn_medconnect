# Medical Professional Platform

A comprehensive platform for medical professionals to connect, learn, and grow in their careers.

## Features

- User Authentication & Authorization
- Professional Dashboard
- Medical Knowledge Hub
- Career Development Center
- Professional Networking Portal
- Medical Resources Library

## Setup Instructions

### 1. Supabase Configuration

This project uses Supabase for backend services. To set up Supabase:

1. **Create a Supabase Project**
   - Go to [https://app.supabase.com](https://app.supabase.com)
   - Click "New Project"
   - Fill in your project details
   - Wait for the project to be created

2. **Get Your Credentials**
   - In your Supabase dashboard, go to **Settings** > **API**
   - Copy your **Project URL** (it looks like: `https://your-project-id.supabase.co`)
   - Copy your **anon/public key** (starts with `eyJ...`)

3. **Configure the Application**
   - Open `js/supabase-config.js`
   - Replace `YOUR_SUPABASE_URL` with your actual Project URL
   - Replace `YOUR_SUPABASE_ANON_KEY` with your actual anon key

   ```javascript
   const supabaseUrl = 'https://your-project-id.supabase.co';
   const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';
   ```

4. **Set Up Database Schema**
   - In your Supabase dashboard, go to **SQL Editor**
   - Copy the contents of `sql/setup.sql`
   - Run the SQL commands to create the necessary tables

### 2. Running the Application

This is a static HTML application that can be served using any web server:

#### Option 1: Using Live Server (VS Code)
1. Install the "Live Server" extension in VS Code
2. Right-click on `index.html`
3. Select "Open with Live Server"

#### Option 2: Using Python
```bash
# Python 3
python -m http.server 8000

# Python 2
python -m SimpleHTTPServer 8000
```

#### Option 3: Using Node.js
```bash
npx http-server
```

### 3. Troubleshooting

**Error: "TypeError: Failed to construct 'URL': Invalid URL"**
- This error occurs when Supabase credentials are not properly configured
- Make sure you've replaced the placeholder values in `js/supabase-config.js`
- Check the browser console for detailed setup instructions

**Authentication Issues**
- Ensure your Supabase project has the correct authentication settings
- Check that email auth is enabled in your Supabase dashboard

## Project Structure

```
├── index.html                          # Landing page
├── pages/                              # Application pages
│   ├── login.html
│   ├── signup.html
│   ├── professional_dashboard.html
│   └── ...
├── js/                                 # JavaScript files
│   └── supabase-config.js             # Supabase configuration
├── css/                               # Stylesheets
│   ├── main.css
│   └── tailwind.css
├── sql/                               # Database schema
│   └── setup.sql
└── public/                            # Static assets
    └── ...
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.

Built with ❤️ on Rocket.new