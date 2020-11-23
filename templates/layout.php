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
    <?= $Js = $Js ?? "" ?>
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1 class="visually-hidden">Giftube</h1>
            <a class="logo" href="/index.php">
                <img class="logo__img" src="../img/logotype.svg" alt="Giftube" width="160" height="38">
            </a>
            <form class="search" action="/search.php" method="get">
                <div class="search__control">
                    <input class="search__text" type="text" name="q" value="<?= $search; ?>" placeholder="Поиск гифки…">

                    <div class="search__submit">
                        <input class="button" type="submit" name="" value="Найти">
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
                            <a href="/favorites.php">Избранное</a>
                            <a href="/logout.php">Выход</a>
                        </nav>
                    <?php else : ?>
                        <nav class="navigation__links">
                            <a href="/signup.php">Регистрация</a>
                            <a href="/signin.php">Вход для своих</a>
                        </nav>
                    <?php endif; ?>
                </div>
                <div class="navigation__item">
                    <h3 class="navigation__title navigation__title--list">Категории</h3>
                    <nav class="navigation__links">
                        <?php foreach ($categories as $category): ?>
                           <a href="/category.php?id=<?= $category['id']; ?>"><?= $category['name']; ?></a>
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
    </div>
</body>
</html>
