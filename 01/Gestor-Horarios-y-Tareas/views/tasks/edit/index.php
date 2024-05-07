<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Add task - Gesbank</title>
</head>

<body>
    <!-- Top fixed menu -->
    <?php require_once "template/partials/menuAut.php"; ?>
    <!-- capa principal -->
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- card that contains the form -->
        <div class="card">
            <div class="card-header">
                <?php require_once "views/tasks/partials/header.php"; ?>
            </div>
            <div class="card-body">
                <!-- formulario  -->
                <form action="<?= URL ?>tasks/update/<?= $this->id ?>" method="POST">

                    <!-- task -->
                    <div class="mb-3">
                        <label for="task" class="form-label">Task</label>
                        <input type="text" class="form-control" name="task" value="<?= $this->task->task ?>">
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['task'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['task'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" name="description"
                            value="<?= $this->task->description ?>">
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['description'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['description'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- id_project -->
                    <div class="mb-3">
                        <label for="id_project" class="form-label">Project</label>
                        <select class="form-select" name="id_project" id="id_project">
                            <option selected disabled>Select Project</option>
                            <?php foreach ($this->projects as $project): ?>
                                <option value="<?= $project->id ?>" <?= ($this->task->id_project == $project->id) ? "selected" : null; ?>>
                                    <?= $project->project ?> (<?= $project->description ?>) - Project Manager:
                                    <?= $project-> manager_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['id_project'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['id_project'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>tasks/" role="button">Cancelar</a>
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