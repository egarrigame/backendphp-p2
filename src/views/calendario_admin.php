<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="/">ReparaYa</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Hola, <b><?= htmlspecialchars($nombre) ?></b></span>
            <a href="/panel" class="btn btn-outline-light btn-sm me-2">📋 Ver tabla</a>
            <a href="/perfil" class="btn btn-outline-light me-2 btn-sm">Perfil</a>
            <a href="/logout" class="btn btn-danger btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Calendario</h2>
        <div>
            <span class="badge bg-primary me-2">Estándar</span>
            <span class="badge bg-danger">Urgente</span>
        </div>
    </div>
    
    <div class="card shadow-sm border-0 p-3">
        <div id='calendar'></div>
    </div>
</div>

<div class="modal fade" id="eventoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="eventoTitulo">Detalles</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Cliente:</strong> <span id="eventoCliente"></span></p>
                <p><strong>Dirección:</strong> <span id="eventoDireccion"></span></p>
                <p><strong>Estado:</strong> <span id="eventoEstado"></span></p>
                <p><strong>Técnico:</strong> <span id="eventoTecnico"></span></p>
                <hr>
                <p><strong>Descripción:</strong></p>
                <p id="eventoDescripcion" class="text-muted"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay' // vistas
        },
        events: '/api/incidencias', // nuestra api de php
        
        eventClick: function(info) { // click al detalle con los props
            info.jsEvent.preventDefault(); 
            
            document.getElementById('eventoTitulo').innerText = info.event.title;
            document.getElementById('eventoCliente').innerText = info.event.extendedProps.cliente;
            document.getElementById('eventoDireccion').innerText = info.event.extendedProps.direccion;
            document.getElementById('eventoEstado').innerText = info.event.extendedProps.estado;
            document.getElementById('eventoTecnico').innerText = info.event.extendedProps.tecnico;
            document.getElementById('eventoDescripcion').innerText = info.event.extendedProps.descripcion;
            
            var myModal = new bootstrap.Modal(document.getElementById('eventoModal'));
            myModal.show();
        }
    });
    
    calendar.render();
});
</script>
</body>
</html>