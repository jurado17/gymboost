<template>

    <div id="indicators-carousel" class="relative z-0 px-56 pt-10 pb-12 w-auto h-auto" data-carousel="static">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden md:h-96">
            <!-- Item 1 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                <img src="../../../../../public/carousel_images/maxresdefault.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://cdn.hsnstore.com/media/interaktingslider/interaktingslider_ES-Outlet-Marzo_14.webp"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 3 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="../../../../../public/carousel_images/tipos-proteinas-optimum.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 4 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="../../../../../public/carousel_images/banner.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 5 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
            <button type="button" class="w-3 h-3 rounded-full bg-blue-600" aria-current="true" aria-label="Slide 1"
                data-carousel-slide-to="0">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#74C0FC" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"/></svg>
            </button>
            <button type="button" class="w-3 h-3 rounded-full bg-blue-600 hover:bg-blue-800" aria-current="false" aria-label="Slide 2"
                data-carousel-slide-to="1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="#74C0FC" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z" />
                </svg></button>
            <button type="button" class="w-3 h-3 rounded-full bg-blue-600" aria-current="false" aria-label="Slide 3"
                data-carousel-slide-to="2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="#74C0FC" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z" />
                </svg></button>
            <button type="button" class="w-3 h-3 rounded-full bg-blue-600" aria-current="false" aria-label="Slide 4"
                data-carousel-slide-to="3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="#74C0FC" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z" />
                </svg></button>
            <button type="button" class="w-3 h-3 rounded-full bg-blue-600" aria-current="false" aria-label="Slide 5"
                data-carousel-slide-to="4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="#74C0FC" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z" />
                </svg></button>
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full dark:bg-blue-800/30 group-hover:bg-white/50 dark:group-hover:bg-blue-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-blue-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-blue-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute right-40 top-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full dark:bg-blue-800/30 group-hover:bg-white/50 dark:group-hover:bg-blue-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-blue-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-black dark:text-blue-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>

</template>

<script setup>

</script>