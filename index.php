<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();
  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
    
    if (!empty($_COOKIE['password'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password']));
    }
    
  }
  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['birth_date'] = !empty($_COOKIE['birthDate_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['amount_of_limbs'] = !empty($_COOKIE['amountOfLimbs_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  $errors['informed'] = !empty($_COOKIE['informed_error']);
  
  
  // Выдаем сообщения об ошибках.
  if ($errors['name']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('name_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Введите имя корректно.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Введите почту.</div>';
  }
  if ($errors['birth_date']) {
    setcookie('birthDate_error', '', 100000);
    $messages[] = '<div class="error">Введите год рождения.</div>';
  }
  if ($errors['sex']) {
    setcookie('sex_error', '', 100000);
    $messages[] = '<div class="error">Выберите пол.</div>';
  }
  if ($errors['amount_of_limbs']) {
    setcookie('amountOfLimbs_error', '', 100000);
    $messages[] = '<div class="error">Выберите количество конечностей.</div>';
  }
  if ($errors['abilities']) {
    setcookie('abilities_error', '', 100000);
    $messages[] = '<div class="error">Выберите сверхспособности.</div>';
  }
  if ($errors['biography']) {
    setcookie('biography_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  if ($errors['informed']) {
    setcookie('informed_error', '', 100000);
    $messages[] = '<div class="error">Поставьте галочку "С контрактом ознакомлен(а).</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  
  $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['birth_date'] = empty($_COOKIE['birthDate_value']) ? '' : $_COOKIE['birthDate_value'];
  $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
  $values['amount_of_limbs'] = empty($_COOKIE['amountOfLimbs_value']) ? '' : $_COOKIE['amountOfLimbs_value'];
  $values['abilities'] = empty($_COOKIE['abilities_value']) ? '' : unserialize($_COOKIE['abilities_value']);
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : strip_tags($_COOKIE['biography_value']);
  $values['informed'] = empty($_COOKIE['informed_value']) ? '' : $_COOKIE['informed_value'];
  
  $values['login'] = empty($_COOKIE['login']) ? '' : $_COOKIE['login'];
  $values['password'] = empty($_COOKIE['password']) ? '' : $_COOKIE['password'];
  
  
   if (empty($errors) && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
     $user = 'u52811';
     $pass = '8150350';
     $db = new PDO('mysql:host=localhost;dbname=u52811', $user, $pass,
       [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
     
     $stmt1 = $db->prepare("SELECT name from application1 where login = ? and id=?");
     $stmt1->execute([$_SESSION['login'],$_SESSION['uid']]);
     $name = $stmt1->fetchAll();
     $values['name']=strip_tags($name);
     
     $stmt2 = $db->prepare("SELECT email from application1 where login = ? and id=?");
     $stmt2->execute([$_SESSION['login'],$_SESSION['uid']]);
     $email = $stmt2->fetchAll();
     $values['email']=strip_tags($email);
     
     $stmt3 = $db->prepare("SELECT birth_date from application1 where login = ? and id=?");
     $stmt3->execute([$_SESSION['login'],$_SESSION['uid']]);
     $birth_date = $stmt3->fetchAll();
     $values['birth_date']=$birth_date;
     
     $stmt4 = $db->prepare("SELECT sex from application1 where login = ? and id=?");
     $stmt4->execute([$_SESSION['login'],$_SESSION['uid']]);
     $sex = $stmt4->fetchAll();
     $values['sex']=$sex;
     
     $stmt5 = $db->prepare("SELECT amount_of_limbs from application1 where login = ? and id=?");
     $stmt5->execute([$_SESSION['login'],$_SESSION['uid']]);
     $amount_of_limbs = $stmt5->fetchAll();
     $values['amount_of_limbs']=$amount_of_limbs;
     
     $stmt6 = $db->prepare("SELECT ab_id from application1 join application_ability on (application1.id=application_ability.app_id) where login = ? and id=?");
     $stmt6->execute([$_SESSION['login'],$_SESSION['uid']]);
     $abilities = serialize($stmt6->fetchAll());
     $values['abilities']=unserialize($abilities);
     
     $stmt7 = $db->prepare("SELECT biography from application1 where login = ? and id=?");
     $stmt7->execute([$_SESSION['login'],$_SESSION['uid']]);
     $biography = $stmt7->fetchAll();
     $values['biography']=strip_tags($biography);
     
     $stmt8 = $db->prepare("SELECT informed from application1 where login = ? and id=?");
     $stmt8->execute([$_SESSION['login'],$_SESSION['uid']]);
     $informed = $stmt8->fetchAll();
     $values['informed']=$informed;
     
     $stmt9 = $db->prepare("SELECT password from application1 where login = ? and id=?");
     $stmt9->execute([$_SESSION['login'],$_SESSION['uid']]);
     $password = $stmt9->fetchAll();
     $values['password']=$password;
     
     $values['login']=$_SESSION['login'];
    
     //$values = ["name"=>strip_tags($name), "email"=>strip_tags($email), "birth_date"=>$birth_date, "amount_of_limbs"=>$amount_of_limbs, "abilities"=>$abilities, "biography"=>strip_tags($biography), "informed"=>$informed, "login"=>$_SESSION['login'], "password"=>$password];
     
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }
  
  include('form.php');
}


// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  $errors = FALSE;
  if (empty($_POST['name']) || !preg_match('/^([a-zA-Z\'\-]+\s*|[а-яА-ЯёЁ\'\-]+\s*)$/u', $_POST['name'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }
  //
  if (empty($_POST['email']) || !preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['birth_date']) || !is_numeric($_POST['birth_date']) || !preg_match('/^\d+$/', $_POST['birth_date'])) {
    setcookie('birthDate_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('birthDate_value', $_POST['birth_date'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['sex']) || !($_POST['sex']=='ж' || $_POST['sex']=='м')) {
    setcookie('sex_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('sex_value', $_POST['sex'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['amount_of_limbs']) || !is_numeric($_POST['amount_of_limbs']) || ($_POST['amount_of_limbs']<2) || ($_POST['amount_of_limbs']>4)) {
    setcookie('amountOfLimbs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('amountOfLimbs_value', $_POST['amount_of_limbs'], time() + 30 * 24 * 60 * 60);
  }
  
  $abilities = [10 => 'Бессмертие', 20 => 'Прохождение сквозь стены', 30 => 'Левитация'];
  if (empty($_POST['abilities']) || !is_array($_POST['abilities'])) {
    setcookie('abilities_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    foreach ($_POST['abilities'] as $ability) {
      if (!in_array($ability, $abilities)) {
        setcookie('abilities_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
        break;
      }
    }
    setcookie('abilities_value', serialize($_POST['abilities']), time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['biography'])) {
    setcookie('biography_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['informed']) || !($_POST['informed'] == 'on' || $_POST['informed'] == 1)) {
    setcookie('informed_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('informed_value', $_POST['informed'], time() + 30 * 24 * 60 * 60);
  }
  
  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('birthDate_error', '', 100000);
    setcookie('sex_error', '', 100000);
    setcookie('amountOfLimbs_error', '', 100000);
    setcookie('abilities_error', '', 100000);
    setcookie('biography_error', '', 100000);
    setcookie('informed_error', '', 100000);
  } 
  
  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: перезаписать данные в БД новыми данными,
    // кроме логина и пароля.
    
    $log=serialize($_SESSION['login']);
    $uid=serialize($_SESSION['uid']);
    
     $user = 'u52811';
     $pass = '8150350';
     $db = new PDO('mysql:host=localhost;dbname=u52811', $user, $pass,
       [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
    
    $log=serialize($_SESSION['login']);
    $uid=$_SESSION['uid'];
    
    try {
    $stmt=$db->prepare("UPDATE application1 SET name = ?, email = ?, birth_date = ?, sex = ?, amount_of_limbs = ?, biography = ? where id=$uid"); 
    $stmt -> execute([$_POST['name'], $_POST['email'], $_POST['birth_date'], $_POST['sex'], $_POST['amount_of_limbs'], $_POST['biography']]);
    }
    catch (PDOException $e) {
       print('Error : ' . $e->getMessage());
       exit();
    }
    $app_id = $db->lastInsertId();
    try {
      $stmt = $db->prepare("UPDATE application_ability SET app_id = ?, ab_id = ? where app_id=$uid");
      foreach ($_POST['abilities'] as $ability) {
        if ($ability=='Бессмертие')
        {$stmt -> execute([$app_id, 10]);}
        else if ($ability=='Прохождение сквозь стены')
        {$stmt -> execute([$app_id, 20]);}
        else if ($ability=='Левитация')
        {$stmt -> execute([$app_id, 30]);}
      }
    }
    catch (PDOException $e) {
       print('Error : ' . $e->getMessage());
       exit();
    }
    
    
  }
  
  else {
    // Генерируем уникальный логин и пароль.
    // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
    $login = substr(str_shuffle('abcdefghijklmnoPQRSTUVWXYZ1234567890'), 0, 5);
    $password = substr(md5(uniqid(rand(1,9))), 0, 12);
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('password', $password);

    // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
    // ...
    
    $user = 'u52811';
    $pass = '8150350';
    $db = new PDO('mysql:host=localhost;dbname=u52811', $user, $pass,
      [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
    try {
      $stmt = $db->prepare("INSERT INTO application1 SET name = ?, email = ?, birth_date = ?, sex = ?, amount_of_limbs = ?, biography = ?, informed = ?, login = ?, password = ?");
      $stmt -> execute([$_POST['name'], $_POST['email'], $_POST['birth_date'], $_POST['sex'], $_POST['amount_of_limbs'], $_POST['biography'], 1, $login, md5($password)]);
    }
    catch(PDOException $e) {
      print('Error : ' . $e->getMessage());
      exit();
    }
    $app_id = $db->lastInsertId();
    try{
      $stmt = $db->prepare("REPLACE INTO ability1 (id,name_of_ability) VALUES (10, 'Бессмертие'), (20, 'Прохождение сквозь стены'), (30, 'Левитация')");
      $stmt-> execute();
    }
    catch (PDOException $e) {
       print('Error : ' . $e->getMessage());
       exit();
    }
    try {
      $stmt = $db->prepare("INSERT INTO application_ability SET app_id = ?, ab_id = ?");
      foreach ($_POST['abilities'] as $ability) {
        if ($ability=='Бессмертие')
        {$stmt -> execute([$app_id, 10]);}
        else if ($ability=='Прохождение сквозь стены')
        {$stmt -> execute([$app_id, 20]);}
        else if ($ability=='Левитация')
        {$stmt -> execute([$app_id, 30]);}
      }
    }
    catch(PDOException $e) {
      print('Error : ' . $e->getMessage());
      exit();
    }
    
  }
  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');
  // Делаем перенаправление.
  header('Location: ./');
}
