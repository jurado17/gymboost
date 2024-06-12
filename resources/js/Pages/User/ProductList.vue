<script setup>
import { ref, onMounted } from 'vue'
import ProductFilter from './Components/ProductFilter.vue';
import ProductGrid from './Components/ProductsGrid.vue';
import UserLayout from './Layouts/UserLayout.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    brands: Array
});


const selectedCategories = ref([]);
const selectedBrands = ref([]);

onMounted(() => {
    const queryParams = new URLSearchParams(window.location.search);
    const category = queryParams.get('category');
    if (category) {
        const categoryObj = props.categories.find(cat => cat.name === category);
        if (categoryObj) {
            selectedCategories.value.push(categoryObj.id);
        }
    }
});

</script>

<template>
    <UserLayout>
        <ProductFilter :categories="categories" :brands="brands" :selectedCategories="selectedCategories" :selectedBrands="selectedBrands">
            <ProductGrid :products="products.data"></ProductGrid>
        </ProductFilter>
    </UserLayout>
</template>
