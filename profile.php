<?php include_once 'init.php'; $uid = (int)($_GET['id'] ?? 0); if(!$uid){ require_login(); $u=user(); $uid = $u['id']; } $stmt=$db->prepare('SELECT * FROM users WHERE id=?'); $stmt->execute([$uid]); $p = $stmt->fetch(PDO::FETCH_ASSOC); if(!$p){ echo 'Нет'; exit; } $own = (isset($_SESSION['user_id']) && $_SESSION['user_id']==$p['id']); if($_SERVER['REQUEST_METHOD']=='POST' && $own){ if(isset($_POST['name'])){ $db->prepare('UPDATE users SET name=?, color=? WHERE id=?')->execute([$_POST['name'],$_POST['color'],$p['id']]); header('Location: profile.php'); exit; } }
$services = $db->prepare('SELECT * FROM services WHERE user_id=?'); $services->execute([$p['id']]); ?>
<!doctype html><html><head><meta charset="utf-8"><title>Профиль</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'index_header_stub.php'; ?>
<main class="container profile">
  <div class="profile-left">
    <div class="avatar" style="background: linear-gradient(135deg,#fff,#<?php echo substr(md5($p['email']),0,6); ?>)"><h2><?php echo htmlspecialchars($p['name'][0] ?? 'U'); ?></h2></div>
    <h2><?php echo htmlspecialchars($p['name']); ?> <?php if($p['role']) echo '('.$p['role'].')'; ?></h2>
    <p>Баланс: <?php echo $p['balance']; ?> ₽</p>
    <?php if($own): ?>
      <form method="post" class="form">
        <label>Имя<input name="name" value="<?php echo htmlspecialchars($p['name']); ?>"></label>
        <label>Цвет ника<input name="color" value="<?php echo htmlspecialchars($p['color']); ?>" type="color"></label>
        <button class="btn">Сохранить</button>
      </form>
    <?php endif; ?>
  </div>
  <div class="profile-right">
    <h3>Объявления пользователя</h3>
    <div class="items">
      <?php foreach($services->fetchAll(PDO::FETCH_ASSOC) as $s): ?>
        <article class="itemcard"><a href="service.php?id=<?php echo $s['id']; ?>"><h4><?php echo htmlspecialchars($s['title']); ?></h4><p><?php echo $s['price']; ?> ₽</p></a></article>
      <?php endforeach; ?>
    </div>
  </div>
</main>
</body></html>
