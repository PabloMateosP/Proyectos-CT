<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Usuarios - Gesbank</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>
        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/users/partials/header.php" ?>
            </div>
            <div class="card-header">

                <!-- Menu principal -->
                <?php require_once "views/users/partials/menu.php" ?>
            </div>
            <div class="card-body">

                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Id </th>
                            <th>Nombre Cliente</th>
                            <th>Email</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->users as $user): ?>
                            <tr>
                                <td>
                                    <?= $user->id ?>
                                </td>
                                <td>
                                    <?= $user->name ?>
                                </td>
                                <td>
                                    <?= $user->email ?>
                                </td>
                                <td>
                                    <?= $user->created_at ?>
                                </td>
                                <td>
                                    <?= $user->update_at ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= URL ?>users/show/<?= $user->id ?>" title="show" class="btn btn-secondary<?= (!in_array($_SESSION['id_rol'], $GLOBALS['admin'])) ?
                                                'disabled' : null ?>"> <i class="bi bi-eye"></i> </a>
                                        <a href="<?= URL ?>users/edit/<?= $user->id ?>" title="edit" class="btn btn-primary
                                        <?= (!in_array($_SESSION['id_rol'], $GLOBALS['admin'])) ?
                                            'disabled' : null ?>"> <i class="bi bi-pencil"></i> </a>
                                        <a href="<?= URL ?>users/delete/<?= $user->id ?>" title="delete"
                                            onclick="return confirm('Want to delete the user?')" class="btn btn-danger"
                                            <?= (!in_array($_SESSION['id_rol'], $GLOBALS['admin'])) ?
                                                'disabled' : null ?>>
                                            <i class="bi bi-trash"></i> </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8">Nº Registros:
                                <?= $this->users->rowCount() ?>
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