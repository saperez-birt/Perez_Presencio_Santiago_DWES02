<?php
session_start();

include('datos.php');

/* Comprobamos la existencia de la variable. Aun que podemos asumir que 
esta variable se envia como parte del script que se lanza con el action
del formulario, es buena practica comprobarlo igualmente para otros casos
que se pueden dar (envios incompletos del formulario, etc. ).  */
function    obtenerCampoPost($campo) {
    if (isset($_POST[$campo])) {
        /* Eliminamos los caracteres blancos del principio y del final para asegurarnos
        de tener el contenido del campo correctamente. */
        if (empty($_POST[$campo])) {
            $_SESSION['errores'][$campo] = 'El campo ' . $campo . ' está vacío.';
            return '';
        }
        return trim($_POST[$campo]);
    }
    $_SESSION['errores'][$campo] = 'El campo ' . $campo . ' está vacío.';
    return '';
}

function    validarDNI($dni) {
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    $numeros = substr($dni, 0, 8);
    $letra = substr($dni, -1);
    $indice = $numeros % 23;
    return $letra === $letras[$indice];
}

function    comprobarError($usuario, $campo, $valor) {
    if (!$usuario || (!isset($_SESSION['errores'][$campo]) && $usuario[$campo] !== $valor)) {
        $_SESSION['errores'][$campo] = ucfirst($campo) . ' incorrecto: ';
    } 
}

function    validarUsuario($nombre, $apellido, $dni) {    
    if (empty($_SESSION['errores'])) {
        foreach(USUARIOS as $usuario) {
            if ($usuario['nombre'] === $nombre && $usuario['apellido'] === $apellido && $usuario['dni'] == $dni) {
                return true;
            }
        }
        return false;
    }
}
/* https://www.anerbarrena.com/sumar-restar-fechas-php-5655/ */
function    disponibilidadVehiculo($modelo, $fechaInicio, $duracion) {
    global $coches;
    foreach($coches as $coche) {
        if ($coche['modelo'] === $modelo) {
            if (!$coche['disponible']) {
                $_SESSION['errores']['modelo'] = 'El vehiculo no está disponible: ';
                return false;
            }
            $inicioReserva = date('Y-m-d', strtotime($fechaInicio));
            $finReserva = date('Y-m-d',strtotime($fechaInicio."+ $duracion days"));
            $coche['disponible'] = false;
            $coche['fecha_inicio'] = $inicioReserva;
            $coche['fecha_fin'] = $finReserva;
            return true;
        }
    }
    $_SESSION['errores']['modelo'] = 'El vehículo seleccionado no existe: ';
    return false;
}

// Comprueba que los campos se hayan enviado y no esten vacios
$nombre = obtenerCampoPost('nombre', null);
$apellido = obtenerCampoPost('apellido', null);
$dni = obtenerCampoPost('dni', null);
$modelo = obtenerCampoPost('modelo', null);
$fechaInicio = obtenerCampoPost('fecha_inicio', null);
$duracion = (int) obtenerCampoPost('duracion', null);

if (!empty($dni) && !validarDNI($dni)) {
    $_SESSION['errores']['dni']  = 'DNI no valido:';
}

if (!validarUsuario($nombre, $apellido, $dni)) {
    $usuarioErroneo = null;
    foreach(USUARIOS as $usuario) {
        if ($usuario['nombre'] === $nombre || $usuario['apellido'] === $apellido || $usuario['dni'] === $dni) {
            $usuarioErroneo = $usuario;
            break;
        }
    }
    comprobarError($usuarioErroneo, 'nombre', $nombre);
    comprobarError($usuarioErroneo, 'apellido', $apellido);
    comprobarError($usuarioErroneo, 'dni', $dni);
}

if ($fechaInicio <= date('Y-m-d')) {
    $_SESSION['errores']['fecha_inicio'] = "La fecha de reserva no puede ser anterior a la actual: ";
}

if ($duracion < 1 || $duracion > 30) {
    $_SESSION['errores']['duracion'] = 'La reserva solo puede ser de 1 a 30 días: ';
}

disponibilidadVehiculo($modelo, $fechaInicio, $duracion);

$_SESSION['reserva'] = [
    'nombre' => $nombre,
    'apellido' => $apellido,
    'dni' => $dni,
    'modelo' => $modelo,
    'fecha_inicio' => $fechaInicio,
    'duracion' => $duracion
];
if (!empty($_SESSION['errores'])) {
    header('Location: reserva_erronea.php');
    exit();
} else {
    header('Location: reserva_exitosa.php');
    exit();
}
?>
