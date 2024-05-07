<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>customers</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>
        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/customers/partials/header.php" ?>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/customers/partials/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <?php require ('template/partials/modalClientes.php'); ?>
                <!-- tabla customers -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->customers as $customer): ?>
                            <tr>
                                <td>
                                    <?= $customer->name ?>
                                </td>
                                <td>
                                    <?= $customer->phone ?>
                                </td>
                                <td>
                                    <?= $customer->city ?>
                                </td>
                                <td>
                                    <?= $customer->address ?>
                                </td>
                                <td>
                                    <?= $customer->email ?>
                                </td>
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])): ?>
                                    <td style="display:flex; gap: 5px;">
                                        <a href="<?= URL ?>customers/delete/<?= $customer->id ?>" title="delete"
                                            onclick="return confirm('Confirm customer deletion') " class="btn btn-danger"> <i
                                                class="bi bi-trash"></i></a>
                                        <a href="<?= URL ?>customers/edit/<?= $customer->id ?>" title="edit"
                                            class="btn btn-success"> <i class="bi bi-pencil-square"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Nº Registros:
                                <?= $this->customers->rowCount() ?>
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