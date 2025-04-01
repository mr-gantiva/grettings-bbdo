<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cumple BBDO</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@200;400&display=swap" rel="stylesheet" />
  <!-- <link rel="stylesheet" href="../css/style.css" /> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>

</head>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  @font-face {
    font-family: "partita";
    src: url(https://res.cloudinary.com/kenobi22v/raw/upload/v1711489446/fonts/Partita-Regular_sh5rik.otf) format("opentype");
  }

  /*INDEX.HTML*/
  .main-container-index {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #edf1f7;

    .btn-accesos {
      display: flex;
      gap: 50px;

      a {
        text-decoration: none;
        background: #ffffff;
        padding: 20px;
        font-family: "Barlow", sans-serif;
        color: #db0000;
        font-weight: 600;
        border-radius: 5px;
        border: 1px solid rgb(102, 102, 102, 0.2);
      }
    }
  }

  body {
    width: 100%;
  }

  .main-container {
    width: 100%;
    display: flex;
    gap: 40px;
    justify-content: center;
    align-items: center;
  }

  .formulario-container {
    display: flex;
    flex-direction: column;
    gap: 40px;

    .formulario-imagen {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .formulario-info {
      display: flex;
      flex-direction: column;
      gap: 40px;
    }
  }

  .card-container {
    background: #edf1f7;
    min-width: 650px;
    max-width: 650px;
  }

  .title-container {
    position: relative;
    line-height: 0.8;

    .name-container,
    .lastname-container {
      display: flex;
      font-size: 90px;
      font-family: "Barlow", sans-serif;
      z-index: 10;
      text-transform: capitalize;
    }

    .name-container {
      position: relative;
      margin-top: 12px;
      margin-left: 4px;
    }

    .lastname-container {
      position: relative;
      justify-content: end;
      margin-right: 4px;
      z-index: 100;

      p {
        color: #db0000;
        font-family: "partita", sans-serif;
        font-size: 14px;
      }
    }

    .date-container {
      position: absolute;
      top: 60px;
      right: 100px;
      font-family: "partita", sans-serif;
    }

    .position-container {
      display: flex;
      justify-content: center;
      margin-top: 15px;
      margin-left: 10px;

      p {
        color: #db0000;
        font-family: "partita", sans-serif;
        font-weight: lighter;
      }
    }

    .globos {
      position: absolute;
      top: 70px;
      z-index: 11;

      img {
        width: 230px;
      }
    }
  }

  .picture-section {
    position: relative;
    margin: 110px 0 20px 0;

    .picture-container {
      position: relative;

      img {
        width: 100%;
        height: 553px;
        object-fit: cover;
        /* filter: grayscale(100%); */
      }

      /* .mask1 {
        -webkit-mask-image: url(https://www.desarrolloflare.cl/sitios/cumple_bbdo/assets/img/logo-bbdox650.png);
        mask-image: url(https://www.desarrolloflare.cl/sitios/cumple_bbdo/assets/img/logo-bbdox650.png);
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
      } */
      .logo-bbdo {
        position: relative;
        z-index: 10;
      }
    }

    .cintas {
      position: absolute;
      bottom: -10px;
      right: 0;

      img {
        width: 160px;
      }
    }

  }

  .mensaje-section {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-left: 30px;
    position: relative;
    margin-bottom: 47px;

    .logo-vertical {
      img {
        width: 34px;
      }
    }

    .descripcion {
      span {
        font-family: "Barlow", sans-serif;
        font-size: 65px;
        font-weight: lighter;
        line-height: 0.9;
      }
    }

    .globo-footer {
      position: absolute;
      top: 50px;
      right: 0;

      img {
        width: 180px;
      }
    }
  }



  /* Estilos Generales del Formulario */
  form {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  /* Estilizando cada campo del formulario */
  form input[type="text"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
  }

  /* Estilo del botón de envío */
  form input[type="submit"] {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    color: white;
    background-color: #007bff;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  form input[type="submit"]:hover {
    background-color: #0056b3;
  }

  /* Estilos para mantener los elementos centrados y con una estética agradable */
  body {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #eee;
  }

  /* Estilo para los labels del formulario, para una mejor legibilidad */
  form label {
    display: block;
    margin-bottom: 5px;
    color: #666;
  }
</style>

<body>

  <?php
  $nombre = $fecha = $apellido = $cargo = $imagen = "";
  $mostrarContenedor = false;

  // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagen"])) {
  //     $target_dir = "uploads/"; // Asegúrate de que este directorio exista y tenga permisos de escritura.
  //     $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
  //     $uploadOk = 1;
  //     $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  //     // Comprobar si el archivo es realmente una imagen
  //     if (isset($_POST["submit"])) {
  //         $check = getimagesize($_FILES["imagen"]["tmp_name"]);
  //         if ($check !== false) {
  //             echo "El archivo es una imagen - " . $check["mime"] . ".";
  //             $uploadOk = 1;
  //         } else {
  //             echo "El archivo no es una imagen.";
  //             $uploadOk = 0;
  //         }
  //     }

  //     // Intentar subir el archivo
  //     if ($uploadOk == 0) {
  //         echo "Lo siento, tu archivo no se pudo subir.";
  //     } else {
  //         if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
  //             echo "El archivo ". htmlspecialchars(basename($_FILES["imagen"]["name"])). " ha sido subido.";
  //         } else {
  //             echo "Lo siento, hubo un error subiendo tu archivo.";
  //         }
  //     }
  // }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    $apellido = $_POST["apellido"];
    $cargo = $_POST["cargo"];
    $mostrarContenedor = true;
  }
  ?>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Nombre: <input type="text" name="nombre" value="<?php echo $nombre; ?>"><br>
    Fecha: <input type="text" name="fecha" value="<?php echo $fecha; ?>"><br>
    Apellido: <input type="text" name="apellido" value="<?php echo $apellido; ?>"><br>
    Cargo: <input type="text" name="cargo" value="<?php echo $cargo; ?>"><br>

    <input type="submit" name="submit" value="Enviar">
  </form>
  <?php
  if ($mostrarContenedor) {
    echo '
        <main class="main-container">
            <section class="card-container" id="htmlcapture">
                <div class="title-container">
                    <div class="name-container">
                        <span id="nombre">' . htmlspecialchars($nombre) . '</span>
                    </div>
                    <div class="date-container">
                        <span id="fecha" class="partita">' . htmlspecialchars($fecha) . '</span>
                    </div>
                    <div class="lastname-container">
                        <span id="apellido">' . htmlspecialchars($apellido) . '</span>
                    </div>
                    <div class="position-container">
                        <p id="cargo" class="partita">*   ' . htmlspecialchars($cargo) . '</p>
                    </div>
                    <div class="globos">
                        <div class="globos-container">
                            <img src="../img/globos-izquierda.png" alt="" />
                        </div>
                    </div>
                </div>
                <div class="picture-section">
                    <div class="picture-container">
                        <div class="contenedor-imagen">
                        <!--<img class="logo-bbdo" src="../img/logo-bbdo-cumpleax650.png/">-->
                        <img class="torta" id="imagen" src="../img/logo-bbdo-cumpleax650.png" alt="Cinque Terre" />
                        </div>
                        <canvas id="canvas" style="display: none"></canvas>
                    </div>
                    <div class="cintas">
                        <div class="cintas-container">
                            <img src="../img/cintas-derecha.png" alt="" />
                        </div>
                    </div>
                </div>
                <div class="mensaje-section">
                    <div class="logo-vertical">
                        <img src="../img/logo-bbdo-vertical.png" alt="" />
                    </div>
                    <div class="descripcion">
                        <span>
                            FELIZ<br />
                            CUMPLEAÑOS
                        </span>
                    </div>
                    <div class="globo-footer">
                        <div class="globo-footer-container">
                            <img src="../img/globos-rojos-puntos.png" alt="" />
                        </div>
                    </div>
                </div>
            </section>
        </main>
        ';
  }

  ?>
  <button onclick="captureContent()">Generar Imagen</button>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js" integrity="sha512-01CJ9/g7e8cUmY0DFTMcUw/ikS799FHiOA0eyHsUWfOetgbx/t6oV4otQ5zXKQyIrQGTHSmRVPIgrgLcZi/WMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="/assets/js/script.js"></script>

  <script>
    function captureContent() {
      html2canvas(document.getElementById("htmlcapture")).then(canvas => {
        var image = canvas.toDataURL("image/png", 1.0);
        var link = document.createElement('a');
        link.download = 'mi-imagen.png';
        link.href = image;
        link.click();
      });
    }
  </script>
</body>

</html>