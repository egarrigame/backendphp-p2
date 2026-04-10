<?php
// src/controllers/AuthController.php

class AuthController {
    

    public function mostrarRegistro() { // método para mostrar el form de registro (petición GET) 
        $datos = [
            'titulo' => 'Registro de usuario'
        ];
        
        $this->render('registro', $datos);
    }

    public function procesarRegistro() { // método para procesar los datos del formulario de registro
        $nombre = $_POST['nombre'] ?? ''; // recogemos los datos
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($nombre) || empty($email) || empty($password)) { // validación de campos obligatorios
            $this->render('registro', [
                'titulo' => 'Registro',
                'error' => 'Faltan campos obligatorios.'
            ]);
            return;
        }

        require_once 'src/models/Usuario.php'; // importamos el modelo Usuario
        $db = conectarDB(); // obtenemos la conexión a la bbdd
        $usuarioModel = new Usuario($db); // instanciamos el modelo con la conexión a la bbdd
        $registroExitoso = $usuarioModel->registrar($nombre, $email, $password, $telefono); // ejecutamos el método de registrar y guardamos el resultado

        if ($registroExitoso) { // decidimos qué mostrar
            header('Location: /~uocx1/login?exito=1');
            exit();
        } else {
            $this->render('registro', [
                'titulo' => 'Registro - ReparaYa',
                'error' => 'El correo electrónico ya está registrado.'
            ]);
        }
    }

    public function mostrarLogin() { // método para el login (petición GET)
        $mensajeExito = isset($_GET['exito']) ? 'Registro completado, ahora iniciar sesión.' : ''; // recogemos la variable del registro éxito
        $datos = [
            'titulo' => 'Iniciar sesión',
            'mensajeExito' => $mensajeExito
        ];
        $this->render('login', $datos);
    }

    public function procesarLogin() { // método para el login (petición POST)
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->render('login', [
                'titulo' => 'Iniciar sesión',
                'error' => 'Rellena todos los campos.'
            ]);
            return;
        }

        require_once 'src/models/Usuario.php'; // llamamos al modelo
        $db = conectarDB();
        $usuarioModel = new Usuario($db);
        $usuarioLogueado = $usuarioModel->login($email, $password); // comprobamos credenciales

        if ($usuarioLogueado) { // éxito, guardamos sesión
            $_SESSION['usuario_id'] = $usuarioLogueado['id'];
            $_SESSION['usuario_nombre'] = $usuarioLogueado['nombre'];
            $_SESSION['usuario_rol'] = $usuarioLogueado['rol'];

            header('Location: /~uocx1/panel'); // redirigimos a dashboard
            exit();
        } else {
            $this->render('login', [
                'titulo' => 'Iniciar sesión',
                'error' => 'Correo o contraseña incorrectos.'
            ]);
        }
    }

    public function cerrarSesion() { // método para cerrar sesión
        $_SESSION = []; // vaciamos variable de sesión
        session_destroy();
        header('Location: /~uocx1/'); // redirigimos a home
        exit();
    }

    private function render($vista, $datos = []) { // método helper para cargar la vista
        extract($datos);
        $rutaVista = 'src/views/' . $vista . '.php';

        if (file_exists($rutaVista)) {
            require_once $rutaVista;
        } else {
            echo "Error Crítico: No se encuentra la vista $rutaVista";
        }
    }
}