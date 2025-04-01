<?php
//show all errors php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


//si se envia el formulario


if (isset($_POST['nombre'])) {
    //obtenemos por get el nombre del usuario
    $nombre = $_POST['nombre'];
    //obtenemos el cargo
    $cargo = $_POST['cargo'];
    //obtenemos el equipo
    $equipo = $_POST['equipo'];
    //desafio
    $desafio = $_POST['desafio'];
    //intereses
    $intereses = $_POST['intereses'];
    //fecha
    $fecha = str_replace('-', '.', date("d-m-Y", strtotime($_POST['fecha'])));

    //imagen


    if (isset($_FILES['imagen']['name'])) {
        // The image ideal size is 640x427
        $desiredWidth = 640;
        $desiredHeight = 427;

        $imagen = $_FILES['imagen']['name'];
        // Random unique name + date today
        $imagen = uniqid() . date("Y-m-d h_i_s") . $imagen;

        $ruta = $_FILES['imagen']['tmp_name'];
        $destino = "../img/users_image/" . $imagen;

        // Get the original image dimensions
        list($originalWidth, $originalHeight) = getimagesize($ruta);

        // Calculate the proportional dimensions
        $proportionalWidth = $desiredWidth;
        $proportionalHeight = $originalHeight * ($desiredWidth / $originalWidth);

        // Create a new image with the desired dimensions
        $newImage = imagecreatetruecolor($proportionalWidth, $proportionalHeight);

        // Load the original image
        $originalImage = imagecreatefromjpeg($ruta);

        // Resize and crop the image to the desired dimensions
        imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $proportionalWidth, $proportionalHeight, $originalWidth, $originalHeight);

        // Save the resized image
        imagejpeg($newImage, $destino);

        // Free up memory
        imagedestroy($newImage);
        imagedestroy($originalImage);
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@200;400&display=swap" rel="stylesheet" /> -->
    <title>Bienvenida BBDO</title>
    <link rel="stylesheet" href="../css/bienvenida.css">
    <script src="../js/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

</head>
<style>
    /* Estilos generales del formulario */
    form {
        max-width: 650px;
        width: 100%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    /* Estilo para las etiquetas */
    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    /* Estilo para los campos de entrada */
    input[type="text"],
    input[type="date"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* Estilo para el botón de envío */
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-transform: uppercase;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    /* Pequeño espacio entre las etiquetas y los campos de entrada */
    label+input {
        margin-top: 10px;
    }

    /* text area no modificable*/
    textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        resize: none;

    }

    .d-none {
        display: none;

    }


    .desafio {
        min-height: 170px;
    }

    .descripcion {
        margin-left: -20px;
        margin-top: 20px;
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        text-transform: uppercase;
        font-size: 20px;
        font-family: "Barlow", sans-serif;
    }

    .contenedor-botones {
        display: flex;

        justify-content: center;
        align-items: center;
        margin-bottom: 20px;

    }

    .contenedor-botones button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-transform: uppercase;
        margin-right: 10px;
    }

    .contenedor-botones button:hover {
        background-color: #45a049;
    }
</style>

<body>
    <div class="main-container formulario  <? if (isset($nombre)) {
                                                echo "d-none";
                                            } ?>">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre de la persona:</label><br>
            <input type="text" id="nombre" name="nombre"><br>

            <label for="cargo">Cargo que le corresponde:</label><br>
            <input type="text" id="cargo" name="cargo"><br>

            <label for="cargo">Equipo a cual pertenece:</label><br>
            <input type="text" id="equipo" name="equipo"><br>

            <label for="desafio">Desafío de la persona:</label><br>
            <!-- <input type="text" id="desafio" name="desafio"><br> -->
            <!-- text area-->
            <textarea name="desafio" id="desafio" cols="30" rows="10"></textarea>

            <label for="intereses">Intereses de la persona:</label><br>
            <!-- <input type="text" id="intereses" name="intereses"><br> -->
            <textarea name="intereses" id="intereses" cols="30" rows="10"></textarea>

            <label for="fecha">Fecha de ingreso</label><br>
            <input type="date" id="fecha" name="fecha"><br><br>
            <label for="imagen">Imagen de la persona (ideal que la imagen sea de 640x427):</label><br>
            <input type="file" id="imagen" name="imagen"><br><br>

            <input type="submit" value="Enviar">

        </form>

    </div>
    <main class="main-container salida <? if (!isset($nombre)) {
                                            echo "d-none";
                                        } ?>">

        <section class="bienvenida-container">
            <header>
                <div class="contenedor-imagen mask1">
                    <img class="mask-logo" src="../img/logo-bbdo-bienvenidax650.png" alt="">
                    <img src="../img/users_image/<?php echo $imagen; ?>" alt="">
                </div>
            </header>

            <section class="info-container">
                <div class="logo-vertical">
                    <img src="../img/logo-bbdo-vertical.png" alt="">
                </div>
                <div class="info-persona">
                    <div class="nombre">
                        <p><? echo $nombre; ?></p>
                    </div>
                    <div class="cargo">
                        <p>*&nbsp;&nbsp;&nbsp;&nbsp;<? echo $cargo; ?></p>
                    </div>

                    <div class="equipo">
                        <p>*&nbsp;&nbsp;&nbsp;&nbsp;Equipo: <? echo $equipo; ?></p>
                    </div>

                    <div class="descripcion">
                        <span>bienvenidx</span>
                    </div>
                </div>
                <div class="formas-left">
                    <div class="globo-rojo">
                        <img src="../img/globo-bienvenida-rojo.png" alt="">
                    </div>
                </div>
            </section>

            <section class="info-desafio">
                <div class="fecha-ingreso">
                    <p><? echo $fecha; ?></p>
                </div>

                <div class="contenedor-desafio-intereses">
                    <div class="desafio">
                        <span>¿Cuál es tu desafío en BBDO?</span>
                        <p><? echo $desafio; ?>
                        </p>
                    </div>

                    <div class="intereses">
                        <span>¿Cualés son tus intereses?</span>
                        <p><? echo $intereses; ?>
                        </p>
                    </div>
                </div>

                <div class="onda-fuscia">
                    <img src="../img/ondas-fucsia-bienvenida-left.png" alt="">
                </div>
            </section>

        </section>
    </main>
    <?php if (isset($nombre)) {
        echo "<div class='contenedor-botones'>
        <button class='boton-volver' onclick='window.location.href=\"bienvenida.php\"'>Volver</button>
        <button class='boton-descargar'>Descargar</button>
    </div>";
    } ?>

    <!-- <div class="contenedor-botones">
        <button class="boton-volver" onclick="window.location.href='../../index.php'">Volver</button>
        <button class="boton-descargar">Descargar</button>
    </div> -->

