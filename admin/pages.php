<?php
require_once '../load.php';
redirectIfGuest();
if (isset($_GET['edit'])) {
    adminTop('Редактиране на страница', 'Редактиране');
    $id = (int) $_GET['edit'];
    $query = dbQuery('SELECT * FROM `pages` WHERE `id`=' . $id);
    if (!dbCount($query)) {
        $_SESSION['doesntexist'] = TRUE;
        redirect('pages.php');
    } else {
        $page = dbAssoc($query);
        if (isset($_POST['update'])) {
            $title = trim($_POST['title']);
            $parent = (int) $_POST['parent'];
            $visiable = $_POST['visiable'];
            $isHome = $_POST['home_page'];
            $content = trim($_POST['content']);

            if (strlen($title) < 4) {
                $error[] = 'Твърде кратко заглавие!';
            } elseif(dbCount(dbQuery('SELECT `id` FROM `pages` WHERE `title`="'.$title.'" AND `id`!='.$id))) {
                $error[] = 'Вече има страница с това име!';
            }
            if (strlen($title) > 550) {
                $error[] = 'Твърде дълго заглавие!';
            }
            if (strlen(strip_tags($content)) < 2) {
                $error[] = 'Твърде кратко съдържание!';
            }
            if (!$error) {
                if($isHome == 'on') {
                    $isHome = '1';
                    dbQuery('UPDATE `pages` SET `isHome`=0 WHERE `isHome`=1');
                }
                if($visiable == 'on') {
                    $visiable = '1';
                }
                
                dbQuery('UPDATE `pages` SET `title`="' . escape($title) . '", `content`="' . escape($content,'nohtml','no') . '", `key`="'.  transliterate($title).'", `isHome`='.(int) $isHome.', `visiable`='.(int) $visiable.' WHERE `id`=' . $id);
                if (dbError()) {
                    $error[] = 'Страницата не може да бъде обновена! Моля, свържете се с администратор!';
                } else {
                    $_SESSION['success'] = TRUE;
                    redirect('pages.php?edit=' . $id);
                }
            }
        } else {
            $title = $page['title'];
            $parent = $page['parent'];
            $redirect = $page['redirect'];
            $visiable = $page['visiable'];
            $isHome = $page['isHome'];
            $content = $page['content'];
        }
        if ($error) {
            foreach ($error as $value) {
                ?><p class="error"><?= $value; ?></p><?php
            }
        } elseif ($_SESSION['success']) {
            ?><p class="success">Страницата е обновена успешно!</p><?php
            $_SESSION['success'] = FALSE;
        }
        ?>
        <script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
        <script type="text/javascript" src="js/tinymce.js"></script>
        <form method="POST">
            <fieldset>
                <p>
                    <label>Заглавие: <sup class="red">*</sup></label>
                    <input type="text" name="title" value="<?= $title; ?>" class="text-long" />
                </p>
                <p>
                    <label>Начално страница: </label>
                    <label class="text-long"><input type="checkbox" name="home_page" id="home_page" <?php if($isHome == (1 || 'on')) { ?> checked <?php } ?> /> Начална страница</label>
                </p>
                <p id="visiable">
                    <label>Видимост: </label>
                    <label class="text-long"><input type="checkbox" name="visiable" <?php if($visiable == (1 || 'on')) { ?> checked <?php } ?> /> Показвай в менюто</label>
                </p>
                <p>
                    <label>Съдържание: <sup class="red">*</sup></label>
                    <textarea class="editor" name="content"><?= $content; ?></textarea>
                </p>
                <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
                <input type="submit" name="update" value="Обнови" class="button-submit"/>
            </fieldset>
        </form>
        <?php
    }
} else {
    adminTop('Списък със страници');
    //$pagination = pagination($_GET['p'], 'SELECT `id` FROM `pages`', 'admin/pages.php?p=',30);
    
    $query = dbQuery('SELECT `pages`.*,`users`.`username` FROM `pages` LEFT JOIN `users` ON (`users`.`id`=`pages`.`author`)');
    if ($_SESSION['doesntexist']) {
        ?><p class="error">Страницата не съществува!</p><?php
        $_SESSION['doesntexist'] = FALSE;
    }
    echo dbError();
    //echo mysqli_error($system['db']['']);
    ?>
    <script type="text/javascript">
        $(function () {
            $('.future').click(function () {
                alert('Бъдеща опция!');
                return false;
            });
        });
    </script>
    <p><a href="#" class="future">Добави страница</a></p>
    <table class="normal">    
        <tr>
            <th>Заглавие:</th>
            <th>Вид:</th>
            <th>Автор:</th>
            <th colspan="3">Опции:</th>
        </tr>
        <?php
        if (!dbCount($query)) {
            ?>
            <tr>
                <td colspan="6">Няма добавени страници!</td>
            </tr>
            <?php
        }
        while ($page = dbAssoc($query)) {
            if ($page['redirect'] == '') {
                $page['redirect'] = 'Няма';
            }
            $type = '';
            if($page['visiable'] == '0') {
                $type = 'Скрита';
            } if($page['isHome']) {
                $type = '<b>Начална страница</b><br />';
            } if($page['visiable']) {
                $type .= 'Видима в менюто<br />';
            }
            ?>
            <tr class="<?= $page['id']; ?>">
                <td><?= $page['title'] ?></td>
                <td><?= $type; ?></td>
                <td><?= $page['username'] ?></b></td>
                <td><a href="<?= $system['paths']['siteUrl'] ?>/page/<?= $page['key'] . $system['pageExt'] ?>" title="Преглед"><img src="img/view.png" alt="Преглед" /></a></td>
                <td><a href="pages.php?edit=<?= $page['id']; ?>" title="Редактирай"><img src="img/edit.png" alt="Редактирай" /></a></td>
                <td><a href="#" title="Изтрий" class="future" ><img src="img/delete.png" alt="Изтрий" /></a></td>
            </tr>
        <?php   
        }
        ?>
        <tr class="last"></tr>
    </table>
    <?php
    echo $pagination['show'];
}

adminFooter();
?>