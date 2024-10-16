<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Add workingHour</title>
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
                <form action="<?= URL ?>workingHours/create" method="POST">
                    <!-- Time Code -->
                    <div class="mb-3">
                        <label for="id_time_code" class="form-label">Time Code</label>
                        <select class="form-select" name="id_time_code" id="id_time_code">
                            <option selected disabled>Select time code </option>
                            <?php foreach ($this->time_Codes as $time_Code): ?>
                                <option value="<?= $time_Code->id ?>">
                                    <?= $time_Code->time_code ?> (<?= $time_Code->description ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['id_time_code'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['id_time_code'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Project -->
                    <div class="mb-3">
                        <label for="id_project" class="form-label">Project</label>
                        <select class="form-select" name="id_project" id="id_project">
                            <option selected disabled>Select project </option>
                            <?php foreach ($this->projects as $project_): ?>
                                <option value="<?= $project_->id ?>">
                                    <?= $project_->project ?> (<?= $project_->description ?>) - Project Manager:
                                    <?= $project_->manager_name ?>, <?= $project_->manager_last_name ?>
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

                    <!-- Task -->
                    <div class="mb-3">
                        <label for="id_task" class="form-label">Task</label>
                        <select class="form-select" name="id_task" id="id_task">
                            <option selected disabled>Select project </option>
                            <?php foreach ($this->tasks as $task_): ?>
                                <option value="<?= $task_->id ?>">
                                    <?= $task_->task ?> (<?= $task_->description ?>) - Project: <?= $task_->project ?> (<?= $task_->project_description ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['id_task'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['id_task'] ?>
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

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="number" class="form-control" id="duration" name="duration" max="10">
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['duration'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['duration'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Date Worked -->
                    <div class="mb-3">
                        <label for="date_worked" class="form-label">Date Worked</label>
                        <input type="datetime-local" class="form-control" id="date_worked" name="date_worked">
                        <!-- Show possible error -->
                        <?php if (isset($this->errores['date_worked'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['date_worked'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>workingHours/" role="button">Cancel</a>
                        <button type="reset" class="btn btn-danger">Clear</button>
                        <button type="submit" class="btn btn-primary">Create</button>
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