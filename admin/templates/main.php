 <!-- content starts -->
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="/admin/index.php">Главная</a>
            </li>
            <li>
                <a href="#">Отчеты</a>
            </li>
        </ul>
    </div>
    <div class=" row">
        <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip" class="well top-block" href="#">
                <i class="glyphicon glyphicon-user blue"></i>
                <div>Всего зарегистрировано</div>
                <div><?= $users_count; ?></div>
            </a>
        </div>

        <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip"  class="well top-block" href="#">
               <i class="glyphicon glyphicon-envelope red"></i>

                <div>Всего комментариев</div>
                <div><?= $count_comm; ?></div>
            </a>
        </div>

       <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip"  class="well top-block" href="#">
               <i class="glyphicon glyphicon-shopping-cart yellow"></i>

                <div>Всего Items</div>
                <div><?= $items_count; ?></div>
            </a>
        </div>

        <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip" title="0 new messages." class="well top-block" href="#">
                <i class="glyphicon glyphicon-envelope red"></i>

                <div>Сообщений</div>
                <div>0</div>
            </a>
        </div>
    </div>
    <div class=" row">
        <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip" class="well top-block" href="#">
                <i class="glyphicon glyphicon-user blue"></i>
                <div>Пользователей онлайн</div>
                <div><?= $num_online ?></div>
            </a>
        </div>

        <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip"  class="well top-block" href="#">
                <i class="glyphicon glyphicon-user blue"></i>

                <div>Уникальных посетителей за сутки</div>
                <div><?= $num_visitors_hosts; ?></div>
            </a>
        </div>

       <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip"  class="well top-block" href="#">
                <i class="glyphicon glyphicon-user blue"></i>
                <div>Уникальных посетителей за месяц</div>
                <div><?= $hosts_stat_month; ?></div>
            </a>
        </div>

        <div class="col-md-3 col-sm-3 col-xs-6">
            <a data-toggle="tooltip" title="0 new messages." class="well top-block" href="#">
                <i class="glyphicon glyphicon-star green"></i>
                <div>Просмотров сайта за месяц</div>
                <div><?= $views_stat_month; ?></div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well">
                    <h2><i class="glyphicon glyphicon-info-sign"></i> Памятка</h2>
                </div>
                <div class="box-content row">
                    <div class="col-lg-7 col-md-12">
                        <h1>Админпанель</h1>
                        <p>Здесь ваша памятка для администраторов</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="box col-md-4">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-list"></i> Daily Stat</h2>
                </div>
                <div class="box-content">
                    <ul class="dashboard-list">
                        <li>
                            <a href="#">
                                <span class="green"><?= $count_comm_day; ?></span>
                                Новые комментарии
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="red"><?= $users_count_day; ?></span>
                                Новые регистрации
                            </a>
                        </li>
                        <li>
                            <a href="#">
                               <span class="blue"><?= $items_count_day; ?></span>
                                Новые Items
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="box col-md-4">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-list"></i> Weekly Stat</h2>
                </div>
                <div class="box-content">
                    <ul class="dashboard-list">
                        <li>
                            <a href="#">
                                <span class="green"><?= $count_comm_week; ?></span>
                                Новые комментарии
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="red"><?= $users_count_week; ?></span>
                                Новые регистрации
                            </a>
                        </li>
                        <li>
                            <a href="#">
                               <span class="blue"><?= $items_count_week; ?></span>
                                Новые Items
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="box col-md-4">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-list"></i> Monthly Stat</h2>
                </div>
                <div class="box-content">
                    <ul class="dashboard-list">
                        <li>
                            <a href="#">
                                <span class="green"><?= $count_comm_month; ?></span>
                                Новые комментарии
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="red"><?= $users_count_month; ?></span>
                                Новые регистрации
                            </a>
                        </li>
                        <li>
                            <a href="#">
                               <span class="blue"><?= $items_count_month; ?></span>
                                Новые Items
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>