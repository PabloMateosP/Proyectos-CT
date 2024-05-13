<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>

    <title>User Edit</title>
</head>

<body>
    <?php require_once "template/partials/menuAut.php"; ?>
    <div class="container" style="margin-top: 5%;">
        <div class="card">
            <div class="card-header">
                <?php include "views/users/partials/header.php" ?>
            </div>
            <div class="card-body">

                <form action="<?= URL ?>users/update/<?= $this->id ?>" method="POST">
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?= $this->user->name ?>">
                        <?php if (isset($this->errores['name'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['name'] ?>
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
                        <label for="" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password"
                            placeholder="Add a new password">
                        <?php if (isset($this->errores['password'])): ?>
                    <span class=" form-text text-danger" role="alert">
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
        </div>
    </div>

    <br><br><br>

    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>

    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>