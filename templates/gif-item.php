<?php $classname = ($isGifPage) ? "gif--small" : ""; ?>

<li class="gif <?= $classname; ?> gif-list__item">
    <div class="gif__picture">
        <a href="/gif.php?id=<?= $gif['id'];?>" class="gif__preview">
            <img src="<?= $gif['img_path']; ?>" alt="" width="260" height="260">
        </a>
    </div>
    <div class="gif__desctiption">
        <h3 class="gif__desctiption-title">
            <a href="/gif.php?id=<?= $gif['id'];?>"><?= $gif['title']; ?></a>
        </h3>
        <div class="gif__description-data">
            <span class="gif__username"><?= $gif['name']; ?></span>
            <span class="gif__likes"><?= $gif['likes_count']; ?></span>
        </div>
    </div>
</li>
