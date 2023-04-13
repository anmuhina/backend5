<?php

header('Content-Type: text/html; charset=UTF-8');

session_start();

if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход)
     $user = 'u52811';
     $pass = '8150350';
     $db = new PDO('mysql:host=localhost;dbname=u52811', $user, $pass,
       [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
     $stmt1 = $db->prepare("SELECT name from application1 where login = ? and id=?");
     $res1 = $stmt1->execute([$_SESSION['login'],$_SESSION['uid']]);
     $name = $res1->fetchAll();
     $stmt2 = $db->prepare("SELECT email from application1 where login = ? and id=?");
     $res2 = $stmt2->execute([$_SESSION['login'],$_SESSION['uid']]);
     $email = $res2->fetchAll();
     $stmt3 = $db->prepare("SELECT birth_date from application1 where login = ? and id=?");
     $res3 = $stmt3->execute([$_SESSION['login'],$_SESSION['uid']]);
     $birth_date = $res3->fetchAll();
     $stmt4 = $db->prepare("SELECT sex from application1 where login = ? and id=?");
     $res4 = $stmt4->execute([$_SESSION['login'],$_SESSION['uid']]);
     $sex = $res4->fetchAll();
     $stmt5 = $db->prepare("SELECT amount_of_limbs from application1 where login = ? and id=?");
     $res5 = $stmt5->execute([$_SESSION['login'],$_SESSION['uid']]);
     $amount_of_limbs = $res5->fetchAll();
     $stmt6 = $db->prepare("SELECT ab_id from application1 join application_ability on (application1.id=application_ability.app_id) where login = ? and id=?");
     $res6 = $stmt6->execute([$_SESSION['login'],$_SESSION['uid']]);
     $abilities[] = $res6->fetchAll();
     $stmt7 = $db->prepare("SELECT biography from application1 where login = ? and id=?");
     $res7 = $stmt7->execute([$_SESSION['login'],$_SESSION['uid']]);
     $biography = $res7->fetchAll();
     $stmt8 = $db->prepare("SELECT informed from application1 where login = ? and id=?");
     $res8 = $stmt8->execute([$_SESSION['login'],$_SESSION['uid']]);
     $informed = $res8->fetchAll();
     $stmt9 = $db->prepare("SELECT password from application1 where login = ? and id=?");
     $res9 = $stmt9->execute([$_SESSION['login'],$_SESSION['uid']]);
     $password = $res9->fetchAll();
    
     $values = ["name"=>strip_tags($name), "email"=>strip_tags($email), "birth_date"=>$birth_date, "amount_of_limbs"=>$amount_of_limbs, "abilities"=>$abilities, "biography"=>strip_tags($biography), "informed"=>$informed, "login"=>$_SESSION['login'], "password"=>$password];
     
  
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
  
  $login=$_POST['login'];
  $password=$_POST['password'];
  
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибкаx
  $sth = $db->prepare("select login, password from application1 where login=? and password=?");
  $sth->execute([$login, $password]);
  $res1 = $sth->fetchAll();
  if (!$res1) {
    echo '<p class="error">Нет такого пользователя! Проверьте корректность введенных логина и пароля.</p>';
  }
  
  else {
  // Если все ок, то авторизуем пользователя.
  echo '<p> Поздравляем, вы прошли авторизацию!</p>';
  $_SESSION['login'] = $_POST['login'];
  
  // Записываем ID пользователя.
  $stmt=$db->prepare("select id from application1 where login=?");
  $stmt->execute([$_SESSION['login']]);
  $_SESSION['uid'] = $stmt->fetchAll();
    
  }
    
  header('Location: ./');
    
}
