<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL ?>tasks">Horas Trabajadas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active <?= in_array($_SESSION['id_rol'], $GLOBALS['all']) ?: 'disabled' ?>"
                        href="<?= URL ?>tasks/new">New</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Ordenar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/2">Task</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/3">Description</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/4">Project</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/5">Project Description</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/6">Creation Date</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>