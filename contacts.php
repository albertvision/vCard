<?php

include 'load.php';
siteTop('Контакти', true, 'contact');

$settings = dbAssoc(dbQuery('SELECT `email` FROM `settings`'));
if($_POST['send']) {
    $names = trim($_POST['names']);
    $email = trim($_POST['email']);
    $msg = trim($_POST['msg']);
    
    if(strlen($names) <2 ) {
        $error[] = 'Твърде кратко име!';
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Невалиден имейл адрес!';
    }
    if(strlen($msg)<2) {
        $error[] = 'Твърди кратко съдържание!';
    }
    
    if(!$error) {
        if(sendMail($settings['email'], $email, $names, 'Съобщение от vCard', $msg)) {
            $success = 'Съобщението е изпратено успешно!';
            $names = '';
            $email = '';
            $msg = '';
        } else {
            $error[] = 'Съобщението не е изпратено!';
        }
    }
}

?>
<div class="wp-contact clearfix">
    <div id="contact-form">
        <p>Хей, ако искаш да се свържиш с мен, попълни следната формата:</p>
        <?php
        if($error) {
            foreach($error as $value) {
                echo '<p class="error">'.$value.'</p>';
            }
        }
        if($success) {
            echo '<p class="success">Съобщението е изпратено успешно!</p>';
        }
        ?>
        <form method="POST" id="send">
            <input type="text" name="names" placeholder="Име..." class="validate[required]" value="<?= htmlspecialchars($names); ?>"/>
            <div><input type="text" name="email" placeholder="Имейл..."  class="validate[required, custom[email]]" value="<?= htmlspecialchars($email); ?>"/></div>
            <div><textarea name="msg" class="validate[required, minSize[2]] text" placeholder="Съобщение..."><?= $msg; ?></textarea></div>
            <input type="hidden" name="send" value="1" />
        </form>

        <span class="frm-state"></span>

        <a href="#" class="button contact contact-submit">Изпрати</a>
    </div>
</div>
<?php
siteFooter();
?>