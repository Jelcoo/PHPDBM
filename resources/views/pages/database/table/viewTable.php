<nav style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="/database/<?php echo $databaseName; ?>"><?php echo $databaseName; ?></a></li>
        <li class="breadcrumb-item active"><?php echo $tableName; ?></li>
    </ol>
</nav>

<div class="d-flex align-items-center gap-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
    <select class="form-select w-auto" id="resultsSelector">
        <option value="10">10 Results</option>
        <option value="25">25 Results</option>
        <option value="50">50 Results</option>
        <option value="100">100 Results</option>
    </select>
    <a class="btn btn-primary" href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>/export" data-bs-toggle="tooltip" data-bs-title="Export table (SQL script)"><i class="fa-solid fa-file-export"></i></a>
    <a class="btn btn-primary" href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>/edit" data-bs-toggle="tooltip" data-bs-title="Edit table"><i class="fa-solid fa-pencil"></i></a>
    <a class="btn btn-primary" href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>/new" data-bs-toggle="tooltip" data-bs-title="Create row"><i class="fa-solid fa-plus"></i></a>
</div>

<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <?php if ($primaryKey) { ?>
                <th></th>
            <?php } ?>
            <?php foreach ($tableColumns as $column) { ?>
                <th scope="col"><?php echo $column['Field']; ?></th>
            <?php } ?>
        </thead>
        <tbody>
            <?php foreach ($tableRows['data'] as $row) { ?>
                <tr class="row-<?php echo isset($primaryKey) ? $row[$primaryKey] : 'unknown'; ?>">
                    <?php if ($primaryKey) { ?>
                        <td class="d-flex gap-2">
                            <a class="btn btn-primary btn-sm" href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>/edit/<?php echo $row[$primaryKey]; ?>" data-bs-toggle="tooltip" data-bs-title="Edit row"><i class="fa-solid fa-pencil"></i></a>
                            <a class="btn btn-danger btn-sm" href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>/delete/<?php echo $row[$primaryKey]; ?>" data-bs-toggle="tooltip" data-bs-title="Delete row"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    <?php } ?>
                    <?php foreach ($tableColumns as $column) { ?>
                        <?php $value = $row[$column['Field']]; ?>
                        <td class="align-middle text-truncate text-truncate-width <?php echo $value === null || empty($value) ? 'fst-italic text-danger-emphasis' : ''; ?> field-<?php echo $column['Field']; ?>" title="<?php echo htmlspecialchars($value); ?>"><?php echo ($value === null ? 'NULL' : empty($value)) ? 'EMPTY' : htmlspecialchars($value); ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            <?php if (count($tableRows['data']) === 0) { ?>
                <tr>
                    <td colspan="100%">No rows found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <p>Showing page <?php echo $tableRows['pages']['current']; ?> of <?php echo $tableRows['pages']['total']; ?></p>
    <nav>
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
