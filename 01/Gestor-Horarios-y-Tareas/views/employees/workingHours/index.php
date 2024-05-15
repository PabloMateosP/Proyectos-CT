<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Working Hour Employee</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>

        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <h4>Working hours employee</h4>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/employees/workingHours/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <!-- tabla Working Hours -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Time Code</th>
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
                                    <?= $workingHour->date_worked ?>
                                </td>
                                <td class="text-center">
                                    <?= $workingHour->duration ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Nº:
                                <?= $this->workingHours->rowCount() ?>
                            </td>
                            <td> Total:
                                <strong><?= $this->total_hours ?></strong>
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