<div class="container mt-4">

    <h2 class="mb-4">Mi agenda</h2>

    <p class="mb-4">
        Bienvenido, <strong><?= $_SESSION['user']['nombre']; ?></strong>
    </p>

    <?php if (empty($incidencias)): ?>
        <div class="alert alert-info">
            No tienes incidencias asignadas.
        </div>
    <?php else: ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Dirección</th>
                    <th>Urgencia</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($incidencias as $i): ?>

                    <?php
                        $color = $i['tipo_urgencia'] === 'Urgente'
                            ? 'table-danger'
                            : '';
                    ?>

                    <tr class="<?= $color ?>">

                        <td><?= date('d/m/Y H:i', strtotime($i['fecha_servicio'])) ?></td>
                        <td><?= $i['localizador'] ?></td>
                        <td><?= $i['cliente_nombre'] ?></td>
                        <td><?= $i['nombre_especialidad'] ?></td>
                        <td><?= $i['direccion'] ?></td>
                        <td><?= $i['tipo_urgencia'] ?></td>
                        <td><?= $i['nombre_estado'] ?></td>

                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>