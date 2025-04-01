/* Función para capturar y mostrar texto en card */
function llenarCampo(valorInput, valorDisplay, valorPlaceHolder) {
  document.getElementById(valorInput).addEventListener("input", function () {
    document.getElementById(valorDisplay).innerText =
      this.value || valorPlaceHolder;
  });
}

document.getElementById("cargoInput").addEventListener("input", function () {
  document.getElementById("cargo-display").innerText = this.value || "CARGO";
});

llenarCampo("nombreInput", "nombre-display", "Nombre");
llenarCampo("equipoInput", "equipo-display", "Equipo");
llenarCampo("desafioInput", "desafio-display", "Desafios");
llenarCampo("interesInput", "interes-display", "Desafios");
// llenarCampo("apellidoInput", "apellido-display", "Apellido");
// llenarCampo("cargoInput", "cargo-display", "Cargo");

/* Función para capturar la fecha */
// document
//   .getElementById("fechaInput")
//   .addEventListener("change", function (event) {
//     const date = new Date(event.target.value);
//     const formattedDate =
//       ("0" + date.getDate()).slice(-2) +
//       "." +
//       ("0" + (date.getMonth() + 1)).slice(-2) +
//       "." +
//       date.getFullYear().toString().slice(-2);
//     document.getElementById("fecha-display").innerText = formattedDate;
//   });

document
  .getElementById("fechaInput")
  .addEventListener("change", function (event) {
    const [year, month, day] = event.target.value.split("-");
    const formattedDate = `${day}.${("0" + month).slice(-2)}.${year.slice(-2)}`;
    document.getElementById("fecha-display").innerText = formattedDate;
  });

document
  .getElementById("inputGroupFile02")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const img = document.getElementById("uploaded-image");
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

// document.getElementById("dl-png").onclick = function () {
//   const screenshotTarget = document.getElementById("example-table");

//   html2canvas(screenshotTarget).then((canvas) => {
//     const base64image = canvas.toDataURL("image/png");
//     var anchor = document.createElement("a");
//     anchor.setAttribute("href", base64image);
//     anchor.setAttribute("download", "my-image.png");
//     anchor.click();
//     anchor.remove();
//   });
// };

// document.getElementById("dl-png").onclick = function () {
//   const screenshotTarget = document.getElementById("example-table");

//   html2canvas(screenshotTarget).then((canvas) => {
//     // Crear un nuevo canvas para aplicar el filtro
//     const filteredCanvas = document.createElement("canvas");
//     filteredCanvas.width = canvas.width;
//     filteredCanvas.height = canvas.height;
//     const ctx = filteredCanvas.getContext("2d");

//     // Aplicar el filtro
//     ctx.filter = "grayscale(100%)";
//     ctx.drawImage(canvas, 0, 0);

//     // Crear la imagen base64
//     const base64image = filteredCanvas.toDataURL("image/png");
//     const anchor = document.createElement("a");
//     anchor.href = base64image;
//     anchor.download = "my-image.png";
//     anchor.click();
//   });
// };

// document.getElementById("dl-png").onclick = function () {
//   const screenshotTarget = document.getElementById("example-table");
//   const image = document.querySelector(".contenedor-imagen img");

//   // Aplicar temporalmente el filtro a la imagen
//   const originalFilter = image.style.filter;
//   image.style.filter = "grayscale(100%)";

//   html2canvas(screenshotTarget).then((canvas) => {
//     // Crear la imagen base64
//     const base64image = canvas.toDataURL("image/png");
//     const anchor = document.createElement("a");
//     anchor.href = base64image;
//     anchor.download = "my-image.png";
//     anchor.click();

//     // Revertir el filtro de la imagen
//     image.style.filter = originalFilter;
//   });
// };

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("dl-png").addEventListener("click", function () {
    var node = document.getElementById("example-table");
    var nombre = document.getElementById("nombreInput").value.trim();
    // .toLOweCarse(); // Obtener el valor del campo nombreInput

    domtoimage
      .toPng(node)
      .then(function (dataUrl) {
        var link = document.createElement("a");
        link.download = nombre + ".png";
        link.href = dataUrl;
        link.click();
      })
      .catch(function (error) {
        console.error("Error al capturar la imagen:", error);
      });
  });
});
