<?php
session_start();
$dbfile = __DIR__ . '/data.db';
$uploads = __DIR__ . '/uploads';
if (!is_dir($uploads)) mkdir($uploads, 0777, true);

try {
    $db = new PDO('sqlite:' . $dbfile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Cannot open database: ' . $e->getMessage());
}

// Init schema
$db->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT UNIQUE,
  password TEXT,
  name TEXT,
  role TEXT DEFAULT 'user',
  banned INTEGER DEFAULT 0,
  color TEXT DEFAULT '#00e5ff',
  balance INTEGER DEFAULT 10000
);

CREATE TABLE IF NOT EXISTS services (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER,
  title TEXT,
  description TEXT,
  price INTEGER,
  currency TEXT DEFAULT 'RUB',
  image TEXT,
  visible INTEGER DEFAULT 1,
  category TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviews (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  service_id INTEGER,
  user_id INTEGER,
  rating INTEGER,
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  from_user INTEGER,
  to_user INTEGER,
  text TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS notifications (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER,
  text TEXT,
  seen INTEGER DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
SQL
);

function init_admin(){
    global $db;
    $stmt = $db->prepare('SELECT COUNT(*) as c FROM users');
    $stmt->execute(); $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r || $r['c'] == 0) {
        $p = password_hash('AdminPass123', PASSWORD_DEFAULT);
        $db->prepare('INSERT INTO users (email,password,name,role) VALUES (?,?,?,?)')
           ->execute(['admin@shurymury.test',$p,'Site Admin','admin']);
        $p2 = password_hash('ModPass123', PASSWORD_DEFAULT);
        $db->prepare('INSERT INTO users (email,password,name,role) VALUES (?,?,?,?)')
           ->execute(['mod@shurymury.test',$p2,'Moderator','moderator']);
    }
}
init_admin();

function user(){
    global $db;
    if (!isset($_SESSION['user_id'])) return null;
    $s = $db->prepare('SELECT * FROM users WHERE id=?');
    $s->execute([$_SESSION['user_id']]);
    return $s->fetch(PDO::FETCH_ASSOC);
}

function require_login(){
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    $u = user();
    if ($u && $u['banned']) {
        echo "<h2>Ваш аккаунт заблокирован</h2>";
        exit;
    }
}
?>