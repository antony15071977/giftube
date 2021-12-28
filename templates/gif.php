<div class="loading-overlay" style="display: none;">
    <div class="overlay-content">Loading...</div>
</div>
<div class="content__main-col">
    <a href="<?= $gif['urlCat']; ?>"><?= $gif['nameCat']; ?></a><span style="color: #3a4153; transform: rotate(90deg);display: inline-block;;"> ^</span>
    <a href="<?= $gif['url']; ?>"><?= $gif['title']; ?></a>
    <header class="content__header">
        <h2 class="content__header-text" itemprop="name"><?= $gif['title']; ?></h2>
    </header>

    <div class="gif gif--large">
        <div class="gif__picture">
            <?= $gif['question']; ?>
        </div>
        <?php 
        if ($gif["votes"]==0) $rating = 0;
        else $rating = round($gif["points"]/$gif["votes"], 2);
        $clasStar2 = 'star3' ?>
        <div class="rating" id="el_<?= $gif_id; ?>">
            <div class="rating_wrapper">
            <a href="/rating/change_rating_w_js.php?obj_id=<?= $gif_id; ?>&stars=5" data-rating="5" class="star <?php  
            if ($rating>=5) echo $clasStar2;
            else echo (''); ?>"></a>
            <a href="/rating/change_rating_w_js.php?obj_id=<?= $gif_id; ?>&stars=4" data-rating="4" class="star <?php 
            if ($rating>=4) echo $clasStar2;
            else echo (''); ?>"></a>
            <a href="/rating/change_rating_w_js.php?obj_id=<?= $gif_id; ?>&stars=3" data-rating="3" class="star <?php
            if ($rating>=3) echo $clasStar2;
            else echo (''); ?>"></a>
            <a href="/rating/change_rating_w_js.php?obj_id=<?= $gif_id; ?>&stars=2" data-rating="2" class="star <?php 
            if ($rating>=2) echo $clasStar2;
            else echo (''); ?>"></a>
            <a href="/rating/change_rating_w_js.php?obj_id=<?= $gif_id; ?>&stars=1" data-rating="1" class="star <?php 
            if ($rating>=1) echo $clasStar2;
            else echo (''); ?>"></a>
            </div>
            <div id="star_rating">
            Рейтинг: <?php echo $rating;?>
            </div>
            <div id="star_votes">
            Оценили: <?php echo $gif["votes"];?>
            </div>
        </div>
        <div id="star_message">
             <?php
        if(isset($_GET["vote"]) && $_GET["vote"]=="success"){
            $com_vote = "<p>Спасибо, Ваш голос учтен!</p>
            <script type=\"text/javascript\">
            $(document).ready(function() {
                setTimeout(function() {
                    $(\"#star_message\").html('');
                            }, 4000);
                });
            </script>";
            echo ($com_vote);
        }
        if(isset($_GET["vote"]) && $_GET["vote"]=="voted"){
            $com_vote = "<p>Вы уже голосовали!</p>
            <script type=\"text/javascript\">
            $(document).ready(function() {
                setTimeout(function() {
                    $(\"#star_message\").html('');
                            }, 4000);
                });
            </script>";
            echo ($com_vote);
        }
        ?>
        </div>
        <div class="gif__desctiption">
            <div class="gif__description-data">
                <?php $av_path = $gif['avatar_path'] != NULL ? $gif['avatar_path'] : 'user.svg';  ?>
                    <img class="comment__picture" src="/uploads/avatar/<?= $av_path; ?>" alt="" width="30" height="30">
                <span class="gif__username" itemprop="author"><?= $gif['name']; ?></span>
                <span class="favs gif__views"><?= $gif['favs_count']; ?></span>
                <span class="gif__views"><?= $gif['views_count']; ?></span>
                <span class="gif__likes"><?= $gif['likes_count']; ?></span>
            </div>
            <h3 class="gif__desctiption-title" style="color: #000;">
                <?= $gif['dt_add'];?>
            </h3>
            <div style="display: none;" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                <span itemprop="itemReviewed" itemscope="" itemtype="https://schema.org/Book">
                    <span itemprop="name"><?= $gif['title']; ?>        
                    </span>
                </span>
                <meta itemprop="worstRating" content="1">
                <meta itemprop="bestRating" content="5">
                <meta itemprop="ratingCount" content="<?= $gif['votes']; ?>">
                <?php if ($comments != NULL): ?>
                    <meta itemprop="reviewCount" content="<?= $count_comm; ?>">
                <?php endif; ?>
                <span itemprop="ratingValue"><?= $rating; ?></span>
            </div>
        </div>

        <!-- Для зарегистрированных пользователей -->
        <?php if (isset($_SESSION['user'])): ?>
            <div class="gif__controls">
                <?php $query_like = ($isLiked == true) ? ", rem : '1'" : "";
                $query_like_url = ($isLiked == true) ? '&rem=1' : '';
                $classname_like = ($isLiked == true) ? "gif__control--active" : "";
                $name_like = ($isLiked == true) ? "Уже нравится" : "Поставить лайк";
                ?>
                <a class="button gif__control <?= $classname_like; ?>" href="/gif/gif-like.php?id=<?= $gif['id'] ?><?= $query_like_url; ?>" onclick="goFavLike('/gif/gif-like-ajax.php', {id : '<?= $gif_id; ?>'<?= $query_like; ?>}); return false;"><?= $name_like; ?></a>

                <?php $query_fav = ($isFav == true) ? ", rem : '1'" : "";
                $query_fav_url = ($isFav == true) ? '&rem=1' : '';
                $classname_fav = ($isFav == true) ? "gif__control--active" : "";
                $name_fav = ($isFav == true) ? "Уже в избранном" : "В избранное";
                ?>
                <a class="button gif__control <?= $classname_fav; ?>" href="/gif/gif-fav.php?id=<?= $gif['id']; ?><?= $query_fav_url; ?>" onclick="goFavLike('/gif/gif-fav-ajax.php', {id : '<?= $gif_id; ?>'<?= $query_fav; ?>}); return false;"><?= $name_fav; ?></a>
            </div>
        <?php endif; ?>
        <!-- end Для зарегистрированных пользователей -->
    </div>


    <?php if ($comments != NULL): ?>
        <h3 class="comment-list__title">Ответы (<span id="comment-list__count"><?= $count_comm; ?></span>):</h3>
            <div class="comment-list">
        <?php if ($comments != NULL): ?>
            
                <?php foreach($comments as $comment) : ?>
                <?php  $inlineEdit = (isset($_SESSION['user']) && $comment['name'] == $username) ? 'inlineEdit' :  ''; ?>
                <article class="comment">
                    <?php $comm_av_path = $comment['avatar_path'] != NULL ? $comment['avatar_path'] : 'user.svg';  ?>
                    <img class="comment__picture" src="/uploads/avatar/<?= $comm_av_path; ?>" alt="" width="100" height="100">
                    <div class="comment__data">
                        <div class="comment__author"><?= $comment['name']; ?>
                        </div>
                        <div class="comment__author">[<?= $comment['dt_add']; ?>]</div>
                        <p class="comment__text <?= $inlineEdit; ?>" data-id="<?= $comment['id']; ?>"><?= $comment['comment_text']; ?></p>
                        <?php if (isset($_SESSION['user']) && $comment['name'] == $username): ?>

                            <span class="comment__author comment__sign"><img class="comment__edit" src="img/pen.png">Нажмите на свой ответ, чтобы отредактировать</span>

                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        
        <?php endif; ?>
            </div>

    <?php else :?>
        <h3 class="comment-list__title">Еще никто не ответил. <?php if (!isset($_SESSION['user'])): ?>Можно оставить свой ответ после регистрации.<?php endif; ?></h3>     
    <?php endif; ?>

        <a class="button gif__control" href="/gif/gif.php?comments=all&id=<?= $gif['id']; ?>" id="show_more" count_show="3" count_add="5" gif_id="<?= $gif['id']; ?>" >Еще ответы</a>

    <!-- Для зарегистрированных пользователей -->
    <?php if (isset($_SESSION['user'])): ?>
        <form class="comment-form" id="comment-form" action="/gif/gif.php?id=<?= isset($gif['id']) ? $gif['id'] : ''; ?>" method="post">
            <label class="comment-form__label" for="comment">Добавить ответ:</label>
            <!-- Сообщение об ошибках -->
            <?php if(!empty($errors)) : ?>
                <div class="form__errors">
                    <p>Пожалуйста, исправьте следующие ошибки:</p>
                    <ul>
                        <?php foreach($errors as $error => $val) : ?>
                            <li><strong><?= $dict[$error]; ?>:</strong> <?= $val; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- end Сообщение об ошибках -->
            <?php $classname = isset($errors['comment']) ? "form__input--error" : ""; ?>
            <textarea class="comment-form__text <?= $classname; ?>" onkeyup="checkParams()" name="comment" id="comment" rows="8"  cols="80" maxlength="1800" minlength="3" placeholder="Помните о правилах и этикете. Минимум 3, максимум 1800 символов."></textarea>
            <?php if (isset($errors['comment'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
            <input type="hidden" name="gif_id" value="<?= isset($gif['id']) ? $gif['id'] : ''; ?>">
            <div id="success-respond">
        <?php
        if(isset($_GET["comment"]) && $_GET["comment"]=="success"){
            $comment = "<p>Спасибо за оставленный овет, он будет опубликован на сайте в ближайшее время после одобрения модератором.</p>
            <script type=\"text/javascript\">
            $(document).ready(function() {
                setTimeout(function() {
                    $(\"#success-respond\").html('');
                            }, 4000);
                });
            </script>";
            echo ($comment);
        }
        ?>
            </div>
            <input class="button comment-form__button" id="submit" type="submit" name="" onClick="postData(); return false;"  value="Отправить">

        </form>
    <?php endif; ?>
    <!-- end Для зарегистрированных пользователей -->
</div>

<aside class="content__additional-col">
    <h3 class="content__additional-title">Похожие вопросы:</h3>

    <ul class="gif-list gif-list--vertical">
        <?php foreach ($gifs as $gif): ?>
            <?= include_template('gif-item.php', [
                'gif' => $gif,
                'isGifPage' => $isGifPage
            ]); ?>
        <?php endforeach; ?>
    </ul>
</aside>