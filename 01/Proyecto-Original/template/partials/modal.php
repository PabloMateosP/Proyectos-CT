<form action="<?= URL . 'cuentas/importar/' ?>" method="POST" enctype="multipart/form-data">
  <div id="importar" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Subir CSV cuentas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
            <label for="archivos" class="form-label">Seleccione Archivo</label>
            <input type="file" class="form-control" id="archivos" name="archivos" accept=".csv">
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" name="subirArchivo">Subir</button>
        </div>
      </div>
    </div>
  </div>
</form>