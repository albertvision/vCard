<?php

require_once '../load.php'; 

if ($_GET['m'] == 'add') { 
    adminTop('Добавяне на потребител');
    if ($_POST['add']) {
        
        $username = trim($_POST['username']);
        $pass = trim($_POST['pass']);
        $rpass = trim($_POST['rpass']);
        $email = trim($_POST['email']);

        if (strlen($username) < 4) {
            $error[] = 'Твърде катко потребителско име!';
        }
        if (strlen($username) > 50) {
            $error[] = 'Твърде дълго потребителско име!';
        }
        if (strlen($pass) < 6) {
            $error[] = 'Твърде кратка парола';
        } else {
            if ($pass != $rpass) {
                $error[] = 'Паролите не съвпадат!';
            }
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Невалиден имейл!';
        }
        if (strlen($email) > 550) {
            $error[] = 'Твърде дълъг имейл!';
        }
        if (!$error) { 
            if (dbCount(dbQuery('SELECT `id` FROM `users` WHERE `username`="' . escape($username) . '" OR `email`="' . escape($email) . '"'))) { // Ако вече съществува потребител с това потр. име или имейл
                $error[] = 'Това потребителско име или имейл са заети!';
            } else {
                if(dbQuery('INSERT INTO `users` VALUES(NULL,"' . escape($username) . '","' . encrypt($pass) . '","' . escape($email) . '",0,0,UNIX_TIMESTAMP())')) {
                    $_SESSION['add_success_user'] = TRUE;
                    redirect('users.php?m=add');
                } else {
                    $error[] = 'Потребителят не може да бъде създаден! Моля, свържете се с администратор!';
                }
            }
        }
    }
    showMessages();
    if ($_SESSION['add_success_user']) {
        echo success('Потребителят е добавен успешно!');
        $_SESSION['add_success_user'] = FALSE;
    }
    ?>
    <form method="POST">
        <fieldset>
            <p>
                <label>Потребителско име: <sup class="red">*</sup></label>
                <input type="text" name="username" value="<?= $username; ?>" class="text-long"/>
            </p>
            <p>
                <label>Парола: <sup class="red">*</sup></label>
                <input type="password" name="pass" class="text-long" />
            </p>
            <p>
                <label>Повтори паролата: <sup class="red">*</sup></label>
                <input type="password" name="rpass" class="text-long" />
            </p>
            <p>
                <label>Имейл: <sup class="red">*</sup></label>
                <input type="text" name="email" value="<?= $email; ?>" class="text-long" />
            </p>
            <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
            <input type="submit" name="add" value="Добави" class="button-submit"/>
        </fieldset>
    </form>
    <?php
} elseif ($_GET['edit']) { 
    $id = (int) $_GET['edit'];
    $user_query = dbQuery('SELECT `username`,`email` FROM `users` WHERE `id`=' . $id);
    if (!dbCount($user_query)) { 
        $_SESSION['doesntexist_user'] = TRUE;
        redirect('users.php');
    } else { 
        $user = dbAssoc($user_query);
        adminTop('Редактиране на потребител', 'Редактиране');
        if ($_POST['edit']) { 
            $pass = trim($_POST['pass']);
            $rpass = trim($_POST['rpass']);
            $email = trim($_POST['email']);

            if (strlen($pass) != 0) {
                if (strlen($pass) < 6) {
                    $error[] = 'Твърде кратка парола!';
                } else {
                    if ($pass != $rpass) {
                        $error[] = 'Двете пароли не съвпадат!';
                    } else {
                        $password_query = '`password`="' . encrypt($pass) . '",';
                    }
                }
            } else {
                $password_query = '';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = 'Невалиден имейл адрес!';
            }
            if (strlen($email) > 550) {
                $error[] = 'Твърде дълъг имейл адрес!';
            }
            if (!$error) { // Ако няма грешки
                if(dbQuery('UPDATE `users` SET ' . $password_query . ' `email`="' . escape($email) . '" WHERE `id`=' . $id)) {
                    if ($id == $_SESSION['user']['id']) { 
                        $me = dbAssoc(dbQuery('SELECT * FROM `users` WHERE `id`=' . $id));
                        $_SESSION['user'] = $me; 
                    }
                    $_SESSION['sucess_user_edit_'.$id] = TRUE;
                    redirect('users.php?edit=' . $id);
                } else {
                    $error[] = 'Потребителят не може да бъде обновен! Моля, свържете се с администратор!';
                }
            }
        } else {
            foreach($user as $var=>$value) {
                $$var = $value;
            }
        }

        showMessages();
        if ($_SESSION['sucess_user_edit_'.$id]) {
            echo success('Потребителят е обновен успешно!');
            $_SESSION['sucess_user_edit_'.$id] = FALSE;
        }
        ?>
        <form method="POST">
            <fieldset>
                <p>
                    <label>Потребителско име: <sup class="red">*</sup></label>
                    <input type="text" name="username" value="<?= $user['username']; ?>" disabled class="text-long"/>
                </p>
                <p>
                    <label>Нова парола:</label>
                    <input type="password" name="pass" class="text-long" />
                </p>
                <p>
                    <label>Повтори паролата:</label>
                    <input type="password" name="rpass" class="text-long" />
                </p>
                <p>
                    <label>Имейл: <sup class="red">*</sup></label>
                    <input type="text" name="email" value="<?= $email; ?>" class="text-long" />
                </p>
                <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
                <input type="submit" name="edit" value="Обнови" class="button-submit"/>
            </fieldset>
        </form>
        <?php
    }
} elseif($_GET['delete']) {
    $id = (int)$_GET['delete'];
    dbQuery('DELETE FROM `users` WHERE `id`='.$id);
    if($id == $_SESSION['user']['id']) {
        session_destroy();
        redirect('index.php');
    }
} else { // Списък с потребители
    adminTop('Списък с потребители');

    $query = dbQuery('SELECT `id`,`username`,`email`,`lastLogin`,`registered` FROM `users` ORDER BY `id` ASC'); // Взимане на потребителите
    if ($_SESSION['doesntexist_user']) {
        echo error('Потребителят не съществува!');
        $_SESSION['doesntexist_user'] = FALSE;
    }
    ?>
    <p><a href="users.php?m=add">Добави запис</a></p>
    <script type="text/javascript">
        $(function() {
            $('.delete').live('click', function() {
                var id = $(this).attr('id');
                
                <?php if(dbCount($query) == 1) { ?>
                    alert('Не може да бъде изтрит ЕДИНСТВЕНИЯ профил в системата!');
                    return false;
                <?php } else { ?>
                if(id == <?= $_SESSION['user']['id']; ?>) {
                    var question = confirm('Сигурни ли сте, че искате да изтриете СОБСТВЕНИЯ СИ профил?');
                } else {
                    var question = confirm('Сигурни ли сте, че искате да изтриете записа?'); 
                }
                
                return question;
                <?php } ?>
            });
        });
    </script>
    <table class="normal">    
        <tr class="<?= $user['id']; ?>">
            <th><span title="Потребитеско име">Акаунт:</span></th>
            <th>Имейл:</th>
            <th>Последен вход:</th>
            <th>Дата на добавяне:</th>
            <th colspan="2">Опции:</th>
        </tr>
        <?php
        if (!dbCount($query)) { // Ако няма потребители
            ?>
            <tr>
                <td colspan="6">Няма добавени потребители!</td>
            </tr>
            <?php
        }
        while ($user = dbAssoc($query)) { // Обхожда всички потребители
            if ($user['lastLogin'] == 0) {
                $lastLogin = 'Никога';
                $t = $lastLogin;
            } else {
                $lastLogin = myDate($user['lastLogin'], 'short');
                $t = myDate($user['lastLogin']);
            }
            ?>
            <tr>
                <td><?= $user['username']; ?></td>
                <td><?= $user['email']; ?></td>
                <td title="<?= $t; ?>"><?= $lastLogin; ?></td>
                <td title ="<?= myDate($user['registered']); ?>"><?= myDate($user['registered'], 'short'); ?></td>
                <td class="top"><a href="users.php?edit=<?= $user['id']; ?>" title="Редактирай"><img src="img/edit.png" alt="Редактирай" /></a></td>
                <td class="top"><a href="users.php?delete=<?= $user['id']; ?>" title="Изтрий" class="delete" id="<?= $user['id']; ?>"><img src="img/delete.png" alt="Изтрий" /></a></td>
            </tr>
    <?php }
    ?>
        <tr class="last"></tr>
    </table>
    <?php
}
adminFooter();
?>