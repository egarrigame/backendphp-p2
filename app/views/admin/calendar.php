<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?> - ReparaYa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .view-buttons a { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        .view-buttons a.active { background: #27ae60; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
        .calendar-day { background: white; border-radius: 8px; padding: 10px; min-height: 100px; border: 1px solid #ddd; }
        .calendar-day-header { font-weight: bold; text-align: center; margin-bottom: 10px; background: #2c3e50; color: white; padding: 5px; border-radius: 5px; }
        .event { padding: 5px; margin-bottom: 5px; border-radius: 5px; font-size: 12px; cursor: pointer; }
        .event-urgente { background: #e74c3c; color: white; }
        .event-estandar { background: #3498db; color: white; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 20px; width: 400px; border-radius: 8px; }
        .close { float: right; cursor: pointer; font-size: 24px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1><i class="fas fa-tools"></i> ReparaYa - Admin</h1>
            <div class="nav">
                <a href="/admin/dashboard"><i class="fas fa-home"></i> Inicio</a>
                <a href="/admin/tecnicos"><i class="fas fa-user-cog"></i> Técnicos</a>
                <a href="/admin/incidencias"><i class="fas fa-clipboard-list"></i> Incidencias</a>
                <a href="/admin/calendar"><i class="fas fa-calendar-alt"></i> Calendario</a>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="calendar-header">
            <h2><i class="fas fa-calendar-alt"></i> <?= $pageTitle ?></h2>
            <div class="view-buttons">
                <a href="/admin/calendar?view=daily" class="<?= $view == 'daily' ? 'active' : '' ?>"><i class="fas fa-calendar-day"></i> Vista Diaria</a>
                <a href="/admin/calendar?view=weekly" class="<?= $view == 'weekly' ? 'active' : '' ?>"><i class="fas fa-calendar-week"></i> Vista Semanal</a>
                <a href="/admin/calendar?view=monthly" class="<?= $view == 'monthly' ? 'active' : '' ?>"><i class="fas fa-calendar-alt"></i> Vista Mensual</a>
            </div>
        </div>
        
        <?php if ($view == 'monthly'): ?>
            <!-- Vista Mensual -->
            <div class="calendar-grid">
                <div class="calendar-day-header">Lun</div>
                <div class="calendar-day-header">Mar</div>
                <div class="calendar-day-header">Mié</div>
                <div class="calendar-day-header">Jue</div>
                <div class="calendar-day-header">Vie</div>
                <div class="calendar-day-header">Sáb</div>
                <div class="calendar-day-header">Dom</div>
                <?php
                $currentMonth = date('Y-m');
                $firstDay = date('Y-m-01', strtotime($currentMonth));
                $startWeek = date('N', strtotime($firstDay));
                $daysInMonth = date('t', strtotime($currentMonth));
                
                $eventsByDate = [];
                foreach ($incidencias as $inc) {
                    $date = date('Y-m-d', strtotime($inc['fecha_servicio']));
                    $eventsByDate[$date][] = $inc;
                }
                
                $day = 1;
                $startOffset = ($startWeek + 6) % 7;
                for ($i = 0; $i < $startOffset; $i++) {
                    echo '<div class="calendar-day"></div>';
                }
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date = date('Y-m-d', strtotime("$currentMonth-$d"));
                    $events = $eventsByDate[$date] ?? [];
                    ?>
                    <div class="calendar-day">
                        <strong><?= $d ?></strong>
                        <?php foreach ($events as $event): ?>
                            <div class="event <?= $event['tipo_urgencia'] == 'Urgente' ? 'event-urgente' : 'event-estandar' ?>" onclick="showDetails(<?= htmlspecialchars(json_encode($event)) ?>)">
                                <i class="fas fa-wrench"></i> <?= htmlspecialchars($event['nombre_especialidad']) ?><br>
                                <i class="fas fa-clock"></i> <?= substr($event['fecha_servicio'], 11, 5) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            
        <?php elseif ($view == 'weekly'): ?>
            <!-- Vista Semanal -->
            <?php
            $today = new DateTime();
            $startOfWeek = clone $today;
            $startOfWeek->modify('monday this week');
            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $eventsByDate = [];
            foreach ($incidencias as $inc) {
                $date = date('Y-m-d', strtotime($inc['fecha_servicio']));
                $eventsByDate[$date][] = $inc;
            }
            ?>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                <?php for ($i = 0; $i < 7; $i++):
                    $currentDate = clone $startOfWeek;
                    $currentDate->modify("+$i days");
                    $dateStr = $currentDate->format('Y-m-d');
                    $events = $eventsByDate[$dateStr] ?? [];
                ?>
                    <div style="background: white; border-radius: 8px; padding: 10px; min-height: 150px; border: 1px solid #ddd;">
                        <strong><i class="fas fa-calendar-day"></i> <?= $days[$i] ?><br><?= $currentDate->format('d/m') ?></strong>
                        <?php foreach ($events as $event): ?>
                            <div class="event <?= $event['tipo_urgencia'] == 'Urgente' ? 'event-urgente' : 'event-estandar' ?>" style="margin-top: 5px;" onclick="showDetails(<?= htmlspecialchars(json_encode($event)) ?>)">
                                <i class="fas fa-wrench"></i> <?= htmlspecialchars($event['nombre_especialidad']) ?><br>
                                <i class="fas fa-clock"></i> <?= substr($event['fecha_servicio'], 11, 5) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
            
        <?php else: ?>
            <!-- Vista Diaria -->
            <?php
            $today = new DateTime();
            $dateStr = $today->format('Y-m-d');
            $dayEvents = [];
            foreach ($incidencias as $inc) {
                if (date('Y-m-d', strtotime($inc['fecha_servicio'])) == $dateStr) {
                    $dayEvents[] = $inc;
                }
            }
            ?>
            <p><i class="fas fa-calendar-day"></i> Hoy: <?= $today->format('d/m/Y') ?></p>
            <?php if (empty($dayEvents)): ?>
                <p><i class="fas fa-info-circle"></i> No hay servicios programados para hoy.</p>
            <?php else: ?>
                <?php foreach ($dayEvents as $event): ?>
                    <div class="event <?= $event['tipo_urgencia'] == 'Urgente' ? 'event-urgente' : 'event-estandar' ?>" style="margin-bottom: 10px; padding: 10px;" onclick="showDetails(<?= htmlspecialchars(json_encode($event)) ?>)">
                        <strong><i class="fas fa-wrench"></i> <?= htmlspecialchars($event['nombre_especialidad']) ?></strong><br>
                        <i class="fas fa-clock"></i> Hora: <?= substr($event['fecha_servicio'], 11, 5) ?><br>
                        <i class="fas fa-user"></i> Cliente: <?= htmlspecialchars($event['cliente_nombre'] ?? 'N/A') ?><br>
                        <i class="fas fa-map-marker-alt"></i> Dirección: <?= htmlspecialchars($event['direccion']) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <!-- Modal Detalles -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDetailsModal()">&times;</span>
            <h3><i class="fas fa-info-circle"></i> Detalles del Servicio</h3>
            <p><i class="fas fa-barcode"></i> <strong>Código:</strong> <span id="detail_localizador"></span></p>
            <p><i class="fas fa-wrench"></i> <strong>Servicio:</strong> <span id="detail_servicio"></span></p>
            <p><i class="fas fa-clock"></i> <strong>Tipo:</strong> <span id="detail_tipo"></span></p>
            <p><i class="fas fa-calendar-day"></i> <strong>Fecha:</strong> <span id="detail_fecha"></span></p>
            <p><i class="fas fa-user"></i> <strong>Cliente:</strong> <span id="detail_cliente"></span></p>
            <p><i class="fas fa-map-marker-alt"></i> <strong>Dirección:</strong> <span id="detail_direccion"></span></p>
            <p><i class="fas fa-align-left"></i> <strong>Descripción:</strong> <span id="detail_descripcion"></span></p>
            <p><i class="fas fa-chart-simple"></i> <strong>Estado:</strong> <span id="detail_estado"></span></p>
        </div>
    </div>
    
    <script>
        function showDetails(event) {
            document.getElementById('detail_localizador').innerText = event.localizador || 'N/A';
            document.getElementById('detail_servicio').innerText = event.nombre_especialidad || 'N/A';
            document.getElementById('detail_tipo').innerText = event.tipo_urgencia || 'N/A';
            document.getElementById('detail_fecha').innerText = event.fecha_servicio || 'N/A';
            document.getElementById('detail_cliente').innerText = event.cliente_nombre || 'N/A';
            document.getElementById('detail_direccion').innerText = event.direccion || 'N/A';
            document.getElementById('detail_descripcion').innerText = event.descripcion || 'N/A';
            document.getElementById('detail_estado').innerText = event.estado || 'N/A';
            document.getElementById('detailsModal').style.display = 'block';
        }
        
        function closeDetailsModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('detailsModal')) {
                closeDetailsModal();
            }
        }
    </script>
</body>
</html>