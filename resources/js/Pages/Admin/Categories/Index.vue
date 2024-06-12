<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import CategoryList from './CategoryList.vue';

defineProps({
    categories: Array,
});

</script>

<template>
    <AdminLayout>
        <CategoryList :categories = "categories"></CategoryList>
    </AdminLayout>
</template>