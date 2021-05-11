<div>
  <ul class="breadcrumb">
    <li>
      <a href="/admin/index.php">Главная</a>
    </li>
    <li>
      <a href="#">Users</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="box col-md-12">
    <div class="box-inner">
      <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-user"></i> Users</h2>
      </div>
      <div class="box-header well" data-original-title="">
        <div class="col-md-6">
          <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label
              >Поиск по столбцу:
              <select
                size="1"
                name="names"
                id="names"
                aria-controls="DataTables_Table_0"
              >
                <option value="id" selected="selected">id</option>
                <option value="dt_add">Дата реги</option>
                <option value="name">Имя(по алфавиту)</option>
                <option value="email">email</option>
                <option value="status">status</option>
              </select>
              <input
                type="text"
                name="q"
                id="search_box" data-search="users"
                class="search__text"
                placeholder="Что ищем?"
            /></label>
          </div>
        </div>
      </div>
      <div class="box-content">
        <div class="box-header well" data-original-title="">
          <div class="col-md-6">
            <div class="dropdown">
              <a href="#" data-toggle="dropdown"
                ><i class="glyphicon glyphicon-star"></i> Сортировать по
                <span class="caret"></span
              ></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/admin/users/users.php?tab=new">Новые реги сначала</a></li>
                <li><a href="/admin/users/users.php">По порядку</a></li>
              </ul>
            </div>
          </div>
        </div>
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
        <div class="row">
          <div class="col-md-12 center-block">
            <div class="dataTables_paginate paging_bootstrap">
              <?= $pagination; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
