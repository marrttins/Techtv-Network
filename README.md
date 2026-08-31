# TechTV Network

**Africa’s Voice for Technology & Business Insight**

TechTV Network is a Nigerian-based African technology and business media platform delivering trusted news, original insights, executive interviews, thought leadership content, and industry intelligence across technology, innovation, entrepreneurship, and the digital economy.

---

## 🚀 Key Features

- **Modern Web Experience**: High-performance responsive frontend with breaking news tickers, featured sliders, topic grids, and live broadcasts.
- **YouTube Live Stream Integration**: Real-time YouTube live stream broadcast embedding with auto-play and standby program notifications.
- **Full-Featured Admin Suite**:
  - Posts & Articles management with SEO Tag/Keyword selector (up to 5 keywords).
  - Media Library with upload and modal selector.
  - Video management & Live stream broadcast controller.
  - Categories & Navigation Menu management.
  - User and role-based access control.
  - Newsletter subscriptions and interactive modal popups/alerts.
- **SEO & Schema Optimized**: Structured NewsArticle JSON-LD schemas, dynamic OpenGraph/Twitter cards, and meta keyword tags.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11 / PHP 8.2+
- **Database**: MySQL / MariaDB / SQLite
- **Frontend**: Blade Templating, Vanilla CSS (Modern Design System), JavaScript ES6+
- **Editor**: Classic CKEditor 5

---

## ⚙️ Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/marrttins/Techtv-Network.git
   cd Techtv-Network
   ```

2. **Install PHP and Node dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations & Seeds**:
   ```bash
   php artisan migrate --seed
   ```

5. **Start the Local Development Server**:
   ```bash
   php artisan serve
   ```

---

## 📄 License
This project is proprietary software for TechTV Network. All rights reserved.
