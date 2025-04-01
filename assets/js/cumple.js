/* Función para capturar y mostrar texto en card */
function llenarCampo(valorInput, valorDisplay, valorPlaceHolder) {
  document.getElementById(valorInput).addEventListener("input", function () {
    document.getElementById(valorDisplay).innerText =
      this.value || valorPlaceHolder;
  });
}

llenarCampo("nombreInput", "nombre-display", "Nombre");
llenarCampo("apellidoInput", "apellido-display", "Apellido");

/* Capturar la fecha recibida por input en DDMMAA */
document
  .getElementById("fechaInput")
  .addEventListener("change", function (event) {
    const [year, month, day] = event.target.value.split("-");
    const formattedDate = `${day}.${("0" + month).slice(-2)}.${year.slice(-2)}`;
    document.getElementById("fecha-display").innerText = formattedDate;
  });

/* Capturar el cargo */
document.getElementById("cargoInput").addEventListener("input", function () {
  document.getElementById("cargo-display").innerText =
    "*    " + (this.value || "CARGO");
});

/*  */
document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("dl-png").addEventListener("click", function () {
    var node = document.getElementById("htmlcapture");
    var nombre = document.getElementById("nombreInput").value.trim();
    var apellido = document.getElementById("apellidoInput").value.trim();
    // .toLOweCarse(); // Obtener el valor del campo nombreInput

    domtoimage
      .toPng(node)
      .then(function (dataUrl) {
        var link = document.createElement("a");
        link.download = `${nombre} ${apellido}`;
        link.href = dataUrl;
        link.click();
      })
      .catch(function (error) {
        console.error("Error al capturar la imagen:", error);
      });
  });
});
