<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    product: {
        type: Object,
        required: true
    },
    stockProduct: {
        type: Array,
        required: true
    },
    weights: {
        type: Array,
        required: true
    },
    flavours: {
        type: Array,
        required: true
    },
});

const productImage = computed(() => {
    return props.product?.product_images?.[0]?.image || 'default-image.jpg';
});

const id = ref('');
const product_id = ref('');
const flavour_id = ref('');
const weight_id = ref('');
const quantity = ref('');

const dialogVisible = ref(false);
const isAddProduct = ref(false);
const editMode = ref(false);

const selectedFlavours = ref([]);
const selectedWeights = ref([]);

const getQuantityColor = (quantity) => {
    if (quantity > 100) {
        return 'bg-green-400';
    } else if (quantity > 50) {
        return 'bg-yellow-300';
    } else {
        return 'bg-red-600';
    }
}

const resetFormData = () => {
    id.value = '';
    product_id.value = '';
    flavour_id.value = '';
    weight_id.value = '';
    quantity.value = '';
}

const openAddModal = () => {
    resetFormData();
    isAddProduct.value = true;
    dialogVisible.value = true;
    editMode.value = false;
}

const addProduct = async () => {
    const data = {
        product_id: props.product.id || '',
        flavour_id: flavour_id.value,
        weight_id: weight_id.value,
        quantity: quantity.value,
    }
    console.log(data);

    try {
        await router.post(route('admin.productDetail.store'), data, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                })
                dialogVisible.value = false;
                resetFormData();
            },

        });
    } catch (error) {
        console.log(error);
    }

    dialogVisible.value = false;
}

const openEditModal = (product) => {
    resetFormData();
    console.log(product);
    id.value = product.id;
    product_id.value = product.product_id;
    flavour_id.value = product.flavour_id;
    weight_id.value = product.weight_id;
    quantity.value = product.quantity;
    editMode.value = true;
    dialogVisible.value = true;
}

const editProduct = async () => {
    const data = {
        product_id: product_id.value,
        flavour_id: flavour_id.value,
        weight_id: weight_id.value,
        quantity: quantity.value,
    }

    try {
        await router.put(route('admin.productDetail.update', { id: id.value }), data, {

            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                })
                dialogVisible.value = false;
                resetFormData();
            },

        });
    } catch (error) {
        console.log(error);
    }

    dialogVisible.value = false;
}

const deleteProduct = (id, index) => {
    Swal.fire({
        title: '¿Estas Seguro?',
        text: 'Esta accion no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'NO',
        confirmButtonText: 'Si, Eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete(route('admin.productDetail.destroy', id), {
                    onSuccess: (page) => {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success,
                        })
                    }
                })
            } catch (error) {
                console.log(error);
            }
        }
    });
}

const filteredProducts = computed(() => {
    return props.stockProduct.filter(product => {
        const matchesFlavour = selectedFlavours.value.length === 0 || selectedFlavours.value.includes(product.flavour_id);
        const matchesWeight = selectedWeights.value.length === 0 || selectedWeights.value.includes(product.weight_id);
        return matchesFlavour && matchesWeight;
    });
});

watch([selectedFlavours, selectedWeights], () => {
    console.log('Filters updated:', selectedFlavours.value, selectedWeights.value);
});

const resetFilters = () => {
    selectedFlavours.value = [];
    selectedWeights.value = [];
    filteredProducts
};

</script>


