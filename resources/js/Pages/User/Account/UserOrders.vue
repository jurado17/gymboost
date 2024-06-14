<script setup>
import UserLayout from "../Layouts/UserLayout.vue";
import ProfileLayout from "../Layouts/ProfileLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    user: Object,
    userAddress: Array,
    orders: Array,
    orderItemUser: Array,
});

const filterDuration = ref('this week');

const handleFilterChange = (event) => {
    event.preventDefault();
    filterDuration.value = event.target.value;
    router.get(route('user.profile.order'), { duration: filterDuration.value }, {
        preserveState: true,
        preserveScroll: true, // To preserve the scroll position
    });
};
</script>

<template>
    <UserLayout>
        <ProfileLayout>
            <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mx-auto max-w-5xl">
                        <div class="gap-4 sm:flex sm:items-center sm:justify-between">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Mis Pedidos</h2>

                            <div class="mt-6 gap-4 space-y-4 sm:mt-0 sm:flex sm:items-center sm:justify-end sm:space-y-0">
                                <span class="inline-block text-gray-500 dark:text-gray-400"> desde </span>
                                <div>
                                    <label for="duration" class="sr-only mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select duration</label>
                                    <select id="duration" @change="handleFilterChange"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                        <option value="this week" selected>Esta semana</option>
                                        <option value="this month">Este mes</option>
                                        <option value="last 3 months">Últimos 3 meses</option>
                                        <option value="last 6 months">Últimos 6 meses</option>
                                        <option value="this year">Este año</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div v-for="order in orders" :key="order.id" class="mt-6 flow-root sm:mt-8 border-b-2">
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="flex flex-wrap items-center gap-y-4 py-6">
                                    <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">ID Pedido:</dt>
                                        <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                            <a href="#" class="hover:underline">{{ order.id }}</a>
                                        </dd>
                                    </dl>

                                    <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Fecha:</dt>
                                        <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                            {{ new Date(order.created_at).toLocaleDateString() }}
                                        </dd>
                                    </dl>

                                    <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Precio:</dt>
                                        <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                            {{ order.total_price }}
                                        </dd>
                                    </dl>

                                    <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Estado:</dt>
                                        <dd class="me-2 mt-1.5 inline-flex items-center rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                                            </svg>
                                            {{ order.status }}
                                        </dd>
                                    </dl>

                                    <div class="w-full grid sm:grid-cols-2 lg:flex lg:w-64 lg:items-center lg:justify-end gap-4">
                                        <Link :href="route('user.profile.order.detail',order.id)"
                                            class="w-full inline-flex justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-cyan-900 hover:text-white transition focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 lg:w-auto">
                                            Ver Detalles
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </ProfileLayout>
    </UserLayout>
</template>
