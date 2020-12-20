<main class="content">
    <div class="content__main-col">
        <div class="loading-overlay" style="display: none;">
            <div class="overlay-content">Loading...</div>
        </div>
        <header class="content__header content__header--left-pad">
            <h2 class="content__header-text"><?= $title; ?></h2>
            <a class="button button--transparent content__header-button" href="/">Назад</a>
        </header>
        <!-- Блок для вывода сообщений -->
        <div class="block_for_messages">
        <?php
        //Если в сессии существуют сообщения об ошибках, то выводим их
        if(isset($_SESSION["error_messages"]) && !empty($_SESSION["error_messages"])){
            echo $_SESSION["error_messages"];

            //Уничтожаем ячейку error_messages, чтобы сообщения об ошибках не появились заново при обновлении страницы
            unset($_SESSION["error_messages"]);
        }

        //Если в сессии существуют радостные сообщения, то выводим их
        if(isset($_SESSION["success_messages"]) && !empty($_SESSION["success_messages"])){
            echo $_SESSION["success_messages"];
            
            //Уничтожаем ячейку success_messages,  чтобы сообщения не появились заново при обновлении страницы
            unset($_SESSION["success_messages"]);
        }
        ?>
        </div>
    </div>
</main>
