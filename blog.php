<?php
include 'load.php';
siteTop('Блог', false, 'blog');
title('Публикации');

$rssLink = $system['blog']['rss'];

$headers = get_headers($rssLink, 1);

if ($headers[0] == 'HTTP/1.1 200 OK') {
    $rss = file_get_contents($rssLink);

    if (search_string($headers['Content-Type'], 'text/xml')) {
        $xml = new SimpleXMLElement($rss);
        $ret = array();
        if (is_object($xml)) {
            foreach ($xml as $element) {
                if (is_object($element->item)) {
                    foreach ($element as $key => $val) {
                        $i++;
                        if ($key == 'item') {
                            foreach ($val as $key2 => $val2) {
                                if (is_array($val2)) {
                                    foreach ($val2 as $key3 => $val3) {
                                        $blog[$i][$key2][$key3] = $val3;
                                    }
                                }
                                $blog[$i][$key2] = htmlspecialchars($val2);
                            }
                        }
                    }
                }
            }
            foreach ($blog as $post) {
                ?>
                <div class="b-post">
                    <h3 class="post-title"><?= $post['title']; ?><span><?= myDate(strtotime($post['pubDate'])); ?></span></h3>							
                    <div class="post-content">
                        <p><?= str_replace('[...]', '[...] <a href="' . $post['guid'] . '" class="more">Прочети повече</a>', htmlspecialchars_decode($post['description'])); ?></p>						
                    </div>
                </div>
                <?php
            }
        } else {
            echo 'Не мога да заредя блога!';
        }
    } else {
        echo 'Невалиден XML файл!';
    }
} else {
    echo 'Не мога да взема RSS библиотеката!';
}
echo '<br /><p><a href="' . $system['blog']['url'] . '" class="button">Виж всички публикации</a></p>';
siteFooter();
?>