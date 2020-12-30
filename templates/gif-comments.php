<?php if ($comments != NULL): ?>
    
       <?php foreach($comments as $comment) : ?>
            <?php
            $username =  trim(strval($comment['name']));
            $server_user_name = trim(strval($_SESSION['user']['name']));
            $inlineEdit = (isset($_SESSION['user']) && $username == $server_user_name) ? 'inlineEdit' :  '';
            ?>
            <article class="comment">
                <img class="comment__picture" src="<?= $comment['avatar_path']; ?>" alt="" width="100" height="100">
                <div class="comment__data">
                    <div class="comment__author"><?= $comment['name']; ?></div>
                    <div class="comment__author">[<?= $comment['dt_add']; ?>]</div>
                    <p class="comment__text <?= $inlineEdit; ?>" data-id="<?= $comment['id']; ?>"><?= $comment['comment_text']; ?></p>

                    <?php if ($comment['name'] == $_SESSION['user']['name']): ?>
                            <span class="comment__author comment__sign"><img class="comment__edit" src="img/pen.png">Нажмите на свой комментарий, чтобы отредактировать</span>
                    <?php endif; ?>
                    
                </div>
            </article>
        <?php endforeach; ?>
<?php endif; ?>