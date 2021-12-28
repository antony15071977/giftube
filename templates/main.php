<div class="content__main-col">

    <?php if($isMainPage) : ?>

        <h2 class="visually-hidden"><?= $title; ?></h2>
        <header class="content__header">
            <nav class="filter">
                <?php if(isset($_GET['tab']) && $_GET['tab'] == 'new') : ?>

                    <a class="filter__item " href="/index/index.php" onclick="getData('/index/index.php', {top : 'top'}); return false;">Топовые вопросы</a>
                    <a class="filter__item filter__item--active" href="/index/index.php?mode=w_js&tab=new" onclick="getData('/index/index.php', {tab : 'new'}); return false;">Свежачок</a>
                    <a class="filter__item" href="/index/index.php?mode=w_js&tab=rating" onclick="getData('/index/index.php', {tab : 'rating'}); return false;">Рейтинговые вопросы</a>

                <?php elseif(isset($_GET['tab']) && $_GET['tab'] == 'rating') :?>

                    <a class="filter__item" href="/index/index.php" onclick="getData('/index/index.php', {top : 'top'}); return false;">Топовые вопросы</a>
                    <a class="filter__item" href="/index/index.php?mode=w_js&tab=new" onclick="getData('/index/index.php', {tab : 'new'}); return false;">Свежачок</a>
                    <a class="filter__item filter__item--active" href="/index/index.php?mode=w_js&tab=rating" onclick="getData('/index/index.php', {tab : 'rating'}); return false;">Рейтинговые вопросы</a>

                <?php else :?>

                    <a class="filter__item filter__item--active" href="/index/index.php" onclick="getData('/index/index.php', {top : 'top'}); return false;">Топовые вопросы</a>
                    <a class="filter__item" href="/index/index.php?mode=w_js&tab=new" onclick="getData('/index/index.php', {tab : 'new'}); return false;">Свежачок</a>
                    <a class="filter__item" href="/index/index.php?mode=w_js&tab=rating" onclick="getData('/index/index.php', {tab : 'rating'}); return false;">Рейтинговые вопросы</a>

                <?php endif; ?>
            </nav>
            <?php if (isset($_SESSION['user'])): ?>
                    <a class="button button--transparent button--transparent-thick content__header-button" href="/add/"><b>Задать вопрос</b></a>
                <?php else : ?>
                    <a class="button button--transparent button--transparent-thick content__header-button" href="/"><b>Задать вопрос</b><br>(только для зарегистрированных пользователей)</a>
            <?php endif; ?>
            
        </header>

    <?php else : ?>

        <?php $classname = ($isFormPage) ? "content__header--left-pad" : ""; ?>
        
        <header class="content__header <?= $classname; ?>">
        <div class="loading-overlay" style="display: none;">
            <div class="overlay-content">Loading...</div>
        </div>
            <h2 class="content__header-text"><?= $title; ?></h2>
            <a class="button button--transparent content__header-button" href="/">Домой</a>
            <?php if(isset($category_name) || isset($up_category_name)) : ?>
                <a class="button button--transparent content__header-button" href="/<?= $url; ?>"><?= $up_category_name; ?><?= $category_name; ?></a>
            <?php endif; ?>            
        </header>

    <?php endif; ?>


    <?php if($isFormPage) : ?>

        <?= $form; ?>

        <?php elseif($is404error) : ?>

        <?= include_template('404.php'); ?>

    <?php else : ?>

        <div class="loading-overlay" style="display: none;">
            <div class="overlay-content">Loading...</div>
        </div>
        <ul class="gif-list">
            <?php foreach ($gifs as $gif): ?>
                <?= include_template('gif-item.php', ['gif' => $gif]); ?>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

    <?= $pagination; ?>

</div>
