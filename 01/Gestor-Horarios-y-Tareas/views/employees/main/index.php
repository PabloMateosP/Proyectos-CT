<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>employees</title>
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
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Ciudad</th>
                            <th>Horas Totales</th>
                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin'])): ?>
                                <th>Acciones</th>
                            <?php elseif ((isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['organiser']))): ?>
                                <th>Acciones</th>
                            <?php else: ?>
                                <!-- No permitido -->
                            <?php endif; ?>
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
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])): ?>
                                    <td style="display:flex; gap: 5px;">
                                        <a href="#" title="Mostrar" class="btn btn-warning"> <i class="bi bi-eye"></i></a>
                                        <a href="<?= URL ?>employees/delete/<?= $employee->id ?>" title="Delete"
                                            onclick="return confirm('Confirm employee deletion') " class="btn btn-danger"> <i
                                                class="bi bi-trash"></i></a>
                                        <a href="<?= URL ?>employees/edit/<?= $employee->id ?>" title="Mostrar"
                                            class="btn btn-success"> <i class="bi bi-pencil-square"></i></a>
                                    </td>
                                <?php else: ?>
                                    <!-- No permitido -->
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