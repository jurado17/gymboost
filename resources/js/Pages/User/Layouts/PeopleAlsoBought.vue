<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const products = ref([]);

onMounted(() => {
    // Fetch products
    axios.get('/api/products')
        .then(response => {
            products.value = response.data;
            console.log(products);
        })
        .catch(error => {
            console.error("Error fetching products:", error);
        });
});
</script>

<template>
    <div class="hidden xl:mt-8 xl:block">
        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Otros usuarios tambien vieron</h3>
        <div  class="mt-6 grid grid-cols-3 gap-4 sm:mt-8">
            <div v-for="product in products"
                class="space-y-6 overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <Link :href="route('product.overview', [product.slug])">
                    <a href="#" class="overflow-hidden rounded">

                        <img class="mx-auto h-44 w-44 dark:block"
                        :src="`/storage/${product.product_images[0].image}`"
                            alt="product image" /> 
                    </a>
                    <div>
                        <a href="#"
                            class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">
                            {{ product.name }}
                        </a>
                        <p class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ product.category.name }}
                        </p>
                        <p class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ product.brand.name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            <span v-if="product.promotion_id !== null" class="mr-2 text-xl font-extrabold text-red-500">{{ (Number(product.price) - (Number(product.price) * product.promotion.porcentual_discount)).toFixed(2) }} €</span>
                                    <span v-if="product.promotion_id !== null" class="text-lg font-light line-through text-gray-500">{{ product.price }} €</span>
                                    <span v-else class="mr-2 text-xl font-extrabold">{{ product.price }} €</span>
                        </p>
                    
                    </div>
                </Link>  
                <div class="mt-6 flex items-center gap-2.5">
                    
                    
                    <button type="button"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-cyan-900 px-5 py-2.5 text-sm font-medium  text-white hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-300 dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800">
                        <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7h-1M8 7h-.688M13 5v4m-2-2h4" />
                        </svg>
                        Add to cart
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>