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
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/tasks/partials/header.php" ?>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/tasks/partials/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <?php require ('template/partials/modalClientes.php'); ?>
                <!-- table tasks -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Description</th>
                            <th>Project</th>
                            <th>Project Description</th>
                            <th>Creation date</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->tasks as $task_): ?>
                            <tr>
                                <td>
                                    <?= $task_->task ?>
                                </td>
                                <td>
                                    <?= $task_->description ?>
                                </td>
                                <td>
                                    <?= $task_->project ?>
                                </td>
                                <td>
                                    <?= $task_->projectDescription ?>
                                </td>
                                <td>
                                    <?= $task_->created_at ?>
                                </td>
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['all'])): ?>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Acciones">
                                            <a href="<?= URL ?>tasks/edit/<?= $task_->id ?>" title="edit"
                                                class="btn btn-primary <?= (!in_array($_SESSION['id_rol'], $GLOBALS['all'])) ? 'disabled' : null ?>">
                                                <i class="bi bi-pencil"></i> </a>
                                            <a href="<?= URL ?>tasks/delete/<?= $task_->id ?>" title="Eliminar"
                                                onclick="return confirm('Confirm task deletion') "
                                                class="btn btn-danger <?= (!in_array($_SESSION['id_rol'], $GLOBALS['all'])) ? 'disabled' : null ?>">
                                                <i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Nº:
                                <?= $this->tasks->rowCount() ?>
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