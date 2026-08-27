import domtoimage from 'dom-to-image'

/** Espera a que el navegador pinte, para capturar el estado ya aplicado. */
export function esperarPintado() {
  return new Promise((resolve) =>
    requestAnimationFrame(() => requestAnimationFrame(resolve)),
  )
}

/**
 * Replica el comportamiento de descarga del proyecto original:
 * toma un nodo del DOM, lo convierte a PNG con dom-to-image y
 * dispara la descarga con un <a download>.
 */
export function useDescargaImagen() {
  function descargarNodo(nodo, nombreArchivo) {
    if (!nodo) {
      console.error('No se encontró el nodo a capturar')
      return
    }

    return domtoimage
      .toPng(nodo)
      .then(function (dataUrl) {
        const link = document.createElement('a')
        link.download = nombreArchivo
        link.href = dataUrl
        link.click()
      })
      .catch(function (error) {
        console.error('Error al capturar la imagen:', error)
      })
  }

  return { descargarNodo }
}

/**
 * Mismo formateo de fecha que script.js / bienvenida.js / cumple.js:
 * de YYYY-MM-DD a DD.MM.AA
 */
export function formatearFecha(valor) {
  const [year, month, day] = valor.split('-')
  return `${day}.${('0' + month).slice(-2)}.${year.slice(-2)}`
}
