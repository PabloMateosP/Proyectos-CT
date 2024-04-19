<!DOCTYPE html>
<html lang="es">

<head>
    <!-- bootstrap  -->
    <?php require_once("template/partials/head.php");  ?>

    <title>Editar Usuario · Gesbank</title>
</head>

<body>
    <!-- menú principal -->
    <?php require_once "template/partials/menu.php"; ?>
    <!-- capa principal -->
    <div class="container">
        <!-- cabecera  -->
        <?php include "views/users/partials/header.php" ?>
        <!-- Formulario -->
        <form action="<?= URL ?>users/update/<?= $this->id ?>" method="POST">
            <!-- nombre -->
            <div class="mb-3">
                <label for="" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="name" value="<?= $this->user->name ?>">
                <!-- Mostrar posible error -->
                <?php if (isset($this->errores['nombre'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['nombre'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- email -->
            <div class="mb-3">
                <label for="" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="" value="<?= $this->user->email ?>">
                <?php if (isset($this->errores['email'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['email'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- role -->
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" name="rol">
                    <?php foreach ($this->roles as $rol): ?>
                        <option value="<?= $rol->id ?>" <?= ($rol) ? 'selected' : '' ?>>
                            <?= $rol->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- password -->
            <div class="mb-3">
                <label for="" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="Añada una nueva contraseña"">
                <?php if (isset($this->errores['password'])): ?>
                    <span class="form-text text-danger" role="alert">
                        <?= $this->errores['password'] ?>
                    </span>
                <?php endif; ?>
            </div>
            
            
            <!-- botones de acción -->
            <div class="mb-3">
                <a name="" id="" class="btn btn-secondary" href="<?= URL ?>users" role="button">Cancelar</a>
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