# Raaz Room — PHP + MySQL Starter

## Quick Start (XAMPP)
1. Copy the entire `raaz_room_site` folder into your XAMPP web root (e.g., `C:/xampp/htdocs/`).
2. In phpMyAdmin, run `sql/schema.sql` to create the database and tables.
3. Update DB credentials in `includes/config.php` if needed.
4. Visit `http://localhost/raaz_room_site/public/index.php`.

### Admin Login
- Email: `admin@example.com`
- Password: `Admin@123` (change immediately)
- Toggle admin flag on your own account if you want: `UPDATE users SET is_admin=1 WHERE email='you@example.com';`

### Notes
- Registration uses a dev-mode email verification link shown on-screen. For production, integrate SMTP (e.g., PHPMailer) and set `DEV_SHOW_VERIFY_LINK` to `false`.
- Post click -> `public/go.php` shows an ad placeholder with a 5s countdown, then redirects to the post's `redirect_link` (set in Admin -> New Post/Edit Post).
- Only logged-in users can access `go.php` and comment.
- Styles are mobile-first with velvet color palette and subtle animations.
