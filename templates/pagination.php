<?php 
if ($pages_count > 1): ?>
<div class="pagination">
    <ul class="pagination__control">
    <?php
    // Текущая страница
    $page = $current_page;
   // Переменная, означающая сколько цифр пагинации отображать    
    $inline = 5;
    // $pages_count - сколько всего страниц
        function paginator($page, $pages_count, $inline) {
            if($pages_count > $inline) {
                $around = (int) ($inline / 2);
                if($page <= ($around + 1)) {
                    $start = 1;
                    $stop = $inline;
                } elseif($page <= ($pages_count - ($around + 1))) {
                    $start = $page - $around;
                    $stop = $page + $around;
                } else {
                    $start = $pages_count - $inline;
                    $stop = $pages_count;
                }
            } else {
                $start = 1;
                $stop = $pages_count;
            }
            $r = range($start, $stop);
            return($r);
        }
        if($page < 1) {
            $page = 1;
        }
        if($page > $pages_count) {
            $page = $pages_count;
        }
        $pagesOut = paginator($page, $pages_count, $inline);
        $out = '';
        if($pages_count > 1) {
            if($page > 1) {
                $out .= "<li class='pagination__item'>" . "<a href=\"{$_SERVER['PHP_SELF']}"."?mode=w_js"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) || (isset($_GET['url'])) ? "" : "&") . "page=1\" onclick=\"getData('$url', {page : '1'" . ((isset($_GET['tab']) && $_GET['tab'] == 'new') ? ', tab : \'new\'' : '') . ((isset($_GET['tab']) && $_GET['tab'] == 'rating') ? ', tab : \'rating\'' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? ', id : ' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? $cat_id : '')."}); return false;\">◀" . '</a></li>';

                $out .= "<li class='pagination__item'>" . "<a href=\"{$_SERVER['PHP_SELF']}"."?mode=w_js"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) || (isset($_GET['url'])) ? "" : "&") . "page=" . ($page - 1) ."\" onclick=\"getData('$url', {page : " . ($page - 1) . ((isset($_GET['tab']) && $_GET['tab'] == 'new') ? ', tab : \'new\'' : '') . ((isset($_GET['tab']) && $_GET['tab'] == 'rating') ? ', tab : \'rating\'' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? ', id : ' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? $cat_id : '')."}); return false;\">◀◀" . '</a></li>';
            }
            foreach($pagesOut as $p)

                $out .= (($p == $current_page) ? "<li class='pagination__item pagination__item--active'>" : "<li class='pagination__item'>") . "<a href=\"{$_SERVER['PHP_SELF']}"."?mode=w_js"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) || (isset($_GET['url'])) ? "" : "&") . "page={$p}\" onclick=\"getData('$url', {page : '$p'" . ((isset($_GET['tab']) && $_GET['tab'] == 'new') ? ', tab : \'new\'' : '') . ((isset($_GET['tab']) && $_GET['tab'] == 'rating') ? ', tab : \'rating\'' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? ', id : ' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? $cat_id : '')."}); return false;\">{$p}" . '</a></li>';

            if($page < $pages_count) {
                $out .= "<li class='pagination__item'>" . "<a href=\"{$_SERVER['PHP_SELF']}"."?mode=w_js"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) || (isset($_GET['url'])) ? "" : "&") . "page=" . ($page + 1) ."\" onclick=\"getData('$url', {page : " . ($page + 1) . ((isset($_GET['tab']) && $_GET['tab'] == 'new') ? ', tab : \'new\'' : '') . ((isset($_GET['tab']) && $_GET['tab'] == 'rating') ? ', tab : \'rating\'' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? ', id : ' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? $cat_id : '')."}); return false;\">▶▶" . '</a></li>';

                $out .= (($page < $pages_count) ? "<li class='pagination__item'>" : "<li class='pagination__item'>") . "<a href=\"{$_SERVER['PHP_SELF']}"."?mode=w_js"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) || (isset($_GET['url'])) ? "" : "&")."page={$pages_count}\" onclick=\"getData('$url', {page : '{$pages_count}'" . ((isset($_GET['tab']) && $_GET['tab'] == 'new') ? ', tab : \'new\'' : '') . ((isset($_GET['tab']) && $_GET['tab'] == 'rating') ? ', tab : \'rating\'' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? ', id : ' : '') . ((isset($_GET['id'])) || (isset($_GET['url'])) ? $cat_id : '')."}); return false;\">▶" . '</a></li>';
            }
        }
        echo($out);
        ?>
        </ul>
</div>
<?php endif; ?>