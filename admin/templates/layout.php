<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/admin/img/favicon.ico" rel="icon" >
    <link id="bs-css" href="/admin/css/bootstrap-cyborg.min.css" rel="stylesheet"><link href="/admin/css/charisma-app.css" rel="stylesheet">
    <link href='/admin/bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <script src="/js/jquery.min.js"></script>
    <script src='/admin/js/admin.js'></script>
    <?= $Js = $Js ?? "" ?>
</head>

<body class="home">
    <div class="loading-overlay" style="display: none;">
            <div class="overlay-content">
                <img src="/admin/img/ajax-loaders/ajax-loader-5.gif">
           </div>
        </div>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Переключатель</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/admin/index.php">
                <span>ADMINPANEL</span></a>
            <div class="btn-group pull-right">
            <?php if (isset($_SESSION['user'])&&$status==2): ?>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> <?= $username; ?></span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="/admin/logout.php">Выйти</a></li>
                </ul>
            
            <?php else : ?>

                <a href="login.php" class="btn btn-default">
                   <span class="">Войти</span>
                </a>

            <?php endif; ?>

            </div>
            <ul class="collapse navbar-collapse nav navbar-nav top-menu">
                <li><a href="/" target="_blank"><i class="glyphicon glyphicon-globe"></i> Visit Site</a></li>
            </ul>

        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">Главная</li>
                        <li <?= $active_main; ?>><a class="ajax-link" href="/admin/index.php"><i class="glyphicon glyphicon-home"></i><span> Отчеты</span></a>
                        </li>
                        <li <?= $active_items; ?>><a class="ajax-link" href="/admin/Item/item.php">
                        <i class="glyphicon glyphicon-align-justify"></i><span> Items</span></a></li>
                        <li <?= $active_users; ?>><a class="ajax-link" href="/admin/users/users.php"><i class="glyphicon glyphicon-user"></i><span> Users</span></a></li>
                        <li <?= $active_upload; ?>><a class="ajax-link" href="/admin/upload.php"><i class="glyphicon glyphicon-picture"></i><span> Загрузить файл(ы)</span></a></li>
                    </ul>
                </div>
            </div>
        </div>

    <div id="content" class="col-lg-10 col-sm-10">
        <?= $content; ?>
    </div>
</div>
    <hr>

    <footer class="row"></footer>
<div class="overlay">
    <div class="modal" id="modal1">
        <a class="close-modal" data-modal="#modal1" href="#"><i class="glyphicon glyphicon-remove"></i></a>
        <div class="modal__content"></div>
    </div>
</div>

</div>
<script src='/admin/js/jquery.dataTables.min.js'></script>
<script src="/admin/bower_components/responsive-tables/responsive-tables.js"></script>
<script src="/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
