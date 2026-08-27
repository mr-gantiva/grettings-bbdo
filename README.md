# grettings-bbdo-vue

Generador de tarjetas BBDO en Vue 3. Port del proyecto original `grettings-bbdo`,
con un editor que permite mover y redimensionar los textos de cada pieza.

## Correr el proyecto

```bash
npm install
npm run dev      # http://localhost:5173
npm run build    # genera dist/
npm run preview  # sirve dist/
```

Necesita Node 20 o superior. Si el proyecto se copia entre Windows, macOS y Linux,
hay que borrar `node_modules` y volver a correr `npm install`: algunas dependencias
traen binarios propios de cada sistema.

## Rutas

| Ruta          | Equivale a                      |
| ------------- | ------------------------------- |
| `/`           | `index.html`                    |
| `/bienvenida` | `assets/views/bienvenida.html`  |
| `/cumple`     | `assets/views/cumple-bbdo.html` |

## Estructura

```
public/assets/img      imágenes copiadas del proyecto original
public/assets/fonts    Partita-Regular.otf
src/views              una vista por tarjeta, más el menú
src/components         ElementoEditable y PanelEditor
src/composables        editor de la tarjeta, descarga a PNG, formateo de fecha
src/assets/styles      marca, tarjetas, interfaz y editor
src/router             vue-router
```

## Cómo funciona

Cada vista tiene el formulario a la izquierda y la tarjeta a la derecha. Los campos
son `ref` de Vue y se reflejan en la tarjeta al escribir. El botón "Descargar imagen"
pasa el nodo de la tarjeta a `dom-to-image`, que devuelve un PNG. Todo ocurre en el
navegador, no hay servidor.

### El editor

`crearEditor()` guarda el estado de cada texto movible: desplazamiento, tamaño de
fuente e interlineado. Cada `<ElementoEditable>` se registra solo al montarse,
midiendo su tamaño real, así que la pieza arranca exactamente igual al diseño base.

Se pueden mover y redimensionar los textos que salen del formulario. En Bienvenida
son seis (nombre, equipo, cargo, fecha, desafío e intereses) y en Cumpleaños cuatro
(nombre, apellido, fecha y cargo). Los títulos fijos, logos y globos quedan clavados.

Formas de manipularlos:

- Arrastrar con el mouse.
- Elegirlos en el panel y usar los sliders de tamaño, interlineado y posición.
- Con uno seleccionado, las flechas del teclado lo mueven de a 1 px, y con Shift
  de a 10 px.

El desplazamiento se aplica con `transform: translate()`, que mueve el texto sin
reacomodar lo que tiene al lado. El tamaño de fuente entra por la variable CSS
`--ed-fs`, que cada regla de las hojas de estilo lee con el valor original como
respaldo, así que si el editor no toca nada la pieza se ve igual que siempre.

Para cerrar la selección: clic en cualquier parte fuera de la tarjeta, o Escape.
Los clics dentro de la columna de controles no la cierran, así se puede mover un
slider o escribir en el formulario sin perder el elemento elegido.

Antes de exportar, la tarjeta recibe la clase `ed-exportando`, que anula las
transiciones y apaga contornos y etiquetas. El PNG descargado no trae ninguna
marca del editor. Anular la transición es lo que importa: si el contorno todavía
se está desvaneciendo, `getComputedStyle` devuelve el valor a medio camino y
`dom-to-image` se lo lleva al archivo.

Los ajustes no se guardan: al recargar, cada pieza vuelve al diseño base. Hay dos
botones de restablecer, uno para el elemento seleccionado y otro para todo.

### Colores

`src/assets/styles/marca.scss` define la paleta como variables CSS. Cambiar un valor
ahí lo cambia en las tarjetas y en la interfaz a la vez.

| Variable        | Valor     | Uso                          |
| --------------- | --------- | ---------------------------- |
| `--bbdo-rojo`   | `#EF4023` | Acentos de tarjetas y botones |
| `--bbdo-negro`  | `#231F20` | Fondo de la app y textos      |
| `--bbdo-blanco` | `#FFFFFF` | Paneles y textos sobre negro  |

El fondo de la tarjeta se controla desde el panel, con un selector de color y cinco
muestras. Por defecto queda en `#EDF1F7`, el mismo del diseño original.

## Diferencias con el proyecto original

Ninguna es accidental.

1. **El rojo pasó de `#DB0000` a `#EF4023`**, el rojo de marca. `#DB0000` no estaba
   en el manual.
2. **El texto negro pasó de `#212529` a `#231F20`.** El original heredaba el gris de
   Bootstrap sin quererlo; ahora usa el negro de marca y queda declarado.
3. **La tarjeta de Bienvenida mide 650px fijos.** El original no fijaba ancho, así
   que la pieza cambiaba de tamaño según el ancho de la ventana y el PNG salía
   distinto cada vez. 650px es el ancho real de las imágenes del diseño.
   La de Cumpleaños lleva `display: flow-root` por un motivo parecido: en el
   original contenía el margen inferior de `.mensaje-section` solo porque era ítem
   flex, y al envolverla ese margen se escapaba y la pieza perdía 47px.
4. **La fuente Partita es local**, ya no se pide a `desarrolloflare.cl`.
5. **Bootstrap 5.3.3 viene de npm**, no del CDN. Misma versión.
6. **El formulario, los botones y el menú se rediseñaron** con la paleta de marca.
   Las tarjetas no cambiaron por esto.
7. **Los estilos se portaron desde los `.css` compilados, no desde los `.scss`.**
   En el repo original el `style.css` fue editado a mano después de compilarse, así
   que el `.scss` quedó desactualizado y ya no describe lo que se ve.

Se dejaron fuera del port: `bienvenida-2.html` (le falta `bienvenida2.js`), los
`.php` y las librerías `html2canvas` y `FileSaver.js`, que el original cargaba sin usar.

Estos defectos del original se replicaron a propósito:

- El placeholder del campo Intereses dice "Desafios".
- El archivo de Cumpleaños se descarga sin extensión en el nombre.
- Borrar la fecha después de haberla puesto deja un texto inválido.

## Verificación

Comparación píxel a píxel contra el proyecto original, en estado inicial y con el
formulario lleno, para las dos plantillas. Fuera de los dos cambios de color de la
lista anterior, no hay diferencias.

El editor se probó con 27 comprobaciones automatizadas: arrastre con mouse, sliders
de tamaño, interlineado y posición, movimiento con teclado, cambio de fondo,
restablecer, cerrar la selección con clic fuera y con Escape, y que el PNG salga
exactamente igual con un elemento seleccionado que sin nada seleccionado.
