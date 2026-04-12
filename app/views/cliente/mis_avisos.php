<div class="container mt-4">

    <h2 class="mb-4">Mis avisos</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="/cliente/nueva-incidencia" class="btn btn-primary">
            Nueva incidencia
        </a>
    </div>

    <?php if (empty($avisos)): ?>
        <div class="alert alert-info">
            No tienes incidencias registradas.
        </div>
    <?php else: ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Localizador</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Dirección</th>
                    <th>Urgencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($avisos as $aviso): ?>

                    <?php
                        $color = $aviso['tipo_urgencia'] === 'Urgente'
                            ? 'table-danger'
                            : '';
                    ?>

                    <tr class="<?= $color ?>">

                        <td><?= $aviso['localizador'] ?></td>
                        <td><?= $aviso['nombre_especialidad'] ?></td>
                        <td><?= $aviso['nombre_estado'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($aviso['fecha_servicio'])) ?></td>
                        <td><?= $aviso['direccion'] ?></td>
                        <td><?= $aviso['tipo_urgencia'] ?></td>

                        <td>

                            <?php if ($aviso['nombre_estado'] === 'Pendiente'): ?>
                                <form method="POST" action="/cliente/cancelar-incidencia" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $aviso['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Cancelar incidencia?')">
                                        Cancelar
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">No disponible</span>
                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>