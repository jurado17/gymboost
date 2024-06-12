<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import UserList from './UserList.vue';

defineProps({
    users: Array,
});
</script>

<template>
<AdminLayout>
    <UserList :users = "users"></UserList>
</AdminLayout>
</template>