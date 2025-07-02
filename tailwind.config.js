module.exports = {
  content: ["./pages/*.{html,js}", "./index.html"],
  theme: {
    extend: {
      colors: {
        // Primary Colors - Medical Authority
        primary: {
          DEFAULT: "#1e40af", // blue-700
          50: "#eff6ff", // blue-50
          100: "#dbeafe", // blue-100
          200: "#bfdbfe", // blue-200
          300: "#93c5fd", // blue-300
          400: "#60a5fa", // blue-400
          500: "#3b82f6", // blue-500
          600: "#2563eb", // blue-600
          700: "#1d4ed8", // blue-700
          800: "#1e3a8a", // blue-800
          900: "#1e40af", // blue-900
        },
        // Secondary Colors - Professional Depth
        secondary: {
          DEFAULT: "#0f172a", // slate-900
          50: "#f8fafc", // slate-50
          100: "#f1f5f9", // slate-100
          200: "#e2e8f0", // slate-200
          300: "#cbd5e1", // slate-300
          400: "#94a3b8", // slate-400
          500: "#64748b", // slate-500
          600: "#475569", // slate-600
          700: "#334155", // slate-700
          800: "#1e293b", // slate-800
          900: "#0f172a", // slate-900
        },
        // Accent Colors - Critical Alerts
        accent: {
          DEFAULT: "#dc2626", // red-600
          50: "#fef2f2", // red-50
          100: "#fee2e2", // red-100
          200: "#fecaca", // red-200
          300: "#fca5a5", // red-300
          400: "#f87171", // red-400
          500: "#ef4444", // red-500
          600: "#dc2626", // red-600
          700: "#b91c1c", // red-700
          800: "#991b1b", // red-800
          900: "#7f1d1d", // red-900
        },
        // Background Colors
        background: "#fefefe", // neutral-50
        surface: "#f8fafc", // slate-50
        // Text Colors
        text: {
          primary: "#0f172a", // slate-900
          secondary: "#64748b", // slate-500
        },
        // Status Colors
        success: {
          DEFAULT: "#059669", // emerald-600
          50: "#ecfdf5", // emerald-50
          100: "#d1fae5", // emerald-100
          500: "#10b981", // emerald-500
          600: "#059669", // emerald-600
        },
        warning: {
          DEFAULT: "#d97706", // amber-600
          50: "#fffbeb", // amber-50
          100: "#fef3c7", // amber-100
          500: "#f59e0b", // amber-500
          600: "#d97706", // amber-600
        },
        error: {
          DEFAULT: "#dc2626", // red-600
          50: "#fef2f2", // red-50
          100: "#fee2e2", // red-100
          500: "#ef4444", // red-500
          600: "#dc2626", // red-600
        },
        // Border Colors
        border: {
          DEFAULT: "#e2e8f0", // slate-200
          light: "#f1f5f9", // slate-100
        },
      },
      fontFamily: {
        inter: ['Inter', 'sans-serif'],
        'source-sans': ['Source Sans Pro', 'sans-serif'],
        'crimson': ['Crimson Text', 'serif'],
        sans: ['Source Sans Pro', 'sans-serif'],
        serif: ['Crimson Text', 'serif'],
      },
      fontSize: {
        'xs': ['0.75rem', { lineHeight: '1rem' }],
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'base': ['1rem', { lineHeight: '1.5rem' }],
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
        '2xl': ['1.5rem', { lineHeight: '2rem' }],
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
        '5xl': ['3rem', { lineHeight: '1' }],
        '6xl': ['3.75rem', { lineHeight: '1' }],
      },
      boxShadow: {
        'clinical': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'clinical-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
      },
      borderRadius: {
        'clinical': '0.5rem',
      },
      transitionDuration: {
        'clinical': '300ms',
      },
      transitionTimingFunction: {
        'clinical': 'cubic-bezier(0.4, 0, 0.2, 1)',
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
      },
    },
  },
  plugins: [],
}