<template>
    <AdminLayout>

        <div class="container mx-auto py-2">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full space-y-3 px-4">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex flex-wrap -mx-2">
                            <div class="px-2 inline-flex justify-center items-center space-x-6">
                                <img v-if="props.product.product_images.length > 0" :src="`/storage/${props.product.product_images[0].image}`" width="80" height="80"
                                    class="rounded" alt="">
                                
                                <img v-else
                                    src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                    :alt="product.imageAlt" class="h-12 w-12 object-cover object-center" />
                                <label class="block text-lg font-medium text-gray-700">{{ props.product?.name
                                    || '' }}</label>
                                <label class="block text-lg font-medium text-gray-700">{{
                                    props.product?.brand.name || '' }}</label>
                            </div>
                        </div>
                    </div>


                    <section class="z-1 bg-gray-50 dark:bg-gray-900 py-3 sm:py-5">
                        <div class="mx-auto max-w-screen-2xl">
                            <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                                <div
                                    class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4">
                                    <div class="w-full md:w-1/2">
                                        <form class="flex items-center">
                                            <label for="simple-search" class="sr-only">Search</label>
                                            <div class="relative w-full">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true"
                                                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewbox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input type="text" id="simple-search"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="Search" required="">
                                            </div>
                                        </form>
                                    </div>
                                    <div
                                        class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3">
                                        <!--Abrir modal para nuevo producto-->
                                        <button type="button" @click="openAddModal"
                                            class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path clip-rule="evenodd" fill-rule="evenodd"
                                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                            </svg>
                                            Add new product
                                        </button>


                                        <!-- Filtros -->
                                        <div class="flex items-center space-x-3 w-full md:w-auto">

                                            <button @click="resetFilters"
                                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800">
                                                X Eliminar Filtros
                                            </button>

                                            <!-- Filtrar por Peso -->
                                            <button id="filterWeightDropdownButton"
                                                data-dropdown-toggle="filterWeightDropdown"
                                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                                type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                                    class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Peso
                                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor"
                                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                                    aria-hidden="true">
                                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                </svg>
                                            </button>
                                            <div id="filterWeightDropdown"
                                                class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                                <ul class="space-y-2 text-sm"
                                                    aria-labelledby="filterWeightDropdownButton">
                                                    <li v-for="weight in props.weights" :key="weight.id"
                                                        class="flex items-center">
                                                        <input type="checkbox" :value="weight.id"
                                                            v-model="selectedWeights"
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                        <label
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ weight.name }}
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Filtrar por Sabores -->
                                            <button id="filterFlavourDropdownButton"
                                                data-dropdown-toggle="filterFlavourDropdown"
                                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                                type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                                    class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Sabores
                                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor"
                                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                                    aria-hidden="true">
                                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                </svg>
                                            </button>
                                            <div id="filterFlavourDropdown"
                                                class="absolute z-30 hidden w-48 max-h-48 overflow-y-auto p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                                <ul class="space-y-2 text-sm"
                                                    aria-labelledby="filterFlavourDropdownButton">
                                                    <li v-for="flavour in props.flavours" :key="flavour.id"
                                                        class="flex items-center">
                                                        <input type="checkbox" :value="flavour.id"
                                                            v-model="selectedFlavours"
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                        <label
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ flavour.name }}
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="overflow-x-auto pb-36">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 pb-24">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-4 py-3">Sabor</th>
                                                <th scope="col" class="px-4 py-3">Peso</th>
                                                <th scope="col" class="px-4 py-3">Stock</th>
                                                <th scope="col" class="px-4 py-3">En Stock</th>
                                                <th scope="col" class="px-4 py-3">Creado</th>
                                                <th scope="col" class="px-4 py-3">Última Modificación</th>
                                                <th scope="col" class="px-4 py-3">Accciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <template
                                                v-if="Array.isArray(filteredProducts) && filteredProducts.length > 0">
                                                <tr v-for="product in filteredProducts" :key="product.id"
                                                    class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    
                                                    <th scope="row"
                                                        class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ product.flavour.name }}
                                                    </th>
                                                    <td class="px-4 py-2">
                                                        <span
                                                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                            {{ product.weight.name }}
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <div class="flex items-center">
                                                            <div class="inline-block w-4 h-4 mr-2 rounded-full"
                                                                :class="getQuantityColor(product.quantity)">
                                                            </div>
                                                            {{ product.quantity }}
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <span v-if="product.isStocked"
                                                            class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">En
                                                            stock</span>
                                                        <span v-else
                                                            class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Sin
                                                            Stock</span>
                                                    </td>
                                                    
                                                    <td
                                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        <div class="flex items-center">
                                                            {{ new Date(product.created_at).toLocaleDateString() }}
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ new Date(product.updated_at).toLocaleDateString() }}
                                                    </td>
                                                    <td class="px-4 py-2 inline-flex space-x-2">
                                                        <button type="button" @click="openEditModal(product)"
                                                            class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-yellow-300 hover:bg-yellow-400 focus:ring-4 focus:ring-blue-300 dark:bg-yellow-300 dark:hover:bg-yellow-300 focus:outline-none dark:focus:ring-yellow-400 transition duration-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                                fill="currentColor" class="size-4 ">
                                                                <path
                                                                    d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z" />
                                                                <path
                                                                    d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z" />
                                                            </svg>
                                                        </button>

                                                        <button type="button" @click="deleteProduct(product.id)"
                                                            class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-400 transition duration-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                                fill="currentColor" class="size-4">
                                                                <path fill-rule="evenodd"
                                                                    d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr>
                                                    <td colspan="9" class="px-4 py-3 text-center">No hay productos
                                                        disponibles</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>

                                </div>
                            
                            </div>
                        </div>
                    </section>


                    <!--CREATE MODAL-->
                    <el-dialog v-model="dialogVisible" title=" Add New" width="700">
                        <form @submit.prevent=" editMode ? editProduct() : addProduct()">
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                                <div>
                                    <label for="weight_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Peso</label>
                                    <select id="weight_id" v-model="weight_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option v-for="weight in weights" :key="weight.id" :value="weight.id">{{
                                            weight.name
                                        }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="flavours_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sabores</label>
                                    <select id="flavours_id" v-model="flavour_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option v-for="flavour in flavours" :key="flavour.id" :value="flavour.id">{{
                                            flavour.name
                                        }}</option>
                                    </select>
                                </div>

                                <div class="w-full">
                                    <label for="quantity"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock</label>
                                    <input type="text" name="quantity" id="quantity" v-model="quantity"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="99 unds" required="">
                                </div>
                            </div>

                            <div>
                                <button type="submit"
                                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 mt-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Add new product
                                </button>
                            </div>

                        </form>

                    </el-dialog>
                    <!--END CREATE MODAL-->
                </div>
            </div>
        </div>


    </AdminLayout>
</template>