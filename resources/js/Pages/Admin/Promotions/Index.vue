<template>
    <AdminLayout>
        <PromotionList :promotions = "promotions"></PromotionList>
    </AdminLayout>

</template>

<script setup>
defineProps({
    promotions:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import PromotionList from './PromotionList.vue';
</script>