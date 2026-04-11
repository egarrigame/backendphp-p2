<h2>Registro</h2>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form method="POST" action="/register">
    <input type="text" name="nombre" placeholder="Nombre" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="telefono" placeholder="Teléfono" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Registrarse</button>
</form>

<a href="/login">Volver al login</a>