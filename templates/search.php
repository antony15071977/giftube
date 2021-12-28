<div class="content__main-col">
<header class="content__header ">
    <h2 class="content__header-text">Результаты поиска</h2>
</header>
<?php if($items_count !== 0) : ?>
    <label class="label-search">Всего найдено - <?= $items_count; ?></label>
    <table class="table table-striped table-bordered">
      <tr>
        <th>Title</th>
        <th>Вопрос</th>
      </tr>

        <?php foreach ($gifs as $row): ?>
        <tr>
          <td><a href="/<?= $row['urlCat'] ?>/<?= $row['url'] ?>/"><?= $row['title'] ?></td></a>
          <td><a href="/<?= $row['urlCat'] ?>/<?= $row['url'] ?>/"><?= $row["question"] ?></td></a>
        </tr>
        <?php endforeach; ?>

    </table>
<?php else : ?>
<h2 class="content__header-text">Ничего не найдено</h2>
 <?php endif; ?>
<?= $pagination; ?>
</div>
