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
        
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full dark:bg-blue-800/30 group-hover:bg-white/50 dark:group-hover:bg-blue-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-blue-800/70 group-focus:outline-none">
                <
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute right-40 top-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full dark:bg-blue-800/30 group-hover:bg-white/50 dark:group-hover:bg-blue-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-blue-800/70 group-focus:outline-none">
                >
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>

</template>

<script setup>

</script>