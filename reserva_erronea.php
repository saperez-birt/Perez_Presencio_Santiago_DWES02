<?php
    session_start();
    if (!isset($_SESSION['errores']) || !isset($_SESSION['reserva'])) {
        header('Location: index.html');
        exit();
    }

    function    mostrarCampo($campo) {
        if (isset($_SESSION['errores'][$campo])) {
            $color = 'red';
            $mensaje = $_SESSION['errores'][$campo] . $_SESSION['reserva'][$campo];
        } else {
            $color = 'green';
            $mensaje = ucfirst($campo) . ' correcto: ' . $_SESSION['reserva'][$campo];
        }
        echo '<li style="color:' . $color . ';">' . $mensaje . '</li>';
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Reserva erronea</title>
    </head>
    <body>
        <h2>Datos de la reserva: </h2>
        <ul>
            <?php
                $campos = ['nombre', 'apellido', 'dni', 'modelo', 'fecha_inicio', 'duracion'];
                foreach ($campos as $campo) {
                    mostrarCampo($campo);
                }
            ?>
        </ul>
        <?php
            unset($_SESSION['reserva']);
            unset($_SESSION['errores']);
        ?>
        <br></br>
        <a href="index.html">Volver al formulario de reserva</a>
    </body>
</html>
