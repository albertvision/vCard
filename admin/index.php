<?php

require_once '../load.php';
redirectIfGuest('login.html');
if(!$_GET) { // Ако няма GET параметър
    adminTop('Начало','Описание на системата');
    ?>
    <p>Здравейте!</p> 
    <p>Това е администраторският панел на портфолиото на Ясен Георгиев. Както виждате дизайнът е простичък - това ще Ви помогне да се ориентирате по-лесно със системата!</p>
    <p><b>Функции на администраторския панел:</b></p>
	<ul class="bullets">
		<li>Управление на: страници, CV, портфолио и потребители.</li>
		<li>Промяна на настойките на сайта.</li>
		<li>Създаване на бекъп.</li>
		<li>Възстановяне от бекъп.</li>
		<li>Преглед на списъка с грешки.</li>
	</ul>
    <p></p>
    <p><b>Нека да преминем към интерфейса:</b></p>
    <p>Отгоре се намира главното меню - чрез него Вие можете да отидите в страницата за управление на всеки един модул.</p>
    <p>Във всяка страница има дясно меню - то помага за подмодулите. Например ако сте в страницата <i>Страници</i>, чрез подмодула <i>Добави</i> вие ще можете да добавяте страници.</p>
    <p><b>За да отидете в главния сайт - кликнете върху линка <i>КЪМ САЙТА</i>, който се намира в главното меню.</b></p>
    <?php
} elseif($_GET['m']=='about') { // За системата
    adminTop('За системата');
    ?>
    <p><b>Системата е създадена от Ясен Георгиев през учебната 2012/2013 година за проекта "1000 стипендии" на Фондация "Комунитас".</b></p>
    <p>Административният панел използва готовия темплейт <a href="https://github.com/zapnap/transdmin" target="_blank">Transmit Light</a>.</p>
    <p><b>Пренадзначение на уеб сайта:</b></p>
    <p>Целта на сайта е да представи Ясен Георгиев в Уеб пространството.</p>
    <p><b>Използвани технологии:</b></p>
    <p><ul class="bullets">
        <li><b>PHP</b> - Backend</li>
        <li><b>MySQL</b> - БД</li>
        <li><b>HTML</b> - Оформяне на уеб страниците</li>
        <li><b>CSS</b> - Дизайн на уеб страниците</li>
        <li><b>JavaScript</b> - По-голяма маневреност
            <ul class="bullets">
                <li>jQuery</li>
                <li>AJAX</li>
            </ul>
        </li>		
        <li><b>Adobe Photoshop</b> - Предварителен дизайн</li>
    </ul></p>
    <p><b>Използвани библиотеки:</b></p>
    <p><ul class="bullets">
        <li><a href="http://jquery.com">jQuery</a> - Лесния начин за писане на JS</li>
        <li><a href="http://www.tinymce.com/">TinyMCE</a> - WYSIWYG редактор</li>
        <li><a href="http://www.phpconcept.net/pclzip">PclZip</a> - Генериране на ZIP архиви</li>
    </ul></p>
    <p><b>Системата е лицензирана под GNU GPL v2</b>.</p>
    <?php
} elseif($_GET['m']=='log') { // Лог
    if($_POST['clear']) { // Изтриване на логовете
        dbQuery('TRUNCATE `logs`');
        redirect('system.php?m=log');
    }
    adminTop('Лог с грешки');
    $query = dbQuery('SELECT * FROM `logs`');
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
    if(!dbCount($query)) {
        ?>
        <tr>
            <td colspan="4">Няма записани грешки!</td>
        </tr>    
        <?php
    }
    while($log = dbAssoc($query)) {
        ?>
            <tr>
                <td><?= htmlEscape($log['value']); ?></td>
                <td><?= $log['ip']; ?></td>
                <td><?= htmlEscape($log['page']); ?></td>
                <td><?= newDate($log['saved']); ?></td>
            </tr>
        <?php
    }
    ?>
        </table>
        <form method="POST"><input type="submit" name="clear" value="Изчисти" class="button-submit"></form>
    <?php
} else {
    adminTop('Начало');
}
adminFooter();
?>