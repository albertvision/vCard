<?php
require_once '../load.php';

redirectIfGuest();
$eduLevels = array('1'=>'Начално','2'=>'Основно','3'=>'Средно', '4'=>'Средно специално', '5'=>'Професионален бакалавър', '6'=>'Бакалавър', '7'=>'Магистър', '8'=>'Доктор');

// Start education
if($_GET['add'] == 'edu') { //Add education
    adminTop('CV', 'Образование');
    if($_POST['add']) {
        $school = trim(htmlEscape($_POST['school']));
        $level = (int)$_POST['level'];
        $since = (int)$_POST['since'];
        $to = (int)$_POST['to'];

        if(strlen($school) < 2) {
            $error[] = 'Твърде кратко име на учебно заведение!';
        }
        if(strlen($school) > 550) {
            $error[] = 'Твърде дълго име на учебно заведение!';
        }
        if($level < 1 || $level >8) {
            $error[] = 'Невалидна степен на образованието!';
        }
        if($since > $to) {
            $error[] = 'Не може стойността на полето "ДО" да е по-малка от "ОТ"';
        }
        if(!$error) {
            if(dbQuery('INSERT INTO `cv_education` VALUES(NULL, "'.escape($school, 'nohtml').'", '.$level.', "'.$since.' - '.$to.'", UNIX_TIMESTAMP())')) {
                $_SESSION['add_success_edu'] = 'Добавянето е успешно!';
                redirect('cv.php?m=edu');
            } else {
                $error[] = 'Възникна грешка! Моля, свържете се с администратор!';
            }
        }
    }
    
    showMessages();
    ?>
    <form method="POST">
        <fieldset>
            <p>
                <label>Учебно заведение: <sup class="red">*</sup></label>
                <input type="text" name="school" value="<?= htmlspecialchars($school); ?>" class="text-long" />
            </p>
            <p>
                <label>Степен на образование: </label>
                <span>
                    <select name="level" class="text-long no-float">
                        <?php
                        foreach($eduLevels as $key=>$value) {
                            $eduList .= '<option value="'.$key.'">'.$value.'</option>';
                        }
                        echo str_replace('<option value="'.$level.'">', '<option value="'.$level.'" selected>', $eduList);
                        ?>
                    </select>
                </span>
            </p>
            <p>
                <label>Години: </label>
                <!--<input type="text" name="years" value="<?= $years; ?>" class="text-long" />-->
                <span><select name="since" class="text-medium no-float">
                    <?php  
                    for($y = date('Y'); $y >= date('Y')-80; $y--) { 
                        $sinceList .= '<option value="'.$y.'">'.$y.'</option>';
                    } 
                    echo str_replace('<option value="'.$since.'">', '<option value="'.$since.'" selected>', $sinceList);
                    ?>
                </select></span>
                <span> до </span>
                <select name="to" class="text-medium no-float">
                    <option value="now">сега</option>
                    <?php  
                    for($y = date('Y'); $y > date('Y')-80; $y--) {
                        $toList .= '<option value="'.$y.'">'.$y.'</option>';
                    } 
                    echo str_replace('<option value="'.$to.'">', '<option value="'.$to.'" selected>', $toList);
                    ?>
                </select>
            </p>
            <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
            <input type="submit" name="add" value="Добави" class="button-submit"/>
        </fieldset>
    </form>
    <?php
}
if($_GET['m'] == 'edu') { 
    
    if($_GET['edit']) { //Edit education
        $id = (int)$_GET['edit'];
        $query = dbQuery('SELECT `school`,`level`,`years` FROM `cv_education` WHERE `id`='.$id);
        if(!dbCount($query)) {
            $_SESSION['doesnt_exist_edu'] = TRUE;
            redirect('cv.php?m=edu');
            die();
        }
        $edu = dbAssoc($query);
        
        if($_POST['edit']) {
            $school = trim(htmlEscape($_POST['school']));
            $level = (int)$_POST['level'];
            $since = (int)$_POST['since'];
            $to = (int)$_POST['to'];
            
            if(strlen($school) < 2) {
                $error[] = 'Твърде кратко име на учебно заведение!';
            }
            if(strlen($school) > 550) {
                $error[] = 'Твърде дълго име на учебно заведение!';
            }
            if($level < 1 || $level >8) {
                $error[] = 'Невалидна степен на образованието!';
            }
            if($since > $to) {
                $error[] = 'Не може стойността на полето "ДО" да е по-малка от "ОТ"';
            }
            if(!$error) {
                if(dbQuery('UPDATE `cv_education` SET `school`="'.escape($school, 'nohtml').'", `level`='.$level.', `years`="'.$since.' - '.$to.'" WHERE `id`='.$id)) {
                    $_SESSION['update_success_edu_'.$id] = 'Обновяването е успешно!';
                    redirect('cv.php?m=edu&edit='.$id);
                } else {
                    $error[] = 'Възникна грешка! Моля, свържете се с администратор!';
                }
            }
        } else {
            foreach($edu as $var=>$value) {
                $$var = $value;
            }
            $yearsExploded = explode(' - ', $years);
            $since = $yearsExploded[0];
            $to = $yearsExploded[1];
        }
        
        adminTop('CV', 'Редактиране на образование');
        showMessages();
        if($_SESSION['update_success_edu_'.$id]) {
            echo success($_SESSION['update_success_edu_'.$id]);
            $_SESSION['update_success_edu_'.$id] = FALSE;
        }
        ?>
        <form method="POST">
            <fieldset>
                <p>
                    <label>Учебно заведение: <sup class="red">*</sup></label>
                    <input type="text" name="school" value="<?= $school; ?>" class="text-long" />
                </p>
                <p>
                    <label>Степен на образование: <sup class="red">*</sup></label>
                    <span>
                        <select name="level" class="text-long no-float">
                            <?php
                            foreach($eduLevels as $key=>$value) {
                                $eduList .= '<option value="'.$key.'">'.$value.'</option>';
                            }
                            echo str_replace('<option value="'.$level.'">', '<option value="'.$level.'" selected>', $eduList);
                            ?>
                        </select>
                    </span>
                </p>
                <p>
                    <label>Години: <sup class="red">*</sup></label>
                    <!--<input type="text" name="years" value="<?= $years; ?>" class="text-long" />-->
                    <span><select name="since" class="text-medium no-float">
                        <?php  
                        for($y = date('Y'); $y >= date('Y')-80; $y--) { 
                            $sinceList .= '<option value="'.$y.'">'.$y.'</option>';
                        } 
                        echo str_replace('<option value="'.$since.'">', '<option value="'.$since.'" selected>', $sinceList);
                        ?>
                    </select></span>
                    <span> до </span>
                    <select name="to" class="text-medium no-float">
                        <option value="now">сега</option>
                        <?php  
                        for($y = date('Y'); $y > date('Y')-80; $y--) {
                            $toList .= '<option value="'.$y.'">'.$y.'</option>';
                        } 
                        echo str_replace('<option value="'.$to.'">', '<option value="'.$to.'" selected>', $toList);
                        ?>
                    </select>
                </p>
                <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
                <input type="submit" name="edit" value="Редактирай" class="button-submit"/>
            </fieldset>
        </form>
        <?php
    } elseif($_GET['delete']) { //Delete education
        $id = (int)$_GET['delete'];
        dbQuery('DELETE FROM `cv_education` WHERE `id`='.$id);
        $_SESSION['del_success_edu'] = 'Изтриването е успешно!';
        redirect('cv.php?m=edu');
    } else {  //Show education
        adminTop('CV', 'Образование');
        $query = dbQuery('SELECT * FROM `cv_education` ORDER BY `years` DESC');
        if ($_SESSION['doesntexist']) {
            echo error('Записът не съществува!');
            $_SESSION['doesntexist'] = FALSE;
        } elseif($_SESSION['add_success_edu']) {
            echo success($_SESSION['add_success_edu']);
            $_SESSION['add_success_edu'] = FALSE;
        } elseif($_SESSION['del_success_edu']) {
            echo success($_SESSION['del_success_edu']);
            $_SESSION['del_success_edu'] = FALSE;
        }
        ?>
        <script type="text/javascript">
            $(function () {
                $('.delete').click(function() {
                    var question = confirm('Сигурни ли сте, че искате да изтриете записа? ');
                    return question;
                });
            });
        </script>
        <p><a href="cv.php?add=edu">Добави запис</a></p>
        <table class="normal">    
            <tr>
                <th>Учебно заведение:</th>
                <th>Степен:</th>
                <th>Години:</th>
                <th colspan="2">Опции:</th>
            </tr>
            <?php
            if (!dbCount($query)) {
                ?>
                <tr>
                    <td colspan="5">Няма добавени записи!</td>
                </tr>
                <?php
            }
            while ($edu = dbAssoc($query)) {
                ?>
                <tr class="<?= $edu['id']; ?>">
                    <td><?= $edu['school'] ?></td>
                    <td><?= returnLevel($edu['level']);; ?></td>
                    <td><?= $edu['years'] ?></b></td>
                    <td><a href="cv.php?m=edu&edit=<?= $edu['id']; ?>" title="Редактирай"><img src="img/edit.png" alt="Редактирай" /></a></td>
                    <td><a href="cv.php?m=edu&delete=<?= $edu['id']; ?>" title="Изтрий" class="delete"><img src="img/delete.png" alt="Изтрий" /></a></td>
                </tr>
            <?php   
            }
            ?>
            <tr class="last"></tr>
        </table>
        <?php
    }
} elseif($_GET['add'] == 'skill') {
    adminTop('CV', 'Добавяне на умение');
    if($_POST['add']) {
        $name = trim($_POST['name']);
        $desc = trim($_POST['desc']);
        $rate = (int)$_POST['rate'];
        
        if(strlen($name) < 2) {
            $error[] = 'Твърде кратко умение!';
        }
        if(strlen($name) > 550) {
            $error[] = 'Твърде дълго умение!';
        }
        if(strlen($desc) > 550) {
            $error[] = 'Твърде дълго описание!';
        }
        if($rate <1 || $rate >4) {
            $error[] = 'Невалиден рейтинг!';
        }
        
        if(!$error) {
            if(dbCount(dbQuery('SELECT `id` FROM `cv_skills` WHERE `name`="'.escape($name).'"'))) {
                $error[] = 'Това умение е вече добавено!';
            } else {
                if(dbQuery('INSERT INTO `cv_skills` VALUES(NULL, "'.  escape($name).'", "'.  escape($desc).'", '.$rate.', UNIX_TIMESTAMP())')) {
                    $_SESSION['add_success_skill'] = 'Добавянето е успешно!';
                    redirect('cv.php?m=skills');
                } else {
                    $error[] = 'Възникна грешка! Моля, свържете се с администратор!';
                }
            }
        }
    }
    showMessages();
    ?>
    <form method="POST">
        <fieldset>
            <p>
                <label>Умение: <sup class="red">*</sup></label>
                <input type="text" name="name" value="<?= $name; ?>" class="text-long" />
            </p>
            <p>
                <label>Кратко описание: </label>
                <input type="text" name="desc" value="<?= $desc; ?>" class="text-long" />
            </p>
            <p>
                <label>Рейнинг <i>(знание)</i>: <sup class="red">*</sup></label>
                <select name="rate" class="text-long">
                    <?php
                    for($rt=1; $rt<=4; $rt++) {
                        $rateList .= '<option value="'.$rt.'">'.$rt.'</option>';
                    }
                    echo str_replace('<option value="'.$rate.'">','<option value="'.$rate.'" selected>',$rateList);
                    ?>
                </select>
            </p>
        </fieldset>
        <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
        <input type="submit" name="add" value="Добави" class="button-submit" />
    </form>
    <?php
} elseif($_GET['m'] == 'skills') {
    
    if($_GET['edit']) {
        $id = (int)$_GET['edit'];
        $query = dbQuery('SELECT `name`,`desc`,`rate` FROM `cv_skills` WHERE `id`='.$id);
        if(!dbCount($query)) {
            $_SESSION['doesntexist'] = TRUE;
            redirect('cv.php?m=skills');
            die();
        }
        $skill = dbAssoc($query);
        if($_POST['edit']) {
            $name = trim($_POST['name']);
            $desc = trim($_POST['desc']);
            $rate = (int)$_POST['rate'];

            if(strlen($name) < 2) {
                $error[] = 'Твърде кратко умение!';
            }
            if(strlen($name) > 550) {
                $error[] = 'Твърде дълго умение!';
            }
            if(strlen($desc) > 550) {
                $error[] = 'Твърде дълго описание!';
            }
            if($rate <1 || $rate >4) {
                $error[] = 'Невалиден рейтинг!';
            }
            if(!$error) {
                if(dbCount(dbQuery('SELECT `id` FROM `cv_skills` WHERE `name`="'.  escape($name).'" AND `id`!='.$id))) {
                    $error[] = 'Това умение вече е добавено!';
                } else {
                    if(dbQuery('UPDATE `cv_skills` SET `name`="'.escape($name).'", `desc`="'.escape($desc).'", `rate`='.$rate.' WHERE `id`='.$id)) {
                        $_SESSION['update_success_skill_'.$id] = 'Обновяването е успешно!';
                        redirect('cv.php?m=skills&edit='.$id);
                    } else {
                        $error[] = 'Възникна грешка! Моля, свържете се с администратор!';
                    }
                }
            }
            
        } else {
            foreach($skill as $var=>$value) {
                $$var = $value;
            }
        }
        
        adminTop('CV', 'Редактиране на умение');
        showMessages();
        if($_SESSION['update_success_skill_'.$id]) {
            echo success($_SESSION['update_success_skill_'.$id]);
            $_SESSION['update_success_skill_'.$id] = FALSE;
        }
        ?>
        <form method="POST">
            <fieldset>
                <p>
                    <label>Умение: <sup class="red">*</sup></label>
                    <input type="text" name="name" value="<?= $name; ?>" class="text-long" />
                </p>
                <p>
                    <label>Кратко описание: </label>
                    <input type="text" name="desc" value="<?= $desc; ?>" class="text-long" />
                </p>
                <p>
                    <label>Рейнинг <i>(знание)</i>: <sup class="red">*</sup></label>
                    <select name="rate" class="text-long">
                        <?php
                        for($rt=1; $rt<=4; $rt++) {
                            $rateList .= '<option value="'.$rt.'">'.$rt.'</option>';
                        }
                        echo str_replace('<option value="'.$rate.'">','<option value="'.$rate.'" selected>',$rateList);
                        ?>
                    </select>
                </p>
            </fieldset>
            <p class="italic small">Полетата означени с  <sup class="red">*</sup>, са задължитени.</p>
            <input type="submit" name="edit" value="Редактирай" class="button-submit" />
        </form>
        <?php
    } elseif($_GET['delete']) {
        $id = (int)$_GET['delete'];
        dbQuery('DELETE FROM `cv_skills` WHERE `id`='.$id);
        $_SESSION['del_success_skill'] = 'Изтриването е успешно!';
        redirect('cv.php?m=skills');
    } else {
        adminTop('CV', 'Умения');
        $query = dbQuery('SELECT * FROM `cv_skills` ORDER BY `rate` DESC');
        if ($_SESSION['doesntexist']) {
            echo error('Записът не съществува!');
            $_SESSION['doesntexist'] = FALSE;
        } elseif($_SESSION['add_success_skill']) {
            echo success($_SESSION['add_success_skill']);
            $_SESSION['add_success_skill'] = FALSE;
        } elseif($_SESSION['del_success_skill']) {
            echo success($_SESSION['del_success_skill']);
            $_SESSION['del_success_skill'] = FALSE;
        }
        ?>
        <script type="text/javascript">
            $(function () {
                $('.delete').click(function() {
                    var question = confirm('Сигурни ли сте, че искате да изтриете записа? ');
                    return question;
                });
            });
        </script>
        <p><a href="cv.php?add=skill">Добави запис</a></p>
        <table class="normal">    
            <tr>
                <th>Умение:</th>
                <th>Кратко описание:</th>
                <th>Рейтинг:</th>
                <th colspan="2">Опции:</th>
            </tr>
            <?php
            if (!dbCount($query)) {
                ?>
                <tr>
                    <td colspan="5">Няма добавени записи!</td>
                </tr>
                <?php
            }
            while ($skill = dbAssoc($query)) {
                ?>
                <tr class="<?= $skill['id']; ?>">
                    <td><?= $skill['name'] ?></td>
                    <td><?= $skill['desc']; ?></td>
                    <td><?= $skill['rate']; ?></b></td>
                    <td><a href="cv.php?m=skills&edit=<?= $skill['id']; ?>" title="Редактирай"><img src="img/edit.png" alt="Редактирай" /></a></td>
                    <td><a href="cv.php?m=skills&delete=<?= $skill['id']; ?>" title="Изтрий" class="delete"><img src="img/delete.png" alt="Изтрий" /></a></td>
                </tr>
            <?php   
            }
            ?>
            <tr class="last"></tr>
        </table>
        <?php
    }
}
if(!$_GET) { //Main page
    adminTop('CV');
    $edu_query = dbQuery('SELECT `school`,`level`,`years` FROM `cv_education`');
    if(dbCount($edu_query)) {
        ?><h3>Образование</h3><?php
        while($edu = dbAssoc($edu_query)) {
            $level = returnLevel($edu['level']);
            ?>
            <div class="left">
                <h4><?= $level; ?></h4>
                <span><?= $edu['school']; ?></span>
            </div>
            <div class="right">
                <span><?= $edu['years']; ?></span>
            </div>
            <?php
        }
    }
    
    $skill_query = dbQuery('SELECT * FROM `cv_skills` ORDER BY `added` ASC');
    if(dbCount($skill_query)) {
        ?><h3>Умения</h3><?php
        while($skill = dbAssoc($skill_query)) {
            ?>
            <p>
                <div class="left">
                    <h4><?= $skill['name']; ?></h4>
                </div>
                <div class="right">
                    <span><?= $skill['desc']; ?></span>
                    <p><b>Брой звезди:</b> <?= $skill['rate']; ?></p>
                </div>
            </p>

            <?php
        }
    }
}
adminFooter();
?>