<?php $classname = ($isGifPage) ? "gif--small" : ""; ?>

<?php
include('../config/config.php');
$res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id WHERE g.id = "'.$gif['id'].'" AND NOT moderation = 0');
$count_comm = mysqli_fetch_assoc($res_count_comm)['cnt'];
?>

<li class="gif <?= $classname; ?> gif-list__item">
    <div class="gif__picture">
        <a href="/<?= $gif['urlCat'];?>/<?= $gif['url'];?>/" class="gif__preview">
            <?= $gif['question']; ?>
        </a>
    </div>
    <div class="star_rating">
        Рейтинг: <?= $gif['avg_points']; ?>
        </div>
    <div class="gif__desctiption">
        <h3 class="gif__desctiption-title" style="color: #000;">
            <?= $gif['dt_add'];?></h3>
        <h3 class="gif__desctiption-title" style="color: #000;">
            Ответов - <?= $count_comm;?></h3>
        <div class="gif__description-data">
            <?php $av_path = $gif['avatar_path'] != NULL ? $gif['avatar_path'] : 'user.svg';  ?>
            <img class="comment__picture" src="/uploads/avatar/<?= $av_path; ?>" alt="" width="30" height="30">
            <span class="gif__username"><?= $gif['name']; ?></span>
            <span class="gif__likes"><?= $gif['favs_count']; ?></span>
            <span class="gif__views"><?= $gif['views_count']; ?></span>
            <span class="gif__likes"><?= $gif['likes_count']; ?></span>
        </div>
    </div>
</li>
