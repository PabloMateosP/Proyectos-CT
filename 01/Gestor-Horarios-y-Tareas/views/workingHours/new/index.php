<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Add workingHour - Gesbank</title>
</head>

<body>
    <!-- Top fixed menu -->
    <?php require_once "template/partials/menuAut.php"; ?>
    <!-- capa principal -->
    <div class="container" style="margin-top: 5%;">
        <!-- card that contains the form -->
        <div class="card">
            <div class="card-header">
                <?php require_once "views/workingHours/partials/header.php"; ?>
            </div>
            <div class="card-body">
                <!-- formulario  -->
                <form action="<?= URL ?>workingHours/create" method="POST">
                    <!-- name -->
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?= $this->workingHour->id ?>">
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
                            value="<?= $this->workingHour->last_name ?>">
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
                        <input type="text" class="form-control" name="city" value="<?= $this->workingHour->city ?>">
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
                        <input type="email" class="form-control" name="email" value="<?= $this->workingHour->email ?>">
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
                        <input type="number" class="form-control" name="phone" value="<?= $this->workingHour->phone ?>">
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
                        <input type="text" class="form-control" name="dni" value="<?= $this->workingHour->dni ?>">
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['dni'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['dni'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Total_hours -->
                    <div class="mb-3">
                        <label for="" class="form-label">Total Hours</label>
                        <input type="text" class="form-control" name="total_hours"
                            value="<?= $this->workingHour->total_hours ?>">
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['total_hours'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['total_hours'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- ID_user -->
                    <div class="mb-3">
                        <label for="" class="form-label">Id User</label>
                        <input type="text" class="form-control" name="id_user"
                            value="<?= $this->workingHour->id_user ?>">
                        <!-- Mostrar posible error -->
                        <?php if (isset($this->errores['id_user'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['id_user'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>workingHours" role="button">Cancelar</a>
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