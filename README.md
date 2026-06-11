# PeerConnect

A peer-to-peer academic collaboration platform built for university students to connect, share knowledge, and communicate with each other.

## Features

- **User Authentication** — Register, login, email verification, password reset
- **Student Profiles** — Profile picture, bio, faculty, department, semester, student ID
- **Peer Discovery** — Search and filter students by name, faculty, department, or semester
- **Peer Requests** — Send, accept, or decline connection requests
- **Posts & Comments** — Share posts with file attachments (PDF, images, documents); comment on posts
- **Private Messaging** — 1-to-1 chat between accepted peers with read receipts
- **Dashboard** — Overview of your posts, connections, unread messages, and pending requests

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Database | SQLite |
| Auth | Laravel Breeze |
| Build Tool | Vite |

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm

### Installation

```bash
# Clone the repository
git clone https://github.com/safith001/peerconnect.git
cd peerconnect

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Link storage
php artisan storage:link

# Build frontend assets
npm run build
```

### Running Locally

```bash
php artisan serve
```

Then visit `http://localhost:8000` in your browser.

To run all services at once (server + queue + logs + vite):

```bash
composer dev
```

## Project Structure

```
app/
├── Http/Controllers/   # Request handling (Posts, Comments, Messages, Peers, etc.)
├── Models/             # Eloquent models (User, Post, Comment, Message, etc.)
├── Policies/           # Authorization logic
└── View/Components/    # Blade components

database/
├── migrations/         # Database schema
└── seeders/            # Test data

resources/views/        # Blade templates
routes/web.php          # Application routes
```

## License

This project is open-source and available under the [MIT License](LICENSE).
