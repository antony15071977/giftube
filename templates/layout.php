<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <base href="/">
    <title><?= $title; ?> | GifTube</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/custom.js"></script>
    <?= $Js = $Js ?? "" ?>
</head>
<body class="home">
    <div class="container">
        <header class="main-header">
            <h1 class="visually-hidden">Giftube</h1>
            <a class="logo" href="/">
                <img class="logo__img" src="../img/logotype.svg" alt="Giftube" width="160" height="38">
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
                    <h3 class="navigation__title navigation__title--account">Мой Giftube</h3>
                    <?php if (isset($_SESSION['user'])): ?>
                        <nav class="navigation__links">
                            <a href="javascript:;"><?= $username; ?></a>
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
                           <a href="/gif/category.php?id=<?= $category['id']; ?>"><?= $category['name']; ?></a>
                        <?php endforeach; ?>
                    </nav>
                </div>
            </section>
            <main class="content"><?= $content; ?></main>
        </div>

        <footer class="main-footer">
            <div class="main-footer__col">Если у вас вдруг возникли вопросы, свяжитесь с нами по почте: <a href="mailto:info@giftube.com">info@giftube.com</a>.</div>

            <div class="main-footer__col">Сохранение смешных гифок разрешено только для личного использования.</div>
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
<script src="../js/popup.js"></script>
</body>
</html>
