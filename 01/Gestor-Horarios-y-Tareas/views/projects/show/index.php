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
                <?php require_once "views/projects/partials/header.php"; ?>
            </div>
            <div class="card-body">
                <!-- formulario  -->
                <form>

                    <!-- Project -->
                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <input type="text" class="form-control" id="project" name="project"
                            value="<?= $this->project_->project ?>" disabled>
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
                        <input type="text" step="any" class="form-control" id="description" name="description"
                            value="<?= $this->project_->description ?>" disabled>
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
                        <select class="form-select" name="id_project_manager" id="id_project_manager" disabled>
                            <option selected disabled>Select project manager</option>
                            <?php foreach ($this->projectManagers as $project_manager): ?>
                                <option value="<?= $project_manager->id ?>"
                                    <?= ($this->project_->id_projectManager == $project_manager->id) ? "selected" : null; ?>>
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
                        <select class="form-select" name="id_customer" id="id_customer" disabled>
                            <option selected disabled>Select project </option>
                            <?php foreach ($this->customers as $customer): ?>
                                <option value="<?= $customer->id ?>" <?= ($this->project_->id_customer == $customer->id) ? "selected" : null; ?>>
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

                    <!-- Employees -->
                    <div class="mb-3">
                        <label class="form-label">Employees</label>
                        <div class="row">
                            <?php $count = 0; ?>
                            <?php foreach ($this->employees as $employee_): ?>
                                <?php if ($count % 4 === 0 && $count !== 0): ?>
                                </div>
                                <div class="row">
                                <?php endif; ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="employees[]"
                                            value="<?= $employee_->id ?>" id="employee<?= $employee_->id ?>"
                                            <?= (in_array($employee_->id, $this->projectEmployees)) ? "checked" : null; ?> disabled>
                                        <label class="form-check-label" for="employee<?= $employee_->id ?>">
                                            <?= $employee_->employee ?>
                                        </label>
                                    </div>
                                </div>
                                <?php $count++; ?>
                            <?php endforeach; ?>
                        </div>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['employees'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['employees'] ?>
                            </span>
                        <?php endif; ?>
                    </div>


                    <!-- Finish Date -->
                    <div class="mb-3">
                        <label for="finish_date" class="form-label">Finish Date</label>
                        <input type="datetime-local" class="form-control" id="finish_date" name="finish_date"
                            value="<?= $this->project_->finish_date ?>" disabled>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['finish_date'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['finish_date'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>projects/" role="button">Cancel</a>
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