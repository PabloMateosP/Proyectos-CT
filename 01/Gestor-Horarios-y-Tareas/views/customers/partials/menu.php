<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL ?>customers">Customers</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active"
                        href="<?= URL ?>customers/new">New</a>
                </li>
            </ul>
            <form class="d-flex" method="get" action="<?= URL ?>customers/buscar">
                <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search"
                    name="expresion">
                <button
                    class="btn btn-outline-secondary <?= in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']) ? 'null' : 'disabled' ?>"
                    type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>