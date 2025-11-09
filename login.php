<?php include_once 'init.php'; if ($_SERVER['REQUEST_METHOD']=='POST'){ $email=$_POST['email']; $stmt=$db->prepare('SELECT * FROM users WHERE email=?'); $stmt->execute([$email]); $u=$stmt->fetch(PDO::FETCH_ASSOC); if($u && password_verify($_POST['password'],$u['password'])){ if($u['banned']){ $err='Ваш аккаунт заблокирован'; } else { $_SESSION['user_id']=$u['id']; header('Location: index.php'); exit; } } else { $err='Неверные данные'; } } ?>
<!doctype html><html><head><meta charset="utf-8"><title>Вход</title><link rel="stylesheet" href="assets/style.css"></head><body>
<div class="authbox">
  <h2>Вход</h2>
  <?php if(isset($err)) echo '<p class="error">'.htmlspecialchars($err).'</p>'; ?>
  <form method="post">
    <label>Email<input name="email" type="email" required></label>
    <label>Пароль<input name="password" type="password" required></label>
    <button class="btn">Войти</button>
  </form>
  <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</div></body></html>
