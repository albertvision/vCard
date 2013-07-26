<?php

include '../load.php';
redirectIfLogged();

if(isset($_POST['login'])) { 
    $username = trim($_POST['username']);
    $password = encrypt($_POST['password']);
    
    $q = dbQuery('SELECT * FROM `users` WHERE `username`="'.escape($username).'" AND `password`="'.$password.'"');
    if(dbCount($q)) { 
        $user = dbAssoc($q);
        if($_POST['remember']=='on') {
            $key = encrypt(gstring(10));
            $expire=time()+31536000;
            setcookie('loginKey', $key, $expire);
        }
        
        dbQuery('UPDATE `users` SET `lastLogin`='.time().', `loginKey`="'.$key.'" WHERE `id`='.$user['id']);
        
        $_SESSION['logged'] = TRUE;
        $_SESSION['user'] = $user;
        redirect('index.php');
    } else {
        $error = TRUE;
    }
    /* if($username == 'albertvision' && $password = '620986a9b7046abae10dc38084d1933c831a9162d10ed378d162e6140e5c34ab') {
        $_SESSION['logged'] = TRUE;
        $user = array('id'=>1, 'username'=>'albertvision','password'=>'620986a9b7046abae10dc38084d1933c831a9162d10ed378d162e6140e5c34ab','email'=>'avbincco@gmail.com','loginKey'=>'12312qwewq','lastLogin'=>time(),'registered'=>'0');
        $_SESSION['user'] = $user;
        redirect('index.php');
    } else {
        $error = TRUE;
    } */
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Вход - Админ панел</title>
        <link rel="stylesheet" type="text/css" href="css/login.css" />
    </head>
    <body>
        <div id="container">
            <div id="content">
                <form method="POST">
                    <p>
                        <label>Потребителско име:</label>
                        <input type="text" name="username" value="<?= $_POST['username']; ?>" />
                    </p>
                    <p>
                        <label>Парола:</label>
                        <input type="password" name="password" />
                    </p>
                    <p>
                        <label><input type="checkbox" name="remember" checked> Запомни ме</label>
                    </p>
                    <p>
                        <input type="submit" name="login" value="Вход" class="centered" />
                    </p>
                    <?php
                    // Грешки
                    if ($error) {
                        ?><p class="error">Грешни данни!</p><?php
                    }
                    ?>
                </form>
            </div>
            <div id="footer">
                <p>&copy; <?= date('Y') ?> <a href="http://ygeorgiev.com/" target="_blank">Ясен Георгиев</a>. Всички права запазени!</p>
            </div>
        </div>
    </body>
</html>