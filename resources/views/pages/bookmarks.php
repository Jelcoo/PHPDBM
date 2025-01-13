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
    <button class="btn btn-primary" id="createBookmark">Create new</button>
</div>

<script>
    const storedBookmarks = localStorage.getItem("bookmarks");
    if (!storedBookmarks || storedBookmarks.length == 0) {
        document.getElementById("bookmarksTable").innerHTML = "<tr><td colspan='3'>No bookmarks</td></tr>";
    } else {
        document.getElementById("bookmarksTable").innerHTML = storedBookmarks;
    }

    const createBookmark = document.getElementById("createBookmark");
    createBookmark.addEventListener("click", () => {
        const name = prompt("Name");
        const query = prompt("Query");
        if (name && query) {
            const table = document.getElementById("bookmarksTable");
            const row = document.createElement("tr");
            row.innerHTML = `
                <td><button class="btn btn-danger" onclick="deleteBookmark(this)">Delete</button></td>
                <td>${name}</td>
                <td>${query}</td>
            `;
            table.appendChild(row);
            localStorage.setItem("bookmarks", table.innerHTML);
        }
    });
</script>
