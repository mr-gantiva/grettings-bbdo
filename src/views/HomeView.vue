<script setup>
// Equivalente a index.html del proyecto original.
</script>

<template>
  <main class="menu">
    <h1>Plantillas <strong>BBDO</strong></h1>
    <p>Arma la pieza, ajusta los textos a mano y descárgala en PNG.</p>

    <div class="tarjetas">
      <RouterLink to="/bienvenida">
        <span class="titulo">Bienvenida</span>
        <span class="detalle">Para nuevos ingresos. Incluye foto, equipo, desafío e intereses.</span>
      </RouterLink>

      <RouterLink to="/cumple">
        <span class="titulo">Cumpleaños</span>
        <span class="detalle">Pieza de cumpleaños con nombre, apellido, fecha y cargo.</span>
      </RouterLink>
    </div>
  </main>
</template>
