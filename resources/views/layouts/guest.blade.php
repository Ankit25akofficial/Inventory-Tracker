<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'InvenTrack QR Inventory System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&display=swap" rel="stylesheet">

        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: 'Inter', sans-serif;
                background-color: #0b0f19;
                color: #cbd5e1;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }
            
            /* Background Ambient Glow */
            body::before {
                content: '';
                position: absolute;
                top: -20%; left: -10%;
                width: 50%; height: 50%;
                background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(0,0,0,0) 70%);
                z-index: 0;
            }
            body::after {
                content: '';
                position: absolute;
                bottom: -20%; right: -10%;
                width: 50%; height: 50%;
                background: radial-gradient(circle, rgba(168,85,247,0.15) 0%, rgba(0,0,0,0) 70%);
                z-index: 0;
            }

            .login-container {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 400px;
                padding: 40px;
                background: rgba(30, 37, 54, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 24px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }

            .logo-container {
                text-align: center;
                margin-bottom: 30px;
            }
            
            .logo-icon {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #6366f1, #a855f7);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 16px;
                box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
            }

            .logo-icon svg {
                width: 32px;
                height: 32px;
                fill: white;
            }

            .logo-text {
                font-family: 'Outfit', sans-serif;
                font-size: 24px;
                font-weight: 700;
                color: #f8fafc;
                letter-spacing: -0.5px;
            }
            
            .logo-subtext {
                font-size: 13px;
                color: #94a3b8;
                margin-top: 4px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                display: block;
                font-size: 13px;
                font-weight: 500;
                color: #cbd5e1;
                margin-bottom: 8px;
            }

            .form-control {
                width: 100%;
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                padding: 12px 16px;
                color: #f1f5f9;
                font-size: 14px;
                transition: all 0.3s ease;
                outline: none;
            }

            .form-control:focus {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
                background: rgba(15, 23, 42, 0.9);
            }

            .btn-primary {
                width: 100%;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                color: white;
                border: none;
                border-radius: 12px;
                padding: 12px 24px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 12px -2px rgba(99, 102, 241, 0.4);
            }

            .forgot-password {
                font-size: 13px;
                color: #6366f1;
                text-decoration: none;
                transition: color 0.2s;
            }
            .forgot-password:hover {
                color: #8b5cf6;
            }

            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            input[type="checkbox"] {
                accent-color: #6366f1;
                width: 16px;
                height: 16px;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="logo-container">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16zM12 22.27L3 17.07V6.93L12 1.73l9 5.2v10.14l-9 5.2z"/>
                        <path d="M12 11.27L4.53 6.93 12 2.6l7.47 4.33L12 11.27z"/>
                        <path d="M3 8v10l9 5.2V13.07L3 8z"/>
                    </svg>
                </div>
                <div class="logo-text">InvenTrack</div>
                <div class="logo-subtext">QR Inventory Management System</div>
            </div>

            {{ $slot }}
        </div>
    </body>
</html>
