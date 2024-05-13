<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>New User</title>
</head>

<body>

    <?php require_once "template/partials/menuAut.php"; ?>

    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">

        <div class="card">
            <div class="card-header">
                <?php include "views/users/partials/header.php" ?>
            </div>

            <div class="card-body">

                <?php include 'template/partials/error.php' ?>

                <form action="<?= URL ?>users/create" method="POST">

                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text"
                            class="form-control <?= (isset($this->errores['name'])) ? 'is-invalid' : null ?>"
                            name="name" value="<?= $this->usuario->name ?>">

                        <?php if (isset($this->errores['name'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['name'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email"
                            class="form-control <?= (isset($this->errores['email'])) ? 'is-invalid' : null ?>"
                            name="email" value="<?= $this->usuario->email ?>">

                        <?php if (isset($this->errores['email'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['email'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Roles</label>
                        <select class="form-select <?= (isset($this->errores['roles'])) ? 'is-invalid' : null ?>"
                            name="roles">
                            <option selected disabled>Select rol </option>
                            <?php foreach ($this->roles as $rol): ?>
                                <div class="form-check">
                                    <?php
                                    $selected = isset($this->rolSeleccionado) && $rol->id == $this->rolSeleccionado ? 'selected' : '';
                                    ?>
                                    <option value="<?= $rol->id ?>" <?= $selected ?>>
                                        <?= $rol->name ?>
                                    </option>
                                </div>
                            <?php endforeach; ?>
                        </select>

                        <?php if (isset($this->errores['roles'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['roles'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Password</label>
                        <input type="password"
                            class="form-control <?= (isset($this->errores['password'])) ? 'is-invalid' : null ?>"
                            name="password">

                        <?php if (isset($this->errores['password'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['password'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Password Confirm</label>
                        <input type="password"
                            class="form-control <?= (isset($this->errores['passwordConfirm'])) ? 'is-invalid' : null ?>"
                            name="passwordConfirm">

                        <?php if (isset($this->errores['passwordConfirm'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['passwordConfirm'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <a name="" id="" class="btn btn-secondary" href="<?= URL ?>users/" role="button">Cancel</a>
                        <button type="button" class="btn btn-danger">Clear</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br>

    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>

    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>

</body>

</html>