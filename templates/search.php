<div class="content__main-col">
<header class="content__header ">
    <h2 class="content__header-text">Результаты поиска</h2>
</header>
<?php if($items_count !== 0) : ?>
    <label class="label-search">Total Records - <?= $items_count; ?></label>
    <table class="table table-striped table-bordered">
      <tr>
        <th>Title</th>
        <th>Description</th>
      </tr>

        <?php foreach ($gifs as $row): ?>
        <tr>
          <td><a href="/gif/gif.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></td></a>
          <td><a href="/gif/gif.php?id=<?= $row['id']?>"><?= $row["description"] ?></td></a>
        </tr>
        <?php endforeach; ?>

    </table>
<?php else : ?>
<h2 class="content__header-text">Ничего не найдено</h2>
 <?php endif; ?>
<?= $pagination; ?>
</div>
