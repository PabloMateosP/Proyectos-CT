<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Projects Main</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>

        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/projects/partials/header.php" ?>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/projects/partials/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <?php require ('template/partials/modalClientes.php'); ?>
                <!-- tabla projects -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Description</th>
                            <th>Project Manager</th>
                            <th>Customer</th>
                            <th>Finish Date</th>
                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['all'])): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->projects as $project_): ?>
                            <tr>
                                <td>
                                    <?= $project_->project ?>
                                </td>
                                <td>
                                    <?= $project_->description ?>
                                </td>
                                <td>
                                    <?= $project_->manager_name ?>
                                </td>
                                <td>
                                    <?= $project_->customerName ?>
                                </td>
                                <td>
                                    <?= $project_->finish_date ?>
                                </td>
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['all'])): ?>
                                    <td style="display:flex; gap: 5px;">
                                        <div class="btn-group" role="group" aria-label="Project Actions">
                                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                                                <a href="<?= URL ?>projects/show/<?= $project_->id ?>" title="View"
                                                    class="btn btn-secondary <?= (!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])) ? 'disabled' : null ?>">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                                                <a href="<?= URL ?>projects/edit/<?= $project_->id ?>" title="Edit"
                                                    class="btn btn-primary <?= (!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])) ? 'disabled' : null ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>  
                                            <a href="<?= URL ?>projects/tasks/<?= $project_->id ?>" title="Tasks"
                                                class="btn btn-success <?= (!in_array($_SESSION['id_rol'], $GLOBALS['all'])) ? 'disabled' : null ?>">
                                                <i class="bi bi-list-task"></i>
                                            </a>
                                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                                                <a href="<?= URL ?>projects/delete/<?= $project_->id ?>" title="Delete"
                                                    onclick="return confirm('Confirm project deletion, that will do that the children tasks will be deleted')"
                                                    class="btn btn-danger <?= (!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])) ? 'disabled' : null ?>">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>

                                        </div>
                                    </td>

                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">Nº:
                                <?= $this->projects->rowCount() ?>
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