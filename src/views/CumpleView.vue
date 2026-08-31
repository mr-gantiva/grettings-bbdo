<script setup>
import { nextTick, ref } from "vue";
import {
  useDescargaImagen,
  formatearFecha,
  esperarPintado,
} from "../composables/useDescargaImagen";
import { crearEditor } from "../composables/useEditorTarjeta";
import ElementoEditable from "../components/ElementoEditable.vue";
import PanelEditor from "../components/PanelEditor.vue";
import * as xlsx from "xlsx";
import JSZip from "jszip";
import { saveAs } from "file-saver";
import domtoimage from "dom-to-image";

const { descargarNodo } = useDescargaImagen();
const editor = crearEditor();

// Nodo que se captura como PNG (equivale a #htmlcapture)
const tarjeta = ref(null);

// Valores del formulario
const nombreInput = ref("");
const apellidoInput = ref("");
const cargoInput = ref("");
const fechaInput = ref("");
const procesandoExcel = ref(false);
const procesandoPaso = ref(false);
const registrosExcel = ref([]);
const indiceActual = ref(0);
let zipActual = null;

/*
  Igual que en Bienvenida: el texto inicial es el que trae el HTML original
  y solo cambia a "valor || placeholder" cuando el campo se toca.
*/
const nombreDisplay = ref("NOMBRE");
const apellidoDisplay = ref("APELLIDO");
const fechaDisplay = ref(" 12.07.23 ");
const cargoDisplay = ref("*" + " ".repeat(4) + " DIRECTOR DE ARTE");

function onNombre() {
  nombreDisplay.value = nombreInput.value || "Nombre";
}
function onApellido() {
  apellidoDisplay.value = apellidoInput.value || "Apellido";
}
function onCargo() {
  cargoDisplay.value = "*    " + (cargoInput.value || "CARGO");
}
function onFecha() {
  if (!fechaInput.value) {
    fechaDisplay.value = " 12.07.23 ";
    return;
  }
  fechaDisplay.value = formatearFecha(fechaInput.value);
}

async function descargar() {
  const nombre = nombreInput.value.trim();
  const apellido = apellidoInput.value.trim();
  // Apaga contornos y etiquetas del editor antes de capturar
  editor.exportando.value = true;
  editor.deseleccionar();
  await nextTick();
  await esperarPintado();
  try {
    // El original no agrega extensión al nombre del archivo
    await descargarNodo(tarjeta.value, `${nombre} ${apellido}`);
  } finally {
    editor.exportando.value = false;
  }
}

async function cargarExcel(event) {
  const file = event.target.files[0];
  if (!file) return;

  procesandoExcel.value = true;
  const reader = new FileReader();

  reader.onload = async (e) => {
    try {
      const data = new Uint8Array(e.target.result);
      const workbook = xlsx.read(data, { type: "array" });
      const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
      const rows = xlsx.utils.sheet_to_json(firstSheet).filter(r => r["Nombre"]); // Filtrar filas vacías

      if (rows.length === 0) {
        alert("No se encontraron registros válidos en el Excel.");
        procesandoExcel.value = false;
        return;
      }

      registrosExcel.value = rows;
      zipActual = new JSZip();
      indiceActual.value = 0;
      
      cargarRegistroActual();
    } catch (error) {
      console.error("Error leyendo Excel", error);
      alert("Hubo un error leyendo el Excel. Revisa la consola.");
    } finally {
      procesandoExcel.value = false;
      event.target.value = ""; // Limpiar input file
    }
  };
  reader.readAsArrayBuffer(file);
}

