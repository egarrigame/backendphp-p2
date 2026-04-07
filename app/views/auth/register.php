<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - ReparaYa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .register-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h1 { text-align: center; color: #2c3e50; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #7f8c8d; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #229954; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .login-link { text-align: center; margin-top: 15px; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #95a5a6; }
        .input-icon input { padding-left: 35px; }
        .row { display: flex; gap: 15px; }
        .row .input-icon { flex: 1; }
    </style>
</head>
<body>
    <div class="register-container">
        <h1><i class="fas fa-tools"></i> ReparaYa</h1>
        <div class="subtitle"><i class="fas fa-user-plus"></i> Crear una cuenta</div>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <form method="POST" action="/register">
            <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" placeholder="Nombre completo" required value="<?= htmlspecialchars($_SESSION['old_data']['nombre'] ?? '') ?>">
            </div>
            
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Correo electrónico" required value="<?= htmlspecialchars($_SESSION['old_data']['email'] ?? '') ?>">
            </div>
            
            <div class="input-icon">
                <i class="fas fa-phone"></i>
                <input type="tel" name="telefono" placeholder="Teléfono (opcional)" value="<?= htmlspecialchars($_SESSION['old_data']['telefono'] ?? '') ?>">
            </div>
            
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Contraseña (mínimo 6 caracteres)" required>
            </div>
            
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" name="password_confirm" placeholder="Confirmar contraseña" required>
            </div>
            
            <button type="submit"><i class="fas fa-user-check"></i> Registrarse</button>
        </form>
        
        <div class="login-link">
            <i class="fas fa-sign-in-alt"></i> ¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a>
        </div>
    </div>
    <?php unset($_SESSION['old_data']); ?>
</body>
</html>
