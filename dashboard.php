<?php include_once 'init.php'; require_login(); $u = user(); ?>
<!doctype html><html><head><meta charset="utf-8"><title>Панель</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'index_header_stub.php'; ?>
<main class="container">
  <h2>Панель пользователя</h2>
  <p>Баланс: <strong><?php echo $u['balance']; ?> ₽</strong></p>
  <a class="btn" href="new_service.php">Создать услугу</a>
  <h3>Мои услуги</h3>
  <div class="items">
  <?php $stmt=$db->prepare('SELECT * FROM services WHERE user_id=? ORDER BY created_at DESC'); $stmt->execute([$u['id']]); foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $s): ?>
    <article class="itemcard">
      <a href="service.php?id=<?php echo $s['id']; ?>">
        <div class="imgwrap"><?php if($s['image']): ?><img src="uploads/<?php echo htmlspecialchars($s['image']); ?>"><?php else: ?><div class="placeholder">Изображение</div><?php endif; ?></div>
        <div class="meta"><h3><?php echo htmlspecialchars($s['title']); ?></h3><p><?php echo $s['price']; ?> ₽</p></div>
      </a>
      <div class="actions"><a href="edit_service.php?id=<?php echo $s['id']; ?>">Редактировать</a></div>
    </article>
  <?php endforeach; ?>
  </div>
</main></body></html>
