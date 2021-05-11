<div>
  <ul class="breadcrumb">
    <li>
      <a href="/admin/index.php">Главная</a>
    </li>
    <li>
      <a href="#">Items</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="box col-md-12">
    <div class="box-inner">
      <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-picture"></i> Items</h2>
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
                <option value="category_name">category_name</option>
                <option value="user_name">user_name</option>
                <option value="title">title</option>
                <option value="description">description</option>
                <option value="img_path">img_path</option>
                <option value="url">url</option>
              </select>
              <input
                type="text"
                name="q"
                id="search_box" data-search="items"
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
                <li><a href="/admin/Item/item.php?tab=new">Новые сначала</a></li>
                <li><a href="/admin/Item/item.php">По порядку</a></li>
                <li>
                  <a href="/admin/Item/item.php?tab=rating">Рейтинговые сначала</a>
                </li>
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
                  href="/admin/Item/item.php?del=<?= $gif['id']; ?>" data-url="/admin/Item/item.php?del=<?= $gif['id']; ?>"
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
      </div>
    </div>
  </div>
</div>
