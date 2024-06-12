<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';


const props = defineProps({
    aplyPromProduct: Array,
    nonAplyPromProduct: Array,
    promotions: Array,
})

//form data
const product_id = ref('');
const promotion_id = ref('');

const dialogVisible = ref(false);
const dialogEditVisible = ref(false);
const selectedProducts = ref([]);
const searchValue = ref('');

const resetFormData = () => {
    product_id.value = '';
    promotion_id.value = '';
}

const openAddModal = () => {
    resetFormData();
    dialogVisible.value = true;
}

const aplyPromotion = async () => {
    const data = {
        product_id: product_id.value,
        promotion_id: promotion_id.value,
    }

    try {
        await router.post(route('admin.aplyProms.store', data), {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                    timer: 2000,
                    timerProgressBar: true,
                })
                dialogVisible.value = false;
                resetFormData();
            }
        })
    } catch (error) {
        console.log(error);
    }
    dialogVisible.value = false;
    resetFormData();
}

const filteredData = computed(() => {
    let promotion = props.aplyPromProduct;

    //filtrar por valor de busqueda
    if (searchValue.value) {
        promotion = promotion.filter(row => {
            return Object.values(row).some(column => column !== null && column.toString().toLowerCase().includes(searchValue.value.toLowerCase()));
        });
    }

    return promotion;
})

const updateSelectedProducts = (productId) => {
    const index = selectedProducts.value.indexOf(productId);
    if (index > -1) {
        selectedProducts.value.splice(index, 1); // Deselect
    } else {
        selectedProducts.value.push(productId); // Select
    }
}

const openEditModal = () => {
    if (selectedProducts.value.length > 0) {
        dialogEditVisible.value = true;
    } else {
        Swal.fire({
            toast: true,
            icon: 'warning',
            position: 'top-end',
            showConfirmButton: false,
            title: 'Por favor selecciona al menos un producto',
            timer: 2000,
            timerProgressBar: true,
        });
    }
}

const updatePromotion = async () => {
    const data = {
        product_ids: selectedProducts.value,
        promotion_id: promotion_id.value,
    }

    try {
        await router.post(route('admin.aplyProms.update', data), {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                    timer: 2000,
                    timerProgressBar: true,
                })
                dialogEditVisible.value = false;
                resetFormData();
                selectedProducts.value = [];
            }
        })
    } catch (error) {
        console.log(error);
    }
}

const resetCheckbox = () => {
    selectedProducts.value = []
}
</script>


<template>
    <AdminLayout>
        <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex pt-4 pl-4">Productos con Promociones Aplicadas</div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="simple-search" v-model="searchValue"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Search" required="">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                            <button type="button" @click="openAddModal()"
                                class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path
                                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                                Aplicar Promoción
                            </button>
                            <div class="flex items-center space-x-3 w-full md:w-auto">

                                <button type="button" @click="resetCheckbox()"
                                    class="flex items-center justify-center text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                    Borrar Seleccion
                                </button>

                                <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    type="button">
                                    <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                    Acciones
                                </button>
                                <div id="actionsDropdown"
                                    class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="actionsDropdownButton">
                                        <li>
                                            <a href="#" @click="openEditModal()"
                                                class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                Editar
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="py-1">
                                        <a href="#"
                                            class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Borrar
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-all" type="checkbox"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-all" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3">Producto</th>
                                    <th scope="col" class="px-4 py-3">Marca</th>
                                    <th scope="col" class="px-4 py-3">Categoria</th>
                                    <th scope="col" class="px-4 py-3">Nombre Cupon</th>
                                    <th scope="col" class="px-4 py-3">Descuento </th>
                                    <th scope="col" class="px-4 py-3">Codigo </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in filteredData" class="border-b dark:border-gray-700">
                                    <td class="w-4 px-4 py-3">
                                        <div class="flex items-center">
                                            <input id="checkbox-table-search-{{ product.id }}" type="checkbox"
                                                :value="product.id" @change="updateSelectedProducts(product.id)"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                        </div>
                                    </td>
                                    <th scope="row"
                                        class="inline-flex px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img v-if="product.product_images.length > 0"
                                            :src="`/${product.product_images[0].image}`" :alt="product.imageAlt"
                                            class="h-12 w-12 object-cover object-center" />

                                        <img v-else
                                            src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                            :alt="product.imageAlt" class="h-12 w-12 object-cover object-center" />

                                        <a class="flex ml-3 items-center">{{ product.name }}</a>
                                    </th>
                                    <td class="px-4 py-3">{{ product.brand.name }}</td>
                                    <td class="px-4 py-3">{{ product.category.name }}</td>
                                    <td class="px-4 py-3">{{ product.promotion.name }}</td>
                                    <td class="px-4 py-3">{{ product.promotion.porcentual_discount * 100 }} % </td>
                                    <td class="px-4 py-3">{{ product.promotion.discount_code }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>


        <!--MODAL aplicar promociones-->
        <el-dialog v-model="dialogVisible" :title="'Aplicar Promoción'" width="700">
            <form @submit.prevent="aplyPromotion()">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <div>
                        <label for="brand"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
                        <select id="brand" v-model="product_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option v-for="product in nonAplyPromProduct" :key="product.id" :value="product.id">{{
                                product.name }}</option>
                        </select>
                    </div>


                    <div>
                        <label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Promoción</label>
                        <select id="category" v-model="promotion_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option v-for="prom in promotions" :key="prom.id" :value="prom.id">{{ prom.name
                                }}</option>

                        </select>
                    </div>


                </div>

                <div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 mt-6 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Aplicar Promoción
                    </button>
                </div>

            </form>

        </el-dialog>

        <!--Modal para editar-->
        <el-dialog v-model="dialogEditVisible" :title="'Editar Promoción'" width="700">
            <form @submit.prevent="updatePromotion">
                <div class="mb-4">
                    <label for="promotion_id_edit" class="block text-sm font-medium text-gray-700">Nueva
                        Promoción</label>
                    <select id="promotion_id_edit" v-model="promotion_id" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option v-for="promotion in props.promotions" :key="promotion.id" :value="promotion.id">{{
                            promotion.name }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="selected_products" class="block text-sm font-medium text-gray-700">Productos
                        seleccionados</label>
                    <ul>
                        <li v-for="productId in selectedProducts" :key="productId">
                            {{ props.aplyPromProduct.find(product => product.id === productId).name }}
                        </li>
                    </ul>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Actualizar</button>
                    <button type="button" @click="dialogEditVisible = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancelar</button>
                </div>
            </form>
        </el-dialog>

    </AdminLayout>
</template>