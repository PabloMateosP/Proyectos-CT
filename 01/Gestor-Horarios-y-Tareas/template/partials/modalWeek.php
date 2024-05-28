<?php
// Este código recoge las fechas de las semanas para que sea más intuitiva en el modal
function getWeekRanges($year)
{
    $dto = new DateTime();
    $dto->setISODate($year, 1);
    $ranges = [];

    for ($week = 1; $week <= 52; $week++) {
        $start = clone $dto;
        $end = clone $dto;
        $end->modify('+6 days');
        $ranges[] = [
            'week' => $week,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d')
        ];
        $dto->modify('+7 days');
    }

    return $ranges;
}

$year = date('Y');
$weekRanges = getWeekRanges($year);
?>

<form id="formExportar" action="<?= URL . 'workingHours/exportByWeek/' ?>" method="POST" enctype="multipart/form-data">
    <div id="exportModalWeek" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Working Hours By Week</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong><label for="exportWeek">Week of Export</label></strong>
                        <select class="form-control" id="exportWeek" name="exportWeek">
                            <?php foreach ($weekRanges as $range): ?>
                                <option value="<?= $range['week'] ?>">
                                    Week <?= $range['week'] ?> (<?= $range['start'] ?> - <?= $range['end'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="export">Export</button>
                </div>
            </div>
        </div>
    </div>
</form>
