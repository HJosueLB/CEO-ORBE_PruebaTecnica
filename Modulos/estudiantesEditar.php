<?php

// Llamado al archivo de conexión a la base de datos.
require_once '../BaseDatos/db_conexion.php';

// Asignar variable a la función de conexión a la base de datos.
$conexion = db_conexion();

// Variable para almacenar mensajes de error
$mensajeError = "";

// Verificar la llegada del ID desde estudiantes.php y obtener los datos con el procedimiento CALL P_ObtenerEstudianteID(?) 
try {
    // Verificar la llegada del ID desde estudiantes.php
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        // Consulta al procedimiento de obtener estudiante por id.
        $sentenciaSQL = "CALL P_ObtenerEstudianteID(?)";

        // Preparar la consulta con STMT para evitar inyección SQL.
        $stmt = $conexion->prepare($sentenciaSQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Guardar los resultados en un arreglo
        $resultado = $stmt->get_result();

        // Manejo de errores
        if ($resultado->num_rows == 1) {
            $estudiante = $resultado->fetch_assoc();
        } else {
            throw new Exception("Estudiante no se ha encontrado en la base de datos.");
        }

        // Liberar los resultados de la primera consulta
        $resultado->free();
        $stmt->close();
    } else {
        throw new Exception("El ID del estudiante no ha sido recibido.");
    }
} catch (Exception $e) {

     // Asignar el mensaje de error a la variable
     $mensajeError = $e->getMessage();

     //Mostrar mensaje de error en consola.
     echo "<script>
     console.error('Ha ocurrido un error de consulta: " . $mensajeError . "');
     </script>";

    // Si ocurre algún error, mostramos el mensaje con SweetAlert
    echo "
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Ejecución fallida',
                text: 'Lo sentimos, ha ocurrido un error con la función de editar. Favor contactar al equipo de TI.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'estudiantes.php';
            });
        });
    </script>";
}

// Procedimiento POST para actualizar el estudiante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['correo'])) {
    
    try {

        //Declaración de variables
        $idEstudiante = intval($_POST['id']); 
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];

        // Consulta al procedimiento de editar estudiante.
        $sentenciaSQL = "CALL P_EditarEstudiantes(?, ?, ?)";

        // Preparar la consulta con STMT para evitar inyección SQL.
        $stmt = $conexion->prepare($sentenciaSQL);

        // Vincular los parámetros con las variables (iss: i = integer / s = string)
        $stmt->bind_param("iss", $idEstudiante, $nombre, $correo);

        // Condicionales para mostrar mensaje de: Eliminación exitosa / no se encontro estudiante / Ocurrió un error.
        if ($stmt->execute()) {
            $mensaje = "Se ha actualizado correctamente el registro.";
            $tipo = "success";
        } 

        // Cerrar la declaración
        $stmt->close();

    } catch (Exception $e) {
        $mensaje = "Lo sentimos, Ha fallado la ejecución la actualización del registro del estudiante. Favor contactar al equipo de TI.";
        $mensajeError = "Ha ocurrido un error de consulta: " . $e->getMessage();
        $tipo = "error";
    }

    //Los mensajes anteriores correspondiente de las condicionales de la ejecución del procedimiento son llamados por los JS de SweetAlert
    
}

// Cerrar la conexión
$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar estudiante</title>

    <!-- Integración de la liberia Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Integración de la liberia Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Integración de la liberia SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Llamar estilos CSS para el documento HTML -->
    <link rel="stylesheet" href="../estilos/estudiantesEditar.css">

</head>

<body>
    
    <!-- div para centar el contenido en pantalla (Nav - section - footer) -->
    <div id="contenido">

    <!-- integración del componente NavBar al documento HTML -->
    <?php include "../componentes/navbar.php"; ?>

    <!-- Section del documento HTML -->
    <section>

        <!-- Titulo -->
        <h1 class="text-center mt-3">Editar información de estudiante</h1>

        <!-- Contenedor del formulario de Editar -->
        <div class="container">

            <!-- Contenedor del botón de regreso-->
            <div class="d-flex mb-3">
                    <div class="ms-auto p-2">
                        <a href="../Modulos/estudiantes.php" class="btn btn-secondary">Regresar</a>
                    </div>
            </div>

            <div class="container" id="form_contenedor">

                <!-- Formulario: Obtiene los datos del estudiante del procedimiento obtenerEstudianteID y adicional procede con 
                la actualización del estudiante bajo el metodo POST -->
                <form action="estudiantesEditar.php?id=<?php echo $id; ?>" method="POST">

                    <!-- Subtitulo -->
                    <h5>Formulario de edición de datos</h5>

                    <!-- Contenedor de la imagen de ilustración -->
                    <div class="text-center">
                        <img src="../Recursos/pngwing - Estudiante Imagen.png" class="rounded" id="img_estudiante" alt="...">
                    </div>

                    <!-- Campos no editables - Se oculta ID del estudiante -->
                    <input type="hidden" name="id" value="<?php echo $estudiante['IdEstudiante']; ?>">

                    <!-- Campos editables -->

                    <!-- Contenedor del input de nombre del estudiante -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($estudiante['NombreEstudiante']); ?>" required>
                    </div>

                    <!-- Contenedor del input de correo del estudiante -->
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <label for="correo">Correo:</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($estudiante['CorreoEstudiante']); ?>" required>
                    </div>
                    <!-- Botón de realizar la actualización de datos del estudiante -->
                    <button id="boton_actualizar" type="submit" class="btn btn-primary">Realizar actualización</button>

                </form>
            </div>
        </div>
        
    </section>

    <!-- integración del componente Footer al documento HTML -->
    <?php include "../componentes/footer.php"; ?>

</div>  

</body>

<!-- Script JS de SweetAlert2 para Actualizar estudiantes -->
<script>

    // SweetAlert2 para mostrar confirmación que el estudiante fue eliminado o si ocurrió un error.
    <?php if (!empty($mensaje)): ?>

        //Metodo SweetAlert2 - Mostración de información en la alerta.
        Swal.fire({
            title: '<?php echo $tipo == "success" ? "Ejecución correcta" : "Ejecución fallida"; ?>',  // Se utiliza operador ternario para mostrar el título correcto
            text: '<?php echo $mensaje; ?>', // Se muestra el mensaje que se colocó en la condicional del procedimiento almacenado
            icon: '<?php echo $tipo; ?>' // Se muestra el tipo de icono según el tipo de mensaje
        }).then(() => {
            
            //Regirigir al documento HTML estudiantes.php
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