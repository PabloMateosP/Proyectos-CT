<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Time Codes Main</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>

        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/timeCodes/partials/header.php" ?>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/timeCodes/partials/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <?php require ('template/partials/modalClientes.php'); ?>
                <!-- tabla projects -->
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Id</th>
                            <th>Time Code</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->timeCodes as $timeCode): ?>
                            <tr>
                                <td></td>
                                <td>
                                    <?= $timeCode->id ?>
                                </td>
                                <td>
                                    <?= $timeCode->time_code ?>
                                </td>
                                <td>
                                    <?= $timeCode->description ?>
                                </td>
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])): ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= URL ?>timeCodes/edit/<?= $timeCode->id ?>" title="edit" class="btn btn-primary <?= (!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])) ?
                                                    'disabled' : null ?>"> <i class="bi bi-pencil"></i> </a>
                                            <a href="<?= URL ?>timeCodes/delete/<?= $timeCode->id ?>" title="Eliminar"
                                                onclick="return confirm('Confirmar project deletion') " class="btn btn-danger"
                                                <?= (!in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])) ?
                                                    'disabled' : null ?>> <i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Nº:
                                <?= $this->timeCodes->rowCount() ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>

    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>