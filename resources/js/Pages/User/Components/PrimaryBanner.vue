<template>
  <section class="bg-white antialiased dark:bg-gray-900 md:py-10">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <div class="relative mx-auto  my-8">
        <!-- Imagen de fondo -->
        <img class="w-full h-full bg-cover isolate relative dark:hidden" src="../../../../../public/carousel_images/banner2.jpg" alt="" />
        <img class="hidden w-full dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-showcase-dark.svg" alt="" />

        <!-- Capa de superposición -->
        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Texto superpuesto -->
        <div class="absolute inset-0 flex items-center justify-center text-center">
          <div class="block space-y-12">

            <h1 class="text-white text-2xl font-bold">Comienza a cuidar hoy tu bienestar</h1>
            <h1 class="text-white text-5xl font-bold">{{ Number(promotion.porcentual_discount) * 100 }}% de Descuento</h1>
          
          <h1 class="text-white text-xl font-bold">Usa el codigo {{ promotion.discount_code }} en el carrito para obtener este descuento</h1>
          </div>
          
          <h1></h1>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>

import { usePage } from '@inertiajs/vue3';
const promotion = usePage().props.promotions
</script>

<style scoped>
/* Ajustes adicionales si es necesario */
</style>
