<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import WeightList from './WeightList.vue';

defineProps({
    weights:Array
})
</script>

<template>

    <AdminLayout>
        <WeightList :weights = "weights">
            
        </WeightList>
    </AdminLayout>
</template>