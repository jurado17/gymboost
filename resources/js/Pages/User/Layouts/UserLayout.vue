<script setup>
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from './Header.vue';
import Footer from './Footer.vue';



// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})


</script>

<template>
    <!--START HEADER-->
    <Header></Header>
    <!--END HEADER-->

<!-- START CONTENIDO PRICIPAL -->

    <!-- START LISTA ULTIMAS NOVEDADES-->
    <slot/>
 <!--END LISTA ULTIMAS NOVEDADES-->

    <!-- END CONTENIDO PRICIPAL -->

    <!--START FOOTER-->
    <Footer></Footer>
    <!--END FOOTER-->
</template>