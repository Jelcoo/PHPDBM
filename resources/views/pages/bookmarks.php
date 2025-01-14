<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/run">Run SQL</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/users">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/connections">Connections</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/bookmarks">Bookmarks</a>
    </li>
</ul>

<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Query</th>
            </tr>
        </thead>
        <tbody id="bookmarksTable">
        </tbody>
    </table>
</div>

<div class="d-flex align-items-center gap-2 mt-2">
    <button class="btn btn-primary" id="createBookmark" data-bs-toggle="modal" data-bs-target="#createModal">Create new</button>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Create Bookmark</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createBookmarkForm">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="name" placeholder="Name" />
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating mt-2">
                        <p class="form-label">Query</p>
                        <div id="editor" style="height: 600px" name="query"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createBookmarkButton">Create</button>
            </div>
        </div>
    </div>
</div>

<?php require(__DIR__ . '/../templates/monaco.php'); ?>

<script>
    const storedBookmarks = localStorage.getItem("bookmarks");
    if (!storedBookmarks || storedBookmarks.length == 0) {
        document.getElementById("bookmarksTable").innerHTML = "<tr><td colspan='3'>No bookmarks</td></tr>";
    } else {
        const bookmarks = JSON.parse(storedBookmarks);
        const table = document.getElementById("bookmarksTable");
        for (const bookmark of bookmarks) {
            table.appendChild(bookmarkToRow(bookmark));
        }
    }

    const createBookmarkButton = document.getElementById("createBookmarkButton");
    createBookmarkButton.addEventListener("click", () => {
        const table = document.getElementById("bookmarksTable");
        let bookmarks = JSON.parse(localStorage.getItem("bookmarks"));
        if (!bookmarks) {
            bookmarks = [];
        }

        const name = document.getElementById("createBookmarkForm").name.value;
        const query = window.editor.getValue();

        if (!name) {
            alert("Please enter a name");
            return;
        }

        if (!query) {
            alert("Please enter a query");
            return;
        }

        if (bookmarks.filter(b => b.name == name).length > 0) {
            alert("Bookmark name already exists");
            return;
        }

        if (query.length > 2500) {
            alert("Query is too long. Please use 2500 chars max.");
            return;
        }

        if (!storedBookmarks || storedBookmarks.length == 0) {
            table.innerHTML = "";
        }
        table.appendChild(bookmarkToRow({
            name: name,
            query: query
        }));
        bookmarks.push({
            name: name,
            query: query
        });
        localStorage.setItem("bookmarks", JSON.stringify(bookmarks));
    });

    function bookmarkToRow(bookmark) {
        const row = document.createElement("tr");
        const fullQuery = bookmark.query;
        const displayQuery = fullQuery.length > 100 ? fullQuery.substring(0, 100) + "..." : fullQuery.replace(/\n/g, "<br>");
        row.innerHTML = `
            <td>
                <button class="deleteBookmark btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Delete bookmark"><i class="fa-solid fa-trash"></i></button>
                <button class="copyBookmark btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="Copy bookmark"><i class="fa-solid fa-copy"></i></button>
            </td>
            <td>${bookmark.name}</td>
            <td title="${fullQuery}">${displayQuery}</td>
        `;
        row.querySelector(".deleteBookmark").addEventListener("click", () => {
            const table = document.getElementById("bookmarksTable");
            table.removeChild(row);
            let bookmarks = JSON.parse(localStorage.getItem("bookmarks"));
            if (!bookmarks) {
                bookmarks = [];
            }
            bookmarks = bookmarks.filter(b => b.name != bookmark.name);
            localStorage.setItem("bookmarks", JSON.stringify(bookmarks));
        });
        row.querySelector(".copyBookmark").addEventListener("click", () => {
            const textarea = document.createElement("textarea");
            textarea.value = fullQuery;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            Swal.fire({
                icon: 'success',
                title: 'Copied to clipboard',
                html: '<code>' + displayQuery + '</code>',
                showConfirmButton: false,
                timer: 1500
            });
        });
        return row;
    }
</script>
