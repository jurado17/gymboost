<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted } from 'vue';
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
});
</script>


<template>

    <div class="flex justify-center p-2 bg-gray-400 text-white">
        <h1>Envios gratuitos por compras menores de 24,99</h1>
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
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="search-navbar" v-model="searchQuery" @input="searchProducts"
                                class="block w-full p-2 px-56 ps-10 text-sm border border-gray-300 rounded-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 text-gray-900 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search...">
                            <div v-if="searchResults.length" v-bind:hidden="searchResults.value === ''"
                                class="absolute z-10 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg mt-1">
                                <ul class="py-1">
                                    <li v-for="result in searchResults" :key="result.id"
                                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <Link :href="`/${result.slug}`"
                                            class="inline-flex justify-center items-center text-center px-6 text-gray-900">
                                            <img  :src="`/${result.product_images[0].image}`" alt="imagen" class="mr-5 w-14 h-14">
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
                        Perfil
                    </button>

                    <div v-if="canLogin"
                        class="z-50 w-60 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="user-dropdown">
                        <div v-if="auth.user">
                            <div class="px-4 py-3">
                                <span class="block text-sm text-white">{{ auth.user.name }}</span>
                                <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ auth.user.email
                                    }}</span>
                            </div>

                            <ul class="py-2 space-y-2" aria-labelledby="user-menu-button">
                                <li v-if="$page.props.auth.user">
                                    <a :href="route('admin.dashboard')" v-if="auth.user.isAdmin"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">

                                        Dashboard</a>
                                </li>
                                <li>
                                    <Link :href="route('user.profile')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">

                                    Perfil
                                    </Link>
                                </li>

                                <li>
                                    <Link :href="route('user.profile.order')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">

                                    Pedidos
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('logout')" method="post" as="button"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
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
                        Cesta


                        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-0 -end-0 dark:border-gray-900"
                            :hidden="cart.data.count === 0">
                            {{ cart.data.count }}
                        </div>
                        </Link>

                    </div>
                    <!--Boton carrito-->

                    <!-- Botón del menú en dispositivos pequeños -->
                    <button @click="toggleSidebar"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="sidebar" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        Menu
                    </button>

                </div>
            </div>

        </nav>
        <nav class="bg-cyan-900  dark:bg-gray-700 hidden lg:flex">
            <div class="max-w-screen-xl px-4 py-4 mx-auto">
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
                            </button>
                        </li>
                        <li>
                            <Link :href="route('products.index')" class="text-white hover:underline">
                            Ver Todos
                            </Link>
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
            class="fixed inset-y-0 left-0 w-64 bg-white border-r lg:hidden border-gray-200 dark:bg-gray-900 transform ease-in-out transition-all duration-300 z-50 shadow-lg">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">GymBoost</h2>
                <button @click="closeSidebar"
                    class="text-gray-600 hover:dark:text-gray-400 dark:hover:text-gray-100">
                    Menu
                </button>
            </div>
            <ul class="p-4 space-y-2">
                <li>
                    <Link :href="route('user.home')"
                        class="block py-2 px-3 text-sm rounded-md text-white bg-blue-500 hover:bg-blue-600">
                    Inicio
                    </Link>
                </li>

                <!-- Dropdown for Brands -->
                <li>
                    <button @click="toggleDropdown" id="mega-menu-full-dropdown-button"
                        class="flex items-center justify-between w-full py-2 px-3 text-sm text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                        Marcas
                        
                    </button>

                    <nav v-if="dropdownOpen"
                        class="mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md shadow-lg">
                        <ul class="p-4 space-y-2">
                            <li v-for="brand in brands" :key="brand.id">
                                <Link :href="`/products?brand=${brand.id}`"
                                    class="block py-2 px-3 text-sm rounded-md text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ brand.name }}
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </li>

                <li v-for="category in categories" :key="category.id">
                    <Link :href="`/products?category=${category.name}`"
                        class="block py-2 px-3 text-sm rounded-md text-white hover:bg-gray-100 dark:hover:bg-gray-800">
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

    <div class="flex justify-center text-center p-2 bg-cyan-500 text-white text-sm">
        <h1 class="hover:underline">¡SEMANA BOOST! <br> 50% de Descuento en nuestra selección de productos</h1>
    </div>

    <div class="flex justify-center text-center p-2 bg-teal-500 text-white text-sm">
        <h1 class="hover:underline">20% EXTRA! <br> Aplica el codigo 20LESS para obtener un 20% extra de descuento </h1>
    </div>
</template>
