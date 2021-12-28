<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <base href="/">
    <title><?= $title; ?> | Форум</title>
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Форум">
    <meta property="og:locale" content="ru">
    <meta property="og:image" content="img/favicon.ico">
    <meta property="og:image:width" content="968">
    <meta property="og:image:height" content="504">
    <meta name="viewport" content="width=device-width" initial-scale=1>
    <meta name="keywords" content="Вопросы, форум<?php if (isset($gif["nameCat"])): ?>, <?= $gif["nameCat"]; ?><?php endif; ?>, <?= $title; ?>, сайт 'Форум'">   
    <?php if (isset($href_amp)): ?>
        <meta name="description" content="<?php if (isset($href_amp)&&isset($gif)): ?>Вопрос: <?php endif; ?><?= $title; ?><?php if (isset($href_amp)&&isset($gif)): ?>... в категории '<?= $gif["nameCat"]; ?>'<?php endif; ?> на сайте 'Форум'">
        <meta property="og:url" content="<?= $href; ?>">
        <meta property="og:description" content="<?php if (isset($href_amp)&&isset($gif)): ?>Вопрос: <?php endif; ?><?= $title; ?><?php if (isset($href_amp)&&isset($gif)): ?>... в категории '<?= $gif["nameCat"]; ?>'<?php endif; ?> на сайте 'Форум'">
        <link rel="amphtml" href="<?= $href_amp; ?>">        
    <?php else : ?>
        <meta name="description" content="<?= $title; ?> | Форум">        
        <meta property="og:description" content="<?= $title; ?> | Форум">
    <?php endif; ?>
    <?php if (isset($href)): ?>
        <link rel="canonical" href="<?= $href; ?>" />
    <?php endif; ?>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel='stylesheet' href='../rating/rating.css'>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">    
</head>
<body class="home">
    <div class="container">
        <header class="main-header">
            <h1 class="visually-hidden">Форум</h1>
            <a class="logo" href="/">
                <img class="logo__img" src="../img/logo-2x.png" alt="Форум" width="146" height="57">
            </a>
            <form class="search" action="/search/search.php" method="get">
                <div class="search">
                    <div class="search__control">
                        <input type="text" name="q" id="search_box" class="search__text" placeholder="ПОИСК" />
                        <div class="search__submit">
                            <input class="button" type="submit" name="" value="Найти">
                        </div>
                    </div>
                </div>
            </form>
        </header>

        <div class="main-content">
            <section class="navigation">
                <h2 class="visually-hidden">Навигация</h2>
                <div class="navigation__item">
                    <h3 class="navigation__title navigation__title--account">Форум вопросов и ответов</h3>
                    <h3 class="navigation__title">Всего вопросов - <?= $items_count; ?></h3>
                    <?php if (isset($_SESSION['user'])): ?>
                        <nav class="navigation__links">
                            <a href="javascript:;"><?= $username; ?></a>
                            <a href="/restore/reset_password.php" onclick="changePass(); return false;">Изменить пароль</a>
                            <a href="/gif/favorites.php" onclick="getData('/gif/favorites.php', {fav : 'fav'}); return false;">Избранное</a>
                            <a href="/logout.php" onclick="logOut(); return false;">Выход</a>
                        </nav>
                    <?php else : ?>
                        <nav class="navigation__links">
                           <?php $disabled = isset($signup_errors) ? "disabled" : "open-modal"; ?>
                            <a href="/signup/signup.php" class="<?= $disabled; ?>" data-modal="#modal1" data-url="/templates/signup-popup.php">Регистрация</a>

                            <?php $disabled = isset($signin_errors) ? "disabled" : "open-modal"; ?>
                            <a href="/signin/signin.php" class="<?= $disabled; ?>" data-modal="#modal1" data-url="/templates/signin-popup.php">Вход для своих</a>
                        </nav>
                    <?php endif; ?>
                </div>
                <div class="navigation__item">
                    <h3 class="navigation__title navigation__title--list">Категории</h3>
                    <nav class="navigation__links">
                        <?php foreach ($categories as $category): ?>
                            <?php if($category['upcategories_id']==0) : ?>
                                <a href="/category/<?= $category['urlCat']; ?>"><?= $category['nameCat']; ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        
                        <?php foreach ($upcategories as $upcategory): ?>
                            <a class="upcategory" href="/upcategory/<?= $upcategory['url_up_Cat']; ?>"><?= $upcategory['name_up_Cat']; ?><div class="upcategory_after"></div></a>
                            <?php  $i = 0; ?>
                            <?php if($i==0) : ?><div class="upcategory_link"><?php endif; ?>
                            <?php foreach ($categories as $category): ?>
                                <?php if($upcategory['up_id']==$category['upcategories_id']) : ?>
                                    <a href="/category/<?= $category['urlCat']; ?>"><?= $category['nameCat']; ?></a><br>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php $i++; ?>
                            <?php if($i!==0) : ?></div><?php endif; ?>
                        <?php endforeach; ?>
                    </nav>
                </div>
            </section>
            <main class="content"><?= $content; ?></main>
        </div>

        <footer class="main-footer">
            <div class="main-footer__col">Если у вас вдруг возникли вопросы, свяжитесь с нами по почте: <a href="mailto:info@rthrhrth.com">info@rtjrtrr.com</a>.</div>

            <div class="main-footer__col">Только для личного использования.</div>
            <div class="main-footer__col">
             <p>Пользователей онлайн: <?= $num_online ?></p>
                <p>Уникальных посетителей за сутки: <?= $num_visitors_hosts; ?></p>
                <p>Просмотров за сутки: <?= $num_visitors_views; ?></p>
                <p>Уникальных посетителей за месяц: <?= $hosts_stat_month; ?></p>
                <p>Просмотров сайта за месяц: <?= $views_stat_month; ?></p>
             </div>
            <div class="main-footer__col main-footer__col--short">
                <a class="copyright-logo" href="/"><img src="img/htmlacademy.svg" alt="" width="27" height="34"></a>
            </div>
        </footer>
        
        <div class="modal" id="modal1">
            <a class="close-modal" data-modal="#modal1" href="#">X</a>
            <div class="modal__content">
            </div>
        </div>

    </div>
<script src="../js/jquery.min.js"></script>
<script src="../js/custom.js"></script>
<?= $Js = $Js ?? "" ?>    
<script src="../js/popup.js"></script>
</body>
</html>
