<!-- Navigation -->

<nav class="navbar fixed-top navbar-expand-lg navbar-dark" style="background-color: #f2a900;">
  <div class="container-fluid">
    <a class="navbar-brand" href="workingHours/"><strong>WORKING HOURS</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
      aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])): ?>
            <a class="nav-link active" href="<?= URL ?>employees/">Employees</a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['all'])): ?>
            <a class="nav-link active" href="<?= URL ?>workingHours/">Working Hours</a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
            <a class="nav-link active" href="<?= URL ?>projects/">Projects</a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
            <a class="nav-link active" href="<?= URL ?>tasks/">Tasks</a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
            <a class="nav-link active" href="<?= URL ?>projectManagers/">Project Manager</a>
          <?php endif; ?>
        </li>

        <li class="nav-item">
          <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin'])): ?>
            <a class="nav-link active" href="<?= URL ?>users/">Users</a>
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