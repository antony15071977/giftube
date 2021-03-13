<?php
    $query_like = ($isLiked == true) ? ", rem : '1'" : "";
    $query_like_url = ($isLiked == true) ? '&rem=1' : '';
    $classname_like = ($isLiked == true) ? "gif__control--active" : "";
    $name_like = ($isLiked == true) ? "Уже нравится" : "Поставить лайк";
?>
    <a class="button gif__control <?= $classname_like; ?>" href="/gif/gif-like.php?id=<?= $gif['id'] ?><?= $query_like_url; ?>" onclick="goFavLike('/gif/gif-like-ajax.php', {id : '<?= $gif_id; ?>'<?= $query_like; ?>}); return false;"><?= $name_like; ?></a>
<?php
    $query_fav = ($isFav == true) ? ", rem : '1'" : "";
    $query_fav_url = ($isFav == true) ? '&rem=1' : '';
    $classname_fav = ($isFav == true) ? "gif__control--active" : "";
    $name_fav = ($isFav == true) ? "Уже в избранном" : "В избранное";
?>
    <a class="button gif__control <?= $classname_fav; ?>" href="/gif/gif-fav.php?id=<?= $gif['id']; ?><?= $query_fav_url; ?>" onclick="goFavLike('/gif/gif-fav-ajax.php', {id : '<?= $gif_id; ?>'<?= $query_fav; ?>}); return false;"><?= $name_fav; ?>
            
    </a>
    <?php
        $count_likes = $gif['likes_count'];
        $count_favs = $gif['favs_count'];
    ?>
