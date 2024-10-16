<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Add employee - Gesbank</title>
</head>

<body>
    <!-- Top fixed menu -->
    <?php require_once "template/partials/menuAut.php"; ?>
    <!-- capa principal -->
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- card that contains the form -->
        <div class="card">
            <div class="card-header">
                <?php require_once "views/employees/partials/header.php"; ?>
            </div>
            <div class="card-body">
                <!-- formulario  -->
                <form>
                    <!-- name -->
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?= $this->employee->name ?>"
                            disabled>
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['name'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['name'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- last_name -->
                    <div class="mb-3">
                        <label for="" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name"
                            value="<?= $this->employee->last_name ?>" disabled>
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['last_name'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['last_name'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- city -->
                    <div class="mb-3">
                        <label for="" class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="<?= $this->employee->city ?>"
                            disabled>
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['city'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['city'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- email -->
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $this->employee->email ?>"
                            disabled>
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['email'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['email'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- phone -->
                    <div class="mb-3">
                        <label for="" class="form-label">Phone</label>
                        <input type="number" class="form-control" name="phone" value="<?= $this->employee->phone ?>"
                            disabled>
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['phone'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['phone'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- dni -->
                    <div class="mb-3">
                        <label for="" class="form-label">DNI</label>
                        <input type="text" class="form-control" name="dni" value="<?= $this->employee->dni ?>" disabled>
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['dni'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['dni'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Projects -->
                    <div class="mb-3">
                        <label class="form-label">Project</label>
                        <div class="row">
                            <?php $count = 0; ?>
                            <?php foreach ($this->projects as $project_): ?>
                                <?php if ($count % 4 === 0 && $count !== 0): ?>
                                </div>
                                <div class="row">
                                <?php endif; ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="projects[]"
                                            value="<?= $project_->id ?>" id="employee<?= $project_->id ?>"
                                            <?= (in_array($project_->id, $this->projectEmployees)) ? "checked" : null; ?>
                                            disabled>
                                        <label class="form-check-label" for="employee<?= $project_->id ?>">
                                            <?= $project_->project ?>
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

                    <!-- Total_hours -->
                    <div class="mb-3">
                        <label for="" class="form-label">Total Hours</label>
                        <input type="text" class="form-control" name="total_hours"
                            value="<?= $this->employee->total_hours ?>" disabled>
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['total_hours'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['total_hours'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>employees/" role="button">Cancelar</a>
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