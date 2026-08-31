<script setup>
import { nextTick, ref } from 'vue'
import { useDescargaImagen, formatearFecha, esperarPintado } from '../composables/useDescargaImagen'
import { crearEditor } from '../composables/useEditorTarjeta'
import ElementoEditable from '../components/ElementoEditable.vue'
import PanelEditor from '../components/PanelEditor.vue'

const { descargarNodo } = useDescargaImagen()
const editor = crearEditor()

// Nodo que se captura como PNG (equivale a #example-table)
const tarjeta = ref(null)

// Valores del formulario
const nombreInput = ref('')
const cargoInput = ref('')
const equipoInput = ref('')
const desafioInput = ref('')
const interesInput = ref('')
const fechaInput = ref('')
const tamañoOnda = ref(180)

// Imagen subida por el usuario
// Esta ruta es dinamica, asi que Vite no puede reescribirla al compilar.
// BASE_URL le antepone la base del sitio (/ en local, /grettings-bbdo/ en Pages).
const imagenSrc = ref(import.meta.env.BASE_URL + "assets/img/img-muestra.jpg")

/*
  El original arranca con el texto de muestra escrito en el HTML y solo
  después del primer input pasa a "valor || placeholder". Se replica con
  un texto inicial que se descarta apenas el campo se toca.
*/
const LOREM =
  'Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore asperiores excepturi debitis numquam eos id expedita est optio velit illum! Ex distinctio amet laborum eligendi pariatur quidem consequuntur. Ex, voluptates! Aspernatur totam iste earum provident libero quis nulla quasi ex. Iure, architecto itaque!'

const nombreDisplay = ref('nombre apellido.')
const equipoDisplay = ref('  Nombre equipo')
const cargoDisplay = ref('nombre cargo')
const desafioDisplay = ref(LOREM)
const interesDisplay = ref(LOREM)
const fechaDisplay = ref('12.07.23')

/* Mismos placeholders de fallback que bienvenida.js */
function onNombre() {
  nombreDisplay.value = nombreInput.value || 'Nombre'
}
function onEquipo() {
  equipoDisplay.value = equipoInput.value || 'Equipo'
}
function onCargo() {
  cargoDisplay.value = cargoInput.value || 'CARGO'
}
function onDesafio() {
  desafioDisplay.value = desafioInput.value || 'Desafios'
}
function onInteres() {
  // El original usa "Desafios" también acá (copy-paste del campo anterior)
  interesDisplay.value = interesInput.value || 'Desafios'
}
function onFecha() {
  fechaDisplay.value = formatearFecha(fechaInput.value)
}

