<div class="loading-overlay" style="display: none;">
    <div class="overlay-content">Loading...</div>
</div>
<div class="content__main-col">
    <header class="content__header">
        <h2 class="content__header-text" itemprop="name"><?= $gif['title']; ?></h2>
        <label for="gifControl">click</label>
    </header>

    <div class="gif gif--large">
        <div class="gif__picture">
            <input type="checkbox" name="" id="gifControl" value="1" class="hide">
            <label for="gifControl">Проиграть</label>
            <img src="<?= $gif['img_path']; ?>" alt="" class="gif_img main hide">
        </div>
        <?php 
        if ($gif["votes"]==0) $rating = 0;
        else $rating = round($gif["points"]/$gif["votes"], 2);
        $clasStar2 = 'star3' ?>
        <div class="rating" id="el_<?= $gif_id; ?>">
            <div data-rating="1" class="star <?php 
            if ($rating>=1) echo $clasStar2;
            else echo (''); ?>"></div>
            <div data-rating="2" class="star <?php 
            if ($rating>=2) echo $clasStar2;
            else echo (''); ?>"></div>
           <div data-rating="3" class="star <?php 
            if ($rating>=3) echo $clasStar2;
            else echo (''); ?>"></div>
            <div data-rating="4" class="star <?php 
            if ($rating>=4) echo $clasStar2;
            else echo (''); ?>"></div>
            <div data-rating="5" class="star <?php 
            if ($rating>=5) echo $clasStar2;
            else echo (''); ?>"></div>
            <div id="star_rating">
            Рейтинг: <?php echo $rating;?>
            </div>
            <div id="star_votes">
            Оценили: <?php echo $gif["votes"];?>
            </div>
        </div>
        <div id="star_message"></div>
        <div class="gif__desctiption">
            <div class="gif__description-data">
                <span class="gif__username" itemprop="author"><?= $gif['name']; ?></span>
                <span class="gif__views"><?= $gif['favs_count']; ?></span>
                <span class="gif__views"><?= $gif['views_count']; ?></span>
                <span class="gif__likes"><?= $gif['likes_count'] > 0 ? $gif['likes_count'] : ""; ?></span>
            </div>
            <div itemprop="description" class="gif__description">
                <p><?= $gif['description']; ?></p>
            </div>
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

    <h3 class="comment-list__title">Комментарии (<span id="comment-list__count"><?= $count_comm; ?></span>):</h3>
        <div class="comment-list">

    <?php if ($comments != NULL): ?>
            
                <?php foreach($comments as $comment) : ?>
                <?php  $inlineEdit = (isset($_SESSION['user']) && $comment['name'] == $username) ? 'inlineEdit' :  ''; ?>
                <article class="comment">
                    <img class="comment__picture" src="<?= $comment['avatar_path']; ?>" alt="" width="100" height="100">
                    <div class="comment__data">
                        <div class="comment__author"><?= $comment['name']; ?>
                        </div>
                        <div class="comment__author">[<?= $comment['dt_add']; ?>]</div>
                        <p class="comment__text <?= $inlineEdit; ?>" data-id="<?= $comment['id']; ?>"><?= $comment['comment_text']; ?></p>
                        <?php if (isset($_SESSION['user']) && $comment['name'] == $username): ?>

                            <span class="comment__author comment__sign"><img class="comment__edit" src="img/pen.png">Нажмите на свой комментарий, чтобы отредактировать</span>

                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        
     <?php endif; ?>
    
        </div>
        <a class="button gif__control" href="/gif/gif.php?comments=all&id=<?= $gif['id']; ?>" id="show_more" count_show="3" count_add="5" gif_id="<?= $gif['id']; ?>" style="display: none;">Еще комментарии</a>

    <!-- Для зарегистрированных пользователей -->
    <?php if (isset($_SESSION['user'])): ?>
        <form class="comment-form" id="comment-form" action="/gif/gif.php?id=<?= isset($gif['id']) ? $gif['id'] : ''; ?>" method="post">
            <label class="comment-form__label" for="comment">Добавить комментарий:</label>
            <?php $classname = isset($errors['comment']) ? "form__input--error" : ""; ?>
            <textarea class="comment-form__text <?= $classname; ?>" onkeyup="checkParams()" name="comment" id="comment" rows="8" required="required" cols="80" maxlength="180" minlength="3" placeholder="Помните о правилах и этикете. Минимум 3, максимум 180 символов."></textarea>
            <?php if (isset($errors['comment'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
            <input type="hidden" name="gif_id" value="<?= isset($gif['id']) ? $gif['id'] : ''; ?>">
            <input class="button comment-form__button gif__control--active" id="submit" type="submit" name="" onClick="postData(); return false;" disabled="disabled" value="Отправить">
        </form>
    <?php endif; ?>
    <!-- end Для зарегистрированных пользователей -->
</div>

<aside class="content__additional-col">
    <h3 class="content__additional-title">Похожие гифки:</h3>

    <ul class="gif-list gif-list--vertical">
        <?php foreach ($gifs as $gif): ?>
            <?= include_template('gif-item.php', [
                'gif' => $gif,
                'isGifPage' => $isGifPage
            ]); ?>
        <?php endforeach; ?>
    </ul>
</aside>