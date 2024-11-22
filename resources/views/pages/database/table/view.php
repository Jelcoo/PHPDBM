<h1><?= $databaseName ?> - <?= $tableName ?></h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <?php foreach ($tableColumns as $column): ?>
                <th scope="col"><?= $column['Field'] ?></th>
            <?php endforeach; ?>
        </thead>
        <tbody>
            <?php foreach ($tableRows['data'] as $row): ?>
                <tr>
                    <?php foreach ($tableColumns as $column): ?>
                        <td class="text-truncate"><?= $row[$column['Field']] ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<nav aria-label="Table navigation">
    <ul class="pagination justify-content-end">
        <li class="page-item">
            <a id="previous" class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        <?php foreach (array_reverse($tableRows['pages']['previous']) as $i): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endforeach; ?>
        <li class="page-item"><a class="page-link" href="?page=<?= $tableRows['pages']['current'] ?>"><?= $tableRows['pages']['current'] ?></a></li>
        <?php foreach ($tableRows['pages']['next'] as $i): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endforeach; ?>
        <li class="page-item">
            <a id="next" class="page-link" href="#">Next</a>
        </li>
    </ul>
</nav>

<script>
    const lastPage = <?= $tableRows['pages']['total'] ?>;
    const currentPage = <?= $tableRows['pages']['current'] ?>;

    const previousButton = document.getElementById('previous');
    const nextButton = document.getElementById('next');
    const activePageButton = document.querySelectorAll('.page-item').entries().find(page => page[1].querySelector('.page-link').textContent === currentPage.toString());
    if (activePageButton && activePageButton[1]) {
        activePageButton[1].classList.add('active');
    }

    previousButton.addEventListener('click', () => {
        if (currentPage > 1) {
            window.location.href = `?page=${currentPage - 1}`;
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentPage < lastPage) {
            window.location.href = `?page=${currentPage + 1}`;
        }
    });

    if (currentPage === lastPage) {
        nextButton.classList.add('disabled');
    }
    if (currentPage === 1) {
        previousButton.classList.add('disabled');
    }
</script>
