<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>employees</title>
</head>

<body>
    <div class="container" style="margin-top: 5%;">
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
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Ciudad</th>
                            <th>Horas Totales</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->employees as $employee): ?>
                            <tr>
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
                                <td>
                                    <?= $employee->total_hours ?>
                                </td>
                                <td style="display:flex; justify-content:space-between;">
                                    <a href="#" title="Mostrar" class="btn btn-warning<?= (!in_array($_SESSION['id_rol'], $GLOBALS['admin'])) ?
                                        'disabled' : null ?>"> <i class="bi bi-eye"></i> </a>
                                </td>
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