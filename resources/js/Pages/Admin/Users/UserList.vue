<script setup>
import { ref, computed, toRefs } from 'vue';
import { Link } from '@inertiajs/vue3';

// Desestructuramos las propiedades definidas
const props = defineProps({
    users: Array,
});

// Convertimos las propiedades a referencias
const { users } = toRefs(props);

// Estado para el término de búsqueda
const searchTerm = ref('');

// Propiedad computada para filtrar los usuarios
const filteredUsers = computed(() => {
    if (!searchTerm.value) {
        return users.value;
    }
    return users.value.filter(user =>
        user.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" v-model="searchTerm"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search" required>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="bg-white" id="">
                    <ul role="list" class="divide-y divide-gray-100">
                        <li v-for="user in filteredUsers" :key="user.id" class="hover:bg-slate-200 flex justify-between gap-x-6 py-5">
                            <Link :href="route('admin.users.show', user.id)" class="flex w-full justify-between">
                                <div class="px-10 flex min-w-0 gap-x-4">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ user.name }}</p>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ user.email }}</p>
                                    </div>
                                </div>
                                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end px-10">
                                    <p v-if="user.isAdmin == 1" class="text-sm leading-6 text-gray-900">Administrador</p>
                                    <p v-else class="text-sm leading-6 text-gray-900">Cliente</p>
                                    <div>
                                        <div v-if="user.alta == 1" class="mt-1 flex items-center gap-x-1.5">
                                            <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                            </div>
                                            <p class="text-xs leading-5 text-gray-500">De Alta</p>
                                        </div>
                                        <div v-if="user.alta == 0" class="mt-1 flex items-center gap-x-1.5">
                                            <div class="flex-none rounded-full bg-red-500/20 p-1">
                                                <div class="h-1.5 w-1.5 rounded-full bg-red-500" />
                                            </div>
                                            <p class="text-xs leading-5 text-gray-500">De Baja</p>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
