<div class="content__main-col">
    <header class="content__header">
        <h2 class="content__header-text"><?= $gif['title']; ?></h2>
        <label for="gifControl">click</label>
    </header>

    <div class="gif gif--large">
        <div class="gif__picture">
            <input type="checkbox" name="" id="gifControl" value="1" class="hide">
            <label for="gifControl">Проиграть</label>
            <img src="<?= $gif['img_path']; ?>" alt="" class="gif_img main hide">
            <img src="uploads/preview_gif58dbdf3251fcf.gif" alt="" class="gif_img preview">
        </div>

        <div class="gif__desctiption">
            <div class="gif__description-data">
                <span class="gif__username"><?= $gif['name']; ?></span>
                <span class="gif__views"><?= $gif['favs_count']; ?></span>
                <span class="gif__views"><?= $gif['views_count']; ?></span>
                <span class="gif__likes"><?= $gif['likes_count'] > 0 ? $gif['likes_count'] : ""; ?></span>
            </div>
            <div class="gif__description">
                <p><?= $gif['description']; ?></p>
            </div>
        </div>

        <!-- Для зарегистрированных пользователей -->
        <?php if (isset($_SESSION['user'])): ?>
            <div class="gif__controls">
                <?php $query = ($isLiked == true) ? "&rem=1" : "";
                $classname = ($isLiked == true) ? "gif__control--active" : ""; ?>
                <a class="button gif__control <?= $classname; ?>" href="/gif/gif-like.php?id=<?= $gif['id'] ?><?= $query; ?>">Мне нравится</a>
                <?php $query = ($isFav == true) ? "&rem=1" : "";
                $classname = ($isFav == true) ? "gif__control--active" : ""; ?>
                <a class="button gif__control <?= $classname; ?>" href="/gif/gif-fav.php?id=<?= $gif['id']; ?><?= $query; ?>">В избранное</a>
            </div>
        <?php endif; ?>
        <!-- end Для зарегистрированных пользователей -->
    </div>

    <div class="comment-list">
        <h3 class="comment-list__title">Комментарии:</h3>

        <?php foreach($comments as $comment) : ?>
            <article class="comment">
                <img class="comment__picture" src="<?= $comment['avatar_path']; ?>" alt="" width="100" height="100">
                <div class="comment__data">
                    <div class="comment__author"><?= $comment['name']; ?></div>
                    <p class="comment__text"><?= $comment['comment_text']; ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Для зарегистрированных пользователей -->
    <?php if (isset($_SESSION['user'])): ?>
        <form class="comment-form" action="/gif/gif.php?id=<?= isset($gif['id']) ? $gif['id'] : ''; ?>" method="post">
            <label class="comment-form__label" for="comment">Добавить комментарий:</label>
            <?php $classname = isset($errors['comment']) ? "form__input--error" : ""; ?>
            <textarea class="comment-form__text <?= $classname; ?>" name="comment" id="comment" rows="8" cols="80" placeholder="Помните о правилах и этикете. "></textarea>
            <?php if (isset($errors['comment'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
            <input type="hidden" name="gif_id" value="<?= isset($gif['id']) ? $gif['id'] : ''; ?>">
            <input class="button comment-form__button" type="submit" name="" value="Отправить">
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