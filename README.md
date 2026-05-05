# RepoHive Laravel UI Prototype

RepoHive is a Laravel migration of the original static HTML prototype for the TCC integrative API project. This phase focuses on UI/UX structure only, so backend authentication, OTP delivery, email delivery, and AI responses are simulated in the browser.

## Included Modules

- Authentication screens: login, registration, and simulated Google sign-in
- OTP flow: phone OTP, email OTP, and verification screen using prototype code `123456`
- Mailbox: inbox, sent history, archived messages, search, and compose modal
- AI chatbot: animated chat interface with prototype responses

## Laravel Routes

- `/` - app hub
- `/login` - authentication login
- `/register` - account registration
- `/otp/phone` - phone OTP request
- `/otp/email` - email OTP request
- `/otp/verify` - OTP validation
- `/mailbox` - mailbox dashboard
- `/ai-chatbot` - AI assistant

Legacy static paths such as `/index.html`, `/mailbox.html`, and `/ai-chatbot.html` redirect to the new Laravel routes.

## Project Structure

```text
app/                    Laravel application classes
public/assets/          Migrated prototype CSS, JavaScript, and image assets
resources/views/        Blade layouts, pages, and partials
routes/web.php          UI route definitions and legacy redirects
tests/Feature/          Route smoke tests for the UI prototype
```

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Open the local URL shown by `php artisan serve`.

## Prototype Notes

- OTP code is always `123456`.
- Prototype auth state is saved in `localStorage`.
- Sent mailbox messages are saved in `localStorage`.
- The chatbot uses local JavaScript replies and is ready for backend AI integration in the next phase.
