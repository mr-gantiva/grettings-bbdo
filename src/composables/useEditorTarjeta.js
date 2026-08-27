import { reactive, ref, provide, inject, onMounted, onBeforeUnmount } from 'vue'

const CLAVE = Symbol('editor-tarjeta')

const FONDO_POR_DEFECTO = '#edf1f7'

/**
 * Estado del editor de una tarjeta: qué elementos se pueden mover, cuál está
 * seleccionado, el color de fondo y la bandera de exportación.
 *
 * La vista lo crea una vez y los <ElementoEditable> se registran solos al
 * montarse, midiendo su tamaño de fuente real para que el punto de partida
 * sea exactamente el diseño original.
 */
export function crearEditor() {
  const elementos = reactive([])
  const porClave = reactive({})
  const seleccionada = ref(null)
  const exportando = ref(false)
  const fondo = ref(FONDO_POR_DEFECTO)

  function registrar(clave, etiqueta, base) {
    if (porClave[clave]) return porClave[clave]
    const elemento = reactive({
      clave,
      etiqueta,
      x: 0,
      y: 0,
      fontSize: base.fontSize,
      // null = se respeta el interlineado del diseño original
      lineHeight: null,
      base: { fontSize: base.fontSize, lineHeight: base.lineHeight },
    })
    porClave[clave] = elemento
    elementos.push(elemento)
    return elemento
  }

  function seleccionar(clave) {
    seleccionada.value = clave
  }

  function deseleccionar() {
    seleccionada.value = null
    // Suelta el foco para que no quede el contorno del navegador
    const activo = document.activeElement
    if (activo && activo.classList?.contains('ed-elemento')) activo.blur()
  }

  /*
    Un clic fuera de la tarjeta, o Escape, cierran la selección. Se ignoran los
    clics dentro de la columna de controles, que si no se cerraría al mover un
    slider o al escribir en el formulario.
  */
  function alClicFuera(evento) {
    if (!seleccionada.value) return
    const destino = evento.target
    if (destino.closest?.('.ed-elemento')) return
    if (destino.closest?.('[data-editor-panel], .contenedor-formulario')) return
    deseleccionar()
  }

  function alEscape(evento) {
    if (evento.key === 'Escape') deseleccionar()
  }

  onMounted(() => {
    document.addEventListener('pointerdown', alClicFuera)
    document.addEventListener('keydown', alEscape)
  })

  onBeforeUnmount(() => {
    document.removeEventListener('pointerdown', alClicFuera)
    document.removeEventListener('keydown', alEscape)
  })

  function mover(clave, dx, dy) {
    const e = porClave[clave]
    if (!e) return
    e.x += dx
    e.y += dy
  }

  function restablecer(clave) {
    const e = porClave[clave]
    if (!e) return
    e.x = 0
    e.y = 0
    e.fontSize = e.base.fontSize
    e.lineHeight = null
  }

  function restablecerTodo() {
    for (const e of elementos) restablecer(e.clave)
    fondo.value = FONDO_POR_DEFECTO
    seleccionada.value = null
  }

  const hayCambios = () =>
    fondo.value !== FONDO_POR_DEFECTO ||
    elementos.some(
      (e) => e.x !== 0 || e.y !== 0 || e.fontSize !== e.base.fontSize || e.lineHeight !== null,
    )

  const api = {
    elementos,
    porClave,
    seleccionada,
    exportando,
    fondo,
    registrar,
    seleccionar,
    deseleccionar,
    mover,
    restablecer,
    restablecerTodo,
    hayCambios,
    FONDO_POR_DEFECTO,
  }

  provide(CLAVE, api)
  return api
}

export function usarEditor() {
  const api = inject(CLAVE, null)
  if (!api) {
    throw new Error('ElementoEditable necesita una vista que haya llamado a crearEditor()')
  }
  return api
}
