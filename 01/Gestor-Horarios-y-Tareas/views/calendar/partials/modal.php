<script src="<?= URL ?>views/calendar/partials/calendar.js"></script>
<div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0">
                <h5 class="modal-title">Detalles de evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Editar</button>
                    <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete"
                        data-id="">Eliminar</button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-0"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>