function onArchivo(event) {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = function (e) {
      imagenSrc.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

async function descargar() {
  // Apaga contornos y etiquetas del editor antes de capturar
  editor.exportando.value = true
  editor.deseleccionar()
  await nextTick()
  await esperarPintado()
  try {
    await descargarNodo(tarjeta.value, nombreInput.value.trim() + '.png')
  } finally {
    editor.exportando.value = false
  }
}
</script>

<template>
  <main class="container main-container">
    <section class="col-12 col-md-5 contenedor-formulario">
      <div class="panel">
        <h2>Datos de la persona</h2>

        <div class="campo">
          <label for="inputGroupFile02">Foto</label>
          <input type="file" class="form-control" id="inputGroupFile02" @change="onArchivo" />
        </div>

        <div class="campo">
          <label for="nombreInput">Nombre y apellido</label>
          <input
            type="text"
            class="form-control"
            id="nombreInput"
            placeholder="Nombre y apellido"
            required
            v-model="nombreInput"
            @input="onNombre"
          />
        </div>

        <div class="campo">
          <label for="fechaInput">Fecha de ingreso</label>
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

        <div class="campo">
          <label for="equipoInput">Equipo</label>
          <input
            type="text"
            class="form-control"
            id="equipoInput"
            required
            placeholder="Equipo"
            v-model="equipoInput"
            @input="onEquipo"
          />
        </div>

        <div class="campo">
          <label for="desafioInput">Desafío</label>
          <textarea
            class="form-control"
            placeholder="Desafios"
            id="desafioInput"
            v-model="desafioInput"
            @input="onDesafio"
          ></textarea>
        </div>

        <div class="campo">
          <label for="interesInput">Intereses</label>
          <textarea
            class="form-control"
            placeholder="Intereses"
            id="interesInput"
            v-model="interesInput"
            @input="onInteres"
          ></textarea>
        </div>

        <div class="campo">
          <label for="ondaInput" class="form-label d-flex justify-content-between">
            Tamaño onda fucsia <span>{{ tamañoOnda }}px</span>
          </label>
          <input
            type="range"
            class="form-range"
            id="ondaInput"
            min="50"
            max="300"
            v-model="tamañoOnda"
          />
        </div>

        <button type="button" class="btn btn-primary w-100" id="dl-png" @click="descargar">
          Descargar imagen
        </button>
      </div>

      <PanelEditor :editor="editor" />
    </section>

    <section class="previsualizacion">
      <div
        class="bienvenida-container"
        id="example-table"
        ref="tarjeta"
        :class="{ 'ed-exportando': editor.exportando.value }"
        :style="{ '--fondo-tarjeta': editor.fondo.value }"
        @pointerdown.self="editor.deseleccionar()"
      >
        <header>
          <div class="box-img">
            <img src="/assets/img/logo-bbdo-bienvenidax650.png" alt="" />
          </div>
          <div class="contenedor-imagen mask1">
            <img
              style="filter: grayscale(100%); object-fit: cover"
              id="uploaded-image"
              :src="imagenSrc"
              alt=""
            />
          </div>
        </header>

        <section class="info-container">
          <div class="logo-vertical">
            <img src="/assets/img/logo-bbdo-vertical.png" alt="" />
          </div>
          <div class="info-persona">
            <ElementoEditable clave="nombre" etiqueta="Nombre" class="nombre" ref-texto="p">
              <p class="text-capitalize" id="nombre-display">{{ nombreDisplay }}</p>
            </ElementoEditable>

            <ElementoEditable
              clave="equipo"
              etiqueta="Equipo"
              class="equipo d-flex"
              ref-texto="p"
            >
              <div class="equipo titulo">
                <p>*&nbsp;&nbsp;EQUIPO:&nbsp;</p>
              </div>
              <div class="equipo titulo">
                <p id="equipo-display">{{ equipoDisplay }}</p>
              </div>
            </ElementoEditable>

            <ElementoEditable clave="cargo" etiqueta="Cargo" class="cargo d-flex" ref-texto="p">
              <div class="equipo titulo">
                <p>*&nbsp;&nbsp;</p>
              </div>
              <p id="cargo-display">{{ cargoDisplay }}</p>
            </ElementoEditable>
          </div>
          <div class="formas-left">
            <div class="globo-rojo">
              <img src="/assets/img/globo-bienvenida-rojo.png" alt="" />
            </div>
          </div>
        </section>

        <section class="info-desafio">
          <div class="fecha-ingreso">
            <ElementoEditable clave="fecha" etiqueta="Fecha" class="pb-3" ref-texto="span">
              <span id="fecha-display">{{ fechaDisplay }}</span>
            </ElementoEditable>
            <div class="img-bienvenida pt-3">
              <img src="/assets/img/bienvenidx.png" alt="" />
            </div>
          </div>

          <div class="contenedor-desafio-intereses">
            <div class="desafio">
              <span>¿Cuál es tu desafío en BBDO?</span>
              <ElementoEditable clave="desafio" etiqueta="Desafío" tag="p" id="desafio-display">{{
                desafioDisplay
              }}</ElementoEditable>
            </div>

            <div class="intereses">
              <span>¿Cualés son tus intereses?</span>
              <ElementoEditable clave="intereses" etiqueta="Intereses" tag="p" id="interes-display">{{
                interesDisplay
              }}</ElementoEditable>
            </div>
            <div class="logo-footer">
              <img src="/assets/img/BBDO.png" alt="" />
            </div>
          </div>

          <div class="onda-fuscia">
            <img src="/assets/img/ondas-fucsia-bienvenida-left.png" :style="{ width: tamañoOnda + 'px' }" alt="" />
          </div>
        </section>
      </div>
    </section>
  </main>
</template>
