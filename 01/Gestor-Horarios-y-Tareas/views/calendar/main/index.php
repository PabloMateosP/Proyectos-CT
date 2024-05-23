<!DOCTYPE html>
<html lang='es'>

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        var scheds = <?= json_encode($this->sched_res) ?>;
    </script>
</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <?php require_once "template/partials/menuAut.php"; ?>

        <script src="<?= URL ?>views/calendar/partials/calendar.js"></script>
        <script src="<?= URL ?>views/calendar/partials/es.js"></script>

        <div class="container py-5" id="page-container">
            <div class="row">
                <?php if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], $GLOBALS['admin_manager'])): ?>
                    <div class="col-md-9">
                        <?php require_once "template/partials/mensaje.php" ?>
                        <div id="calendar"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card rounded-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Crear Evento</h5>
                            </div>
                            <div class="card-body">
                                <form action="<?= URL ?>calendar/handleRequest" method="post" id="schedule-form">
                                    <input type="hidden" name="id" value="">
                                    <div class="form-group mb-3">
                                        <label for="title" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="title" id="title" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label">Descripción</label>
                                        <textarea rows="3" class="form-control" name="description" id="description"
                                            required></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="start_datetime" class="form-label">Inicio</label>
                                        <input type="datetime-local" class="form-control" name="start_datetime"
                                            id="start_datetime" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="end_datetime" class="form-label">Fin</label>
                                        <input type="datetime-local" class="form-control" name="end_datetime"
                                            id="end_datetime" required>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-save"></i> Guardar
                                        </button>
                                        <button class="btn btn-secondary" type="reset">
                                            <i class="fa fa-times"></i> Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
                        <script>
                            const URL = '<?= URL ?>';
                        </script>
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-0">
                                <div class="modal-header rounded-0">
                                    <h5 class="modal-title">Detalles de evento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body rounded-0">
                                    <div class="container-fluid">
                                        <dl>
                                            <dt class="text-muted">Nombre</dt>
                                            <dd id="title" class="fw-bold fs-4"></dd>
                                            <dt class="text-muted">Descripción</dt>
                                            <dd id="description" class=""></dd>
                                            <dt class="text-muted">Inicio</dt>
                                            <dd id="start" class=""></dd>
                                            <dt class="text-muted">Fin</dt>
                                            <dd id="end" class=""></dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="modal-footer rounded-0">
                                    <div class="text-end">
                                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete"
                                            data-id="">Eliminar</button>
                                        <button type="button" class="btn btn-secondary btn-sm rounded-0"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-12">
                        <?php require_once "template/partials/mensaje.php" ?>
                        <div id="calendar"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>
    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>