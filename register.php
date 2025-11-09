<?php include_once 'init.php'; if ($_SERVER['REQUEST_METHOD']=='POST'){ $email=$_POST['email']; $pass=password_hash($_POST['password'], PASSWORD_DEFAULT); $name=$_POST['name']; try{ $db->prepare('INSERT INTO users (email,password,name) VALUES (?,?,?)')->execute([$email,$pass,$name]); header('Location: login.php'); exit;}catch(Exception $e){ $err='Ошибка: возможно email уже занят'; } } ?>
<!doctype html><html><head><meta charset="utf-8"><title>Регистрация</title><link rel="stylesheet" href="assets/style.css"></head><body>
<div class="authbox">
  <h2>Регистрация</h2>
  <?php if(isset($err)) echo '<p class="error">'.htmlspecialchars($err).'</p>'; ?>
  <form method="post">
    <label>Имя<input name="name" required></label>
    <label>Email<input name="email" type="email" required></label>
    <label>Пароль<input name="password" type="password" required pattern=".{6,}" title="От 6 символов"></label>
    <button class="btn">Зарегистрироваться</button>
  </form>
  <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</div></body></html>
