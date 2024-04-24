<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Editar Cuenta - GESBANK</title>

</head>

<body>
    <!-- menu principal fijo superior -->
    <?php require_once "template/partials/menuAut.php"; ?>
    <!-- capa principal -->
    <div class="container" style="margin-top: 5%;">
        <!-- cabecera o título -->
        <?php include "views/workingHours/partials/header.php" ?>

        <form action="<?= URL ?>workingHours/update/<?= $this->id ?>" method="POST">

            <!-- Empleado -->
            <div class="mb-3" hidden>
                <label for="" class="form-label">Empleado</label>
                <span
                    class="form-control"><?= isset($this->employees->employee_) ? $this->employees->employee_ : '' ?></span>
                <?php if (isset($this->errores['id_employee'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['id_employee'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Time Code -->
            <div class="mb-3">
                <label for="" class="form-label">Time Code</label>
                <select class="form-select" name="id_cliente" id="">
                    <option selected disabled>Select a time code </option>
                    <?php foreach ($this->time_codes as $time_code): ?>
                        <option value="<?= $time_code->id ?>" <?= ($this->workingHours->id_time_code == $time_code->id) ? "selected" : null; ?>>
                            <?= $time_code->time_code_ ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($this->errores['id_time_code'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['id_time_code'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Work Order -->
            <div class="mb-3">
                <label for="work_order" class="form-label">Work Order</label>
                <select class="form-select" name="work_order" id="work_order">
                    <option selected disabled>Select a work order </option>
                    <?php foreach ($this->work_orders as $work_order_): ?>
                        <option value="<?= $work_order_->id ?>" <?= ($this->workingHours->id_work_order == $work_order_->id) ? "selected" : null; ?>>
                            <?= $work_order_->work_order ?> (<?= $work_order_->description ?>) - Work Order Manager:
                            <?= $work_order_->order_responsible ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($this->errores['id_time_code'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['id_time_code'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- saldo  -->
            <div class="mb-3">
                <label for="" class="form-label">Saldo</label>
                <input type="number" class="form-control" name="saldo" id="" step="0.01"
                    value="<?= $this->cuenta->saldo ?>">
                <?php if (isset($this->errores['saldo'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['saldo'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- botones de acción -->
            <div class="mb-3">
                <a name="" id="" class="btn btn-secondary" href="<?= URL ?>cuentas" role="button">Cancelar</a>
                <button type="button" class="btn btn-danger">Borrar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>

            </div>
        </form>
    </div>

    <br><br><br>

    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>


    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>