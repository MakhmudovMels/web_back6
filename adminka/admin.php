<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.

$db = new PDO('mysql:host=localhost;dbname=u46502', 'u46502', '3119750', array(PDO::ATTR_PERSISTENT => true));
$stmt = $db->prepare("SELECT * FROM admin WHERE id = ?");
$stmt -> execute([1]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != $row['login'] ||
    md5($_SERVER['PHP_AUTH_PW']) != $row['pass']) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

// успешно авторизовались и видим защищенные паролем данные
// собирамем статистику по суперспособностям
$stmt = $db->prepare("SELECT * FROM superability WHERE name_of_superability = ?");
$stmt -> execute(["Бессмертие"]);
$count1 = $stmt->rowCount();
$stmt = $db->prepare("SELECT * FROM superability WHERE name_of_superability = ?");
$stmt -> execute(["Прохождение сквозь стены"]);
$count2 = $stmt->rowCount();
$stmt = $db->prepare("SELECT * FROM superability WHERE name_of_superability = ?");
$stmt -> execute(["Левитация"]);
$count3 = $stmt->rowCount();

$stmt = $db->query("SELECT max(id) FROM human");
$row = $stmt->fetch();
$count = (int) $row[0];//Берем максимальный айди среди пользователей для заполнения списка пользователей

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])){//Если была нажата кнопка удалить пользователя

  if($_POST['select_user'] == 0){//Обработчик того был ли выбран пользователь
      header('Location: admin.php');
  }
  try{

    $user_id = (int) $_POST['select_user'];//Получение айди выбраного польвователя

    //Удаление всех выбраных им суперспособностей
    $stmt = $db->prepare("DELETE * FROM superability WHERE human_id = ?");
    $stmt -> execute([$user_id]);
    //Удаление выбранного пользователя
    $stmt = $db->prepare("DELETE * FROM login_pass WHERE human_id = ?");
    $stmt -> execute([$user_id]);
    $stmt = $db->prepare("DELETE FROM human WHERE id = ?");
    $stmt -> execute([$user_id]);

  } catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
  }
  
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])){//Если была нажата кнопка изменить данные пользователя

  if($_POST['select_user'] == 0){//Обработчик того был ли выбран пользователь
     header('Location: adminroom.php');
  }
  //Берем данные об измененных способностях
  $power1=in_array('s1',$_POST['capabilities']) ? '1' : '0';
  $power2=in_array('s2',$_POST['capabilities']) ? '1' : '0';
  $power3=in_array('s3',$_POST['capabilities']) ? '1' : '0';
  $power4=in_array('s4',$_POST['capabilities']) ? '1' : '0';

  //Способности сохраняются в единную строку которая позже будет сохранена в бд
  if($power1 == 1){
      $ability = 'immortal' . ',';
  }

  if($power2 == 1 && !empty($ability)){
      $ability .= 'noclip' . ',';
  }else if($power2 == 1 && empty($ability)){
      $ability = 'noclip' . ',';
  }

  if($power3 == 1 && !empty($ability)){
      $ability .= 'flying' . ',';
  }else if($power3 == 1 && empty($ability)){
      $ability = 'flying' . ',';
  }

  if($power4 == 1 && !empty($ability)){
      $ability .= 'lazer' . ',';
  }else if($power4 == 1 && empty($ability)){
      $ability = 'lazer' . ',';
  }

  //Блок изменения данных пользователя введеных админом
  $id = $_COOKIE['id'];

  $stmt = $db->prepare("UPDATE users SET name = ?, mail = ?, bio = ?, date = ?, gender = ?, limbs = ? WHERE id = ?");
  $stmt -> execute(array($_POST['name'],$_POST['email'],$_POST['bio'],$_POST['year'],$_POST['gender'],$_POST['limbs'], $id));

  $stmt = $db->prepare("UPDATE  super_power SET superabilities = ? WHERE human_id = ?");
  $stmt -> execute([$ability,$id]);

  setcookie('id','',1);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="admin.css">
  <title>Админка</title>
</head>
<body>
  <h1>Панель администратора</h1>

  <h4>Статистика по суперспособностям</h4>
  <section>Бессмертие: <?php print $count1 ?></section> <br>
  <section>Прохождение сквозь стены: <?php print $count2 ?></section> <br>
  <section>Левитация: <?php print $count3 ?></section> <br>

  <h4>Выбери пользователя, которого хочешь отредактировать или удалить</h4>
  <form action="" method="POST">
    <select name="select_user" class ="slc_user" id="selector_user">
      <option selected disabled value ="0">Выбрать пользователя</option>
      <?php
      for($index =1 ;$index <= $count;$index++){//Заполнение списка пользователями
        $stmt = $db->prepare("SELECT * FROM human WHERE id = ?");
        $stmt -> execute([$index]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user['id'] == $index){//Проверка на существование пользователя с айди index
            print("<option value =" . $index . ">" . "id: ". $user['id'] . ", Имя: " . $user['name'] . "</option>");//Добавление в список пользователя с существующим айди
        }
      }
      ?>
    </select>
    <input name="delete_user" type="submit" value="Удалить пользователя" />
  </form>
</body>
</html>