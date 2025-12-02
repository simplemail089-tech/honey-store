<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>تسجيل الدخول - رحيق</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #111827;
            --primary-gold: #D4A017;
            --primary-gold-hover: #B8860B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--primary-dark);
            min-height: 100vh;
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* خلفية مزخرفة */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(212, 160, 23, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(212, 160, 23, 0.08) 0%, transparent 70%);
            animation: rotate 25s linear infinite reverse;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border: none;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1f2937 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--primary-gold), transparent);
        }

        .brand-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-gold) 0%, #FFD700 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 8px 25px rgba(212, 160, 23, 0.4);
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
        }

        .brand-icon:hover {
            transform: rotate(0deg) scale(1.05);
        }

        .brand-icon i {
            font-size: 2.25rem;
            color: white;
        }

        .login-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            opacity: 0.8;
            font-size: 0.95rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-group {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-left: none;
            color: #666;
            padding: 0.75rem 1rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-right: none;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-gold);
            box-shadow: none;
        }

        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-gold);
            color: var(--primary-gold);
        }

        .form-check-input:checked {
            background-color: var(--primary-gold);
            border-color: var(--primary-gold);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(212, 160, 23, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-gold) 0%, var(--primary-gold-hover) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 160, 23, 0.4);
            background: linear-gradient(135deg, #FFD700 0%, var(--primary-gold) 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }

        .divider span {
            padding: 0 1rem;
            color: #999;
            font-size: 0.85rem;
        }

        .demo-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            border: 1px dashed #ddd;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .demo-info small {
            color: #666;
        }

        .demo-info code {
            display: block;
            margin-top: 0.5rem;
            background: var(--primary-dark);
            color: var(--primary-gold);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--primary-gold);
        }

        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-body {
                padding: 1.5rem;
            }

            .brand-icon {
                width: 70px;
                height: 70px;
            }

            .brand-icon i {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-icon">
                    <i class="bi bi-hexagon-fill"></i>
                </div>
                <h3>رحيق</h3>
                <p>لوحة تحكم المتجر</p>
            </div>
            
            <div class="login-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>
                            البريد الإلكتروني
                        </label>
                        <div class="input-group">
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   placeholder="admin@example.com">
                            <span class="input-group-text">
                                <i class="bi bi-at"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>
                            كلمة المرور
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="••••••••">
                            <span class="input-group-text">
                                <i class="bi bi-key"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            تذكرني في هذا الجهاز
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        تسجيل الدخول
                    </button>
                </form>
                
                <div class="divider">
                    <span>معلومات الدخول</span>
                </div>
                
                <div class="demo-info">
                    <small>للدخول استخدم:</small>
                    <code>admin@example.com / password</code>
                </div>
            </div>
        </div>

        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-right me-1"></i>
            العودة للمتجر
        </a>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
