<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Working Hours Main</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>

        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/workingHours/partials/header.php" ?>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/workingHours/partials/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <?php require ('template/partials/modalClientes.php'); ?>
                <!-- tabla Working Hours -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Time Code</th>
                            <th>Work Order</th>
                            <th>Project</th>
                            <th>Description (Task)</th>
                            <th>Date Worked</th>
                            <th>Duration</th>
                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['employee'])): ?>
                                <th>Acciones</th>
                            <?php else: ?>
                                <!-- No permitido -->     
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->workingHours as $workingHour): ?>
                            <tr>
                                <td>
                                    <?= $workingHour->employee_name ?>
                                </td>
                                <td>
                                    <?= $workingHour->time_code ?>
                                </td>
                                <td>
                                    <?= $workingHour->project_name ?>
                                </td>
                                <td>
                                    <?= $workingHour->task_description ?>
                                </td>
                                <td>
                                    <?= $workingHour->work_order_description ?>
                                </td>
                                <td>
                                    <?= $workingHour->date_worked ?>
                                </td>
                                <td class="text-center">
                                    <?= $workingHour->duration ?>
                                </td>
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['employee'])): ?>   
                                    <td style="display:flex; gap: 5px;">
                                       <a href="<?= URL ?>workingHours/edit/<?= $workingHour->id ?>" title="edit" class="btn btn-primary <?= (!in_array($_SESSION['id_rol'], $GLOBALS['employee'])) ?
                                            'disabled' : null ?>"> <i class="bi bi-pencil"></i> </a>
                                        <a href="<?= URL ?>workingHours/delete/<?= $workingHour->id ?>" title="Eliminar" onclick="return confirm('Confirmar eliminación Cuenta') " class="btn btn-danger" <?= (!in_array($_SESSION['id_rol'], $GLOBALS['employee'])) ?
                                            'disabled' : null ?>> <i class="bi bi-trash"></i></a> 
                                    </td>
                                <?php else: ?>
                                        <!-- No permitido -->     
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            
                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['employee'])): ?>
                                <td colspan="6">Nº:
                                    <?= $this->workingHours->rowCount() ?>
                                </td>
                                <td colspan="2"> Total:
                                    <strong><?= $this->total_hours ?></strong>
                                </td>
                            <?php else: ?>
                                <td colspan="8">Nº record:
                                    <?= $this->workingHours->rowCount() ?>
                                </td>
                            <?php endif; ?>
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