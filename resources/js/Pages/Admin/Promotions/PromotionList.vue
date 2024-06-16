<script setup>
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    promotions: Array
})

const isAddPromotion = ref(false);
const dialogVisible = ref(false);
const editMode = ref(false);

//datos de formulario
const id = ref('');
const type = ref('');
const name = ref('');
const description = ref('');
const discount_code = ref('');
const porcentual_discount = ref('');
const active_until = ref('');

//funcion para vaciar los formularios
const resetFormData = () => {
    id.value = '';
    type.value = '';
    name.value = '';
    description.value = '';
    discount_code.value = '';
    porcentual_discount.value = '';
    active_until.value = '';
}

//funcion para abrir el modal para crear nuevas promociones
const openAddModal = () => {
    resetFormData();
    isAddPromotion.value = true;
    dialogVisible.value = true;
    editMode.value = false;
}

const addPromotion = async () => {
    const data = {
        type: type.value,
        name: name.value,
        description: description.value,
        discount_code: discount_code.value,
        porcentual_discount: porcentual_discount.value,
        active_until: active_until.value,
    }
    try {
        await router.post(route('admin.promotions.store', data), {
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
}

const openEditModal = (promotion) => {
    resetFormData();
    id.value = promotion.id,
        type.value = promotion.type;
    name.value = promotion.name;
    description.value = promotion.description;
    discount_code.value = promotion.discount_code;
    porcentual_discount.value = promotion.porcentual_discount;
    active_until.value = promotion.active_until;
    isAddPromotion.value = false;
    editMode.value = true;
    dialogVisible.value = true;
}

const editPromotion = async () => {
    const data = {
        id: id.value,
        type: type.value,
        name: name.value,
        description: description.value,
        discount_code: discount_code.value,
        porcentual_discount: porcentual_discount.value,
        active_until: active_until.value,
    }

    try {
        await router.put(route('admin.promotions.update', data), {
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
}

const deletePromotion = (id, index) => {
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
                router.delete(route('admin.promotions.delete', id), {
                    onSuccess: (page) => {
                        deleteFlavour(product, index);
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success,
                            timer: 2000,
                            timerProgressBar: true,
                        })
                    }
                })
            } catch (error) {
                console.log(error);
            }
        }
    });
}



