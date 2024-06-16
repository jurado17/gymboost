<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
const categories = ref([]);

onMounted(() => {
    // Fetch categories
    axios.get('/api/categories')
        .then(response => {
            categories.value = response.data;
        })
        .catch(error => {
            console.error("Error fetching categories:", error);
        });
});

</script>
<template>

    <div id="container" class="py-8 w-full lg:inline-flex px-28">
        <div  class=" w-full" v-for="category in categories">
            <a :href="`/products?category=${category.name}`"
                class="text-center block max-w-sm m-2 p-3 py-5 bg-cyan-950 border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                <h5 class="mb-2 text-2xl font-bold tracking-tight  dark:text-white">{{
                category.name}}</h5>
                
            </a>
        </div>
    </div>

</template>

<style>
#container {
    background-color: #e9e2d8;
    color: #e9e2d8;
}
</style>