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

const { descargarNodo } = useDescargaImagen();
const editor = crearEditor();

// Nodo que se captura como PNG (equivale a #htmlcapture)
const tarjeta = ref(null);

// Valores del formulario
const nombreInput = ref("");
const apellidoInput = ref("");
const cargoInput = ref("");
const fechaInput = ref("");

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
        >
          Descargar imagen
        </button>
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
