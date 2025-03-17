<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu principal</title>

    <!-- Integración de la liberia Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Integración de la liberia Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Integración de la liberia SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Llamar estilos CSS para el documento HTML -->
    <link rel="stylesheet" href="estilos/index.css">

</head>

<body>
  
  <!-- div para centar el contenido en pantalla (Nav - section - footer) -->
  <div id="contenido">

    <!-- integración del componente NavBar al documento HTML -->
    <?php include "componentes/navbar-index.php"; ?>

    <!-- Section del documento HTML -->
    <section>

      <!-- Contenedor de la tabla de registros de estudiantes -->
      <div class="container">

        <!-- Titulo -->
        <h3 class="text-center mt-3">Portal de Gestión Estudiantil</h3> 

        <!-- Subtitulo -->
        <h4 class="text-center mt-3">Menú principal</h4> 

        <!-- Contenedor del modulo de Estudiantes -->
        <div class="container" id="Modulos-Carta">
          <div class="col-sm-3 mb-3 mb-sm-0">
            <div class="card"style="width: 18rem;">
            <img src="Recursos/Freepick - CheckList icon.png" class="card-img-top" id="img_listaEstudiantes" alt="..." >
              <div class="card-body">
                <h5 class="card-title">Estudiantes</h5>
                <p class="card-text">Gestión de estudiantes</p>
                <a href='Modulos/estudiantes.php' class="btn btn-primary">Ingresar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </section>

    <!-- integración del componente Footer al documento HTML -->
    <?php include "componentes/footer.php"; ?>

  </div>
</body>

<!-- Integración de la liberia Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</html>