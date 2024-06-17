<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted, watch } from 'vue';
import axios from 'axios';

const canLogin = usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const auth = usePage().props.auth;
const cart = computed(() => usePage().props.cart);
const sidebarOpen = ref(false);
const dropdownOpen = ref(false);
const categories = ref([]);
const brands = ref([]);
const searchQuery = ref('');
const searchResults = ref([]);
const countdown = ref(null);
const countdownInterval = ref(null);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
    sidebarOpen.value = false;
};

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const closeDropdown = () => {
    dropdownOpen.value = false;
};

const searchProducts = () => {
    if (searchQuery.value) {
        axios.get(`/api/products/search?q=${searchQuery.value}`)
            .then(response => {
                searchResults.value = response.data;
            })
            .catch(error => {
                console.error("Error searching products:", error);
            });
    } else {
        searchResults.value = [];
    }
};

const startCountdown = () => {
    if (countdownInterval.value) {
        clearInterval(countdownInterval.value);
    }
    countdown.value = parseInt(localStorage.getItem('countdown')) || 20 * 60; // 20 minutes in seconds
    countdownInterval.value = setInterval(() => {
        if (countdown.value > 0) {
            countdown.value--;
            localStorage.setItem('countdown', countdown.value);
        } else {
            clearInterval(countdownInterval.value);
            localStorage.removeItem('countdown');
        }
    }, 1000);
};

onMounted(() => {
    // Fetch categories
    axios.get('/api/categories')
        .then(response => {
            categories.value = response.data;
        })
        .catch(error => {
            console.error("Error fetching categories:", error);
        });

    // Fetch brands
    axios.get('/api/brands')
        .then(response => {
            brands.value = response.data;
        })
        .catch(error => {
            console.error("Error fetching brands:", error);
        });

    // Initialize countdown if there is a value in localStorage
    if (localStorage.getItem('countdown')) {
        startCountdown();
    }
});

watch(() => cart.value.data.count, (newCount, oldCount) => {
    if (newCount > oldCount) {
        startCountdown();
    }
});
</script>


