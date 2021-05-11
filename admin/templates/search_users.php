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
                     <th>Дата реги</th>
                     <th>name</th>
                     <th>email</th>
                     <th>avatar_path</th>
                     <th>status</th>
                     <th>Действия</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($gifs as $gif): ?>
      <?php $value = $gif['status']==3 ? '<span class="label label-info">Admin</span>' : ($gif['status']==1 ? '<span class="label-default label label-danger">Забанен</span>' : '<span class="label-success label label-default">Зареган</span>'); ?>
      <tr id="tr_<?= $gif['id']; ?>">
        <td><?= $gif['id']; ?></td>
              <td><?= $gif['dt_add']; ?></td>
              <td><?= $gif['name']; ?></td>
              <td><?= $gif['email']; ?></td>
              <td><?= $gif['avatar_path']; ?></td>
              <td><?= $value; ?></td>
        <td class="center">
          <a
            target="_blank"
            class="btn btn-success"
            href="#"
          >
            <i class="glyphicon glyphicon-zoom-in icon-white"></i>
            View
          </a>
          <button
                  class="btn btn-info"
                  href="/admin/users/users.php?edit=<?= $gif['id']; ?>" 
                  onclick="AddEdit('/admin/users/edit-ajax.php', '<?= $gif['id']; ?>'); return false;"  
                  <?php $disabled = $gif['status']==3 ? 'disabled="disabled"' : ""; echo ($disabled); ?>
                >
                  <i class="glyphicon glyphicon-edit icon-white"></i>
                  Edit
          </button>
          <button
                  class="btn btn-danger" 
                  href="/admin/users/users.php?del=<?= $gif['id']; ?>" data-url="/admin/users/users.php?del=<?= $gif['id']; ?>"
                  onclick="Delete(<?= $gif['id']; ?>); return false;"
                  <?php $disabled = $gif['status']==3 ? 'disabled="disabled"' : ""; echo ($disabled); ?>
                >
                  <i class="glyphicon glyphicon-trash icon-white"></i>
                  Delete
          </button>
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
