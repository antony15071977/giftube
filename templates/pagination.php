<?php if ($pages_count > 1): ?>
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
                $out .= (($page > 1) ? "<li class='pagination__item'>" : "<li class='pagination__item'>") . "<a  href=\"{$_SERVER['PHP_SELF']}"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) ? "" : "?") . "page=1\">◀" . '</a></li>';
                $out .= "<a href=\"{$_SERVER['PHP_SELF']}"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) ? "" : "?") . "page=" . ($page - 1) . '">◀◀</a> ';
            }
            foreach($pagesOut as $p)

                $out .= (($p == $current_page) ? "<li class='pagination__item pagination__item--active'>" : "<li class='pagination__item'>") . "<a  href=\"{$_SERVER['PHP_SELF']}"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) ? "" : "?") . "page={$p}\">{$p}" . '</a></li>';

            if($page < $pages_count) {
                $out .= "<a href=\"{$_SERVER['PHP_SELF']}"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) ? "" : "?") . "page=" . ($page + 1) . '">▶▶</a> ';
                $out .= (($p < $pages_count) ? "<li class='pagination__item'>" : "<li class='pagination__item'>") . "<a  href=\"{$_SERVER['PHP_SELF']}"."$param".((isset($_GET['tab'])) || (isset($_GET['id'])) ? "" : "?")."page={$pages_count}\">▶" . '</a></li>';
            }
        }
        echo($out);
        ?>
        </ul>
</div>
<?php endif; ?>