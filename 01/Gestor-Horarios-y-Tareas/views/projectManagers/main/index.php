<!DOCTYPE html>
<html lang="es">

<head>
    <!-- head -->
    <?php require_once ("template/partials/head.php"); ?>
    <title>Project Managers Main</title>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>

        <div class="card">
            <div class="card-header">
                <!-- cabecera  -->
                <?php include "views/projectManagers/partials/header.php" ?>
            </div>
            <div class="card-header">
                <!-- Menu principal -->
                <?php require_once "views/projectManagers/partials/menu.php" ?>
            </div>
            <div class="card-body">
                <!-- Mensaje -->
                <?php require_once "template/partials/mensaje.php" ?>
                <?php require ('template/partials/modalClientes.php'); ?>
                <!-- tabla projects -->
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Id</th>
                            <th>Project Manager</th>
                            <th>Project</th>
                            <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                                <th>Actions</th>
                            <?php else: ?>

                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->projectManagers as $projectManager): ?>
                            <tr>
                                <td></td>
                                <td>
                                    <?= $projectManager->id ?>
                                </td>
                                <td>
                                    <?= $projectManager->pManager_name ?>
                                </td>

                                <td>
                                    <?php foreach ($this->projects as $project): ?>
                                        <?php if ($projectManager->id == $project["id_projectManager"]): ?>
                                            <div><?= $project["project"] ?></div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])): ?>
                                    <td style="display:flex; gap: 5px;">
                                        <a href="<?= URL ?>projectManagers/edit/<?= $projectManager->id ?>" title="edit" class="btn btn-primary <?= (!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])) ?
                                                'disabled' : null ?>"> <i class="bi bi-pencil"></i> </a>
                                        <a href="<?= URL ?>projectManagers/delete/<?= $projectManager->id ?>" title="Eliminar"
                                            onclick="return confirm('Confirmar project deletion') " class="btn btn-danger"
                                            <?= (!in_array($_SESSION['id_rol'], $GLOBALS['exceptEmp'])) ?
                                                'disabled' : null ?>> <i class="bi bi-trash"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Nº:
                                <?= $this->projectManagers->rowCount() ?>
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