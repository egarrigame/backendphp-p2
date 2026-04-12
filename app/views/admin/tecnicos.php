<div class="container mt-4">

    <h2 class="mb-4">Gestión de técnicos</h2>

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

    <!-- CREAR TÉCNICO -->
    <div class="card p-3 mb-4">
        <h5>Nuevo técnico</h5>

        <form method="POST" action="/tecnicos/guardar">

            <div class="row">

                <div class="col-md-4">
                    <input type="text" name="nombre_completo" class="form-control"
                           placeholder="Nombre completo" required>
                </div>

                <div class="col-md-4">
                    <select name="especialidad_id" class="form-control" required>
                        <option value="">Especialidad</option>
                        <?php foreach ($especialidades as $e): ?>
                            <option value="<?= $e['id'] ?>">
                                <?= $e['nombre_especialidad'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Crear</button>
                </div>

            </div>

        </form>
    </div>

    <!-- LISTADO -->
    <?php if (empty($tecnicos)): ?>
        <p>No hay técnicos registrados.</p>
    <?php else: ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($tecnicos as $t): ?>
                    <tr>

                        <td><?= $t['nombre_completo'] ?></td>
                        <td><?= $t['nombre_especialidad'] ?></td>
                        <td>
                            <?= $t['disponible'] ? 'Sí' : 'No' ?>
                        </td>

                        <td>

                            <!-- EDITAR -->
                            <form method="POST" action="/tecnicos/actualizar" class="mb-2">

                                <input type="hidden" name="id" value="<?= $t['id'] ?>">

                                <div class="d-flex gap-2">

                                    <select name="especialidad_id" class="form-control">
                                        <?php foreach ($especialidades as $e): ?>
                                            <option value="<?= $e['id'] ?>"
                                                <?= $e['id'] == $t['especialidad_id'] ? 'selected' : '' ?>>
                                                <?= $e['nombre_especialidad'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <select name="disponible" class="form-control">
                                        <option value="1" <?= $t['disponible'] ? 'selected' : '' ?>>Sí</option>
                                        <option value="0" <?= !$t['disponible'] ? 'selected' : '' ?>>No</option>
                                    </select>

                                    <button class="btn btn-warning btn-sm">Guardar</button>

                                </div>

                            </form>

                            <!-- ELIMINAR -->
                            <form method="POST" action="/tecnicos/eliminar"
                                  onsubmit="return confirm('¿Eliminar técnico?');">

                                <input type="hidden" name="id" value="<?= $t['id'] ?>">

                                <button class="btn btn-danger btn-sm w-100">
                                    Eliminar
                                </button>

                            </form>

                        </td>

                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>