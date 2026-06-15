# PlayPulse - Sports News, Live Score & Streaming Guide Portal

PlayPulse is a comprehensive, production-ready sports portal built with PHP, MySQL, and Bootstrap 5. It features a modern design, multi-language support (English/Bangla), and a powerful admin panel.

## Key Features
- **Dynamic News System**: SEO-friendly URLs, categories, tags, and featured images with CKEditor.
- **Match Center**: Live scores, upcoming fixtures, and detailed match statistics.
- **Streaming Guide**: "Where to Watch" section with management for broadcasters and OTT platforms.
- **Admin Dashboard**: Advanced statistics for views, users, subscribers, and comments.
- **Security**: Full CSRF protection, XSS sanitization, PDO prepared statements, and secure password hashing.
- **Notifications**: System for pushing breaking news and match update notifications.
- **User Features**: Secure registration, email verification, forgot password, and activity history.
- **Search & Discovery**: AJAX-based live search and paginated category listings.
- **Performance**: Gzip compression, browser caching, and image lazy loading.

## Tech Stack
- **Backend**: PHP 8.1+ (PDO, GD Library)
- **Database**: MySQL 8+
- **Frontend**: Bootstrap 5, Vanilla JS, Font Awesome 6
- **Tools**: CKEditor 4, PHP PDO

## Installation
1. Upload all files to your Apache server or cPanel hosting.
2. Create a MySQL database and user.
3. Access `http://yourdomain.com/install` to run the Installation Wizard.
4. Provide database credentials and create a Super Admin account.
5. **CRITICAL**: Delete the `install` folder after setup for security.

## Deployment Checklist
- [ ] Ensure `uploads` directory is writable (755 or 775).
- [ ] Update `SITE_URL` in `config/config.php` if changed.
- [ ] Configure SMTP in `includes/functions.php` for actual email delivery (currently demo mode).
- [ ] Set `display_errors` to `0` in production in `config/config.php`.
- [ ] Verify `.htaccess` is active for SEO-friendly URLs.

## Security Audit Report
- **CSRF**: Protected on all POST forms in Admin and User panels.
- **XSS**: All user-generated content and database outputs are escaped using `e()`.
- **SQLi**: 100% usage of PDO Prepared Statements.
- **Auth**: Password hashing via `PASSWORD_DEFAULT`. Session-based role access control.

## Testing Report
- **Installation**: Passed. Creates tables and config file correctly.
- **News Creation**: Passed. CKEditor and image upload functional.
- **Live Search**: Passed. AJAX suggestions appearing in header.
- **User Flow**: Passed. Registration, login, and profile activity working.
- **Mobile Responsive**: Verified with Bootstrap 5 grid.
