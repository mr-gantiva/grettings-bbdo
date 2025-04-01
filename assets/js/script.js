/* Función para capturar y mostrar texto en card */
function llenarCampo(valorInput, valorDisplay, valorPlaceHolder) {
  document.getElementById(valorInput).addEventListener("input", function () {
    document.getElementById(valorDisplay).innerText =
      this.value || valorPlaceHolder;
  });
}

document.getElementById("cargoInput").addEventListener("input", function () {
  document.getElementById("cargo-display").innerText =
    "*    " + (this.value || "CARGO");
});

llenarCampo("nombreInput", "nombre-display", "Nombre");
llenarCampo("apellidoInput", "apellido-display", "Apellido");
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
