<div class="container mt-4">

    <h2 class="mb-4">Editar incidencia</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/admin/actualizar-incidencia">

        <input type="hidden" name="id" value="<?= $incidencia['id'] ?>">

        <!-- Cliente (solo lectura) -->
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <input type="text" class="form-control" 
                   value="<?= $incidencia['cliente_nombre'] ?>" disabled>
        </div>

        <!-- Especialidad -->
        <div class="mb-3">
            <label class="form-label">Especialidad</label>
            <select name="especialidad_id" class="form-select">
                <?php foreach ($especialidades as $e): ?>
                    <option value="<?= $e['id'] ?>"
                        <?= $e['id'] == $incidencia['especialidad_id'] ? 'selected' : '' ?>>
                        <?= $e['nombre_especialidad'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Estado -->
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado_id" class="form-select">
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id'] ?>"
                        <?= $estado['id'] == $incidencia['estado_id'] ? 'selected' : '' ?>>
                        <?= $estado['nombre_estado'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required>
<?= $incidencia['descripcion'] ?>
            </textarea>
        </div>

        <!-- Dirección -->
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control"
                   value="<?= $incidencia['direccion'] ?>" required>
        </div>

        <!-- Fecha -->
        <div class="mb-3">
            <label class="form-label">Fecha del servicio</label>
            <input type="datetime-local" name="fecha_servicio"
                   class="form-control"
                   value="<?= date('Y-m-d\TH:i', strtotime($incidencia['fecha_servicio'])) ?>">
        </div>

        <!-- Urgencia -->
        <div class="mb-3">
            <label class="form-label">Tipo de servicio</label>
            <select name="tipo_urgencia" class="form-select">
                <option value="Estándar"
                    <?= $incidencia['tipo_urgencia'] === 'Estándar' ? 'selected' : '' ?>>
                    Estándar
                </option>
                <option value="Urgente"
                    <?= $incidencia['tipo_urgencia'] === 'Urgente' ? 'selected' : '' ?>>
                    Urgente
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Guardar cambios</button>
        <a href="/admin/incidencia-detalle" class="btn btn-secondary">Volver</a>

    </form>

</div>