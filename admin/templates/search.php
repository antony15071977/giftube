<div class="content__main-col">
  <header class="content__header ">
    <h2 class="content__header-text">Результаты поиска</h2>
  </header>
  <?php if($items_count !== 0) : ?>
  <label class="label-search"
    >Всего записей -
    <?= $items_count; ?>
  </label>

  <table
    class="table table-striped table-bordered bootstrap-datatable datatable responsive"
  >
    <thead>
      <tr>
        <th>id</th>
        <th>category_name</th>
        <th>user_name</th>
        <th>title</th>
        <th>description</th>
        <th>img_path</th>
        <th>url</th>
        <th>Действия</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($gifs as $gif): ?>
      <tr id="tr_<?= $gif['id']; ?>">
        <td><?= $gif['id']; ?></td>
        <td><?= $gif['nameCat']; ?></td>
        <td><?= $gif['name']; ?></td>
        <td><?= $gif['title']; ?></td>
        <td><?= $gif['description']; ?></td>
        <td><?= $gif['img_path']; ?></td>
        <td><?= $gif['url']; ?></td>
        <td class="center">
          <a
            target="_blank"
            class="btn btn-success"
            href="/<?= $gif['urlCat']; ?>/<?= $gif['url']; ?>/"
          >
            <i class="glyphicon glyphicon-zoom-in icon-white"></i>
            View
          </a>
          <a
                  class="btn btn-info"
                  href="/admin/Item/item.php?edit=<?= $gif['id']; ?>" onclick="AddEdit('/admin/Item/edit-ajax.php', '<?= $gif['id']; ?>'); return false;"
                >
                  <i class="glyphicon glyphicon-edit icon-white"></i>
                  Edit
                </a>
          <a
                  class="btn btn-danger"
                  href="/admin/Item/item.php?del=<?= $gif['id']; ?>"
                  onclick="Delete(<?= $gif['id']; ?>); return false;"
                >
                  <i class="glyphicon glyphicon-trash icon-white"></i>
                  Delete
                </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
   <a
          href="/admin/add.php"
          class="btn btn-primary"
          onclick="AddEdit('/admin/Item/add-ajax.php'); return false;"
        >
          <i class="glyphicon glyphicon-bell icon-white"></i> ДОБАВИТЬ
        </a>
  <div class="row">
    <div class="col-md-12 center-block">
      <div class="dataTables_paginate paging_bootstrap">
        <?= $pagination; ?>
      </div>
    </div>
  </div>
  <?php else : ?>
  <h2 class="content__header-text">Ничего не найдено</h2>
  <?php endif; ?>
</div>
