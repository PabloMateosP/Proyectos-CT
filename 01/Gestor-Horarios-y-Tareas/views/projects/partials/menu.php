<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL ?>projects">Projects</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                    <a class="nav-link active <?= in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp']) ?: 'disabled' ?>"
                        href="<?= URL ?>projects/new">New</a>
                <?php endif; ?>    
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Order
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>projects/order/1/">Project</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>projects/order/2/">Description</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>projects/order/3/">Project Manager</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>projects/order/4/">Customer</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>projects/order/5/">Finish Date</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" method="get" action="<?= URL ?>projects/search">
                <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search"
                    name="expresion">
                <button
                    class="btn btn-outline-secondary"
                    type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>