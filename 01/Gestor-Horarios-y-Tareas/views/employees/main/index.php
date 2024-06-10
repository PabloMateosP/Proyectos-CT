<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>employees</title>
    <style>
        .hours-green {
            background-color: green !important;
            color: white !important;
            /* Para que el texto sea legible */
        }

        .hours-red {
            background-color: #8C1A1A !important;
            color: white !important;
            /* Para que el texto sea legible */
        }
    </style>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>
        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/employees/partials/header.php" ?>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/employees/partials/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <?php require ('template/partials/modalClientes.php'); ?>
                <!-- tabla employees -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Identification</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Total Hours</th>
                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->employees as $employee): ?>
                            <tr>
                                <td>
                                    <?= $employee->identification ?>
                                </td>
                                <td>
                                    <?= $employee->employee ?>
                                </td>
                                <td>
                                    <?= $employee->email ?>
                                </td>
                                <td>
                                    <?= $employee->phone ?>
                                </td>
                                <td>
                                    <?= $employee->city ?>
                                </td>
                                <td class="<?= $employee->total_hours >= 40 ? 'hours-green' : 'hours-red' ?>">
                                    <?= $employee->total_hours ?>
                                </td>
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])): ?>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= URL ?>employees/show/<?= $employee->id ?>" title="Mostrar"
                                                class="btn btn-secondary"> <i class="bi bi-eye"></i></a>
                                            <a href="<?= URL ?>employees/workingHours/<?= $employee->id ?>" title="WorkingHours"
                                                class="btn btn-success"> <i class="bi bi-list-task"></i>
                                            </a>
                                            <a href="<?= URL ?>employees/edit/<?= $employee->id ?>" title="edit"
                                                class="btn btn-primary"> <i class="bi bi-pencil"></i></a>
                                            <a href="<?= URL ?>employees/delete/<?= $employee->id ?>" title="Delete"
                                                onclick="return confirm('Confirm employee deletion') " class="btn btn-danger">
                                                <i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8">Nº Registros:
                                <?= $this->employees->rowCount() ?>
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