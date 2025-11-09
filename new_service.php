<?php include_once 'init.php'; require_login(); $u = user(); if($_SERVER['REQUEST_METHOD']=='POST'){ $title=$_POST['title']; $desc=$_POST['description']; $price=(int)$_POST['price']; $cat=$_POST['category']; $imgname=''; if(isset($_FILES['image']) && $_FILES['image']['tmp_name']){ $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); $imgname = time().'_'.bin2hex(random_bytes(4)).'.'.preg_replace('/[^a-z0-9]/i','',$ext); move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/uploads/'.$imgname); } $db->prepare('INSERT INTO services (user_id,title,description,price,image,category) VALUES (?,?,?,?,?,?)')->execute([$u['id'],$title,$desc,$price,$imgname,$cat]); header('Location: dashboard.php'); exit; } ?>
<!doctype html><html><head><meta charset="utf-8"><title>Новая услуга</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'index_header_stub.php'; ?>
<main class="container">
  <h2>Создать услугу или товар</h2>
  <form method="post" enctype="multipart/form-data" class="form">
    <label>Название<input name="title" required></label>
    <label>Описание<textarea name="description" required></textarea></label>
    <label>Категория<input name="category" required placeholder="Игры, Услуги, Диджитал"></label>
    <label>Цена (RUB)<input name="price" type="number" min="0" value="100" required></label>
    <label>Картинка<input type="file" name="image" accept="image/*"></label>
    <button class="btn">Опубликовать</button>
  </form>
</main></body></html>
