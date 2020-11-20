<div class="content__main-col">

    <?php if($isMainPage) : ?>

        <h2 class="visually-hidden"><?= $title; ?></h2>
        <header class="content__header">
            <nav class="filter">
                <?php if(isset($_GET['tab'])) : ?>

                    <a class="filter__item " href="/">Топовые гифки</a>
                    <a class="filter__item filter__item--active" href="/?tab=new">Свежачок</a>

                <?php else :?>

                    <a class="filter__item filter__item--active" href="/">Топовые гифки</a>
                    <a class="filter__item " href="/?tab=new">Свежачок</a>

                <?php endif; ?>
            </nav>
            <a class="button button--transparent button--transparent-thick content__header-button" href="/add.php">Загрузить свою</a>
        </header>

    <?php else : ?>

        <?php $classname = ($isFormPage) ? "content__header--left-pad" : ""; ?>
        
        <header class="content__header <?= $classname; ?>">
            <h2 class="content__header-text"><?= $title; ?></h2>
            <a class="button button--transparent content__header-button" href="/">Назад</a>
        </header>

    <?php endif; ?>


    <?php if($isFormPage) : ?>

        <?= $form; ?>

        <?php elseif($is404error) : ?>

        <?= include_template('error404.php'); ?>

    <?php else : ?>

        <ul class="gif-list">
            <?php foreach ($gifs as $gif): ?>
                <?= include_template('gif-item.php', ['gif' => $gif]); ?>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

    <?= $pagination; ?>

</div>
