<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Horas Trabajadas</title>
</head>

<body>
    <div class="container" style="padding-top: 2%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>
        <!-- cabecera  -->
        <?php include "views/workingHours/partials/header.php" ?>
        <!-- Menu principal -->
        <?php require_once "views/workingHours/partials/menu.php" ?>
        <!-- Mensaje -->
        <?php require_once "template/partials/mensaje.php" ?>
        <?php require ('template/partials/modalClientes.php'); ?>
        <!-- tabla Working Hours -->
        <table class="table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Time Code</th>
                    <th>Work Order</th>
                    <th>Project</th>
                    <th>Description (Task)</th>
                    <th>Date Worked</th>
                    <th>Duration</th>
                    
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
                        <td>
                            <?= $workingHour->duration ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">Nº Registros:
                        <?= $this->workingHours->rowCount() ?>
                    </td>
                </tr>
            </tfoot>

        </table>

    </div>

    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>

    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>