</body>


<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission

        // Get form inputs
        var nombre = document.getElementById('nombre').value;
        var cargo = document.getElementById('cargo').value;
        var equipo = document.getElementById('equipo').value;
        var desafio = document.getElementById('desafio').value;
        var intereses = document.getElementById('intereses').value;
        var fecha = document.getElementById('fecha').value;

        // Validate inputs
        if (nombre.trim() === '') {
            alert('Por favor ingrese un nombre válido.');
            return;
        }

        if (nombre.length < 3) {
            alert('El nombre debe tener al menos 3 caracteres.');
            return;
        }

        if (cargo.trim() === '') {
            alert('Por favor ingrese un cargo válido.');
            return;
        }

        if (cargo.length < 3) {
            alert('El cargo debe tener al menos 3 caracteres.');
            return;
        }

        if (equipo.trim() === '') {
            alert('Por favor ingrese un equipo válido.');
            return;
        }

        if (desafio.trim() === '') {
            alert('Por favor ingrese un desafío válido.');
            return;
        }

        if (intereses.trim() === '') {
            alert('Por favor ingrese intereses válidos.');
            return;
        }

        if (fecha.trim() === '') {
            alert('Por favor ingrese una fecha de ingreso válida.');
            return;
        }

        // If all inputs are valid, submit the form
        this.submit();
    });

    document.querySelector('.boton-descargar').addEventListener('click', function() {
        html2canvas(document.querySelector('.salida'), {
            allowTaint: true,
            useCORS: true
        }).then(canvas => {
            console.log("descargando");
            // Crear un enlace para descargar
            let link = document.createElement('a');
            link.download = 'imagen.png';
            link.href = canvas.toDataURL();
            link.click();
        });


        let node = document.querySelector('.salida')
        let nombre = document.querySelector('.nombre p').innerText;
        let slug = nombre.toLowerCase().replace(/\s+/g, '-');

        domtoimage.toPng(node)
            .then(dataUrl => {
                var img = new Image();
                img.src = dataUrl;
                document.body.appendChild(img);

            })
        domtoimage.toBlob(node)
            .then(function(blob) {
                window.saveAs(blob, slug);
                //contenedor-botones css display block


            });

    });
</script>


</html>