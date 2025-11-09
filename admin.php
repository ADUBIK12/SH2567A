<?php include_once 'init.php'; require_login(); $u=user(); if($u['role']!='admin' && $u['role']!='moderator'){ echo 'Доступ запрещен'; exit; }
// handle actions
if(isset($_GET['ban'])){ $db->prepare('UPDATE users SET banned=1 WHERE id=?')->execute([(int)$_GET['ban']]); }
if(isset($_GET['unban'])){ $db->prepare('UPDATE users SET banned=0 WHERE id=?')->execute([(int)$_GET['unban']]); }
if(isset($_GET['hide'])){ $db->prepare('UPDATE services SET visible=0 WHERE id=?')->execute([(int)$_GET['hide']]); }
if(isset($_GET['unhide'])){ $db->prepare('UPDATE services SET visible=1 WHERE id=?')->execute([(int)$_GET['unhide']]); }
$users = $db->query('SELECT * FROM users ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$services = $db->query('SELECT s.*, u.name as author FROM services s JOIN users u ON u.id=s.user_id ORDER BY s.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Модерация</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'index_header_stub.php'; ?>
<main class="container admin">
  <h2>Панель модерации</h2>
  <section>
    <h3>Пользователи</h3>
    <table class="admintab"><tr><th>ID</th><th>Email</th><th>Роль</th><th>Баланс</th><th>Действия</th></tr>
    <?php foreach($users as $us): ?>
      <tr><td><?php echo $us['id'];?></td><td><?php echo htmlspecialchars($us['email']);?></td><td><?php echo $us['role'];?></td><td><?php echo $us['balance'];?></td><td>
        <?php if(!$us['banned']): ?><a href="?ban=<?php echo $us['id'];?>">Ban</a><?php else: ?><a href="?unban=<?php echo $us['id'];?>">Unban</a><?php endif; ?>
      </td></tr>
    <?php endforeach; ?>
    </table>
  </section>

  <section>
    <h3>Объявления</h3>
    <table class="admintab"><tr><th>ID</th><th>Название</th><th>Автор</th><th>Цена</th><th>Видимо</th><th>Действие</th></tr>
    <?php foreach($services as $s): ?>
      <tr><td><?php echo $s['id'];?></td><td><?php echo htmlspecialchars($s['title']);?></td><td><?php echo htmlspecialchars($s['author']);?></td><td><?php echo $s['price'];?></td><td><?php echo $s['visible'];?></td><td><?php if($s['visible']): ?><a href="?hide=<?php echo $s['id'];?>">Скрыть</a><?php else: ?><a href="?unhide=<?php echo $s['id'];?>">Показать</a><?php endif; ?></td></tr>
    <?php endforeach; ?>
    </table>
  </section>
</main>
</body></html>
