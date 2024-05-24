<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL ?>workingHours">Working Hours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active <?= in_array($_SESSION['id_rol'], $GLOBALS['all']) ?: 'disabled' ?>"
                        href="<?= URL ?>workingHours/new">New</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active <?= in_array($_SESSION['id_rol'], $GLOBALS['all']) ?: 'disabled' ?>"
                        href="<?= URL ?>workingHours/export">Export CSV</a>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Export CSV
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<//?= URL ?>workingHours/export/1">Month</a></li>
                        <li><a class="dropdown-item" href="<//?= URL ?>workingHours/export/2">Year</a></li>
                        <li><a class="dropdown-item" href="<//?= URL ?>workingHours/export/3">Week</a></li>
                    </ul>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Order
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/order/4">Time Code</a></li>
                        <?php if (in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                            <li><a class="dropdown-item" href="<?= URL ?>workingHours/order/3">Employee</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/order/5">Project</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/order/6">Task</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/order/7">Date</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/order/8">Duration</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" method="get" action="<?= URL ?>workingHours/search">
                <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search"
                    name="expresion">
                <button
                    class="btn btn-outline-secondary <?= in_array($_SESSION['id_rol'], $GLOBALS['all']) ? 'null' : 'disabled' ?>"
                    type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>