<div class="container mt-4">

    <h2 class="mb-4">Calendario de incidencias</h2>

    <?php if (empty($incidencias)): ?>
        <div class="alert alert-info">
            No hay incidencias registradas.
        </div>
    <?php else: ?>

        <!-- SELECTOR DE MES -->
        <div class="mb-3">
            <input type="month" id="selectorMes" class="form-control">
        </div>

        <!-- CALENDARIO -->
        <div id="calendario" class="row g-2 mb-5"></div>

        <!-- TABLA (fallback + extra info) -->
        <h4 class="mb-3">Listado de incidencias</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Urgencia</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($incidencias as $i): ?>

                    <?php
                        $color = $i['tipo_urgencia'] === 'Urgente'
                            ? 'table-danger'
                            : 'table-success';
                    ?>

                    <tr class="<?= $color ?>">
                        <td><?= date('d/m/Y H:i', strtotime($i['fecha_servicio'])) ?></td>
                        <td><?= $i['localizador'] ?></td>
                        <td><?= $i['cliente_nombre'] ?></td>
                        <td><?= $i['nombre_especialidad'] ?></td>
                        <td><?= $i['tipo_urgencia'] ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

        <div class="mt-3">
            <span class="badge bg-danger">Urgente</span>
            <span class="badge bg-success">Estándar</span>
        </div>

    <?php endif; ?>

</div>

<!-- PASAR DATOS A JS -->
<script>
    const incidencias = <?= json_encode($incidencias) ?>;
</script>

<script src="/assets/js/calendar.js"></script>