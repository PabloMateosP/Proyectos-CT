<html lang='es'>

<head>
    <?php require_once ("template/partials/head.php"); ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

</head>

<body>
    <div class="container" style="margin-top: 5%; margin-bottom: 5%;">
        <!-- menu fijo superior -->
        <?php require_once "template/partials/menuAut.php"; ?>
        
        <script src="<?= URL ?>views/calendar/partials/calendar.js"></script>

        <div id='calendar'></div>
        
    </div>
    <!-- footer -->
    <?php require_once "template/partials/footer.php" ?>

    <!-- Bootstrap JS y popper -->
    <?php require_once "template/partials/javascript.php" ?>
</body>

</html>