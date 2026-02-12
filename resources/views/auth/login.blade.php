<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - School Inventory System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            user-select: none;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .login-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .login-form {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748; /* increased contrast */
            margin-bottom: 8px;
            user-select: none;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 48px; /* space for both left and right icons */
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
            color: #1a202c;
        }
        
        .form-input::placeholder {
            color: #a0aec0; /* better readability */
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        
        .form-input:focus::placeholder {
            opacity: 0.5;
        }
        
        .form-input:focus {
            outline: 3px solid #667eea; /* enhanced focus outline for accessibility */
            background: white;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }
        
        .form-input.error {
            border-color: #ef4444;
            background: #fef2f2;
            animation: shake 0.3s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        
        .input-icon {
            position: relative;
        }

        /* Left-side icon (user/lock) */
        .input-icon i:not(.password-toggle) {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
            z-index: 2;
            pointer-events: none;
        }

        /* Right-side show/hide password icon */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            font-size: 16px;
            transition: color 0.3s ease;
            z-index: 3;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .checkbox-input {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            accent-color: #667eea;
            cursor: pointer;
        }
        
        .checkbox-label {
            font-size: 14px;
            color: #4a5568;
            cursor: pointer;
            user-select: none;
        }
        
        .forgot-password {
            font-size: 14px;
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover,
        .forgot-password:focus {
            color: #5a67d8;
            text-decoration: underline;
            outline: none;
        }
        
        .login-button {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease, background-color 0.4s ease;
            position: relative;
            overflow: hidden;
            user-select: none;
        }
        
        .login-button:focus-visible {
            outline: 3px solid #5a67d8;
            outline-offset: 2px;
        }
        
        .login-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            background-color: #5a67d8;
        }
        
        .login-button:active:not(:disabled) {
            transform: translateY(0);
            box-shadow: none;
        }
        
        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-message {
            font-size: 14px;
            color: #ef4444;
            margin-top: 4px;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            user-select: none;
        }
        
        .error-message.show {
            display: block;
            opacity: 1;
        }
        
        .system-info {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
            user-select: none;
        }
        
        .role-hint {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 16px;
            padding: 8px;
            background: #f3f4f6;
            border-radius: 4px;
            user-select: none;
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
                border-radius: 15px;
                padding: 25px 20px !important;
            }
            
            .login-header {
                padding: 30px 20px;
                font-size: 90%;
            }
            
            .login-form {
                padding: 30px 20px !important;
            }
            
            .role-hint {
                font-size: 11px;
                padding: 6px;
            }
            
            .form-label {
                font-size: 13px;
            }
            
            .login-button {
                font-size: 14px;
                padding: 12px 20px;
            }
        }
    </style>
        
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-warehouse"></i> School Inventory</h1>
            <p>Sign in to manage your inventory system</p>
        </div>
        
        <div class="login-form">
            @if (session('status'))
                <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="role-hint">
                    <i class="fas fa-info-circle"></i> 
                    Use your email/username and password to access the system
                </div>
                
                <div class="form-group">
                    <label for="login" class="form-label">Email or Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="login" id="login" class="form-input" placeholder="Enter your email or username" value="{{ old('login') }}" required>
                    </div>
                    <div class="error-message" id="login-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-input" placeholder="Enter your password" required>
                        <i class="fas fa-eye password-toggle" id="password-toggle"></i>
                    </div>
                    <div class="error-message" id="password-error"></div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" class="checkbox-input">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Forgot password?
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="login-button" id="login-button">
                    <div class="loading-spinner" id="loading-spinner"></div>
                    <span id="button-text">Sign In</span>
                </button>
                
                <div class="system-info">
                    <p><i class="fas fa-shield-alt"></i> Secure login for School Inventory Management System</p>
                    <p>© 2025 School Inventory System. All rights reserved.</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        const passwordToggle = document.getElementById('password-toggle');
        const passwordInput = document.getElementById('password');
        
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        
        const loginForm = document.getElementById('login-form');
        const loginButton = document.getElementById('login-button');
        const loadingSpinner = document.getElementById('loading-spinner');
        const buttonText = document.getElementById('button-text');
        
        loginForm.addEventListener('submit', function(e) {
            loginButton.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            buttonText.textContent = 'Signing In...';
        });
        
        const loginInput = document.getElementById('login');
        const loginError = document.getElementById('login-error');
        
        loginInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                loginError.textContent = 'Email or username is required';
                loginError.style.display = 'block';
                this.classList.add('error');
            } else {
                loginError.style.display = 'none';
                this.classList.remove('error');
            }
        });
        
        passwordInput.addEventListener('blur', function() {
            if (this.value === '') {
                document.getElementById('password-error').textContent = 'Password is required';
                document.getElementById('password-error').style.display = 'block';
                this.classList.add('error');
            } else {
                document.getElementById('password-error').style.display = 'none';
                this.classList.remove('error');
            }
        });
    </script>
</body>
</html>
