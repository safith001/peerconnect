# PeerConnect

A Laravel social networking platform for connecting peers — build communities, share posts, chat in real-time, and manage friendships.

## Features

- **User Authentication** — Registration, login, email verification, password reset (Laravel Breeze)
- **Posts & Comments** — Create, edit, delete posts; comment and like
- **Peer System** — Send/accept/decline peer requests, unfriend
- **Messaging** — Private conversations with accepted peers, unread counts, read receipts
- **Blocking** — Block/unblock users; bidirectional enforcement across messaging and peer requests
- **Reporting** — Report posts and comments for admin review
- **Admin Panel** — Dedicated `admin/` module at `/admin` to manage users, posts, and reports
- **Dark Mode** — Toggle with localStorage persistence, `dark:` variants across all views
- **Glass-morphism UI** — Frosted glass cards, gradient buttons, gradient decorative blobs
- **Responsive** — Tailwind CSS 3 layout with sidebar navigation

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Blade, Tailwind CSS 3, Alpine.js |
| Build | Vite |
| Database | SQLite (default) / MySQL |
| Auth | Laravel Breeze (session-based) |
| Testing | Pest + PHPUnit (58 tests, 135 assertions) |

## Installation

```bash
git clone <repo-url> peerconnect
cd peerconnect
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configure your database in `.env` (SQLite is the default):

```dotenv
DB_CONNECTION=sqlite
```

> **Important:** The app requires `SESSION_DRIVER=database` and `QUEUE_CONNECTION=database`. Create the session table first:

```bash
php artisan session:table
php artisan migrate
```

Seed the admin user:

```bash
php artisan db:seed --class=AdminSeeder
```

Build frontend assets and start the dev server:

```bash
npm run build
php artisan serve
```

> **Do not use XAMPP** — the session driver conflicts. Always use `php artisan serve` (port 8000).

## Usage

| URL | Description |
|-----|-------------|
| `/` | Welcome / landing page |
| `/register` | Create an account |
| `/login` | Sign in |
| `/dashboard` | User dashboard |
| `/posts` | All posts |
| `/peers` | Find peers |
| `/conversations` | Private messages |
| `/connections` | Your accepted peers |
| `/admin` | Admin panel (admin only) |

### Default Admin Account

- **Email:** `admin@peerconnect.com`
- **Password:** `password`
- Admin login redirects to `/admin` automatically.

## Project Structure

```
peerconnect/
├── admin/                          # Self-contained admin module
│   ├── Controllers/                # Admin controllers (ADMIN namespace)
│   │   ├── DashboardController.php
│   │   ├── PostController.php
│   │   ├── ReportController.php
│   │   └── UserController.php
│   ├── Middleware/
│   │   └── CheckAdmin.php          # Admin access middleware
│   ├── views/                      # Admin Blade views (admin:: namespace)
│   │   ├── layouts/
│   │   │   └── admin.blade.php
│   │   ├── posts/
│   │   │   └── index.blade.php
│   │   ├── reports/
│   │   │   └── index.blade.php
│   │   ├── users/
│   │   │   └── index.blade.php
│   │   └── dashboard.blade.php
│   └── routes.php                  # Admin route definitions
├── app/
│   ├── Http/Controllers/           # Main app controllers
│   │   ├── Auth/                   # Breeze auth controllers
│   │   ├── BlockController.php
│   │   ├── CommentController.php
│   │   ├── ConversationController.php
│   │   ├── DashboardController.php
│   │   ├── LikeController.php
│   │   ├── PeerRequestController.php
│   │   ├── PostController.php
│   │   ├── ProfileController.php
│   │   ├── ReportController.php
│   │   └── UserController.php
│   ├── Models/                     # Eloquent models
│   │   ├── Block.php
│   │   ├── Comment.php
│   │   ├── Conversation.php
│   │   ├── Like.php
│   │   ├── Message.php
│   │   ├── PeerRequest.php
│   │   ├── Post.php
│   │   ├── Report.php
│   │   └── User.php
│   ├── Policies/                   # Authorization policies
│   ├── Providers/
│   │   ├── AdminServiceProvider.php
│   │   └── AppServiceProvider.php
│   └── View/Composers/
│       └── PeerRequestComposer.php
├── bootstrap/app.php               # Framework config (providers, middleware)
├── config/                         # Laravel config files
├── database/
│   ├── factories/                  # Model factories
│   ├── migrations/                 # Database migrations (18)
│   └── seeders/
│       ├── AdminSeeder.php
│       └── DatabaseSeeder.php
├── resources/views/                # Blade templates
│   ├── layouts/                    # App, guest, navbar, sidebar
│   ├── auth/                       # Login, register, password reset
│   ├── connections/
│   ├── conversations/
│   ├── peer_requests/
│   ├── posts/
│   ├── profiles/
│   └── users/
├── routes/
│   ├── web.php                     # Web routes
│   └── auth.php                    # Auth routes
└── tests/                          # Test suite (58 passing, 135 assertions)
```

## Testing

```bash
php artisan test
```

All 58 feature tests and 135 assertions should pass.

### Test coverage

- Authentication (login, registration, password reset, email verification)
- Posts CRUD with authorization
- Comments with authorization
- Likes toggle
- Peer requests (send, accept, decline, duplicate, self)
- Conversations (start, message, authorization)
- Profile (update, delete)

## Admin Panel

The admin panel is a self-contained module in `admin/`. It is registered via `AdminServiceProvider` and uses its own route file, controllers, middleware, and views — all isolated from the main app code.

- **Dashboard** — Platform stats (users, posts, comments, reports, peers)
- **Users** — Search, filter by suspension, suspend/restore users
- **Posts** — Search and delete posts (with comments and likes)
- **Reports** — View, dismiss, or mark as action-taken; delete reported posts inline

## License

MIT
