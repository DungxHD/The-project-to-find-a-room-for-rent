<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIHome - Tìm phòng trọ thông minh bằng AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --blue-50: #eff6ff;
            --blue-100: #dbeafe;
            --ink-900: #0f172a;
            --ink-700: #334155;
            --ink-500: #64748b;
            --ink-300: #cbd5e1;
            --line: #e5e9f0;
            --teal: #16a34a;
            --amber: #f59e0b;
            --bg: #f7f9fc;
            --white: #ffffff;
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Be Vietnam Pro', sans-serif;
            color: var(--ink-900);
            background: var(--bg);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
            cursor: pointer;
        }

        ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* HEADER */
        header {
            background: var(--white);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            max-width: 1180px;
            margin: 0 auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--blue-500), var(--blue-600));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg {
            width: 24px;
            height: 24px;
            stroke: #fff;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--ink-900);
        }

        .brand-tag {
            font-size: 12px;
            color: var(--ink-500);
            margin-top: 1px;
        }

        nav.main-nav {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        nav.main-nav a {
            font-size: 15px;
            font-weight: 500;
            color: var(--ink-700);
            padding: 6px 0;
            position: relative;
        }

        nav.main-nav a.active {
            color: var(--blue-600);
            font-weight: 600;
        }

        nav.main-nav a.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -16px;
            height: 2px;
            background: var(--blue-600);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid var(--line);
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-700);
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
        }

        .btn-primary {
            background: var(--blue-600);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-primary:hover {
            background: var(--blue-500);
        }

        /* HERO */
        .hero-section {
            padding: 28px 24px 0;
        }

        .hero {
            max-width: 1180px;
            margin: 0 auto;
            background: linear-gradient(120deg, var(--blue-50) 0%, #f2f7ff 55%, #ffffff 100%);
            border-radius: var(--radius-lg);
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 20px;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
        }

        .hero-text h1 {
            font-size: 36px;
            line-height: 1.25;
            font-weight: 800;
            margin: 0 0 16px;
        }

        .hero-text h1 .accent {
            color: var(--blue-600);
        }

        .hero-text p {
            font-size: 15.5px;
            color: var(--ink-500);
            line-height: 1.6;
            max-width: 440px;
            margin: 0 0 24px;
        }

        .hero-badges {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }

        .hero-badges span {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink-700);
        }

        .hero-badges svg {
            width: 18px;
            height: 18px;
        }

        .badge-blue svg {
            color: var(--blue-600);
        }

        .badge-teal svg {
            color: var(--teal);
        }

        .badge-purple svg {
            color: #8b5cf6;
        }

        .hero-media {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
        }

        .hero-media img {
            width: 100%;
            height: 340px;
            object-fit: cover;
            border-radius: 16px;
        }

        .ai-pill {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #fff;
            padding: 9px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            color: var(--ink-900);
            box-shadow: 0 6px 18px rgba(15, 23, 42, .12);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ai-pill::before {
            content: "✨";
        }

        .hero-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: 0 4px 12px rgba(15, 23, 42, .08);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .hero-arrow.left {
            left: 8px;
        }

        .hero-arrow.right {
            right: 8px;
        }

        .hero-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
        }

        .hero-dots span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .6);
        }

        .hero-dots span.active {
            background: #fff;
            width: 20px;
            border-radius: 5px;
        }

        /* SEARCH BAR */
        .search-section {
            padding: 22px 24px 0;
        }

        .search-card {
            max-width: 1180px;
            margin: 0 auto;
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            border: 1px solid var(--line);
            padding: 22px 26px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .field label {
            display: block;
            font-size: 12.5px;
            color: var(--ink-500);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .select-box {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
        }

        .select-box svg.leading {
            width: 17px;
            height: 17px;
            color: var(--blue-600);
            flex-shrink: 0;
        }

        .select-box select {
            appearance: none;
            border: none;
            outline: none;
            background: transparent;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink-900);
            width: 100%;
            cursor: pointer;
        }

        .select-box svg.chevron {
            width: 16px;
            height: 16px;
            color: var(--ink-500);
            flex-shrink: 0;
        }

        .search-row2 {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 14px;
            margin-top: 18px;
        }

        .keyword-box {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 11px 14px;
            background: #f9fafc;
        }

        .keyword-box svg {
            width: 18px;
            height: 18px;
            color: var(--ink-500);
            flex-shrink: 0;
        }

        .keyword-box input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            font-family: inherit;
            font-size: 14px;
            color: var(--ink-900);
        }

        .keyword-box input::placeholder {
            color: var(--ink-500);
        }

        .btn-search {
            background: var(--blue-600);
            color: #fff;
            border: none;
            padding: 0 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-search svg {
            width: 18px;
            height: 18px;
        }

        .btn-search:hover {
            background: var(--blue-500);
        }

        /* LISTINGS */
        .section {
            padding: 44px 24px 0;
        }

        .section-head {
            max-width: 1180px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-head h2 {
            font-size: 22px;
            font-weight: 800;
            margin: 0;
        }

        .section-head .see-all {
            font-size: 14px;
            font-weight: 600;
            color: var(--blue-600);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cards-grid {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: var(--radius-md);
            border: 1px solid var(--line);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .2s, box-shadow .2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, .08);
        }

        .card-media {
            position: relative;
            height: 170px;
        }

        .card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tag-new {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--teal);
            color: #fff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .fav-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .92);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fav-btn svg {
            width: 16px;
            height: 16px;
            color: var(--ink-700);
        }

        .card-body {
            padding: 14px 16px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .card-title {
            font-size: 15.5px;
            font-weight: 700;
            margin: 0;
        }

        .card-loc {
            font-size: 13px;
            color: var(--ink-500);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-loc svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        .card-price {
            font-size: 16.5px;
            font-weight: 800;
            color: var(--blue-600);
            margin: 2px 0 4px;
        }

        .card-meta {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12.5px;
            color: var(--ink-500);
            font-weight: 600;
        }

        .card-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .card-meta svg {
            width: 14px;
            height: 14px;
        }

        .card-meta .star {
            color: var(--amber);
        }

        .card-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .card-actions button {
            flex: 1;
            font-size: 12.5px;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .card-actions button svg {
            width: 14px;
            height: 14px;
        }

        .btn-vr {
            background: var(--blue-50);
            color: var(--blue-600);
            border: 1px solid var(--blue-100);
        }

        .btn-map {
            background: #ecfdf5;
            color: var(--teal);
            border: 1px solid #d1fae5;
        }

        /* FEATURES STRIP */
        .features-strip {
            max-width: 1180px;
            margin: 44px auto 0;
            background: #eef4ff;
            border-radius: var(--radius-lg);
            padding: 30px 40px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--blue-600);
        }

        .feature-icon svg {
            width: 22px;
            height: 22px;
        }

        .feature-item h3 {
            font-size: 14.5px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .feature-item p {
            font-size: 12.5px;
            color: var(--ink-500);
            margin: 0;
            line-height: 1.5;
        }

        /* FOOTER */
        footer {
            margin-top: 50px;
            background: #fff;
            border-top: 1px solid var(--line);
            padding: 44px 24px 0;
        }

        .footer-grid {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr 1.3fr;
            gap: 30px;
            padding-bottom: 30px;
        }

        .footer-brand p {
            font-size: 13px;
            color: var(--ink-500);
            line-height: 1.6;
            margin: 10px 0 16px;
            max-width: 230px;
        }

        .socials {
            display: flex;
            gap: 10px;
        }

        .socials a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-700);
        }

        .socials svg {
            width: 16px;
            height: 16px;
        }

        .footer-col h4 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .02em;
            margin: 0 0 14px;
            color: var(--ink-900);
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            font-size: 13.5px;
            color: var(--ink-500);
        }

        .footer-col ul li a:hover {
            color: var(--blue-600);
        }

        .newsletter p {
            font-size: 13px;
            color: var(--ink-500);
            margin: 0 0 14px;
            line-height: 1.5;
        }

        .newsletter-box {
            display: flex;
            gap: 8px;
        }

        .newsletter-box input {
            flex: 1;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px 12px;
            font-family: inherit;
            font-size: 13px;
            outline: none;
        }

        .newsletter-box button {
            width: 40px;
            border: none;
            border-radius: 8px;
            background: var(--blue-600);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .newsletter-box button svg {
            width: 16px;
            height: 16px;
        }

        .footer-bottom {
            border-top: 1px solid var(--line);
            padding: 16px 0;
            text-align: center;
            font-size: 12.5px;
            color: var(--ink-500);
        }

        @media (max-width:1024px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .hero-media img {
                height: 260px;
            }

            .search-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .features-strip {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            nav.main-nav {
                display: none;
            }
        }

        @media (max-width:560px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }

            .search-row2 {
                grid-template-columns: 1fr;
            }

            .btn-search {
                padding: 12px;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="header-inner">
            <div class="brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 11l9-8 9 8" />
                        <path d="M5 10v10h14V10" />
                    </svg>
                </div>
                <div>
                    <div class="brand-name">AIHome</div>
                    <div class="brand-tag">Tìm phòng trọ thông minh bằng AI</div>
                </div>
            </div>
            <nav class="main-nav">
                <a href="#" class="active">Trang chủ</a>
                <a href="#">Tìm phòng trọ</a>
                <a href="#">Khu vực</a>
                <a href="#">Về chúng tôi</a>
                <a href="#">Blog</a>
            </nav>
            <div class="header-actions">
                <button class="icon-btn" aria-label="Yêu thích">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M20.8 4.6c-1.6-1.6-4.2-1.6-5.8 0L12 7.6 9 4.6c-1.6-1.6-4.2-1.6-5.8 0-1.6 1.6-1.6 4.2 0 5.8l8.8 8.8 8.8-8.8c1.6-1.6 1.6-4.2 0-5.8z" />
                    </svg>
                </button>
                <button class="icon-btn" aria-label="Thông báo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.7 21a2 2 0 01-3.4 0" />
                    </svg>
                </button>
                <button class="btn-primary">Đăng nhập</button>
            </div>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero">
            <button class="hero-arrow left" aria-label="Trước">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <button class="hero-arrow right" aria-label="Sau">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>

            <div class="hero-text">
                <h1>Tìm phòng trọ thông minh<br>Phù hợp với bạn <span class="accent">bằng AI</span> ✨</h1>
                <p>AI sẽ hiểu nhu cầu của bạn và gợi ý những phòng trọ phù hợp nhất chỉ trong vài giây.</p>
                <div class="hero-badges">
                    <span class="badge-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="9" />
                            <circle cx="12" cy="12" r="4" />
                        </svg> Gợi ý chính xác</span>
                    <span class="badge-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3 3" />
                        </svg> Tiết kiệm thời gian</span>
                    <span class="badge-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z" />
                        </svg> An tâm lựa chọn</span>
                </div>
            </div>

            <div class="hero-media">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=900&h=700&fit=crop"
                    alt="Phòng trọ hiện đại">
                <div class="ai-pill">AI gợi ý cho bạn</div>
                <div class="hero-dots">
                    <span class="active"></span><span></span><span></span>
                </div>
            </div>
        </div>
    </section>

    <section class="search-section">
        <div class="search-card">
            <form id="search-form">
                <div class="search-grid">
                    <div class="field">
                        <label for="doi-tuong">Tìm cho</label>
                        <div class="select-box">
                            <svg class="leading" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 10L12 5 2 10l10 5 10-5z" />
                                <path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5" />
                            </svg>
                            <select id="doi-tuong" name="doi-tuong">
                                <option value="sinh-vien">Sinh viên</option>
                                <option value="nguoi-di-lam">Người đi làm</option>
                            </select>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    <div class="field">
                        <label for="khoang-gia">Khoảng giá</label>
                        <div class="select-box">
                            <svg class="leading" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path
                                    d="M12 7v10M9 9.5c0-1.4 1.3-2.5 3-2.5s3 1.1 3 2.5-1.3 2-3 2.5-3 1.1-3 2.5 1.3 2.5 3 2.5 3-1.1 3-2.5" />
                            </svg>
                            <select id="khoang-gia" name="khoang-gia">
                                <option value="600k-1tr">600.000 - 1.000.000 đ</option>
                                <option value="1tr-2tr">1.000.000 - 2.000.000 đ</option>
                            </select>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    <div class="field">
                        <label for="khu-vuc">Khu vực</label>
                        <div class="select-box">
                            <svg class="leading" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s7-7.4 7-12.6A7 7 0 105 9.4C5 14.6 12 22 12 22z" />
                                <circle cx="12" cy="9" r="2.6" />
                            </svg>
                            <select id="khu-vuc" name="khu-vuc">
                                <option value="dh-nong-lam">Đại học Nông Lâm</option>
                                <option value="cd-fpt">Cao đẳng FPT</option>
                                <option value="dh-thai-nguyen">Đại học Thái Nguyên</option>
                            </select>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    <div class="field">
                        <label for="khoang-cach">Khoảng cách</label>
                        <div class="select-box">
                            <svg class="leading" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 12h13a3 3 0 000-6H8" />
                                <circle cx="6" cy="17" r="2" />
                                <circle cx="17" cy="17" r="2" />
                            </svg>
                            <select id="khoang-cach" name="khoang-cach">
                                <option value="duoi-600m">Dưới 600m</option>
                                <option value="1km-2km">Từ 1km - 2km</option>
                            </select>
                            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="search-row2">
                    <div class="keyword-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M21 21l-4.3-4.3" />
                        </svg>
                        <input type="text" id="yeu-cau" name="yeu-cau"
                            placeholder="Nhập yêu cầu tìm phòng của bạn (VD: phòng có ban công, gần chợ, an ninh tốt...)">
                    </div>
                    <button type="submit" class="btn-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M21 21l-4.3-4.3" />
                        </svg>
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="section-head">
            <h2>Phòng trọ mới nhất ✨</h2>
            <a href="#" class="see-all">Xem tất cả →</a>
        </div>
        <div class="cards-grid" id="cards-grid">
            <!-- cards injected by JS -->
        </div>
    </section>

    <section class="section">
        <div class="features-strip">
            <div class="feature-item">
                <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l1.8 4.6L18 8l-4.2 1.4L12 14l-1.8-4.6L6 8l4.2-1.4z" />
                        <path d="M19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9z" />
                    </svg></div>
                <div>
                    <h3>AI gợi ý thông minh</h3>
                    <p>Đề xuất phòng trọ phù hợp nhất với nhu cầu của bạn</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 3" />
                    </svg></div>
                <div>
                    <h3>Tiết kiệm thời gian</h3>
                    <p>Tìm kiếm nhanh chóng, lọc kết quả chỉ trong vài giây</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="14" rx="2" />
                        <path d="M3 9h18" />
                    </svg></div>
                <div>
                    <h3>Thông tin minh bạch</h3>
                    <p>Hình ảnh thật, giá rõ ràng, đánh giá chân thực</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg></div>
                <div>
                    <h3>Trải nghiệm thực tế ảo</h3>
                    <p>Xem phòng 360° sống động như đang ở trực tiếp</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 11l9-8 9 8" />
                            <path d="M5 10v10h14V10" />
                        </svg>
                    </div>
                    <div>
                        <div class="brand-name">AIHome</div>
                        <div class="brand-tag">Tìm phòng trọ thông minh bằng AI</div>
                    </div>
                </div>
                <p>AIHome giúp bạn tìm phòng trọ phù hợp nhanh chóng, tiết kiệm thời gian và chi phí.</p>
                <div class="socials">
                    <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M13 22v-8h2.7l.4-3H13V9c0-.9.3-1.5 1.6-1.5H16V4.9C15.6 4.8 14.7 4.7 13.6 4.7c-2.4 0-4 1.4-4 4.1V11H7v3h2.6v8z" />
                        </svg></a>
                    <a href="#" aria-label="Zalo"><svg viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10" />
                        </svg></a>
                    <a href="#" aria-label="TikTok"><svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M14 3v9.5a2.5 2.5 0 11-2-2.45V8a5 5 0 105 5V9.8A6.5 6.5 0 0021 11V8a5 5 0 01-4-2.6V3z" />
                        </svg></a>
                    <a href="#" aria-label="Youtube"><svg viewBox="0 0 24 24" fill="currentColor">
                            <rect x="2" y="6" width="20" height="12" rx="3" />
                            <path d="M10 9l5 3-5 3z" fill="#fff" />
                        </svg></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>VỀ AIHOME</h4>
                <ul>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Cách hoạt động</a></li>
                    <li><a href="#">Tin tức</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>HỖ TRỢ</h4>
                <ul>
                    <li><a href="#">Hướng dẫn tìm phòng</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>KHU VỰC NỔI BẬT</h4>
                <ul>
                    <li><a href="#">Hà Nội</a></li>
                    <li><a href="#">TP. Hồ Chí Minh</a></li>
                    <li><a href="#">Đà Nẵng</a></li>
                    <li><a href="#">Hải Phòng</a></li>
                </ul>
            </div>
            <div class="footer-col newsletter">
                <h4>ĐĂNG KÝ NHẬN TIN</h4>
                <p>Nhận thông tin phòng trọ mới nhất và ưu đãi hấp dẫn</p>
                <div class="newsletter-box">
                    <input type="email" placeholder="Nhập email của bạn">
                    <button aria-label="Đăng ký"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M22 2L11 13" />
                            <path d="M22 2l-7 20-4-9-9-4z" />
                        </svg></button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">© 2026 AIHome. All rights reserved.</div>
    </footer>

    <script>
        const rooms = [{
                img: "https://images.unsplash.com/photo-1615874959474-d609969a20ed?w=500&h=400&fit=crop",
                title: "Phòng trọ gần ĐH Nông Lâm",
                loc: "Đường Xuân Thủy, Cầu Giấy, Hà Nội",
                price: "2.800.000 đ/tháng",
                dist: "1.2 km",
                rating: "4.7 (32)"
            },
            {
                img: "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=500&h=400&fit=crop",
                title: "Phòng trọ phố Duy Tân",
                loc: "Duy Tân, Cầu Giấy, Hà Nội",
                price: "3.200.000 đ/tháng",
                dist: "1.5 km",
                rating: "4.6 (28)"
            },
            {
                img: "https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=500&h=400&fit=crop",
                title: "Phòng trọ gần Cao đẳng FPT",
                loc: "Chùa Láng, Đống Đa, Hà Nội",
                price: "2.600.000 đ/tháng",
                dist: "2.3 km",
                rating: "4.5 (19)"
            },
            {
                img: "https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=500&h=400&fit=crop",
                title: "Phòng trọ Kim Mã Thượng",
                loc: "Kim Mã Thượng, Ba Đình, Hà Nội",
                price: "3.000.000 đ/tháng",
                dist: "2.7 km",
                rating: "4.8 (24)"
            }
        ];

        const grid = document.getElementById('cards-grid');
        grid.innerHTML = rooms.map(r => `
    <div class="card">
      <div class="card-media">
        <img src="${r.img}" alt="${r.title}">
        <span class="tag-new">Mới</span>
        <button class="fav-btn" aria-label="Yêu thích">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6c-1.6-1.6-4.2-1.6-5.8 0L12 7.6 9 4.6c-1.6-1.6-4.2-1.6-5.8 0-1.6 1.6-1.6 4.2 0 5.8l8.8 8.8 8.8-8.8c1.6-1.6 1.6-4.2 0-5.8z"/></svg>
        </button>
      </div>
      <div class="card-body">
        <h3 class="card-title">${r.title}</h3>
        <div class="card-loc">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s7-7.4 7-12.6A7 7 0 105 9.4C5 14.6 12 22 12 22z"/><circle cx="12" cy="9" r="2.6"/></svg>
          ${r.loc}
        </div>
        <div class="card-price">${r.price}</div>
        <div class="card-meta">
          <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12h13a3 3 0 000-6H8"/><circle cx="6" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>${r.dist}</span>
          <span class="star"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.1 6.3 6.9 1-5 4.9 1.2 6.9L12 17.8 5.8 21l1.2-6.9-5-4.9 6.9-1z"/></svg>${r.rating}</span>
        </div>
        <div class="card-actions">
          <button class="btn-vr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="10" rx="3"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/></svg>Xem phòng VR</button>
          <button class="btn-map"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3L3 6v15l6-3 6 3 6-3V3l-6 3-6-3z"/><path d="M9 3v15M15 6v15"/></svg>Bản đồ</button>
        </div>
      </div>
    </div>
  `).join('');

        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const data = {
                doiTuong: document.getElementById('doi-tuong').value,
                khoangGia: document.getElementById('khoang-gia').value,
                khuVuc: document.getElementById('khu-vuc').value,
                khoangCach: document.getElementById('khoang-cach').value,
                yeuCau: document.getElementById('yeu-cau').value
            };
            console.log('Tìm kiếm với điều kiện:', data);
            alert('Đang tìm phòng trọ với điều kiện:\n' +
                '- Đối tượng: ' + document.getElementById('doi-tuong').selectedOptions[0].text + '\n' +
                '- Khoảng giá: ' + document.getElementById('khoang-gia').selectedOptions[0].text + '\n' +
                '- Khu vực: ' + document.getElementById('khu-vuc').selectedOptions[0].text + '\n' +
                '- Khoảng cách: ' + document.getElementById('khoang-cach').selectedOptions[0].text +
                (data.yeuCau ? '\n- Yêu cầu: ' + data.yeuCau : ''));
        });
    </script>

</body>

</html>