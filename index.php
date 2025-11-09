<?php include_once 'init.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Shury Mury Marketplace</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/style.css">
  <script src="assets/app.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="theme">
  <header class="site-header">
    <a class="logo" href="/">Shury Mury</a>
    <div class="search">
      <form method="get" action="index.php">
        <input name="q" placeholder="Поиск услуг, товаров..." value="<?php echo htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit"><i class="fa fa-magnifying-glass"></i></button>
      </form>
    </div>
    <nav class="topnav">
      <?php $u = user(); if ($u): ?>
        <a href="dashboard.php">Баланс: <?php echo $u['balance']; ?> ₽</a>
        <a href="profile.php">Привет, <?php echo htmlspecialchars($u['name']); ?></a>
        <?php if($u['role']=='moderator' || $u['role']=='admin'): ?><a href="admin.php">Модерация</a><?php endif; ?>
        <a href="logout.php">Выйти</a>
      <?php else: ?>
        <a href="login.php">Войти</a>
        <a href="register.php">Регистрация</a>
      <?php endif; ?>
    </nav>
  </header>

  <main class="container">
    <section class="hero">
      <h1>Shury Mury — рынок услуг и товаров</h1>
      <p>Продавайте, покупайте и общайтесь с модераторами. Крутой дизайн, темы и мерцания.</p>
      <?php if($u): ?><a class="btn" href="new_service.php">Добавить услугу</a><?php endif; ?>
    </section>

    <section class="catalog">
      <h2>Категории</h2>
      <div class="cats">
        <a href="?cat=Игры">Игры</a>
        <a href="?cat=Гифты">Гифты</a>
        <a href="?cat=Услуги">Услуги</a>
        <a href="?cat=Диджитал">Диджитал</a>
      </div>

      <h2>Товары и услуги</h2>
      <div class="items">
      <?php
        $q = '%' . ($_GET['q'] ?? '') . '%';
        $cat = $_GET['cat'] ?? null;
        if ($cat) {
          $stmt = $db->prepare('SELECT s.*, u.name as author FROM services s JOIN users u ON u.id=s.user_id WHERE s.category=? AND s.visible=1 ORDER BY s.created_at DESC');
          $stmt->execute([$cat]);
        } else {
          $stmt = $db->prepare('SELECT s.*, u.name as author FROM services s JOIN users u ON u.id=s.user_id WHERE (s.title LIKE ? OR s.description LIKE ?) AND s.visible=1 ORDER BY s.created_at DESC');
          $stmt->execute([$q,$q]);
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $r):
      ?>
        <article class="itemcard">
          <a href="service.php?id=<?php echo $r['id']; ?>">
            <div class="imgwrap"><?php if($r['image']): ?><img src="uploads/<?php echo htmlspecialchars($r['image']); ?>"><?php else: ?><div class="placeholder">Изображение</div><?php endif; ?></div>
            <div class="meta">
              <h3><?php echo htmlspecialchars($r['title']); ?></h3>
              <p class="author">от <?php echo htmlspecialchars($r['author']); ?></p>
              <p class="price"><?php echo $r['price']; ?> <?php echo htmlspecialchars($r['currency']); ?></p>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer class="site-foot">
    <p>© Shury Mury — prototype</p>
  </footer>

  <div id="cookieConsent" class="cookie-consent">
    <p>Мы используем cookie для базовой работы сайта. <button onclick="acceptCookies()">Принять</button></p>
  </div>
</body>
</html>
