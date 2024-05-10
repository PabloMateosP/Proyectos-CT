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

                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="<?= URL ?>tasks/exportar">Exportar CSV</a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="#" data-bs-toggle="modal" data-bs-target="#importar">Importar CSV</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Ordenar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/4">Time Code</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/3">Nombre Empleado</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/5">Nombre Proyecto</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/6">Tarea</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/7">Orden de Trabajo</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/8">Fecha de Trabajo</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>tasks/order/9">Duración</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" method="get" action="<?= URL ?>tasks/buscar">
                <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search"
                    name="expresion">
                <button
                    class="btn btn-outline-secondary <?= in_array($_SESSION['id_rol'], $GLOBALS['organiser_employee']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager']) ? 'null' : 'disabled' ?>"
                    type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>