const activatePromotion = (prom, index) => {
    Swal.fire({
        title: 'Cambiar estado de la promocion?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'NO',
        confirmButtonText: 'Si, cambiar'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.put(route('admin.promotions.activate', prom.id), {
                    onSuccess: (page) => {
                        activatePromotion(prom, index);
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


const searchValue = ref('');
const selectedType = ref([]);
const selectedIsActive = ref([]);

//funcion para filtrar los datos
const filteredData = computed(() => {
    let promotion = props.promotions;

    //filtrar por valor de busqueda
    if (searchValue.value) {
        promotion = promotion.filter(row => {
            return Object.values(row).some(column => column !== null && column.toString().toLowerCase().includes(searchValue.value.toLowerCase()));
        });
    }

    //filtar por tipo seleccionado
    if (selectedType.value.length > 0) {
        promotion = promotion.filter(promotion => selectedType.value.includes(promotion.type));
    }

    //filtrar si esta activo o no 
    if (selectedIsActive.value.length > 0) {
        promotion = promotion.filter(promotion => selectedIsActive.value.includes(promotion.isActive));
    }

    return promotion;
})

const resetFilters = () => {
    searchValue.value = '';
    selectedIsActive.value = [];
    selectedType.value = [];
}


const TypeColor = (categoryId) => {
    switch (categoryId) {
        case 'producto':
            return '#9241FF';
        case 'carrito':
            return '#FFA041';

    }
};

</script>

<template>

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

            <Link type="button" :href="route('admin.aplyProms.view')"
                class="flex w-56 items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 my-4 py-3 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 shadow-md">
                Ver Productos Con Promocion
            </Link>
            
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
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
                        <button type="button" @click="openAddModal"
                            class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path
                                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                            Add Promotion
                        </button>

                        <!--Filtros-->
                        <div class="flex items-center space-x-3 w-full md:w-auto">
                            <!--Boton borrar filtros-->
                            <button @click="resetFilters"
                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800">
                                X Eliminar Filtros
                            </button>

                            <!--Filtro tipo-->
                            <button id="filterTypeDropdownButton" data-dropdown-toggle="filterTypeDropdown"
                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                    class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tipo
                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                            <div id="filterTypeDropdown"
                                class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                <ul class="space-y-2 text-sm" aria-labelledby="filterTypeDropdownButton">
                                    <li class="flex items-center">
                                        <input id="inputTypeCar" type="checkbox" value="carrito" v-model="selectedType"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="inputType"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Carrito</label>
                                    </li>

                                    <li class="flex items-center">
                                        <input id="inputTypePro" type="checkbox" value="producto" v-model="selectedType"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="inputType"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Producto</label>
                                    </li>
                                </ul>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Nombre</th>
                                <th scope="col" class="px-4 py-3">Tipo</th>
                                <th scope="col" class="px-4 py-3">Descripcion</th>
                                <th scope="col" class="px-4 py-3">Codigo</th>
                                <th scope="col" class="px-4 py-3">Descuento %</th>
                                <th scope="col" class="px-4 py-3">Activo Hasta</th>
                                <th scope="col" class="px-4 py-3">Estado</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="promotion in filteredData" :key="promotion.id"
                                class="border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white inline-flex">

                                    <a class="flex ml-3 items-center">{{ promotion.id }}</a>
                                </th>

                                <td class="px-4 py-3">{{ promotion.name }}</td>
                                <td class="px-4 py-3">
                                    <span :style="{ backgroundColor: TypeColor(promotion.type) }"
                                        class="text-xs text-black font-medium me-2 px-2.5 py-0.5 rounded-full">
                                        {{ promotion.type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ promotion.description }}</td>
                                <td class="px-4 py-3">{{ promotion.discount_code }}</td>
                                <td class="px-4 py-3">{{ promotion.porcentual_discount }}</td>
                                <td class="px-4 py-3">{{ promotion.active_until }}</td>
                                <td class="px-4 py-3">
                                    <button v-if="promotion.isActive == 1" type="button" @click="activatePromotion(promotion)"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Activo</button>
                                    <button v-else type="button" @click="activatePromotion(promotion)"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Inactivo</button>
                                </td>

                                <td class="px-4 py-3 flex items-center justify-end"> 
                                    <button id="dropdown-button-{{ promotion.id }}" :data-dropdown-toggle="promotion.id"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="promotion.id"
                                        class="hidden z-30 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="dropdown-button">
                                            <li>
                                                <a href="#" @click="openEditModal(promotion)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deletePromotion(promotion, index)"
                                                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                        </div>
                                    </div>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

            
        </div>
    </section>

    <!--MODAL-->
    <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit Promotion' : 'Add Promotion'" width="700">
        <form @submit.prevent="editMode ? editPromotion() : addPromotion()">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">promotion
                        Name</label>
                    <input v-model="name" type="text" name="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Type promotion name" required="">
                </div>

                <div>
                    <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
                    <select id="type" v-model="type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="producto">Producto</option>
                        <option value="carrito">Carrito</option>
                    </select>
                </div>

                <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    <label for="discount_code"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo </label>
                    <input v-model="discount_code" type="text" name="discount_code" id="discount_code"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="50LESS" required="">
                </div>

                <div class="w-full">
                    <label for="porcentual_discount"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">% Descuento</label>
                    <input type="text" name="porcentual_discount" id="porcentual_discount" v-model="porcentual_discount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="0.50, 0.20 ..." required="">
                </div>

                <div>
                    <label for="active_until"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valido Hasta</label>
                    <input type="date" name="active_until" id="active_until" v-model="active_until"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required="">
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Description</label>
                    <textarea id="description" name="description" rows="8" v-model="description"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Descripción completa del promotiono"></textarea>
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
                    Continuar
                </button>
            </div>
        </form>
    </el-dialog>
</template>