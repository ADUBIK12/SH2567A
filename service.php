<?php include_once 'init.php'; $id = (int)($_GET['id'] ?? 0);
// delete review
if(isset($_GET['delete_review'])){ require_login(); $rid=(int)$_GET['delete_review']; $stmt = $db->prepare('SELECT * FROM reviews WHERE id=?'); $stmt->execute([$rid]); $rv = $stmt->fetch(PDO::FETCH_ASSOC); if($rv && $rv['user_id']==$_SESSION['user_id']){ $db->prepare('DELETE FROM reviews WHERE id=?')->execute([$rid]); header('Location: service.php?id='.$id); exit; } }
 $stmt=$db->prepare('SELECT s.*, u.name as author, u.id as author_id FROM services s JOIN users u ON u.id=s.user_id WHERE s.id=?'); $stmt->execute([$id]); $s=$stmt->fetch(PDO::FETCH_ASSOC); if(!$s){ echo 'Не найдена'; exit; } $u=user(); if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['buy'])){ if(!$u){ header('Location: login.php'); exit; } if($u['id']==$s['author_id']) $err='Нельзя купить свое предложение'; elseif($u['balance'] < $s['price']) $err='Недостаточно средств'; else { $db->beginTransaction(); $db->prepare('UPDATE users SET balance=balance-? WHERE id=?')->execute([$s['price'],$u['id']]); $db->prepare('UPDATE users SET balance=balance+? WHERE id=?')->execute([$s['price'],$s['author_id']]); $db->prepare('INSERT INTO notifications (user_id,text) VALUES (?,?)')->execute([$s['author_id'], 'Ваша услуга "'.$s['title'].'" куплена.']); $db->commit(); $msg='Успешно куплено'; } }
// handle review
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['review'])){ if(!$u){ header('Location: login.php'); exit; } if($u['id']==$s['author_id']) $err='Нельзя оставить отзыв на свою услугу'; else { $rating = (int)$_POST['rating']; $comment = $_POST['comment']; $db->prepare('INSERT INTO reviews (service_id,user_id,rating,comment) VALUES (?,?,?,?)')->execute([$s['id'],$u['id'],$rating,$comment]); $msg='Отзыв добавлен'; } }
$reviews = $db->prepare('SELECT r.*, u.name FROM reviews r JOIN users u ON u.id=r.user_id WHERE r.service_id=? ORDER BY r.created_at DESC'); $reviews->execute([$s['id']]); $reviews = $reviews->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title><?php echo htmlspecialchars($s['title']); ?></title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'index_header_stub.php'; ?>
<main class="container service-view">
  <div class="service-left">
    <div class="imgbig"><?php if($s['image']): ?><img src="uploads/<?php echo htmlspecialchars($s['image']); ?>"><?php else: ?><div class="placeholder">Нет изображения</div><?php endif; ?></div>
    <h1><?php echo htmlspecialchars($s['title']); ?></h1>
    <p class="meta">от <?php echo htmlspecialchars($s['author']); ?> — <strong><?php echo $s['price']; ?> ₽</strong></p>
    <p><?php echo nl2br(htmlspecialchars($s['description'])); ?></p>

    
    <section class="reviews">
      <h3>Отзывы</h3>
      <?php foreach($reviews as $r): ?>
        <div class="review"><div class="rev-head"><strong><?php echo htmlspecialchars($r['name']); ?></strong><div class="stars"><?php for($i=1;$i<=5;$i++){ echo $i<=$r['rating']?"<span class='star on'>★</span>":"<span class='star'>☆</span>"; } ?></div><?php if($u && $u['id']==$r['user_id']): ?><a class="delrev" href="?id=<?php echo $s['id']; ?>&delete_review=<?php echo $r['id']; ?>" onclick="return confirm('Удалить отзыв?')">Удалить</a><?php endif; ?></div><p><?php echo htmlspecialchars($r['comment']); ?></p></div>
      <?php endforeach; ?>
      <?php if($u): ?>
        <form method="post" class="form" id="reviewForm">
          <h4>Оставить отзыв</h4>
          <label>Рейтинг
            <div class="rating-select" data-selected="5">
              <span class="star sel" data-value="1">★</span>
              <span class="star sel" data-value="2">★</span>
              <span class="star sel" data-value="3">★</span>
              <span class="star sel" data-value="4">★</span>
              <span class="star sel" data-value="5">★</span>
            </div>
            <input type="hidden" name="rating" id="ratingInput" value="5">
          </label>
          <label>Комментарий<textarea name="comment" required></textarea></label>
          <button name="review" class="btn">Отправить отзыв</button>
        </form>
      <?php else: ?><p><a href="login.php">Войдите</a>, чтобы оставить отзыв.</p><?php endif; ?>
    </section>
    
  </div>

  <aside class="service-right">
    <?php if(isset($err)) echo '<p class="error">'.htmlspecialchars($err).'</p>'; if(isset($msg)) echo '<p class="success">'.htmlspecialchars($msg).'</p>'; ?>
    <form method="post">
      <button name="buy" class="btn big">Купить за <?php echo $s['price']; ?> ₽</button>
    </form>
    <div class="sellerbox">
      <h4>Продавец</h4>
      <p><?php echo htmlspecialchars($s['author']); ?></p>
      <a href="profile.php?id=<?php echo $s['author_id']; ?>">Профиль</a>
    </div>
  </aside>
</main>
</body></html>
