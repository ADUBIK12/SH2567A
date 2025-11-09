Shury Mury - Prototype marketplace
=================================
This is a self-contained prototype (PHP + SQLite) intended for local testing.
It includes:
- user registration/login (roles: user, moderator, admin)
- listing services/products with images
- reviews (users cannot review their own services)
- fake balance (everyone starts with 10000 RUB)
- purchase functionality (deducts balance)
- moderator/admin panel: ban/unban users, hide/unhide services, chat with users
- basic chat between users and moderators/admins
- theme settings, cookie consent, notifications, responsive layout
- initial admin/moderator accounts below

How to run:
1) Put this folder into a PHP-capable server root (e.g., XAMPP htdocs or php -S localhost:8000 from this folder).
2) Ensure PHP has sqlite3 enabled and the webserver can write to the folder (uploads/ and data.db).
3) On first run, the app will initialize the SQLite database automatically.

Admin / Moderator accounts (saved here for convenience):
- Admin:
    login: admin@shurymury.test
    password: AdminPass123
- Moderator:
    login: mod@shurymury.test
    password: ModPass123

Notes:
- This is a prototype. Do NOT use in production without security hardening:
  - Password hashing uses password_hash() but inputs are not fully validated.
  - CSRF protection is minimal.
  - File uploads are loosely validated.
- Images uploaded are stored in uploads/ folder.
- To reset DB delete data.db and reload.

Enjoy the prototype!