function cargarRegistroActual() {
  const row = registrosExcel.value[indiceActual.value];

  // Restablecer posiciones y tamaños del editor para que la nueva tarjeta
  // empiece limpia y no herede las ediciones de la tarjeta anterior.
  editor.restablecerTodo();

  nombreInput.value = String(row["Nombre"]).trim();
  let apellido = String(row["Apellido Paterno"] || "").trim();
  if (row["Apellido Materno"]) {
    apellido += " " + String(row["Apellido Materno"]).trim();
  }
  apellidoInput.value = apellido;
  cargoInput.value = String(row["Cargo"] || "").trim();

  // Procesar la fecha de Excel
  let fecha = row["Fecha nacimiento"];
  const currentYear = new Date().getFullYear();
  
  if (typeof fecha === "number") {
    let dateObj = new Date(Math.round((fecha - 25569) * 86400 * 1000));
    dateObj = new Date(dateObj.getTime() + dateObj.getTimezoneOffset() * 60000);
    if (!isNaN(dateObj)) {
      fechaInput.value = `${currentYear}-${String(dateObj.getMonth() + 1).padStart(2, "0")}-${String(dateObj.getDate()).padStart(2, "0")}`;
    }
  } else if (row["Día"] && row["Mes"]) {
    fechaInput.value = `${currentYear}-${String(row["Mes"]).padStart(2, "0")}-${String(row["Día"]).padStart(2, "0")}`;
  } else {
    fechaInput.value = "";
  }

  onNombre();
  onApellido();
  onCargo();
  onFecha();
}

async function aprobarYSiguiente() {
  procesandoPaso.value = true;
  
  // Capturar tarjeta
  editor.exportando.value = true;
  editor.deseleccionar();
  await nextTick();
  await esperarPintado();
  await new Promise((resolve) => setTimeout(resolve, 100)); // tiempo extra para fuentes

  try {
    const blob = await domtoimage.toBlob(tarjeta.value);
    zipActual.file(`${nombreInput.value} ${apellidoInput.value}.png`, blob);
  } catch(error) {
    console.error("Error al capturar", error);
    alert("Error capturando esta tarjeta.");
  } finally {
    editor.exportando.value = false;
    procesandoPaso.value = false;
  }

  if (indiceActual.value < registrosExcel.value.length - 1) {
    indiceActual.value++;
    cargarRegistroActual();
  } else {
    finalizarExcel();
  }
}

async function finalizarExcel() {
  try {
    const content = await zipActual.generateAsync({ type: "blob" });
    saveAs(content, "Tarjetas_Cumpleaños.zip");
  } catch(e) {
    alert("Error al generar el ZIP");
  } finally {
    cancelarExcel(); // limpiar estado
  }
}

function cancelarExcel() {
  registrosExcel.value = [];
  zipActual = null;
  indiceActual.value = 0;
}
</script>

