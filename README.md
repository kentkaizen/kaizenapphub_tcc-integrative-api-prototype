 # Kaizen App Hub

  Kaizen App Hub is a Laravel prototype for the TCC integrative API project. It converts the original static HTML UI into a Laravel application with
  database authentication, protected pages, OTP verification, mailbox UI, and an AI chatbot prototype.

  ## Features

  - User registration, login, and logout using Laravel authentication
  - Protected mailbox and AI chatbot pages
  - Phone OTP request, resend, and verification
  - Email OTP request, resend, and verification
  - Random 6-digit OTP codes with expiry, attempt limits, and cooldowns
  - Repohive SMS and Email API configuration
  - Mailbox UI with inbox, sent, archive, search, and compose modal
  - AI chatbot interface with prototype browser-side replies
  - Legacy static HTML paths redirected to Laravel routes
  - Feature tests for authentication, protected routes, and OTP flows.

    ## Main Routes

  - `/` - home page
  - `/login` - login form
  - `/register` - registration form
  - `/otp/phone` - phone OTP request
  - `/otp/verify` - phone OTP verification
  - `/otp/email` - email OTP request
  - `/otp/email/verify` - email OTP verification
  - `/mailbox` - protected mailbox dashboard
  - `/ai-chatbot` - protected AI chatbot page


  ## Local Setup

  ```bash
  composer install
  npm install
  cp .env.example .env
  php artisan key:generate

  Create a MySQL database named: laravel

  Then run:

  php artisan migrate --seed
  npm run build
  php artisan serve

  Open the local URL shown by php artisan serve.

  ## Demo Account

  Email: test@example.com
  Password: password

  ## Environment Notes

  The project uses MySQL by default:

  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=root
  DB_PASSWORD=

  OTP delivery uses Repohive API tokens:

  REPOHIVE_SMS_API_TOKEN=
  REPOHIVE_EMAIL_API_TOKEN=

  ## Testing

  php artisan test

  ## Project Notes

  The authentication and OTP flows are handled by Laravel. The mailbox and chatbot screens are still prototype UI modules, with the chatbot currently using
  browser-side sample responses.
