<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL ?>workingHours">Horas Trabajadas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['new'])): ?>
                        <a class="nav-link active" href="<?= URL ?>workingHours/new">New</a>
                    <?php else: ?>
                        <!-- No tienes los permisos necesarios para ver este enlace -->
                    <?php endif; ?>
                </li>

                <li class="nav-item">
                    <a class="nav-link active <?= in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['export']) ?: 'disabled' ?>"
                        href="<?= URL ?>workingHours/exportar">Exportar CSV</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active <?= in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['import']) ?: 'disabled' ?>"
                        href="#" data-bs-toggle="modal" data-bs-target="#importar">Importar CSV</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active <?= in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['export']) ?: 'disabled' ?>"
                        href="<?= URL ?>workingHours/pdf">Exportar PDF</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Ordenar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/ordenar/4">Time Code</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/ordenar/3">Nombre Empleado</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/ordenar/5">Nombre Proyecto</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/ordenar/6">Tarea</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/ordenar/7">Orden de Trabajo</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/ordenar/8">Fecha de Trabajo</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>workingHours/ordenar/9">Duración</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" method="get" action="<?= URL ?>workingHours/buscar">
                <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search"
                    name="expresion">
                <button
                    class="btn btn-outline-secondary <?= in_array($_SESSION['id_rol'], $GLOBALS['workingHours']['filter']) ? 'null' : 'disabled' ?>"
                    type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>