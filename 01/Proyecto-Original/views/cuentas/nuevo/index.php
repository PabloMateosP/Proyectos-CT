<!DOCTYPE html>
<html lang="es">

<head>
    <!-- bootstrap  -->
    <?php require_once("template/partials/head.php");  ?>
    <title>Nueva Cuenta - GESBANK</title>
</head>

<body>
    <!-- bootstrap -->
    <?php require_once "template/partials/menu.php"; ?>
    <!-- capa principal -->
    <div class="container">
        <!-- Menú fijo principal -->
        <?php include "views/clientes/partials/header.php" ?>
        <!-- formulario -->
        <form action="<?= URL ?>cuentas/create" method="POST">
            <!-- Cuenta -->
            <div class="mb-3">
                <label for="" class="form-label">Numero de cuenta</label>
                <input type="text" class="form-control" name="num_cuenta" minlength="20" maxlength="20">
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['num_cuenta'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['num_cuenta'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- Cliente -->
            <div class="mb-3">
                <label for="" class="form-label">Cliente</label>
                <select class="form-select" name="id_cliente" id="">
                    <option selected disabled>Seleccione un cliente </option>
                    <?php foreach ($this->cuentas as  $cliente) : ?>
                        <!-- <div class="form-check"> -->
                            <option value="<?= $cliente->id ?>">
                                <?= $cliente->cliente ?>
                            </option>
                        <!-- </div> -->
                    <?php endforeach; ?>
                </select>
                <?php if (isset($this->errores['id_cliente'])): ?>
                        <span class="form-text text-danger" role="alert">
                            <?= $this->errores['id_cliente'] ?>
                        </span>
                    <?php endif; ?>
            </div>
            <!-- Fecha -->
            <div class="mb-3">
                <label for="" class="form-label">Fecha alta</label>
                <input type="datetime-local" class="form-control" name="fecha_alta">
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['fecha_alta'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['fecha_alta'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- Saldo -->
            <div class="mb-3">
                <label for="" class="form-label">Saldo</label>
                <input type="text" class="form-control" name="saldo" id="" placeholder="0">
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['saldo'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['saldo'] ?>
                    </span>
                <?php endif; ?>
            </div>
            <!-- botones de acción -->
            <div class="mb-3">
                <a name="" id="" class="btn btn-secondary" href="<?= URL ?>cuentas" role="button">Cancelar</a>
                <button type="reset" class="btn btn-danger">Borrar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
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