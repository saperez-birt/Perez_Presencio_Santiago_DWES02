<?php
    session_start();
    if (!isset($_SESSION['reserva'])) {
        header('Location: index.html');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Reserva exitosa</title>
    </head>
    <body>
        <h2>Zorionak, <?php echo $_SESSION['reserva']['nombre'] . ' ' . $_SESSION['reserva']['apellido']; ?>! Tu reserva se ha realizado con éxito.</h2>
        <?php
            $modelo = $_SESSION['reserva']['modelo'];
            $imagenes = [
                "Lancia Stratos" => "imagenes/lancia_stratos.jpg",
                "Audi Quattro" => "imagenes/audi_quattro.jpg",
                "Ford Escort RS1800" => "imagenes/ford_escort.jpg",
                "Subaru Impreza 555" => "imagenes/subaru_impreza.jpg"
            ];

            if (isset($imagenes[$modelo])) {
                echo "<img src='" . $imagenes[$modelo] . "' alt='$modelo' width='50%'>";
            } else {
                echo "<p>Imagen no disponible.</p>";
            }
        ?>
        <br></br>
        <a href="index.html">Volver al formulario de reserva</a>
        <?php
            unset($_SESSION['reserva']);
        ?>
    </body>
</html>