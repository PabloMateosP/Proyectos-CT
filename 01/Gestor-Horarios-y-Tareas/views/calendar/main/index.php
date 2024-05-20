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
        <!-- <script src="<?= URL ?>views/calendar/partials/es.js"></script> -->

        <div class="container py-5" id="page-container">
            <div class="row">
                <div class="col-md-9">
                    <?php require_once "template/partials/mensaje.php" ?>
                    <div id="calendar"></div>
                </div>
                <div class="col-md-3">
                    <div class="cardt rounded-0 shadow">
                        <div class="card-header bg-gradient bg-primary text-light">
                            <h5 class="card-title">Crear Evento</h5>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <form action="<?= URL ?>calendar/handleRequest" method="post" id="schedule-form">
                                    <input type="hidden" name="id" value="">
                                    <div class="form-group mb-2">
                                        <label for="title" class="control-label">Nombre</label>
                                        <input type="text" class="form-control form-control-sm rounded-0" name="title"
                                            id="title" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="description" class="control-label">Descripción</label>
                                        <textarea rows="3" class="form-control form-control-sm rounded-0"
                                            name="description" id="description" required></textarea>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="start_datetime" class="control-label">Inicio</label>
                                        <input type="datetime-local" class="form-control form-control-sm rounded-0"
                                            name="start_datetime" id="start_datetime" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="end_datetime" class="control-label">Fin</label>
                                        <input type="datetime-local" class="form-control form-control-sm rounded-0"
                                            name="end_datetime" id="end_datetime" required>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form">
                                    <i class="fa fa-save"></i> Guardar
                                </button>
                                <button class="btn btn-default border btn-sm rounded-0" type="reset"
                                    form="schedule-form">
                                    <i class="fa fa-reset"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>
    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>