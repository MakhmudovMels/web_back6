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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h2>Статистика по суперспособностям</h2>
  <section>Бессмертие: <?php print $count1 ?></section> <br>
  <section>Прохождение сквозь стены: <?php print $count2 ?></section> <br>
  <section>Левитация: <?php print $count3 ?></section> <br>
</body>
</html>