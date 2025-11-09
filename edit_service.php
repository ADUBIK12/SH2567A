<?php include_once 'init.php'; require_login(); $u=user(); $id=(int)($_GET['id']??0);
$stmt=$db->prepare('SELECT * FROM services WHERE id=?');
$stmt->execute([$id]);
$srv=$stmt->fetch(PDO::FETCH_ASSOC);
if(!$srv || $srv['user_id'] != $u['id']){ echo 'Нет доступа'; exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){
    $db->prepare('UPDATE services SET title=?,description=?,price=?,category=? WHERE id=?')
       ->execute([$_POST['title'],$_POST['description'],(int)$_POST['price'],$_POST['category'],$id]);
    header('Location: dashboard.php'); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Редактировать</title><link rel="stylesheet" href="assets/style.css"></head><body><?php include 'index_header_stub.php'; ?>
<main class="container"><h2>Редактировать услугу</h2><form method="post" class="form">
<label>Название<input name="title" value="<?php echo htmlspecialchars($srv['title']); ?>"></label>
<label>Описание<textarea name="description"><?php echo htmlspecialchars($srv['description']); ?></textarea></label>
<label>Категория<input name="category" value="<?php echo htmlspecialchars($srv['category']); ?>"></label>
<label>Цена<input name="price" type="number" value="<?php echo $srv['price']; ?>"></label>
<button class="btn">Сохранить</button></form></main></body></html>
