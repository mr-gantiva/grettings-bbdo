<script setup>
import { RouterView, RouterLink } from 'vue-router'
</script>

<template>
  <header class="barra">
    <RouterLink to="/" class="marca">
      <span class="punto"></span>
      BBDO Grettings
    </RouterLink>
    <nav>
      <RouterLink to="/bienvenida">Bienvenida</RouterLink>
      <RouterLink to="/cumple">Cumpleaños</RouterLink>
    </nav>
  </header>

  <RouterView />
</template>
