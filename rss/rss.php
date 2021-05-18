<?php
    define('dbOn', '');
    require_once ('../config/config.php');
    require_once('../config/functions.php');
    if (!$mysqli->set_charset("utf8")) {
        printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
        exit();
    }
    else {
        $result = $mysqli->query("SELECT FLOOR(id/990) FROM gifs ORDER BY id DESC LIMIT 1;"); // max 1000
        $row = $result->fetch_row();
        $row = intval($row[0]);
        $header = '<?xml version="1.0" encoding="UTF-8"?>
        <rss xmlns:yandex="http://news.yandex.ru"
        xmlns:media="http://search.yahoo.com/mrss/"
        xmlns:turbo="http://turbo.yandex.ru"
        version="2.0">
        <channel>
        <!-- Информация о сайте-источнике -->
        <title>Название канала</title>
        <link>' . $address_site . '</link>
        <description>Краткое описание канала</description>
        <language>ru</language>
        <turbo:analytics></turbo:analytics>
        <turbo:adNetwork></turbo:adNetwork>';
        $end = '
            </channel>
            </rss>
            ';
        for ($i=0; $i<=$row; $i++) {
            $file = './rss'.$i.'.xml';
            $item_data = '';
            $items = $mysqli->query("SELECT g.id, g.url, g.dt_add, g.title, u.name, c.urlCat, c.nameCat FROM gifs g JOIN users u ON g.user_id = u.id JOIN categories c ON g.category_id = c.id  WHERE g.id>=" . $i * 990 . " AND g.id<" . ($i + 1) * 990 . " ORDER BY g.id DESC LIMIT 990;");
            while ($row_items = $items->fetch_assoc()) {
                $search = array(
                    '&',
                    '\'',
                    '"',
                    '>',
                    '<'
                );
                $replace = array(
                    '&amp;',
                    '&apos;',
                    '&quot;',
                    '&gt;',
                    '&lt;'
                );
                $url = $row_items['url'];
                $url = str_replace($search, $replace, $url);
                $search = array(
                    '&',
                    '\'',
                    '"',
                    '>',
                    '<'
                );
                $replace = array(
                    '&amp;',
                    '&apos;',
                    '&quot;',
                    '&gt;',
                    '&lt;'
                );
                $urlCat = $row_items['urlCat'];
                $urlCat = str_replace($search, $replace, $urlCat);
                //приобразуем дату из БД в вид ГГГГ-ММ-ДД ( у нас в БД вот такой вид ДД/ММ/ГГГГ ЧЧ:ММ )
                $datePOST = explode(' ', $row_items['dt_add']); //делим дату на массив. В первом элементе массива будет ДД/ММ/ГГГГ а во втором ЧЧ:ММ
                $dateMS = $datePOST[1]; //сохраняем минуты и сек в отдельной переменной
                $datePOST = explode("/", $datePOST[0]); //Делим первый элемент массива на другой массив. В новом массиве будет 3 элемента. Первый содержит ДД, второй ММ и третий ГГГГ
                $dateUNIX = strtotime($datePOST[0]); //Формируем дату в виде ДД-ММ-ГГГГ после чего превращаем ее в целое число
                $datePOST = date("D, d M Y ", $dateUNIX); //превращаем дату в вид Thu, 16 Feb 2012 (пример)
                $datePOST = $datePOST . $dateMS . " +0300 GMT"; //пристыковываем к дате еще и время (пример Thu, 16 Feb 2012 04:43:01 GMT)
                $sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.' WHERE g.id = '.$row_items['id'].' AND NOT moderation = 0 ORDER BY c.dt_add DESC  LIMIT 3';
                $res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id  WHERE g.id = "'.$gif_id.'" AND NOT moderation = 0');
                $count_comm = mysqli_fetch_assoc($res_count_comm)['cnt'];
                $res_comments = mysqli_query($connect, $sql_comments);
                if ($res_comments) {
                    $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
                } else {
                    $error = mysqli_error($connect);
                    print('Ошибка MySQL: '.$error);
                }
                $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, '.
                'likes_count, favs_count, views_count, description, points, avg_points, votes '.
                'FROM gifs g '.
                'JOIN categories c ON g.category_id = c.id '.
                'JOIN users u ON g.user_id = u.id '.
                'WHERE g.id = '.$row_items['id'];
                $res_gif = mysqli_query($connect, $sql_gif);
                if ($res_gif) {
                    $gif = mysqli_fetch_assoc($res_gif);
                    if (!isset($gif)) {
                        header('Location: /404.php');
                        http_response_code(404);
                        $is404error = true;
                    }
                } else {
                    $error = mysqli_error($connect);
                    print('Ошибка MySQL: '.$error);
                }
                $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count, favs_count, views_count, points, avg_points, votes, g.url, c.urlCat '.'FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.'JOIN users u ON g.user_id = u.id '.'WHERE category_id = g.category_id AND g.id NOT IN('.$row_items['id'].') LIMIT 6';
                $res_similar = mysqli_query($connect, $sql_similar);
                if ($res_similar) {
                    $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
                } else {
                    $error = mysqli_error($connect);
                    print('Ошибка MySQL: '.$error);
                }
                $page_content = include_template('gif.php', ['username' => $_SESSION['user']['name'], 'errors' => $errors, 'gif' => $gif, 'count_comm' => $count_comm,  'comments' => $comments, 'gifs' => $similar_gifs, 'gif_id' => $gif_id, 'isGifPage' => $isGifPage, 'isFav' => $isFav, 'isLiked' => $isLiked, 'dict' => $dict]);
               
                $item_data .= '
                <item turbo="true">
                <!-- Информация о странице -->
                <turbo:extendedHtml>true</turbo:extendedHtml>
                <link>' . $address_site . $urlCat . '/' . $url . '/' . '</link>
                <turbo:source>' . $address_site . $urlCat . '/' . $url . '/' . '</turbo:source>
                <turbo:topic>' . $row_items['title'] . '</turbo:topic>
                <pubDate>' . $datePOST . '</pubDate>
                <author>' . $row_items['name'] . '</author>
                <metrics>
                <yandex schema_identifier="Идентификатор">
                <breadcrumblist>
                <breadcrumb url="' . $address_site . '" text="Домашняя"/>
                <breadcrumb url="' . $address_site . $urlCat . '" text="' . $row_items['nameCat'] . '"/>
                <breadcrumb url="' . $address_site . $urlCat . '/' . $url . '/' . '" text="' . $row_items['title'] . '"/>
                </breadcrumblist>
                </yandex>
                </metrics>
                <turbo:content>
                <![CDATA['.

                $page_content
                
                .']]>
                </turbo:content>
                </item>
                ';
            }

            file_put_contents($file, $header . $item_data . $end);
        }
    }
    $mysqli->close();
    exit();


