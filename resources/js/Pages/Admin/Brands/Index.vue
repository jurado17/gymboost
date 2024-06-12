<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import BrandList from './BrandList.vue';

defineProps({
    brands:Array
})
</script>

<template>

    <AdminLayout>
        <BrandList :brands = "brands">
            
        </BrandList>
    </AdminLayout>
</template>