<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контактная форма</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

  <?php
  
  if (!empty($messages)) {
    print('<div class="messages">');
    // Выводим все сообщения.
    foreach ($messages as $message) {
      print($message);
    }
    print('</div>');
  }
  // Далее выводим форму отмечая элементы с ошибками классом error
  // и задавая начальные значения элементов ранее сохраненными.
  ?>

  <div class="container">
    <h2>
        Контактная форма
    </h2>
    <form action="" method="POST">
      Имя:<br><input type="text" name="name" <?php if ($errors['name']) {print 'class="group error"';} else print 'class="group"'; ?> value="<?php print $values['name']; ?>">
      <br>
      E-mail:<br><input type="text" name="email" <?php if ($errors['email']) {print 'class="group error';} else print 'class="group"'; ?> value="<?php print $values['email']; ?>">
      <br>
      Год рождения:<br>
      <select id="year" size="3" name="year" <?php if ($errors['year']) {print 'class="group error"';} else print 'class="group"';?> value="<?php print $values['year']; ?>">
          <?php for($i = 1900; $i <= date('Y'); $i++): ?>
          <option value="<?=$i?>" <?php if($i == $values['year']) {print 'selected';} ?>><?=$i?></option>
          <?php endfor; ?>
      </select>
      <div <?php if ($errors['gender']) {print 'class="error"';} ?>>
        Пол:<br>
        <input class="radio" type="radio" name="gender" value="M" <?php if ($values['gender'] == 'M') {print 'checked';} ?>> Мужской
        <input class="radio" type="radio" name="gender" value="W" <?php if ($values['gender'] == 'W') {print 'checked';} ?>> Женский
      </div>
      <div <?php if ($errors['limbs']) {print 'class="error"';} ?>>
        Количество конечностей:<br>
        <input class="radio" type="radio" name="limbs" value="4" <?php if ($values['limbs'] == '4') {print 'checked';} ?>> 4
        <input class="radio" type="radio" name="limbs" value="3" <?php if ($values['limbs'] == '3') {print 'checked';} ?>> 3
        <input class="radio" type="radio" name="limbs" value="2" <?php if ($values['limbs'] == '2') {print 'checked';} ?>> 2
        <input class="radio" type="radio" name="limbs" value="1" <?php if ($values['limbs'] == '1') {print 'checked';} ?>> 1
        <input class="radio" type="radio" name="limbs" value="0" <?php if ($values['limbs'] == '0') {print 'checked';} ?>> 0 
      </div>
      Cверхспособности:<br>
      <select class="group" name="ability[]" size="3" multiple>
          <option value="Бессмертие" <?php if (in_array("Бессмертие", $values['ability'])) {print 'selected';} ?>>Бессмертие</option>
          <option value="Прохождение сквозь стены" <?php if (in_array("Прохождение сквозь стены", $values['ability'])) {print 'selected';} ?>>Прохождение сквозь стены</option>
          <option value="Левитация" <?php if (in_array("Левитация", $values['ability'])) {print 'selected';} ?>>Левитация</option>
      </select>
      <br>
      Биография:<br><textarea class="group" name="bio" rows="3" cols="30"><?php print $values['bio']; ?></textarea>
      <div  <?php if ($errors['checkbox']) {print 'class="error"';} ?>>
        <input type="checkbox" name="checkbox" <?php if ($values['checkbox']) {print 'checked';} ?>> С контрактом ознакомлен(a) 
      </div>
      <input type="submit" id="send" value="ОТПРАВИТЬ">
    </form>
  </div>
  <div class="container">
    <?php
      if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']))
        print('<a href="login.php" class = "enter-exit" title = "Log out">Выйти</a>');
      else
        print('<a href="login.php" class = "enter-exit"  title = "Log in">Войти</a>');
    ?>
  </div>
</body>
</html>
