<template>
    <AdminLayout>
        <FlavoursList :flavours = "flavours">
            
        </FlavoursList>
    </AdminLayout>

</template>

<script setup>
defineProps({
    flavours:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import FlavoursList from './FlavoursList.vue';
</script>