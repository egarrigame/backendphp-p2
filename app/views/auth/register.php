<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - ReparaYa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .register-container { background: white; padding: 30px; border-radius: 10px; width: 100%; max-width: 500px; }
        h1 { text-align: center; color: #2c3e50; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .login-link { text-align: center; margin-top: 15px; }
        a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>🔧 ReparaYa</h1>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <form method="POST" action="/register">
            <input type="text" name="nombre" placeholder="Nombre completo" required value="<?= htmlspecialchars($_SESSION['old_data']['nombre'] ?? '') ?>">
            <input type="email" name="email" placeholder="Correo electrónico" required value="<?= htmlspecialchars($_SESSION['old_data']['email'] ?? '') ?>">
            <input type="tel" name="telefono" placeholder="Teléfono (opcional)" value="<?= htmlspecialchars($_SESSION['old_data']['telefono'] ?? '') ?>">
            <input type="password" name="password" placeholder="Contraseña (mínimo 6 caracteres)" required>
            <input type="password" name="password_confirm" placeholder="Confirmar contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        
        <div class="login-link">
            ¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a>
        </div>
    </div>
    <?php unset($_SESSION['old_data']); ?>
</body>
</html>