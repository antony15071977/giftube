<?php if ($pages_count > 1): ?>
<div class="pagination">
    <ul class="pagination__control">
        <?php foreach ($pages as $page): ?>
            <li class="pagination__item <?= ($page == $current_page) ? "pagination__item--active" : ""; ?>">
                <a href="<?= $_SERVER['SCRIPT_NAME']; ?>?<?= $param; ?>page=<?= $page; ?>"><?= $page; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>

    <ul class="pagination__control">
        <li class="pagination__item">
            <a <?php if ($current_page > 1): ?>
                    href="<?= $_SERVER['SCRIPT_NAME']; ?>?<?= $param; ?>page=<?= $current_page - 1; ?>"
                <?php endif; ?>>◀</a>
        </li>
        <li class="pagination__item">
            <a <?php if ($current_page < count($pages)): ?> href="<?= $_SERVER['SCRIPT_NAME']; ?>?<?= $param; ?>page=<?= $current_page + 1; ?>"
                <?php endif; ?>>▶</a>
        </li>
    </ul>
</div>
<?php endif; ?>