<template>
  <main class="container main-container">
    <section class="col-12 col-md-5 contenedor-formulario">
      <div class="panel">
        <h2>Datos de la persona</h2>

        <div class="campo">
          <label for="nombreInput">Nombre</label>
          <input
            type="text"
            class="form-control"
            id="nombreInput"
            placeholder="Nombre"
            required
            v-model="nombreInput"
            @input="onNombre"
          />
        </div>

        <div class="campo">
          <label for="apellidoInput">Apellido</label>
          <input
            type="text"
            class="form-control"
            id="apellidoInput"
            placeholder="Apellido"
            required
            v-model="apellidoInput"
            @input="onApellido"
          />
        </div>

        <div class="campo">
          <label for="fechaInput">Fecha</label>
          <input
            type="date"
            class="form-control"
            id="fechaInput"
            required
            v-model="fechaInput"
            @change="onFecha"
          />
        </div>

        <div class="campo">
          <label for="cargoInput">Cargo</label>
          <input
            type="text"
            class="form-control"
            id="cargoInput"
            required
            placeholder="Cargo"
            v-model="cargoInput"
            @input="onCargo"
          />
        </div>

        <button
          type="button"
          class="btn btn-primary w-100"
          id="dl-png"
          @click="descargar"
          :disabled="registrosExcel.length > 0"
        >
          Descargar imagen individual
        </button>

        <hr class="my-4" />
        
        <div v-if="registrosExcel.length === 0" class="campo mb-3">
          <label for="excelInput" class="form-label fw-bold">Carga Masiva (Excel)</label>
          <input
            type="file"
            class="form-control"
            id="excelInput"
            accept=".xlsx, .xls"
            @change="cargarExcel"
            :disabled="procesandoExcel"
          />
          <small class="text-muted d-block mt-1">Revisa y ajusta las tarjetas una a una antes de guardarlas en el ZIP.</small>
          <div v-if="procesandoExcel" class="mt-2 text-primary">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Leyendo Excel...
          </div>
        </div>

        <div v-else class="alert alert-info mt-3">
          <h6 class="alert-heading fw-bold">Modo Carga Masiva</h6>
          <p class="mb-2 text-sm">
            Tarjeta <strong>{{ indiceActual + 1 }}</strong> de <strong>{{ registrosExcel.length }}</strong>.
            Puedes editar los datos en el formulario de arriba si lo necesitas.
          </p>
          
          <div class="d-flex flex-column gap-2 mt-3">
            <button 
              @click="aprobarYSiguiente" 
              class="btn btn-success w-100" 
              :disabled="procesandoPaso"
            >
              <span v-if="procesandoPaso" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              {{ indiceActual === registrosExcel.length - 1 ? 'Aprobar y Descargar ZIP' : 'Aprobar y Siguiente' }}
            </button>
            <button @click="cancelarExcel" class="btn btn-outline-danger w-100">
              Cancelar carga masiva
            </button>
          </div>
        </div>
      </div>

      <PanelEditor :editor="editor" />
    </section>

    <section class="previsualizacion">
      <section
        class="card-container"
        id="htmlcapture"
        ref="tarjeta"
        :class="{ 'ed-exportando': editor.exportando.value }"
        :style="{ '--fondo-tarjeta': editor.fondo.value }"
      >
        <div class="title-container">
          <ElementoEditable
            clave="nombre"
            etiqueta="Nombre"
            class="name-container"
            ref-texto="span"
          >
            <span class="text-capitalize" id="nombre-display">{{
              nombreDisplay
            }}</span>
          </ElementoEditable>

          <ElementoEditable
            clave="fecha"
            etiqueta="Fecha"
            class="date-container"
            ref-texto="span"
          >
            <span class="text-uppercase partita" id="fecha-display">{{
              fechaDisplay
            }}</span>
          </ElementoEditable>

          <ElementoEditable
            clave="apellido"
            etiqueta="Apellido"
            class="lastname-container"
            ref-texto="span"
          >
            <span class="text-capitalize" id="apellido-display">{{
              apellidoDisplay
            }}</span>
          </ElementoEditable>

          <ElementoEditable
            clave="cargo"
            etiqueta="Cargo"
            class="position-container"
            ref-texto="p"
          >
            <p class="text-uppercase partita" id="cargo-display">
              {{ cargoDisplay }}
            </p>
          </ElementoEditable>

          <div class="globos">
            <div class="globos-container">
              <img src="/assets/img/globos-izquierda.png" alt="" />
            </div>
          </div>
        </div>

        <div class="picture-section">
          <div class="picture-container">
            <div class="mask1">
              <img
                id="imagen"
                src="/assets/img/logo-bbdo-cumpleax650.png"
                alt="Cinque Terre"
              />
            </div>
            <canvas id="canvas" style="display: none"></canvas>
          </div>

          <div class="cintas">
            <div class="cintas-container">
              <img src="/assets/img/cintas-derecha.png" alt="" />
            </div>
          </div>
        </div>

        <div class="mensaje-section">
          <div class="logo-vertical">
            <img src="/assets/img/logo-bbdo-vertical.png" alt="" />
          </div>

          <div class="descripcion">
            <span> FELIZ<br />CUMPLEAÑOS </span>
          </div>

          <div class="globo-footer">
            <div class="globo-footer-container">
              <img src="/assets/img/globos-rojos-puntos.png" alt="" />
            </div>
          </div>
        </div>
      </section>
    </section>
  </main>
</template>
