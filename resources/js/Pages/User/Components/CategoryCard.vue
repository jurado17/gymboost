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

    <div class="py-20 w-full inline-flex">
        <div id="container" class="bg-orange-100 w-full text-orange-100" v-for="category in categories">
            <a :href="`/products?category=${category.name}`"
                class="text-center block max-w-sm m-8 p-4 bg-cyan-950 border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                <h5 class="mb-2 text-2xl font-bold tracking-tight  dark:text-white">{{
                category.name}}</h5>
                
            </a>
        </div>
    </div>

</template>
