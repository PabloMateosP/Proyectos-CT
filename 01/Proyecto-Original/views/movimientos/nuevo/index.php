<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Bootstrap -->
    <?php require_once("template/partials/head.php"); ?>
    <title>Nuevo Movimiento - GESBANK</title>
</head>

<body>
    <!-- Bootstrap -->
    <?php require_once "template/partials/menu.php"; ?>
    <!-- Capa principal -->
    <div class="container">
        <!-- Menú fijo principal -->
        <?php include "views/movimientos/partials/header.php" ?>
        <!-- Formulario -->
        <form action="<?= URL ?>movimientos/create" method="POST">
            <!-- Id_cuenta -->
            <div class="mb-3">
                <label for="id_cuenta" class="form-label">Cuenta</label>
                <select class="form-select" name="id_cuenta" id="id_cuenta">
                    <option selected disabled>Seleccione una cuenta </option>
                    <?php foreach ($this->cuentas as $cuenta): ?>
                        <option value="<?= $cuenta->id ?>">
                            <?= $cuenta->num_cuenta ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['id_cuenta'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['id_cuenta'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- Fecha_hora -->
            <div class="mb-3">
                <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                <input type="datetime-local" class="form-control" name="fecha_hora">
            </div>
            <!-- Concepto -->
            <div class="mb-3">
                <label for="concepto" class="form-label">Concepto</label>
                <input type="text" class="form-control" name="concepto" maxlength="50">
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['concepto'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['concepto'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- Tipo -->
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" name="tipo" id="tipo">
                    <option selected disabled>Seleccione el tipo </option>
                    <option value="I">Ingreso</option>
                    <option value="R">Reintegro</option>
                </select>
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['tipo'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['tipo'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- Cantidad -->
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" step="any" class="form-control" name="cantidad">
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['cantidad'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['cantidad'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- Botones de acción -->
            <div class="mb-3">
                <a name="" id="" class="btn btn-secondary" href="<?= URL ?>movimientos" role="button">Cancelar</a>
                <button type="reset" class="btn btn-danger">Borrar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </form>
    </div>

    <br>
    <br>
    <!-- Footer -->
    <?php require_once "template/partials/footer.php" ?>

    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>