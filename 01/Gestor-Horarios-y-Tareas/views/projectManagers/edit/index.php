<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Edit Project Manager</title>
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
                <form action="<?= URL ?>projectManagers/update/<?= $this->id ?>" method="POST">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" step="any" class="form-control" id="name" name="name"
                            value="<?= $this->projectManager->name ?>">
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['name'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['name'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- last_name -->
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="<?= $this->projectManager->last_name ?>">
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['last_name'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['last_name'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Projects</label>
                        <div class="row">
                            <?php $count = 0; ?>
                            <?php foreach ($this->projects as $project_): ?>
                                <?php if ($count % 4 === 0 && $count !== 0): ?>
                                </div>
                                <div class="row">
                                <?php endif; ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <?php
                                        // Verificar si el proyecto tiene un project manager asociado
                                        $isDisabled = ($project_->id_projectManager === null || $project_->id_projectManager === 0 || $project_->id_projectManager
                                         == $this->projectManager->id) ? '' : 'disabled';
                                        // Verificar si el proyecto tiene un project manager y establecer el estado 'checked' en consecuencia
                                        $isChecked = ($project_->id_projectManager !== null && $project_->id_projectManager !== 0) ? 'checked' : '';
                                        ?>
                                        <input class="form-check-input" type="checkbox" name="projects[]"
                                            value="<?= $project_->id ?>" id="projects<?= $project_->id ?>" <?= $isDisabled ?>
                                            <?= $isChecked ?>>
                                        <label class="form-check-label" for="projects<?= $project_->id ?>">
                                            <?= $project_->project ?>
                                        </label>
                                    </div>
                                </div>
                                <?php $count++; ?>
                            <?php endforeach; ?>
                        </div>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['projects'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['projects'] ?>
                            </span>
                        <?php endif; ?>
                    </div>


                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>projectManagers/" role="button">Cancel</a>
                        <button type="reset" class="btn btn-danger">Clear</button>
                        <button type="submit" class="btn btn-primary">Update</button>
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