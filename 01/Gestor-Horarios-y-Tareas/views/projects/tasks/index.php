<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Task Main</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>

        <div class="card">
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <!-- table tasks -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Description</th>
                            <th>Project</th>
                            <th>Project Description</th>
                            <th>Creation date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->tasks as $task_): ?>
                            <tr>
                                <td>
                                    <?= $task_['task'] ?>
                                </td>
                                <td>
                                    <?= $task_['description'] ?>
                                </td>
                                <td>
                                    <?= $task_['project'] ?>
                                </td>
                                <td>
                                    <?= $task_['projectDescription']?>
                                </td>
                                <td>
                                    <?= $task_['created_at'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Nº:
                                <?= count($this->tasks)?>
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