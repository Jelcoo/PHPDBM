<h1><a href="/database/<?php echo $databaseName; ?>"><?php echo $databaseName; ?></a> - <?php echo $tableName; ?></h1>
<div class="d-flex align-items-center gap-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
    <select class="form-select w-auto" id="resultsSelector">
        <option value="10">10 Results</option>
        <option value="25">25 Results</option>
        <option value="50">50 Results</option>
        <option value="100">100 Results</option>
    </select>
</div>
<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <th></th>
            <?php foreach ($tableColumns as $column) { ?>
                <th scope="col"><?php echo $column['Field']; ?></th>
            <?php } ?>
        </thead>
        <tbody>
            <?php foreach ($tableRows['data'] as $row) { ?>
                <tr>
                    <td>
                        <button class="btn btn-primary btn-sm"><i class="fa-solid fa-pencil"></i></button>
                    </td>
                    <?php foreach ($tableColumns as $column) { ?>
                        <td class="align-middle text-truncate text-truncate-width field-<?php echo $column['Field']; ?>"><?php echo $row[$column['Field']]; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <p>Showing page <?php echo $tableRows['pages']['current']; ?> of <?php echo $tableRows['pages']['total']; ?></p>
    <nav aria-label="Table navigation">
        <ul class="pagination justify-content-end">
            <li class="page-item">
                <button id="previous" class="page-link" tabindex="-1">Previous</button>
            </li>
            <?php foreach (array_reverse($tableRows['pages']['previous']) as $i) { ?>
                <li class="page-item"><button class="page-link specific-page" id="page<?php echo $i; ?>"><?php echo $i; ?></button></li>
            <?php } ?>
            <li class="page-item active"><button class="page-link specific-page" id="page<?php echo $tableRows['pages']['current']; ?>"><?php echo $tableRows['pages']['current']; ?></button></li>
            <?php foreach ($tableRows['pages']['next'] as $i) { ?>
                <li class="page-item"><button class="page-link specific-page" id="page<?php echo $i; ?>"><?php echo $i; ?></button></li>
            <?php } ?>
            <li class="page-item">
                <button id="next" class="page-link">Next</button>
            </li>
        </ul>
    </nav>
</div>

<style>
    .text-truncate-width {
        max-width: 150px;
    }
</style>

<script>
    const lastPage = <?php echo $tableRows['pages']['total']; ?>;
    const currentPage = <?php echo $tableRows['pages']['current']; ?>;

    const previousButton = document.getElementById('previous');
    const nextButton = document.getElementById('next');
    const pageItemLinks = document.querySelectorAll('.specific-page');

    pageItemLinks.forEach((link) => {
        link.addEventListener('click', () => {
            setUrlQuery(['page',  link.id.replace('page', '')]);
        });
    });

    previousButton.addEventListener('click', () => {
        if (currentPage > 1) {
            setUrlQuery(['page',  currentPage - 1]);
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentPage < lastPage) {
            setUrlQuery(['page',  currentPage + 1]);
        }
    });

    if (currentPage === lastPage) {
        nextButton.classList.add('disabled');
    }
    if (currentPage === 1) {
        previousButton.classList.add('disabled');
    }

    const resultsSelector = document.getElementById('resultsSelector');
    resultsSelector.value = '<?php echo $tableRows['pages']['perPage']; ?>';
    resultsSelector.addEventListener('change', () => {
        setUrlQuery(['page',  1], ['size', resultsSelector.value]);
    });

    const searchInput = document.getElementById('search');
    searchInput.value = '<?php echo $_GET['search'] ?? ''; ?>';
    searchInput.addEventListener('input', debounce(() => {
        setUrlQuery(['page',  1], ['search', searchInput.value]);
    }, 500));

    const table = document.querySelector('table');
    table.addEventListener('dblclick', (event) => {
        if (event.target.tagName === 'TD') {
            const fieldClass = Array.from(event.target.classList).filter((className) => className.startsWith('field-'))[0];
            Array.from(document.getElementsByClassName(fieldClass)).forEach((match) => {
                match.classList.toggle('text-truncate-width');
            });
        }
    });
</script>
