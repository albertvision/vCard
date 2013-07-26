<?php

include 'load.php';
siteTop('CV', false, 'resume');
$edu_query = dbQuery('SELECT `school`,`level`,`years` FROM `cv_education` ORDER BY `years` DESC');
if(dbCount($edu_query)) {
    title('Образование', 'resume');
    while($edu = dbAssoc($edu_query)) {
        $level = returnLevel($edu['level']);
        ?>
        <div class="clearfix">
            <div class="left">
                <h4><?= $level; ?></h4>
                <span><?= $edu['school']; ?></span>
            </div>
            <div class="right">
                <span><?= $edu['years']; ?></span>
            </div>
        </div>
        <?php
    }
}
$skill_query = dbQuery('SELECT * FROM `cv_skills` ORDER BY `rate` DESC');
if(dbCount($skill_query)) {
    title('Умения', ' ');
    while($skill = dbAssoc($skill_query)) {
        ?>
        <div class="clearfix">
            <div class="left">
                <h4><?= $skill['name']; ?></h4>
                <ul class="ratting">
                    <?php
                    if($skill['rate']> 4) { $skill = 4; }
                    for ($loop = 1; $loop <= $skill['rate']; $loop++) {
                        echo '<li></li>';
                    }
                    for($loop2 = $loop; $loop2 <=4; $loop2++) {
                        echo '<li class="grey"></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="right">
                <span><?= $skill['desc']; ?></span>
            </div>
        </div>
    
        <?php
    }
}
siteFooter();
?>
