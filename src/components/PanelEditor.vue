<script setup>
import { computed } from 'vue'

const props = defineProps({
  editor: { type: Object, required: true },
})

const MUESTRAS = [
  { valor: '#edf1f7', nombre: 'Gris original' },
  { valor: '#ffffff', nombre: 'Blanco BBDO' },
  { valor: '#231f20', nombre: 'Negro BBDO' },
  { valor: '#ef4023', nombre: 'Rojo BBDO' },
  { valor: '#f4f1ec', nombre: 'Hueso' },
]

const activo = computed(() => {
  const clave = props.editor.seleccionada.value
  return clave ? props.editor.porClave[clave] : null
})

const minFuente = computed(() => (activo.value ? Math.max(8, Math.round(activo.value.base.fontSize * 0.3)) : 8))
const maxFuente = computed(() => (activo.value ? Math.round(activo.value.base.fontSize * 2.5) : 100))

const interlineado = computed({
  get: () => activo.value?.lineHeight ?? activo.value?.base.lineHeight ?? 1.2,
  set: (v) => {
    if (activo.value) activo.value.lineHeight = Number(v)
  },
})

function cambiado(e) {
  return e.x !== 0 || e.y !== 0 || e.fontSize !== e.base.fontSize || e.lineHeight !== null
}
</script>

<template>
  <div class="panel" data-editor-panel>
    <h2>Ajustar la pieza</h2>

    <div class="editor-lista">
      <button
        v-for="e in editor.elementos"
        :key="e.clave"
        type="button"
        class="editor-chip"
        :class="{ activo: editor.seleccionada.value === e.clave }"
        @click="editor.seleccionar(e.clave)"
      >
        {{ e.etiqueta }}<span v-if="cambiado(e)" class="marca-cambio"></span>
      </button>
    </div>

    <p v-if="!activo" class="editor-vacio">
      Arrastra cualquier texto de la tarjeta para moverlo, o elígelo aquí arriba para cambiarle
      el tamaño.
    </p>

    <template v-else>
      <div class="editor-control">
        <div class="fila">
          <label :for="`fs-${activo.clave}`">Tamaño de texto</label>
          <span class="valor">{{ activo.fontSize }} px</span>
        </div>
        <input
          :id="`fs-${activo.clave}`"
          type="range"
          :min="minFuente"
          :max="maxFuente"
          step="0.5"
          v-model.number="activo.fontSize"
        />
      </div>

      <div class="editor-control">
        <div class="fila">
          <label :for="`lh-${activo.clave}`">Interlineado</label>
          <span class="valor">{{ Number(interlineado).toFixed(2) }}</span>
        </div>
        <input
          :id="`lh-${activo.clave}`"
          type="range"
          min="0.6"
          max="2"
          step="0.05"
          v-model.number="interlineado"
        />
      </div>

      <div class="editor-control">
        <div class="fila">
          <label :for="`x-${activo.clave}`">Posición horizontal</label>
          <span class="valor">{{ activo.x }} px</span>
        </div>
        <input :id="`x-${activo.clave}`" type="range" min="-300" max="300" step="1" v-model.number="activo.x" />
      </div>

      <div class="editor-control">
        <div class="fila">
          <label :for="`y-${activo.clave}`">Posición vertical</label>
          <span class="valor">{{ activo.y }} px</span>
        </div>
        <input :id="`y-${activo.clave}`" type="range" min="-300" max="300" step="1" v-model.number="activo.y" />
      </div>
    </template>

    <div class="editor-control">
      <div class="fila">
        <label for="fondo-tarjeta">Color de fondo</label>
        <span class="valor">{{ editor.fondo.value.toUpperCase() }}</span>
      </div>
      <div class="editor-fondo">
        <input id="fondo-tarjeta" type="color" v-model="editor.fondo.value" />
        <div class="editor-muestras">
          <button
            v-for="m in MUESTRAS"
            :key="m.valor"
            type="button"
            class="editor-muestra"
            :style="{ background: m.valor }"
            :title="m.nombre"
            :aria-label="m.nombre"
            @click="editor.fondo.value = m.valor"
          ></button>
        </div>
      </div>
    </div>

    <div class="editor-acciones">
      <button
        type="button"
        class="btn btn-fantasma btn-mini"
        :disabled="!activo"
        @click="activo && editor.restablecer(activo.clave)"
      >
        Restablecer este
      </button>
      <button
        type="button"
        class="btn btn-fantasma btn-mini"
        :disabled="!editor.hayCambios()"
        @click="editor.restablecerTodo()"
      >
        Restablecer todo
      </button>
    </div>

    <p class="editor-ayuda">
      Con un elemento seleccionado, las flechas del teclado lo mueven de a 1 px, y con Shift de
      a 10 px. Los contornos rojos no salen en el PNG.
    </p>
  </div>
</template>
