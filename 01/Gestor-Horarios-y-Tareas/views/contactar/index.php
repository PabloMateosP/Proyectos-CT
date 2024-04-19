<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Formulario Bootstrap</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<?php require_once("template/partials/head.php") ?>

<body>
    <?php require_once("template/partials/menuBar.php") ?>
    <?php require_once("template/partials/mensaje.php") ?>
    <br>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Formulario de Contacto</h5>
            </div>
            <div class="card-body">
                <form action="<?= URL ?>contactar/validar/" method="POST">

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            placeholder="Ingrese su nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Ingrese su correo electrónico" required>
                    </div>

                    <div class="form-group">
                        <label for="asunto">Asunto:</label>
                        <input type="text" class="form-control" id="asunto" name="asunto"
                            placeholder="Ingrese el asunto" required>
                    </div>

                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="4"
                            placeholder="Ingrese su mensaje" required></textarea>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary">Cancelar</button>
                        <button type="reset" class="btn btn-danger">Borrar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                Made by: Pablo Mateos Palas
            </div>
        </div>
    </div>
    <br><br>
    <br>
    
    <?php require_once("template/partials/javascript.php") ?>
</body>

</html>