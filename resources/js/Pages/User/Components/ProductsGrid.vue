<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    products: Array, // Esto puede quedarse como Array
}); 
</script>

<template>
<div class="lg:col-span-3">
    <div class="bg-white">
        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
            <div v-for="product in products" :key="product.id" class="group relative">
                <Link :href="route('product.overview',[product.slug])">
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                        <img :src="`/${product.product_images[0].image}`" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a :href="product.href">
                                    <span aria-hidden="true" class="absolute inset-0" />
                                    {{ product.name }}
                                </a>
                                <p>{{ product.brand.name }}</p>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">{{ product.color }}</p>
                            <div class="flex justify-center items-baseline my-4">
                                <span v-if="product.promotion_id !== null" class="mr-2 text-xl font-extrabold text-red-500">{{ (Number(product.price) - (Number(product.price) * product.promotion.porcentual_discount)).toFixed(2) }} €</span>
                                <span v-if="product.promotion_id !== null" class="text-lg font-light line-through text-gray-500">{{ product.price }} €</span>
                                <span v-else class="mr-2 text-xl font-extrabold">{{ product.price }} €</span>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</div>
</template>
