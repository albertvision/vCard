<?php
require_once '../load.php';
redirectIfGuest();
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');

if (!$_GET) { // Основни настройки
    adminTop('Настройки');
    $q = dbQuery('SELECT * FROM `settings`');
    $settings = dbAssoc($q);
    if ($_POST['update']) { 
        
        $title = htmlEscape(trim($_POST['title']));
        $email = htmlEscape(trim($_POST['email']));
        $facebook = htmlEscape(trim(str_replace('http://facebook.com/','',str_replace('http://www.facebook.com/','',str_replace('https://facebook.com/','',str_replace('https://www.facebook.com/','',$_POST['facebook']))))));
        $twitter = htmlEscape(trim(str_replace('http://twitter.com/','',str_replace('http://www.twitter.com/','',str_replace('https://twitter.com/','',str_replace('https://www.twitter.com/','',$_POST['twitter']))))));
        $keywords = htmlEscape(trim($_POST['keywords']));
        $desc = htmlEscape(trim($_POST['description']));

        if (strlen($title) < 4) {
            $error[] = 'Твърде кратко заглавие!';
        }
        if (strlen($title) > 550) {
            $error[] = 'Твърде дълго заглавие!';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Невалиден имейл!';
        } elseif (strlen($email) > 1000) {
            $error[] = 'Твърде дълъг имейл!';
        }
        if (strlen($keywords) < 4) {
            $error[] = 'Твърде малко ключови думи!';
        }
        if (strlen($desc) < 4) {
            $error[] = 'Твърде кратко описание!';
        }
        if (!$error) { 
            if(dbQuery('UPDATE `settings` SET `title`="' . escape($title, 'nohtml') . '", `email`="' . escape($email, 'nohtml') . '", `keywords`="' . escape($keywords, 'nohtml') . '", `desc`="' . escape($desc, 'nohtml') . '", `facebook`="'.escape($facebook, 'nohtml').'", `twitter`="'.escape($twitter, 'nohtml').'"')) {
                $_SESSION['success_settings_update'] = TRUE;
            } else {
                $error[] = 'Възникна грешка с обновяването! Моля, свържете се с администратор!';
            }
        }
    } else {
        foreach($settings as $var=>$value) {
            $$var = $value;
        }
    }
    showMessages();
    if($_SESSION['success_settings_update']) {
        echo success('Обновяването е успешно!');
        $_SESSION['success_settings_update'] = FALSE;
    }
    ?> 
    <form method="POST">
        <fieldset>
            <p>
                <label>Заглавие: <sup class="red">*</sup></label>
                <input type="text" name="title" value="<?= $title; ?>" class="text-long">
            </p>
            <p>
                <label>Имейл: <sup class="red">*</sup></label>
                <input type="text" name="email" value="<?= $email; ?>" class="text-long">
            </p>
            <p>
                <label>Facebook: <sup class="red">*</sup></label>
                <input type="text" name="facebook" value="<?= $facebook; ?>" class="text-long">
            </p>
            <p>
                <label>Twitter: <sup class="red">*</sup></label>
                <input type="text" name="twitter" value="<?= $twitter; ?>" class="text-long">
            </p>
            <p>
                <label>Ключови думи: <sup class="red">*</sup></label>
                <input type="text" name="keywords" value="<?= $keywords; ?>" class="text-long">
            </p>
            <p>
                <label>Описание: <sup class="red">*</sup></label>
                <textarea name="description" rows="10" cols="80"><?= $desc; ?></textarea>
            </p>
            <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
            <p>
                <input type="submit" name="update" value="Обнови" class="button-submit">
            </p>
        </fieldset>
    </form>
    <?php
} elseif ($_GET['m'] == 'log') { // Логове
    if ($_POST['clear']) {
        dbQuery('TRUNCATE `logs`');
        redirect('system.php?m=log');
    }
    adminTop('Лог с грешки');

    $pagination = pagination($_GET['p'], 'SELECT `id` FROM `logs`', 'admin/system.php?m=log&p=', 20); // Странициране
    $query = dbQuery('SELECT * FROM `logs` LIMIT ' . $pagination['start'] . ',' . $pagination['elementsPerPage']); // Взима логовете от БД
    ?>
    <h3>Списък</h3>
    <form method="POST"><input type="submit" name="clear" value="Изчисти" class="button-submit"></form>
    <table cellpadding="0" cellspacing="0" class="normal">    
        <tr>
            <th>Грешка:</th>
            <th>IP:</th>
            <th>Страница:</th>
            <th>Дата:</th>
        </tr>
    <?php
    if (!dbCount($query)) {
        ?>
            <tr>
                <td colspan="4">Няма записани грешки!</td>
            </tr>    
            <?php
        }
        while ($log = dbAssoc($query)) { // Обхожда страниците
            ?>
            <tr>
                <td><?= htmlEscape($log['value']); ?></td>
                <td><?= $log['ip']; ?></td>
                <td><?= htmlEscape($log['page']); ?></td>
                <td title="<?= newDate($log['saved']); ?>"><?= newDate($log['saved'], 'short'); ?></td>
            </tr>
        <?php
    }
    ?>
    </table>
    <form method="POST"><input type="submit" name="clear" value="Изчисти" class="button-submit"></form>
    <?php
    echo $pagination['show']; // Показване на странициране
}
adminFooter();
?>