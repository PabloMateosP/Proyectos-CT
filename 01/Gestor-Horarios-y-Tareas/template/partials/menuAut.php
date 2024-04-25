<!-- Navigation -->

<nav class="navbar fixed-top navbar-expand-lg navbar-dark" style="background-color: #f2a900;">
  <div class="container-fluid">
    <img src="../img/logo.png" alt="Logo" width="50" height="50" style="background-color: white; margin-right: 5px;">
    <a class="navbar-brand" href="#"><strong>WORKING HOURS</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
      aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_organiser'])): ?>
            <a class="nav-link active" href="<?= URL ?>employees/">Empleados</a>
          <?php else: ?>
            <!-- No permitido -->
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin'])): ?>
            <!-- No permitido -->
          <?php else: ?>
            <a class="nav-link active" href="<?= URL ?>workingHours/">Horas Laborales</a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin'])): ?>
            <a class="nav-link active" href="<?= URL ?>users/">Users</a>
          <?php else: ?>
            <!-- No permitido -->
          <?php endif; ?>
        </li>
      </ul>
      <div class="d-flex">
        <div class="collapse navbar-collapse" id="exCollapsingNavbar">
          <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
            <li class="nav-item"><a href="<?= URL ?>perfil" class="nav-link active"><?= $_SESSION['name_user'] ?> |</a>
            </li>
            <li class="nav-item"><a href="<?= URL ?>logout" class="nav-link active">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>