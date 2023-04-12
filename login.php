<?php

header('Content-Type: text/html; charset=UTF-8');

session_start();

if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<style>
  #data {
    color: black;
    font-family: 'Times New Roman', Times, serif;
    font-weight: bold;
    font-style: normal;
    font-size: 25px;
  }
  .forma {
    margin: 0 auto;
  }
  .sub {
    padding: 5px;
    color: white;
    margin-top: 10px;
    background-color: rgb(255, 105, 180);
  }
</style>

<form class="forma" action="" method="post">
  <input id="data" name="login" />
  <input id="data" name="password" />
  <input id="data" class="sub" type="submit" value="Войти" />
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
   $user = 'u52811';
   $pass = '8150350';
   $db = new PDO('mysql:host=localhost;dbname=u52811', $user, $pass,
     [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
  
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибкаx
  $sth = $db->prepare("select login, password from application1 where login=? and password=?");
  $res = $sth->execute([$_POST['login'], $_POST['password']]);
  if ($res -> num_rows < 1) {
    print("Необходима авторизация!");
  }
  
  else {
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  
  // Записываем ID пользователя.
  //$_SESSION['uid'] = 123;
  $stmt=$db->prepare("select id from application1 where login=?");
  $result=$stmt->execute([$_SESSION['login']]);
  $_SESSION['uid'] = $result->fetchAll();
  }

  header('Location: ./');
}
