<!DOCTYPE html>
<html lang="es">

<head>
    <!-- bootstrap  -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Users Details</title>
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

                <form>
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" class="form-control" value="<?= $this->user->name ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?= $this->user->email ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Rol</label>
                        <input type="text" class="form-control" value="<?= $this->rol->name ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <a name="" id="" class="btn btn-secondary" href="<?= URL ?>users/" role="button">Go back</a>
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