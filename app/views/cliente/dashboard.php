<h1>Dashboard Admin</h1>

<p>Bienvenido, <?php echo $_SESSION['user']['nombre']; ?></p>

<ul>
    <li><a href="/admin/calendario">Ver calendario</a></li>
    <li><a href="/admin/incidencia-detalle">Ver incidencias</a></li>
    <li><a href="/logout">Cerrar sesión</a></li>
</ul>