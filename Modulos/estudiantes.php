<?php

// Llamado al archivo de conexión a la base de datos.
require_once '../BaseDatos/db_conexion.php';

// Asignar variable a la función de conexión a la base de datos.
$conexion = db_conexion();

// Variable para almacenar mensajes de error
$mensajeError = "";

// Función para obtener la información del procedimiento "P_ObtenerEstudiantes".
function obtenerEstudiantes($conexion, $mensajeError) {

    // Utilización de Try/Catch para manejo de errores.
    try {
        // Consulta al procedimiento de obtener estudiantes.
        $sentenciaSQL = "CALL P_ObtenerEstudiantes()";
        $stmt = $conexion->query($sentenciaSQL);
        
        // Manejar errores
        if (!$stmt) {
            throw new Exception("Error en la consulta: " . $conexion->error);
        }
        
        // Guardar los resultados en un arreglo.
        $estudiantes = [];
        while ($columna = $stmt->fetch_assoc()) {
            $estudiantes[] = $columna;
        }
        
        // Cerrar la declaración
        $stmt->close();

        // Retornamos los resultados.
        return $estudiantes;

    } catch (Exception $e) {

        // Asignar el mensaje de error a la variable
        $mensajeError = $e->getMessage();

        //Mostrar mensaje de error en consola.
        echo "<script>
        console.error('Ha ocurrido un error de consulta: " . $mensajeError . "');
        </script>";

        /* 
        Script JS para mostrar un mensaje de error al usuario en caso de un error en la ejecución del procedimiento.
        Además, utiliza el evento DOMContentLoaded para que la ejecución del JS de SweetAlert2 se muestre luego de cargar el HTML.
        */
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Ejecución fallida',
                    text: 'Lo sentimos, ha ocurrido un error con el servidor de base de datos. Favor contactar al equipo de TI.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    window.location.href = '../index.php'; 
                });
            });
        </script>";

        return [];
    }
}

/* 
Metodo GET para utilizar el procedimiento de eliminar estudiantes por ID, se utiliza ISSET para verificar que el GET no este nulo.
*/
if (isset($_GET['eliminar_id'])) {
    $id = intval($_GET['eliminar_id']); // Convertimos el ID en un entero.

    // Utilización de Try/Catch para manejo de errores.
    try {
        
        // Consulta al procedimiento para la ejecución de eliminar estudiante por id.
        
        $sentenciaSQL = "CALL P_EliminarEstudianteID(?)";
        $stmt = $conexion->prepare($sentenciaSQL);

        // Preparar la consulta con STMT para evitar inyección SQL.
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Condicionales para mostrar mensaje de Eliminación exitosa / no se encontro estudiante / Ocurrió un error.
        if ($stmt->affected_rows > 0) {
            $mensaje = "Se ha eliminado el estudiante correctamente.";
            $tipo = "success";
        } else {
            $mensaje = "Lo sentimos, no se encontro estudiante a eliminar. Favor verificar.";
            $tipo = "error";
        }
    } catch (Exception $e) {
        $mensaje = "Lo sentimos, ha ocurrido un error con la función de eliminar. Favor contactar al equipo de TI.";
        $mensajeError = "Ha ocurrido un error de consulta: " . $e->getMessage();
        $tipo = "error";
    }
    // Estas variables se utiliza en los script de SweetAlert2 para mostrar los mensajes respectivos.
}

// Llamado a la función de obtener Estudiantes.
$estudiantes = obtenerEstudiantes($conexion, $mensajeError);

// Cerrar la conexión
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
    <link rel="stylesheet" href="../estilos/estudiantes.css">

</head>

<body>

    <!-- div para centar el contenido en pantalla (Nav - section - footer) -->
    <div id="contenido">

        <!-- integración del componente NavBar al documento HTML -->
        <?php include "../componentes/navbar.php"; ?>

        <!-- Section del documento HTML -->
        <section>

            <!-- Contenedor de la tabla de registros de estudiantes -->
            <div class="container">

                <!-- Titulo -->
                <h1 class="text-center mt-3">Listado de estudiantes</h1>  

                <!-- Contenedor de botones funciones -->
                <div class="d-flex mb-3">

                    <!-- Botón de Registrar nuevo estudiante -->
                    <div class="p-2">
                        <a href="estudiantesAgregar.php" class="btn btn-success">Registrar nuevo estudiante</a>
                    </div>

                    <!-- Botón de Regreso al menu principal -->
                    <div class="ms-auto p-2">
                        <a href="../index.php" class="btn btn-secondary">Regresar</a>
                    </div>
                </div>

                <!-- Tabla de Registros -->
                <table class="table table-striped mt-3">

                    <!-- Encabezados de tabla -->
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Fecha de creación</th>
                        </tr>
                    </thead>

                    <!-- Filas de registros de estudiantes -->
                    <tbody>

                        <!-- Ciclo Foreach mostrar los datos de los estudiantes del array -->
                        <?php foreach ($estudiantes as $estudiante): ?>
                        <tr>
                            <td>
                                <!-- Botón de editar -->
                                <a href="estudiantesEditar.php?id=<?php echo $estudiante['IdEstudiante']; ?>" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                
                                <!-- Botón de Eliminar -->
                                <button class="btn btn-danger" onclick="confirmarEliminacion(<?php echo $estudiante['IdEstudiante']; ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>

                            <!-- Columnas de los registros de estudiantes -->
                            <td><?php echo htmlspecialchars($estudiante['IdEstudiante']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['NombreEstudiante']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['CorreoEstudiante']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['FechaCreacion']); ?></td>  
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- integración del componente Footer al documento HTML -->
        <?php include "../componentes/footer.php"; ?>
        
</body>

<!-- Script JS de SweetAlert2 para Eliminar estudiantes -->
<script>
        // SweetAlert2 para confirmar la eliminación de un estudiante.
        function confirmarEliminacion(id) {

            //Metodo SweetAlert2 - Mostración de información en la alerta.
            Swal.fire({
                title: 'Confirmación de ejecución',
                text: 'Al eliminar el registro no podra recuperarse.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                //Ejecución del SweetAlert
                if (result.isConfirmed) {

                    //Enviar ID del estudiante a eliminar al metodo PHP del procedimiento de eliminar.
                    window.location.href = 'estudiantes.php?eliminar_id=' + id;
                }
            });
        }

        // SweetAlert2 para mostrar confirmación que el estudiante fue eliminado o si ocurrió un error
        <?php if (!empty($mensaje)): ?>

            //Metodo SweetAlert2 - Mostración de información en la alerta.
            Swal.fire({
                title: '<?php echo $tipo == "success" ? "Ejecución correcta" : "Ejecución fallida"; ?>',  // Se utiliza operador ternario para mostrar el título correcto
                text: '<?php echo $mensaje; ?>', // Se muestra el mensaje que se colocó en la condicional del procedimiento almacenado
                icon: '<?php echo $tipo; ?>' // Se muestra el tipo de icono según el tipo de mensaje
            }).then(() => {

                //Refrescar documento HTML
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