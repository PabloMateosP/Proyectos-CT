<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Add Project - Gesbank</title>
</head>

<body>
    <!-- Top fixed menu -->
    <?php require_once "template/partials/menuAut.php"; ?>
    <!-- capa principal -->
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- card that contains the form -->
        <div class="card">
            <div class="card-header">
                <?php require_once "views/workingHours/partials/header.php"; ?>
            </div>
            <div class="card-body">
                <!-- formulario  -->
                <form action="<?= URL ?>projects/create" method="POST">

                    <!-- Project -->
                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <input type="text" class="form-control" id="project" name="project">
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['project'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['project'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" step="any" class="form-control" id="description" name="description">
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['description'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['description'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Project Manager -->
                    <div class="mb-3">
                        <label for="id_project_manager" class="form-label">Project Manager</label>
                        <select class="form-select" name="id_project_manager" id="id_project_manager">
                            <option selected disabled>Select project manager</option>
                            <?php foreach ($this->project_managers as $project_manager): ?>
                                <option value="<?= $project_manager->id ?>">
                                    <?= $project_manager->name ?>, <?= $project_manager->last_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['id_project_manager'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['id_project_manager'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Customer -->
                    <div class="mb-3">
                        <label for="id_customer" class="form-label">Customer</label>
                        <select class="form-select" name="id_customer" id="id_customer">
                            <option selected disabled>Select project </option>
                            <?php foreach ($this->customers as $customer): ?>
                                <option value="<?= $customer->id ?>">
                                    <?= $customer->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['id_customer'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['id_customer'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Finish Date -->
                    <div class="mb-3">
                        <label for="finish_date" class="form-label">Finish Date</label>
                        <input type="datetime-local" class="form-control" id="finish_date" name="finish_date">
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['finish_date'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['finish_date'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>projects/" role="button">Cancelar</a>
                        <button type="reset" class="btn btn-danger">Borrar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>

    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>