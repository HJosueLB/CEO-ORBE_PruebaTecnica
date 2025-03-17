<?php

// Llamado al archivo de conexión a la base de datos.
require_once '../BaseDatos/db_conexion.php';

// Asignar variable a la función de conexión a la base de datos.
$conexion = db_conexion();

// Variable para almacenar mensajes de error
$mensajeError = "";

// Procedimiento POST para agregar nuevo estudiante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && isset($_POST['correo'])) {

    try {
        
        //Declaración de variables
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];

        // Consulta al procedimiento de agregar nuevo estudiante.
        $sentenciaSQL = "CALL P_AgregarEstudiante(?, ?)";

        // Preparar la consulta con STMT para evitar inyección SQL.
        $stmt = $conexion->prepare($sentenciaSQL);

        // Vincular los parámetros con las variables (ss: s = string)
        $stmt->bind_param("ss", $nombre, $correo);

        // Condicionales para mostrar mensaje de: Eliminación exitosa / no se encontro estudiante / Ocurrió un error.
        if ($stmt->execute()) {
            $mensaje = "Se ha registrado el estudiante correctamente.";
            $tipo = "success";
        } else {
            $mensaje = "Lo sentimos, no se realizó el registro de estudiante.";
            $tipo = "error";
        }

        // Cerrar la declaración
        $stmt->close();

    } catch (Exception $e) {
        $mensaje = "Lo sentimos, ha ocurrido un error con el servidor de base de datos. Favor contactar al equipo de TI.";
        $mensajeError = "Ha ocurrido un error de consulta: " . $e->getMessage();
        $tipo = "error";
    }

    //Los mensajes anteriores correspondiente de las condicionales de la ejecución del procedimiento son llamados por los JS de SweetAlert
      
}

// Cerrar la conexión con la base de datos
$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>

    <!-- Integración de la liberia Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Integración de la liberia Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Integración de la liberia SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Llamar estilos CSS para el documento HTML -->
    <link rel="stylesheet" href="../estilos/estudiantesAgregar.css">

</head>
    
<body>

    <!-- div para centar el contenido en pantalla (Nav - section - footer) -->
    <div id="contenido">

        <!-- integración del componente NavBar al documento HTML -->
        <?php include "../componentes/navbar.php"; ?>

        <!-- Section del documento HTML -->
        <section>

            <!-- Titulo -->
            <h1 class="text-center mt-3">Registrar nuevo estudiante</h1>

            <!-- Contenedor del formulario de registro -->
            <div class="container">

                <!-- Contenedor del botón de regreso-->
                <div class="d-flex mb-3">
                        <div class="ms-auto p-2">
                            <a href="../Modulos/estudiantes.php" class="btn btn-secondary">Regresar</a>
                        </div>
                </div>

                <div class="container" id="form_contenedor">
                    <!-- Formulario de registro - Mediante el metodo POST -->
                    <form action="estudiantesAgregar.php" method="POST">
                        
                        <!-- Subtitulo -->
                        <h5>Formulario de registro</h5>

                        <!-- Contenedor de la imagen de ilustración -->
                        <div class="text-center">
                            <img src="../Recursos/pngwing - Estudiante Imagen.png" class="rounded" id="img_estudiante" alt="...">
                        </div>

                        <!-- Contenedor del input de nombre del estudiante -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Yorlin" required>
                        </div>

                        <!-- Contenedor del input de correo del estudiante -->
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo:</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="yorlin@edu.co.cr" required>
                        </div>

                        <!-- Botón de realizar el registro de estudiante -->
                        <button id="boton_registro" type="submit" class="btn btn-primary">Realizar registro</button>
                    </form>
                </div>
            </div>

        </section>

        <!-- integración del componente Footer al documento HTML -->
        <?php include "../componentes/footer.php"; ?>
        
    </div>        

</body>

<!-- Script JS de SweetAlert2 para Agregar estudiantes -->
<script>

    // SweetAlert2 para mostrar confirmación que el estudiante fue eliminado o si ocurrió un error.
    <?php if (!empty($mensaje)): ?>

        //Metodo SweetAlert2 - Mostración de información en la alerta.
        Swal.fire({
            title: '<?php echo $tipo == "success" ? "Ejecución correcta" : "Ejecución fallida"; ?>',  // Se utiliza operador ternario para mostrar el título correcto
            text: '<?php echo $mensaje; ?>', // Se muestra el mensaje que se colocó en la condicional del procedimiento almacenado
            icon: '<?php echo $tipo; ?>' // Se muestra el tipo de icono según el tipo de mensaje
        }).then(() => {
            
            ///Regirigir al documento HTML estudiantes.php
            window.location.href = 'estudiantes.php';
        });
        
        // Mostrar mensaje de error en consola.
        console.log('<?php echo $mensajeError; ?>');
    <?php endif; ?>
        
</script>

<!-- Integración de la liberia Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</html>