<template>

    <div class="flex justify-center p-1 bg-gray-400 text-white">
        <h1 class="text-sm">Envios gratuitos por compras menores de 24,99</h1>
    </div>

    <div
        class="bg-white dark:bg-gray-900 sticky text-sm w-full z-10 top-0 start-0 ">

        <nav class="bg-white  dark:bg-gray-900">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
                <Link :href="route('user.home')" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../../../../../public/icon.png" class="h-12" alt="Flowbite Logo" />
               
                </Link>

                <!--Buscador de productos-->
                <div>
                    <div class="flex md:order-2">
                        <div class="relative hidden lg:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="search-navbar" v-model="searchQuery" @input="searchProducts"
                                class="block w-full p-2 px-56 ps-10 text-sm border border-gray-300 rounded-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 text-gray-900 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search...">
                            <div v-if="searchResults.length" v-bind:hidden="searchResults.value === ''"
                                class="absolute z-10 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg mt-1">
                                <ul class="py-1 max-h-80 overflow-y-auto">
                                    <li v-for="result in searchResults" :key="result.id"
                                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <Link :href="`/${result.slug}`"
                                            class="inline-flex justify-center items-center text-center px-6 text-gray-900">
                                        <img :src="`/storage/${result.product_images[0].image}`" alt="imagen"
                                            class="mr-5 w-14 h-14">
                                        {{ result.name }}</Link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-6 rtl:space-x-reverse">
                    <!-- BOTON PERFIL -->
                    <button type="button"
                        class="flex text-sm rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </button>

                    <div v-if="canLogin"
                        class="z-50 w-60 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="user-dropdown">
                        <div v-if="auth.user">
                            <div class="px-4 py-3">
                                <span class="block text-sm text-gray-500">{{ auth.user.name }}</span>
                                <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ auth.user.email
                                    }}</span>
                            </div>

                            <ul class="py-2 space-y-2" aria-labelledby="user-menu-button">
                                <li v-if="$page.props.auth.user">
                                    <a :href="route('admin.dashboard')" v-if="auth.user.isAdmin"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 float-start mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                        </svg>


                                        Dashboard</a>
                                </li>
                                <li>
                                    <Link :href="route('user.profile')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 float-start mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>

                                    Perfil
                                    </Link>
                                </li>

                                <li>
                                    <Link :href="route('user.profile.order')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 float-start mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3.75v16.5M2.25 12h19.5M6.375 17.25a4.875 4.875 0 0 0 4.875-4.875V12m6.375 5.25a4.875 4.875 0 0 1-4.875-4.875V12m-9 8.25h16.5a1.5 1.5 0 0 0 1.5-1.5V5.25a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v13.5a1.5 1.5 0 0 0 1.5 1.5Zm12.621-9.44c-1.409 1.41-4.242 1.061-4.242 1.061s-.349-2.833 1.06-4.242a2.25 2.25 0 0 1 3.182 3.182ZM10.773 7.63c1.409 1.409 1.06 4.242 1.06 4.242S9 12.22 7.592 10.811a2.25 2.25 0 1 1 3.182-3.182Z" />
                                    </svg>

                                    Pedidos
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('logout')" method="post" as="button"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 float-start mr-2 ">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>

                                    Cerrar Sesión
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="py-2" aria-labelledby="user-menu-button">
                                <li>
                                    <Link :href="route('admin.login')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    Iniciar Sesion</Link>
                                </li>
                                <li>
                                    <Link :href="route('admin.register')" v-if="canRegister"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    Crear Cuenta</Link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="inline-block ">
        <Link :href="route('cart.view')" type="button"
            class="relative inline-flex items-center p-3 text-sm font-medium text-center text-black focus:ring-4 focus:outline-none focus:ring-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>

            <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-0 -end-0 dark:border-gray-900"
                :hidden="cart.data.count === 0">
                {{ cart.data.count }}
            </div>
        </Link>

        <div v-if="countdown !== null" class="mt-2 text-sm text-gray-500">
            Time left: {{ Math.floor(countdown / 60) }}:{{ (countdown % 60).toString().padStart(2, '0') }}
        </div>
    </div>
                    <!--Boton carrito-->

                    <!-- Botón del menú en dispositivos pequeños -->
                    <button @click="toggleSidebar"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="sidebar" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>

                </div>
            </div>

        </nav>
        <nav class="bg-cyan-950  dark:bg-gray-700 hidden lg:flex">
            <div class="max-w-screen-xl px-4 py-2 mx-auto">
                <div class="flex items-center">
                    <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-md ">
                        <li v-for="category in categories" :key="category.id">
                            <Link :href="`/products?category=${category.name}`"
                                class="text-white hover:underline">
                            {{ category.name }}
                            </Link>
                        </li>
                        <li>
                            <button @click="toggleDropdown" id="mega-menu-full-dropdown-button"
                                class="flex items-center justify-between w-full py-2 px-3 rounded md:w-auto hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700">
                                Marcas
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </nav>
        <!--dropdown marcas-->
        <nav v-if="dropdownOpen" class="bg-white border-gray-200 dark:border-gray-600 dark:bg-gray-900 hidden lg:block">
            <div id="mega-menu-full-dropdown"
                class="mt-3 border-gray-200 shadow-sm bg-gray-50 md:bg-white border-y dark:bg-gray-800 dark:border-gray-600">
                <div class="max-w-screen-xl px-4 py-5 mx-auto text-white sm:grid-cols-2 md:px-6">
                    <ul class="grid grid-cols-4">
                        <li v-for="brand in brands" :key="brand.id">
                            <Link :href="`/products?brand=${brand.id}`"
                                class="text-gray-900 hover:underline">
                            {{ brand.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Menú lateral deslizante -->
        <div v-if="sidebarOpen"
            class="fixed inset-y-0 left-0 w-96  bg-white border-r lg:hidden border-gray-200 dark:bg-gray-900 transform ease-in-out transition-all duration-300 z-50 shadow-lg">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">GymBoost</h2>
                <button @click="closeSidebar"
                    class="text-gray-600 hover:dark:text-gray-400 dark:hover:text-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <ul class="p-4 space-y-2 text-gray-900">

                <div>
                    <div class="flex md:order-2">
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="search-navbar" v-model="searchQuery" @input="searchProducts"
                                class="block w-full p-2 px-56 ps-10 text-sm border border-gray-300 rounded-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 text-gray-900 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search...">
                            <div v-if="searchResults.length" v-bind:hidden="searchResults.value === ''"
                                class="absolute z-10 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg mt-1">
                                <ul class="py-1 max-h-80 overflow-y-auto">
                                    <li v-for="result in searchResults" :key="result.id"
                                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <Link :href="`/${result.slug}`"
                                            class="inline-flex justify-center items-center text-center px-6 text-gray-900">
                                        <img :src="`/storage/${result.product_images[0].image}`" alt="imagen"
                                            class="mr-5 w-14 h-14">
                                        {{ result.name }}</Link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <li>
                    <Link :href="route('user.home')"
                        class="block py-2 px-3 text-sm rounded-md text-white bg-blue-500 hover:bg-blue-600">
                    Inicio
                    </Link>
                </li>

                <!-- Dropdown for Brands -->
                <li>
                    <button @click="toggleDropdown" id="mega-menu-full-dropdown-button"
                        class="flex items-center justify-between w-full py-2 px-3 text-sm  rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                        Marcas
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>

                    <nav v-if="dropdownOpen"
                        class="mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md shadow-lg">
                        <ul class="p-4 space-y-2 max-h-48 overflow-y-auto">
                            <li v-for="brand in brands" :key="brand.id">
                                <Link :href="`/products?brand=${brand.id}`"
                                    class="block py-2 px-3 text-sm rounded-md  hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ brand.name }}
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </li>

                <li v-for="category in categories" :key="category.id">
                    <Link :href="`/products?category=${category.name}`"
                        class="block py-2 px-3 text-sm rounded-md  hover:bg-gray-100 dark:hover:bg-gray-800">
                    {{ category.name }}
                    </Link>
                </li>

                <li>
                    <Link :href="route('products.index')"
                        class="block py-2 px-3 text-sm rounded-md text-white hover:bg-gray-100 dark:hover:bg-gray-800">
                    Ver Todos
                    </Link>
                </li>


            </ul>
        </div>


        <!-- Fondo oscuro al abrir el menú -->
        <div @click="closeSidebar" :class="{ 'block': sidebarOpen, 'hidden': !sidebarOpen }"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden">
        </div>
    </div>

    <div class="flex justify-center text-center p-2 bg-teal-500 text-white text-sm">
        <h1 class="hover:underline">20% EXTRA! <br> Aplica el codigo 20LESS para obtener un 20% extra de descuento </h1>
    </div>
</template>
