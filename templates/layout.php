<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <base href="/">
    <title><?= $title; ?> | GifTube</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
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
                    <input class="search__text" type="text" name="q" value="" placeholder="Поиск гифки…">

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
                    <nav class="navigation__links">
                        <a href="/signup.php">Регистрация</a>
                        <a href="/signin.php">Вход для своих</a>
                    </nav>
                    <!-- Для зарегистрированных пользователей -->
                    <nav class="navigation__links">
                        <a href="/favorites.php">Избранное</a>
                        <a href="/logout">Выход</a>
                    </nav>
                    <!-- end Для зарегистрированных пользователей -->
                </div>
                <div class="navigation__item">
                    <h3 class="navigation__title navigation__title--list">Категории</h3>
                    <nav class="navigation__links">
                        <?php foreach ($categories as $category): ?>
                            <a href="/category?id=<?= $category['id']; ?>"><?= $category['name']; ?></a>
                        <?php endforeach; ?>
                    </nav>
                </div>
            </section>
            <main class="content"><?= $content; ?></main>
        </div>

        <footer class="main-footer">
            <div class="main-footer__col">Если у вас вдруг возникли вопросы, свяжитесь с нами по почте: <a href="mailto:info@giftube.com">info@giftube.com</a>.</div>

            <div class="main-footer__col">Сохранение смешных гифок разрешено только для личного использования.</div>

            <div class="main-footer__col main-footer__col--short">
                <a class="copyright-logo" href="/"><img src="img/htmlacademy.svg" alt="" width="27" height="34"></a>
            </div>
        </footer>
    </div>
</body>
</html>
