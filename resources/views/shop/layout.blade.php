<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    
    <title>@yield('title', 'رحيق الطبيعي') - أجود أنواع العسل</title>
    <meta name="description" content="@yield('description', 'متجر متخصص في بيع العسل الطبيعي والمنتجات الطبيعية عالية الجودة')">
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts - Tajawal -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <style>
        /* استيراد خط عربي فاخر */
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700;900&display=swap');
        
        :root {
            /* لوحة ألوان نظيفة - Clean & Minimal */
            --body-bg: #FFFFFF;           /* أبيض نقي */
            --card-bg: #F9F9F9;           /* رمادي فاتح للبطاقات */
            --text-primary: #000000;      /* أسود للنصوص */
            --text-secondary: #666666;    /* رمادي للنصوص الثانوية */
            --primary-gold: #D4A017;      /* ذهبي - للأزرار فقط */
            --primary-gold-hover: #B8860B; /* ذهبي غامق عند hover */
            --whatsapp-green: #25D366;    /* أخضر واتساب */
            --border-color: #E0E0E0;      /* رمادي للحدود */
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.06);   /* ظل خفيف */
            --shadow-medium: 0 4px 20px rgba(0, 0, 0, 0.08);  /* ظل متوسط */
            --shadow-heavy: 0 8px 25px rgba(0, 0, 0, 0.12);   /* ظل قوي */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            color: var(--text-primary);
            background-color: var(--body-bg);
            line-height: 1.6;
            font-size: 15px;
        }
        
        h1 {
            font-family: 'Amiri', 'Cairo', serif;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1.75rem;
        }
        
        h2 {
            font-family: 'Amiri', 'Cairo', serif;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1.5rem;
        }
        
        h3 {
            font-family: 'Amiri', 'Cairo', serif;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1.25rem;
        }
        
        h4 {
            font-family: 'Amiri', 'Cairo', serif;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        /* النافبار العصري */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            padding: 0.75rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(212, 160, 23, 0.1);
        }
        
        .navbar-custom.scrolled {
            box-shadow: 0 4px 25px rgba(0,0,0,0.12);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #000 !important;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: 'Amiri', serif;
            transition: all 0.3s ease;
        }
        
        .navbar-brand i {
            color: #D4A017;
            font-size: 1.6rem;
        }

        .navbar-brand:hover {
            transform: translateY(-2px);
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 0.6rem 1rem !important;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.95rem;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: #D4A017 !important;
            background: rgba(212, 160, 23, 0.05);
        }
        
        .nav-link.active {
            color: #D4A017 !important;
            background: rgba(212, 160, 23, 0.1);
        }

        /* زر السلة العصري */
        .btn-outline-light {
            color: #000 !important;
            border: 2px solid #E8E8E8 !important;
            background: white !important;
            padding: 0.5rem 1.2rem !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            position: relative;
        }
        
        .btn-outline-light:hover {
            background: #D4A017 !important;
            color: white !important;
            border-color: #D4A017 !important;
            transform: translateY(-2px);
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            left: -6px;
            background: #D4A017;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        /* Navbar Toggler */
        .navbar-toggler {
            border: 2px solid var(--primary-gold);
            padding: 0.5rem 0.75rem;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(212, 160, 23, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.3);
        }
        
        .cart-icon {
            position: relative;
            display: inline-block;
        }

        /* القدم الداكن - إغلاق بصري للصفحة */
        .footer {
            background: linear-gradient(135deg, #1a1512 0%, #2d2419 100%);
            color: rgba(255, 255, 255, 0.85);
            padding: 4rem 0 1.5rem;
            margin-top: 6rem;
            border-top: 4px solid var(--primary-gold);
        }

        .footer h5 {
            color: var(--primary-gold);
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-family: 'Amiri', serif;
            font-size: 1.3rem;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--primary-gold);
            padding-right: 5px;
        }
        
        .footer p {
            color: rgba(255, 255, 255, 0.85);
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.8);
        }

        .social-links a:hover {
            background: var(--primary-gold);
            border-color: var(--primary-gold);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(212, 160, 23, 0.4);
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.4rem;
            }
            
            .nav-link {
                padding: 0.4rem 1rem !important;
            }

            .navbar-custom {
                padding: 0.8rem 0;
            }

            .navbar-collapse {
                background: white;
                padding: 1rem;
                margin-top: 1rem;
                border-radius: 15px;
                box-shadow: var(--shadow-medium);
            }

            .btn-cart {
                width: 100%;
                margin-top: 0.5rem;
            }

            .footer {
                padding: 2rem 0 1rem;
            }

            .footer .col-lg-4 {
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .btn-cart {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        /* Bottom Navigation للموبايل */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
            padding: 0.5rem 0;
        }
        
        .mobile-bottom-nav .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        
        .mobile-bottom-nav .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .mobile-bottom-nav .nav-item.active,
        .mobile-bottom-nav .nav-item:hover {
            color: var(--primary-gold);
        }
        
        .mobile-bottom-nav .nav-item i {
            font-size: 1.5rem;
            margin-bottom: 0.2rem;
        }
        
        .mobile-bottom-nav .nav-item span {
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: block;
            }
            
            body {
                padding-bottom: 70px;
            }
            
            .footer {
                margin-bottom: 70px;
            }
        }
        
        /* زر واتساب عائم */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: linear-gradient(135deg, #25D366 0%, #20ba5a 100%);
            color: white;
            border-radius: 50px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 999;
            transition: all 0.3s ease;
            animation: pulse-whatsapp 2s infinite;
            overflow: hidden;
        }
        
        .whatsapp-float:hover {
            width: 200px;
            background: linear-gradient(135deg, #20ba5a 0%, #1fa952 100%);
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
            transform: translateY(-3px);
        }
        
        .whatsapp-float i {
            transition: transform 0.3s ease;
            min-width: 30px;
        }
        
        .whatsapp-float:hover i {
            transform: scale(1.1);
        }
        
        .whatsapp-text {
            opacity: 0;
            width: 0;
            white-space: nowrap;
            font-size: 1rem;
            font-weight: 600;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .whatsapp-float:hover .whatsapp-text {
            opacity: 1;
            width: 120px;
        }
        
        @keyframes pulse-whatsapp {
            0% {
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            }
            50% {
                box-shadow: 0 4px 25px rgba(37, 211, 102, 0.6), 0 0 0 10px rgba(37, 211, 102, 0.1);
            }
            100% {
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            }
        }
        
        /* Mega Menu Dropdown */
        .mega-menu-wrapper {
            position: relative;
        }

        .nav-link-with-mega {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-link-with-mega i {
            font-size: 0.7rem;
            transition: transform 0.3s ease;
        }

        .mega-menu-wrapper:hover .nav-link-with-mega i {
            transform: rotate(180deg);
        }

        .mega-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            min-width: 700px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 1040;
            margin-top: 0.5rem;
            border: 1px solid #E8E8E8;
        }

        .mega-menu-wrapper:hover .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .mega-menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            padding: 1.5rem;
        }

        .mega-menu-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .mega-menu-item:hover {
            background: rgba(212, 160, 23, 0.08);
            color: #D4A017;
            transform: translateX(-3px);
        }

        .mega-menu-icon {
            width: 60px;
            height: 60px;
            background: #F5F5F5;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 2px solid #E8E8E8;
        }
        
        .mega-menu-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mega-menu-item:hover .mega-menu-icon {
            border-color: #D4A017;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(212, 160, 23, 0.2);
        }

        .mega-menu-content {
            flex: 1;
        }

        .mega-menu-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.2rem;
            color: inherit;
        }

        .mega-menu-count {
            font-size: 0.8rem;
            color: #999;
        }

        .mega-menu-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #F0F0F0;
            text-align: center;
        }

        .mega-menu-footer a {
            color: #D4A017;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .mega-menu-footer a:hover {
            gap: 0.75rem;
        }

        /* Search Form - Modern */
        .search-form {
            position: relative;
            margin-left: 1rem;
        }

        .search-input {
            width: 280px;
            padding: 0.6rem 3rem 0.6rem 1.25rem;
            border: 2px solid #E8E8E8;
            border-radius: 50px;
            background: #F8F9FA;
            color: #333;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: #999;
        }

        .search-input:focus {
            outline: none;
            background: white;
            border-color: #D4A017;
            width: 320px;
            box-shadow: 0 4px 15px rgba(212, 160, 23, 0.15);
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: #D4A017;
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: #B8860B;
            transform: translateY(-50%) scale(1.08);
        }

        .search-btn i {
            font-size: 0.95rem;
        }

        /* Mobile Mega Menu - تحسين للموبايل */
        @media (max-width: 992px) {
            .mega-menu {
                position: static;
                min-width: 100%;
                margin-top: 0.5rem;
                opacity: 1;
                visibility: visible;
                transform: none;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s ease;
                border-radius: 12px;
                box-shadow: none;
                border: none;
                background: #F9F9F9;
            }

            .mega-menu-wrapper.active .mega-menu {
                max-height: 600px;
                overflow-y: auto;
            }

            .mega-menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
                padding: 0.75rem;
            }

            .mega-menu-item {
                padding: 0.6rem 0.75rem;
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            }

            .mega-menu-icon {
                width: 45px;
                height: 45px;
            }

            .mega-menu-title {
                font-size: 0.85rem;
            }

            .mega-menu-count {
                font-size: 0.7rem;
            }

            .mega-menu-footer {
                padding: 0.75rem;
                background: white;
                margin: 0.5rem;
                border-radius: 10px;
            }

            .mega-menu-footer a {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .mega-menu-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 90px;
            left: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-notification {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 320px;
            max-width: 400px;
            animation: slideInLeft 0.3s ease;
            border-right: 4px solid #D4A017;
        }

        .toast-notification.success {
            border-right-color: #28a745;
        }

        .toast-notification.error {
            border-right-color: #dc3545;
        }

        .toast-notification.warning {
            border-right-color: #ffc107;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .toast-notification.success .toast-icon {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .toast-notification.error .toast-icon {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .toast-notification.warning .toast-icon {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #000;
            margin-bottom: 0.25rem;
        }

        .toast-message {
            font-size: 0.85rem;
            color: #666;
        }

        .toast-close {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .toast-close:hover {
            background: #f0f0f0;
            color: #333;
        }

        @media (max-width: 576px) {
            .toast-container {
                left: 10px;
                right: 10px;
                top: 70px;
            }

            .toast-notification {
                min-width: auto;
                max-width: 100%;
            }
        }

        /* Search Modal */
        .search-modal-form .form-control {
            border: 2px solid #E8E8E8;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
        }

        .search-modal-form .form-control:focus {
            border-color: #D4A017;
            box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.1);
        }

        .search-modal-form .btn-primary {
            background: #D4A017;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
        }

        .search-modal-form .btn-primary:hover {
            background: #B8860B;
        }

        /* Quick View Modal */
        #quickViewModal .btn-close-modal {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 40px;
            height: 40px;
            background: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1060;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        #quickViewModal .btn-close-modal:hover {
            background: #FF6B6B;
            color: white;
            transform: rotate(90deg);
        }

        #quickViewModal .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Live Search Results */
        .search-form {
            position: relative;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            margin-top: 10px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1050;
            display: none;
        }

        .search-results.show {
            display: block;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background: #f8f9fa;
        }

        .search-result-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            background: #fafafa;
            padding: 0.5rem;
            margin-left: 1rem;
        }

        .search-result-info {
            flex: 1;
        }

        .search-result-name {
            font-weight: 600;
            color: #000;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }

        .search-result-price {
            color: #D4A017;
            font-weight: 700;
            font-size: 1rem;
        }

        .search-no-results {
            padding: 2rem;
            text-align: center;
            color: #666;
        }

        .search-loading {
            padding: 1.5rem;
            text-align: center;
        }

        /* Mobile WhatsApp Button */
        @media (max-width: 768px) {
            .whatsapp-float {
                bottom: 90px;
                left: 20px;
                width: 55px;
                height: 55px;
                font-size: 1.8rem;
            }
            
            .whatsapp-float:hover {
                width: 55px;
            }
            
            .whatsapp-text {
                display: none;
            }

            .search-results {
                max-height: 300px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- النافبار -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-hexagon-fill"></i>
                رحيق
            </a>
            
            <!-- بحث للموبايل - عرض فقط في الموبايل -->
            <div class="d-lg-none position-relative" style="flex: 1; max-width: 250px;">
                <input type="text" 
                       id="mobileSearchInput"
                       placeholder="ابحث..." 
                       class="form-control form-control-sm"
                       autocomplete="off"
                       style="border-radius: 20px; padding: 0.5rem 1rem; border: 1px solid #e0e0e0; font-size: 0.9rem;">
                <i class="bi bi-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                <!-- Live Search Results -->
                <div class="search-results" id="mobileSearchResults" style="display: none;"></div>
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                    
                    <!-- Mega Menu للأقسام -->
                    <li class="nav-item mega-menu-wrapper">
                        <a class="nav-link nav-link-with-mega {{ request()->routeIs('products*') ? 'active' : '' }}">
                            المنتجات
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="mega-menu">
                            <div class="mega-menu-grid">
                                @if(isset($menuCategories) && $menuCategories->count() > 0)
                                    @foreach($menuCategories as $category)
                                        <a href="{{ route('products', ['category' => $category->id]) }}" class="mega-menu-item">
                                            <div class="mega-menu-icon">
                                                @if($category->image)
                                                    @php
                                                        if (str_starts_with($category->image, 'http')) {
                                                            $catImage = $category->image;
                                                        } elseif (str_starts_with($category->image, 'storage/')) {
                                                            $catImage = asset($category->image);
                                                        } else {
                                                            $catImage = asset('storage/' . $category->image);
                                                        }
                                                    @endphp
                                                    <img src="{{ $catImage }}" alt="{{ $category->name }}">
                                                @else
                                                    <img src="https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=120&h=120&fit=crop" alt="{{ $category->name }}">
                                                @endif
                                            </div>
                                            <div class="mega-menu-content">
                                                <div class="mega-menu-title">{{ $category->name }}</div>
                                                <div class="mega-menu-count">{{ $category->products_count }} منتج</div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mega-menu-footer">
                                <a href="{{ route('products') }}">
                                    عرض جميع المنتجات
                                    <i class="bi bi-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">من نحن</a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2 align-items-center">
                    <!-- Search with Live Results -->
                    <form action="{{ route('products') }}" method="GET" class="search-form d-none d-lg-flex">
                        <input type="text" 
                               name="search" 
                               id="liveSearchInput"
                               placeholder="ابحث عن منتج..." 
                               value="{{ request('search') }}" 
                               class="search-input"
                               autocomplete="off">
                        <button type="submit" class="search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                        <!-- Live Search Results -->
                        <div class="search-results" id="searchResults"></div>
                    </form>
                    
                    <!-- المفضلة -->
                    <a href="{{ route('wishlist.index') }}" class="btn btn-outline-light position-relative cart-icon">
                        <i class="bi bi-heart"></i>
                        <span class="cart-badge" id="wishlistBadge" style="display: none;">0</span>
                    </a>
                    
                    <!-- السلة -->
                    <button onclick="openCartSidebar()" class="btn btn-outline-light position-relative cart-icon" style="background: transparent; border: none;">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-badge" id="cartBadge" style="display: none;">0</span>
                    </button>
                    
                    {{-- جميع أزرار التوثيق مخفية - الموقع للزوار فقط --}}
                    {{-- لوحة التحكم: الدخول من /admin مباشرة --}}
                </div>
            </div>
        </div>
    </nav>

    <!-- المحتوى الرئيسي -->
    <main>
        @yield('content')
    </main>
    
    <!-- زر واتساب عائم -->
    <a href="https://wa.me/201000000000?text=مرحباً، أريد الاستفسار عن منتجاتكم" 
       class="whatsapp-float" 
       target="_blank"
       rel="noopener noreferrer"
       title="تواصل معنا عبر واتساب">
        <i class="bi bi-whatsapp"></i>
        <span class="whatsapp-text">تواصل معنا</span>
    </a>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="modal-body p-0" id="quickViewContent">
                    <!-- Loading Spinner -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- القدم -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>رحيق</h5>
                    <p style="color: rgba(255, 255, 255, 0.75); line-height: 1.8;">
                        متخصصون في تقديم أجود أنواع العسل الطبيعي والمنتجات الطبيعية عالية الجودة من مصادر موثوقة.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5>روابط مهمة</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">الرئيسية</a></li>
                        <li class="mb-2"><a href="{{ route('products') }}">المنتجات</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}">من نحن</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5>تواصل معنا</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2" style="color: #D4A017;"></i>
                            مصر
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2" style="color: #D4A017;"></i>
                            +20 123 456 789
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2" style="color: #D4A017;"></i>
                            info@honeystore.com
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.1);">
            
            <div class="text-center">
                <p class="mb-0" style="color: rgba(255, 255, 255, 0.7);">&copy; {{ date('Y') }} رحيق. جميع الحقوق محفوظة.</p>
                
                @if(config('app.env') === 'demo' || request()->getHost() === 'localhost' || str_contains(request()->getHost(), 'infinityfreeapp'))
                    <div class="demo-disclaimer mt-3 p-3" style="background: rgba(255, 193, 7, 0.1); border-radius: 8px; border: 1px solid rgba(255, 193, 7, 0.3);">
                        <p class="mb-1" style="color: #FFC107; font-size: 0.85rem; font-weight: 600;">
                            <i class="bi bi-info-circle me-1"></i>
                            نسخة تجريبية للعرض فقط
                        </p>
                        <p class="mb-0" style="color: rgba(255, 255, 255, 0.65); font-size: 0.75rem; line-height: 1.5;">
                            هذا الموقع مستضاف على خادم مجاني لأغراض العرض التوضيحي فقط. سرعة التصفح والأداء هنا <strong style="color: #FFC107;">لا تعكس</strong> أداء الموقع الفعلي على استضافة مدفوعة احترافية.
                            <br>
                            <span style="color: rgba(255, 255, 255, 0.5); font-size: 0.7rem;">
                                للحصول على نسخة كاملة بأداء فائق، تواصل معنا لمعرفة باقات الاستضافة المناسبة.
                            </span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </footer>
    
    <!-- Bottom Navigation للموبايل -->
    <div class="mobile-bottom-nav">
        <div class="nav-items">
            <a href="{{ route('home') }}" class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>الرئيسية</span>
            </a>
            <a href="{{ route('products') }}" class="nav-item {{ Request::is('products*') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>المنتجات</span>
            </a>
            <a href="javascript:void(0)" onclick="openCartSidebar()" class="nav-item position-relative cart-icon {{ Request::is('cart*') ? 'active' : '' }}">
                <i class="bi bi-cart-fill"></i>
                <span>السلة</span>
                @if(isset($cartCount) && $cartCount > 0)
                    <span class="cart-badge" id="cartBadgeMobile" style="top: 5px; right: 15px;">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="{{ route('wishlist.index') }}" class="nav-item {{ Request::is('wishlist*') ? 'active' : '' }}">
                <i class="bi bi-heart-fill"></i>
                <span>المفضلة</span>
            </a>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Global Scripts -->
    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile Mega Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const megaMenuWrappers = document.querySelectorAll('.mega-menu-wrapper');
            
            megaMenuWrappers.forEach(wrapper => {
                const navLink = wrapper.querySelector('.nav-link-with-mega');
                
                if (window.innerWidth <= 992) {
                    navLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        wrapper.classList.toggle('active');
                        
                        // Close other menus
                        megaMenuWrappers.forEach(otherWrapper => {
                            if (otherWrapper !== wrapper) {
                                otherWrapper.classList.remove('active');
                            }
                        });
                    });
                }
            });
            
            // Close mega menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.mega-menu-wrapper') && window.innerWidth <= 992) {
                    megaMenuWrappers.forEach(wrapper => {
                        wrapper.classList.remove('active');
                    });
                }
            });

            // Load Cart Count on Page Load
            updateCartCount();
        });

        // ==========================================
        // Toast Notification System
        // ==========================================
        function showToast(message, type = 'success', title = null) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-x-circle-fill',
                warning: 'bi-exclamation-triangle-fill',
                info: 'bi-info-circle-fill'
            };
            
            const titles = {
                success: title || 'نجح!',
                error: title || 'خطأ!',
                warning: title || 'تحذير!',
                info: title || 'معلومة'
            };
            
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi ${icons[type]}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${titles[type]}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.animation = 'slideInLeft 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // ==========================================
        // Cart System - Global Functions
        // ==========================================
        
        // Update Cart Count Badge
        async function updateCartCount() {
            try {
                const response = await fetch('{{ route("cart.count") }}');
                const data = await response.json();
                
                // تحديث جميع badges السلة
                const badges = [
                    document.getElementById('cartBadge'), // Desktop
                    document.getElementById('cartBadgeMobile') // Bottom Nav Mobile
                ];
                
                badges.forEach(badge => {
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                });
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Add to Cart Function
        async function addToCart(productId, quantity = 1, variantId = null) {
            const button = event?.target?.closest('button');
            
            // ✅ FIX #6: منع الضغط المتكرر
            if (button?.disabled) return;
            
            const originalHTML = button?.innerHTML;
            
            try {
                // Loading state
                if (button) {
                    button.disabled = true;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> جاري الإضافة...';
                }
                
                const body = {
                    product_id: productId,
                    quantity: quantity
                };
                
                if (variantId) {
                    body.variant_id = variantId;
                }
                
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });

                const data = await response.json();

                if (data.success) {
                    // Success toast
                    showToast(data.message || 'تم إضافة المنتج إلى السلة', 'success');
                    
                    // Update cart count
                    await updateCartCount();
                    
                    // Success state for button
                    if (button) {
                        button.innerHTML = '<i class="bi bi-check-lg"></i> تمت الإضافة';
                        setTimeout(() => {
                            button.disabled = false;
                            button.innerHTML = originalHTML;
                        }, 2000);
                    }
                } else {
                    throw new Error(data.message || 'حدث خطأ');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showToast(error.message || 'حدث خطأ أثناء إضافة المنتج', 'error');
                
                if (button) {
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                }
            }
        }

        // ==========================================
        // Wishlist System - Global Functions
        // ==========================================
        
        // Toggle Wishlist
        async function toggleWishlist(productId, button) {
            try {
                // Check if already in wishlist
                const checkResponse = await fetch(`{{ route("wishlist.check") }}?product_id=${productId}`);
                const checkData = await checkResponse.json();
                
                if (checkData.in_wishlist) {
                    // Remove from wishlist
                    const response = await fetch('{{ route("wishlist.remove") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        button.classList.remove('active');
                        button.querySelector('i').className = 'bi bi-heart';
                        showToast('تمت إزالة المنتج من المفضلة', 'success');
                        updateWishlistCount();
                    }
                } else {
                    // Add to wishlist
                    const response = await fetch('{{ route("wishlist.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        button.classList.add('active');
                        button.querySelector('i').className = 'bi bi-heart-fill';
                        showToast('تمت إضافة المنتج إلى المفضلة', 'success');
                        updateWishlistCount();
                    } else {
                        showToast(data.message || 'حدث خطأ', 'error');
                    }
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
                showToast('حدث خطأ أثناء إضافة المنتج للمفضلة', 'error');
            }
        }
        
        // Update Wishlist Count Badge
        async function updateWishlistCount() {
            try {
                const response = await fetch('{{ route("wishlist.count") }}');
                const data = await response.json();
                
                const badge = document.getElementById('wishlistBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error updating wishlist count:', error);
            }
        }
        
        // Load Wishlist Count on Page Load
        updateWishlistCount();

        // ==========================================
        // Quick View Modal
        // ==========================================
        async function openQuickView(productId) {
            const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
            const content = document.getElementById('quickViewContent');
            
            // Show modal with loading
            modal.show();
            content.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">جاري التحميل...</p>
                </div>
            `;
            
            try {
                const response = await fetch(`/api/products/${productId}`);
                const product = await response.json();
                
                if (response.ok) {
                    // Build product HTML
                    let imageUrl = 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=500&h=500&fit=crop';
                    if (product.main_image) {
                        imageUrl = product.main_image.startsWith('http') 
                            ? product.main_image 
                            : `/storage/${product.main_image}`;
                    }
                    
                    const stockBadge = product.stock > 0 && product.stock <= 5 
                        ? `<span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill"></i> متبقي ${product.stock} فقط!</span>` 
                        : '';
                    
                    const stockStatus = product.stock > 0 
                        ? '<span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> متوفر</span>' 
                        : '<span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> غير متوفر</span>';
                    
                    // الأحجام
                    let variantsHTML = '';
                    if (product.variants && product.variants.length > 0) {
                        variantsHTML = `
                            <div class="mb-4">
                                <label class="form-label fw-bold">اختر الحجم:</label>
                                <select class="form-select" id="quickViewVariant" onchange="updateQuickViewPrice(${product.id})">
                                    ${product.variants.map(v => `
                                        <option value="${v.id}" data-price="${v.price}" ${v.stock == 0 ? 'disabled' : ''}>
                                            ${v.size} - ${parseFloat(v.price).toFixed(2)} ج.م 
                                            ${v.stock == 0 ? '(غير متوفر)' : ''}
                                        </option>
                                    `).join('')}
                                </select>
                            </div>
                        `;
                    }
                    
                    content.innerHTML = `
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="p-4">
                                    <img src="${imageUrl}" alt="${product.name}" class="w-100" style="object-fit: contain; max-height: 400px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4">
                                    <div class="mb-2">
                                        <span class="badge" style="background: #D4A017;">${product.category?.name || 'عام'}</span>
                                    </div>
                                    <h3 class="mb-3" style="font-family: 'Cairo', sans-serif; font-weight: 700;">${product.name}</h3>
                                    <div class="mb-3">
                                        ${stockStatus}
                                        ${stockBadge}
                                    </div>
                                    <div class="mb-4">
                                        <span class="h3 text-warning fw-bold" id="quickViewPrice">${parseFloat(product.variants && product.variants.length > 0 ? product.variants[0].price : product.price).toFixed(2)} ج.م</span>
                                    </div>
                                    ${variantsHTML}
                                    <div class="mb-4">
                                        <p class="text-muted" style="line-height: 1.8;">${product.description || 'لا يوجد وصف متاح'}</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        ${product.stock > 0 || (product.variants && product.variants.length > 0) ? `
                                            <button class="btn btn-warning flex-fill" onclick="addToCartQuickView(${product.id}); bootstrap.Modal.getInstance(document.getElementById('quickViewModal')).hide();" style="padding: 0.875rem; font-weight: 600;">
                                                <i class="bi bi-cart-plus me-2"></i>
                                                أضف للسلة
                                            </button>
                                        ` : `
                                            <button class="btn btn-secondary flex-fill" disabled style="padding: 0.875rem;">
                                                <i class="bi bi-x-circle me-2"></i>
                                                غير متوفر
                                            </button>
                                        `}
                                        <a href="/products/${product.id}" class="btn btn-outline-dark" style="padding: 0.875rem 1.5rem;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-outline-danger" onclick="toggleWishlist(${product.id}, this)" style="padding: 0.875rem 1.5rem;">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    throw new Error('Product not found');
                }
            } catch (error) {
                console.error('Error loading product:', error);
                content.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3">حدث خطأ أثناء تحميل المنتج</p>
                    </div>
                `;
            }
        }

        // ==========================================
        // Quick View Helper Functions
        // ==========================================
        function updateQuickViewPrice(productId) {
            const select = document.getElementById('quickViewVariant');
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('quickViewPrice').textContent = `${parseFloat(price).toFixed(2)} ج.م`;
        }

        async function addToCartQuickView(productId) {
            const variantSelect = document.getElementById('quickViewVariant');
            const variantId = variantSelect ? variantSelect.value : null;
            await addToCart(productId, 1, variantId);
        }

        // ==========================================
        // Live Search
        // ==========================================
        let searchTimeout;
        const searchInput = document.getElementById('liveSearchInput');
        const searchResults = document.getElementById('searchResults');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    searchResults.classList.remove('show');
                    return;
                }
                
                // Show loading
                searchResults.innerHTML = '<div class="search-loading"><div class="spinner-border spinner-border-sm text-warning"></div></div>';
                searchResults.classList.add('show');
                
                // Debounce search
                searchTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
                        const products = await response.json();
                        
                        if (products.length === 0) {
                            searchResults.innerHTML = `
                                <div class="search-no-results">
                                    <i class="bi bi-search mb-2" style="font-size: 2rem; color: #ddd;"></i>
                                    <p class="mb-0">لا توجد نتائج للبحث "${query}"</p>
                                </div>
                            `;
                        } else {
                            searchResults.innerHTML = products.map(product => {
                                let imageUrl = 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=100&h=100&fit=crop';
                                if (product.main_image) {
                                    imageUrl = product.main_image.startsWith('http') 
                                        ? product.main_image 
                                        : `/storage/${product.main_image}`;
                                }
                                
                                return `
                                    <a href="/products/${product.id}" class="search-result-item">
                                        <img src="${imageUrl}" alt="${product.name}" class="search-result-img">
                                        <div class="search-result-info">
                                            <div class="search-result-name">${product.name}</div>
                                            <div class="search-result-price">${parseFloat(product.price).toFixed(2)} ج.م</div>
                                        </div>
                                    </a>
                                `;
                            }).join('');
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                        searchResults.innerHTML = '<div class="search-no-results">حدث خطأ أثناء البحث</div>';
                    }
                }, 300);
            });
            
            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.remove('show');
                }
            });
        }
        
        // ==========================================
        // Mobile Live Search (Same as Desktop)
        // ==========================================
        const mobileSearchInput = document.getElementById('mobileSearchInput');
        const mobileSearchResults = document.getElementById('mobileSearchResults');
        
        if (mobileSearchInput) {
            mobileSearchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    mobileSearchResults.style.display = 'none';
                    return;
                }
                
                mobileSearchResults.innerHTML = '<div class="search-loading"><div class="spinner-border spinner-border-sm text-warning"></div></div>';
                mobileSearchResults.style.display = 'block';
                
                searchTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
                        const products = await response.json();
                        
                        if (products.length === 0) {
                            mobileSearchResults.innerHTML = `
                                <div class="search-no-results">
                                    <i class="bi bi-search mb-2" style="font-size: 2rem; color: #ddd;"></i>
                                    <p class="mb-0">لا توجد نتائج</p>
                                </div>
                            `;
                        } else {
                            mobileSearchResults.innerHTML = products.map(product => {
                                let imageUrl = 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=100&h=100&fit=crop';
                                if (product.main_image) {
                                    imageUrl = product.main_image.startsWith('http') 
                                        ? product.main_image 
                                        : `/storage/${product.main_image}`;
                                }
                                
                                return `
                                    <a href="/products/${product.id}" class="search-result-item">
                                        <img src="${imageUrl}" alt="${product.name}" class="search-result-img">
                                        <div class="search-result-info">
                                            <div class="search-result-name">${product.name}</div>
                                            <div class="search-result-price">${parseFloat(product.price).toFixed(2)} ج.م</div>
                                        </div>
                                    </a>
                                `;
                            }).join('');
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                        mobileSearchResults.innerHTML = '<div class="search-no-results">حدث خطأ</div>';
                    }
                }, 300);
            });
            
            document.addEventListener('click', function(e) {
                if (!mobileSearchInput.contains(e.target) && !mobileSearchResults.contains(e.target)) {
                    mobileSearchResults.style.display = 'none';
                }
            });
        }
    </script>

    <!-- Cart Sidebar احترافية -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-sidebar-header">
            <h3>سلة التسوق</h3>
            <button class="cart-sidebar-close" onclick="closeCartSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="cart-sidebar-body" id="cartSidebarBody">
            <div class="cart-loading">
                <div class="spinner-border text-warning" role="status"></div>
                <p>جاري التحميل...</p>
            </div>
        </div>
        
        <div class="cart-sidebar-footer" id="cartSidebarFooter" style="display: none;">
            <div class="cart-total">
                <span>الإجمالي:</span>
                <strong id="cartSidebarTotal">0.00 ج.م</strong>
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-primary w-100 mb-2">
                <i class="bi bi-eye me-2"></i>
                عرض السلة كاملة
            </a>
            <a href="{{ route('checkout.index') }}" class="btn btn-success w-100">
                <i class="bi bi-check-circle me-2"></i>
                إتمام الطلب
            </a>
        </div>
    </div>
    
    <!-- Overlay -->
    <div class="cart-sidebar-overlay" id="cartSidebarOverlay" onclick="closeCartSidebar()"></div>
    
    <style>
        /* Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 420px;
            max-width: 100%;
            background: white;
            box-shadow: -2px 0 20px rgba(0,0,0,0.15);
            z-index: 9999;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .cart-sidebar.active {
            transform: translateX(0);
        }
        
        .cart-sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #E8E8E8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #F9F9F9;
        }
        
        .cart-sidebar-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .cart-sidebar-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }
        
        .cart-sidebar-close:hover {
            background: rgba(0,0,0,0.05);
            transform: rotate(90deg);
        }
        
        .cart-sidebar-body {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }
        
        .cart-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #999;
        }
        
        .cart-sidebar-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: #F9F9F9;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s;
        }
        
        .cart-sidebar-item:hover {
            background: #F0F0F0;
        }
        
        .cart-sidebar-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .cart-sidebar-item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .cart-sidebar-item-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #000;
            margin-bottom: 0.25rem;
        }
        
        .cart-sidebar-item-variant {
            font-size: 0.8rem;
            color: #666;
        }
        
        .cart-sidebar-item-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }
        
        .cart-sidebar-item-qty {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            border-radius: 8px;
            padding: 0.25rem 0.5rem;
        }
        
        .cart-sidebar-item-qty button {
            background: transparent;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            color: #666;
            transition: all 0.2s;
        }
        
        .cart-sidebar-item-qty button:hover {
            color: #D4A017;
        }
        
        .cart-sidebar-item-qty span {
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }
        
        .cart-sidebar-item-price {
            font-weight: 700;
            color: #D4A017;
            font-size: 1rem;
        }
        
        .cart-sidebar-item-remove {
            background: transparent;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem;
            transition: all 0.2s;
        }
        
        .cart-sidebar-item-remove:hover {
            transform: scale(1.2);
        }
        
        .cart-sidebar-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }
        
        .cart-sidebar-empty i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        
        .cart-sidebar-footer {
            padding: 1.5rem;
            border-top: 2px solid #E8E8E8;
            background: #F9F9F9;
        }
        
        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
        }
        
        .cart-total strong {
            color: #D4A017;
            font-size: 1.5rem;
        }
        
        .cart-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .cart-sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .cart-sidebar {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
    
    <script>
        // فتح وإغلاق Cart Sidebar
        function openCartSidebar() {
            document.getElementById('cartSidebar').classList.add('active');
            document.getElementById('cartSidebarOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
            loadCartSidebar();
        }
        
        function closeCartSidebar() {
            document.getElementById('cartSidebar').classList.remove('active');
            document.getElementById('cartSidebarOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // تحميل محتوى السلة
        async function loadCartSidebar() {
            const body = document.getElementById('cartSidebarBody');
            const footer = document.getElementById('cartSidebarFooter');
            
            try {
                const response = await fetch('/api/cart');
                const data = await response.json();
                
                if (!data.items || data.items.length === 0) {
                    body.innerHTML = `
                        <div class="cart-sidebar-empty">
                            <i class="bi bi-cart-x"></i>
                            <h5>السلة فارغة</h5>
                            <p class="text-muted">لم تضف أي منتجات بعد</p>
                        </div>
                    `;
                    footer.style.display = 'none';
                    return;
                }
                
                body.innerHTML = data.items.map(item => {
                    let imageUrl = 'https://images.unsplash.com/photo-1587049352846-4a222e784c38?w=150&h=150&fit=crop';
                    if (item.product && item.product.main_image) {
                        imageUrl = item.product.main_image.startsWith('http') 
                            ? item.product.main_image 
                            : `/storage/${item.product.main_image}`;
                    }
                    
                    return `
                        <div class="cart-sidebar-item">
                            <img src="${imageUrl}" alt="${item.product ? item.product.name : 'منتج'}" class="cart-sidebar-item-img">
                            <div class="cart-sidebar-item-info">
                                <div class="cart-sidebar-item-name">${item.product ? item.product.name : 'منتج محذوف'}</div>
                                ${item.variant_name ? `<div class="cart-sidebar-item-variant">${item.variant_name}</div>` : ''}
                                <div class="cart-sidebar-item-bottom">
                                    <div class="cart-sidebar-item-qty">
                                        <button onclick="updateCartItemQty(${item.id}, ${item.quantity - 1})">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <span>${item.quantity}</span>
                                        <button onclick="updateCartItemQty(${item.id}, ${item.quantity + 1})">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="cart-sidebar-item-price">${parseFloat(item.total_price).toFixed(2)} ج.م</div>
                                </div>
                            </div>
                            <button class="cart-sidebar-item-remove" onclick="removeCartItem(${item.id})">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    `;
                }).join('');
                
                document.getElementById('cartSidebarTotal').textContent = `${parseFloat(data.total).toFixed(2)} ج.م`;
                footer.style.display = 'block';
                
            } catch (error) {
                console.error('Error loading cart:', error);
                body.innerHTML = `
                    <div class="cart-sidebar-empty">
                        <i class="bi bi-exclamation-triangle"></i>
                        <p>حدث خطأ في تحميل السلة</p>
                    </div>
                `;
            }
        }
        
        // تحديث كمية المنتج
        async function updateCartItemQty(itemId, newQty) {
            if (newQty < 1) {
                removeCartItem(itemId);
                return;
            }
            
            try {
                const response = await fetch(`/cart/${itemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: newQty })
                });
                
                const data = await response.json();
                if (data.success) {
                    loadCartSidebar();
                    updateCartCount();
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        }
        
        // حذف منتج من السلة
        async function removeCartItem(itemId) {
            try {
                const response = await fetch(`/cart/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    loadCartSidebar();
                    updateCartCount();
                }
            } catch (error) {
                console.error('Error removing item:', error);
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>
