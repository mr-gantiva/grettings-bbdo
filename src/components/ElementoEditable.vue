<script setup>
import { computed, nextTick, onMounted, ref, watch } from 'vue'
import { usarEditor } from '../composables/useEditorTarjeta'

const props = defineProps({
  // Identificador dentro del editor. No se llama "id" para no chocar
  // con el atributo id que la vista pasa al elemento real.
  clave: { type: String, required: true },
  etiqueta: { type: String, required: true },
  // Etiqueta HTML que se renderiza. Reemplaza al div/p original.
  tag: { type: String, default: 'div' },
  // Selector del hijo cuyo tamaño de fuente sirve de referencia.
  // Si va vacío se mide el propio elemento.
  refTexto: { type: String, default: '' },
})

const editor = usarEditor()
const raiz = ref(null)
const estado = ref(null)
const arrastrando = ref(false)

onMounted(() => {
  const objetivo = props.refTexto ? raiz.value.querySelector(props.refTexto) : raiz.value
  const cs = getComputedStyle(objetivo || raiz.value)
  const fontSize = Math.round(parseFloat(cs.fontSize) * 10) / 10
  const lhPx = parseFloat(cs.lineHeight)
  const lineHeight = Number.isNaN(lhPx) ? null : Math.round((lhPx / fontSize) * 100) / 100
  estado.value = editor.registrar(props.clave, props.etiqueta, { fontSize, lineHeight })
})

const seleccionado = computed(() => editor.seleccionada.value === props.clave)

// Al quedar seleccionado toma el foco, así las flechas del teclado lo mueven
// tanto si se llegó arrastrándolo como si se eligió desde el panel.
watch(seleccionado, async (activo) => {
  if (!activo || editor.exportando.value) return
  await nextTick()
  raiz.value?.focus({ preventScroll: true })
})

const estilo = computed(() => {
  if (!estado.value) return {}
  const e = estado.value
  const s = {
    '--ed-fs': `${e.fontSize}px`,
  }
  if (e.x !== 0 || e.y !== 0) s.transform = `translate(${e.x}px, ${e.y}px)`
  if (e.lineHeight !== null) s.lineHeight = String(e.lineHeight)
  return s
})

function alPresionar(evento) {
  if (evento.button !== 0 || editor.exportando.value) return
  editor.seleccionar(props.clave)
  raiz.value.focus({ preventScroll: true })

  const inicio = {
    puntero: { x: evento.clientX, y: evento.clientY },
    elemento: { x: estado.value.x, y: estado.value.y },
  }
  arrastrando.value = true

  const alMover = (ev) => {
    estado.value.x = inicio.elemento.x + (ev.clientX - inicio.puntero.x)
    estado.value.y = inicio.elemento.y + (ev.clientY - inicio.puntero.y)
  }
  const alSoltar = () => {
    arrastrando.value = false
    window.removeEventListener('pointermove', alMover)
    window.removeEventListener('pointerup', alSoltar)
  }

  window.addEventListener('pointermove', alMover)
  window.addEventListener('pointerup', alSoltar)
  evento.preventDefault()
}

const PASOS = { ArrowUp: [0, -1], ArrowDown: [0, 1], ArrowLeft: [-1, 0], ArrowRight: [1, 0] }

function alTeclear(evento) {
  const paso = PASOS[evento.key]
  if (!paso) return
  const salto = evento.shiftKey ? 10 : 1
  estado.value.x += paso[0] * salto
  estado.value.y += paso[1] * salto
  evento.preventDefault()
}
</script>

<template>
  <component
    :is="tag"
    ref="raiz"
    class="ed-elemento"
    :class="{ 'ed-seleccionado': seleccionado, 'ed-arrastrando': arrastrando }"
    :style="estilo"
    tabindex="0"
    :aria-label="`Mover ${etiqueta}`"
    :data-etiqueta="etiqueta"
    @pointerdown="alPresionar"
    @keydown="alTeclear"
  >
    <slot />
  </component>
</template>
