<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <title>Add Customer</title>
</head>

<body>
    <!-- Top fixed menu -->
    <?php require_once "template/partials/menuAut.php"; ?>
    <!-- capa principal -->
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- card that contains the form -->
        <div class="card">
            <div class="card-header">
                <?php require_once "views/customers/partials/header.php"; ?>
            </div>
            <div class="card-body">
                <!-- formulario  -->
                <form action="<?= URL ?>customers/create" method="POST">
                    <!-- name -->
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?= $this->customer->name ?>">
                        <!-- Show error -->
                        <?php if (isset($this->errores['name'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['name'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- phone -->
                    <div class="mb-3">
                        <label for="" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="<?= $this->customer->phone ?>">
                        <!-- Show error -->
                        <?php if (isset($this->errores['phone'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['phone'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- city -->
                    <div class="mb-3">
                        <label for="" class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="<?= $this->customer->city ?>">
                        <!-- Show error -->
                        <?php if (isset($this->errores['city'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['city'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address"
                            value="<?= $this->customer->address ?>">
                        <!-- Show error -->
                        <?php if (isset($this->errores['address'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['address'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- email -->
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $this->customer->email ?>">
                        <!-- Show error -->
                        <?php if (isset($this->errores['email'])): ?>
                            <span class="form-text text-danger" role="alert">
                                <?= $this->errores['email'] ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- botones de acción -->
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="<?= URL ?>customers/" role="button">Cancel</a>
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