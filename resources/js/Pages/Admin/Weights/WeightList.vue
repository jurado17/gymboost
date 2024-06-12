<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    weights: Array,
})

const isAddWeight = ref(false);
const dialogVisible = ref(false);
const editMode = ref(false);

//flavour form data
const id = ref('');
const name = ref('');
const weight_g = ref('');
const price = ref('');

const resetFormData = () => {
    id.value = '',
    name.value = '',
    weight_g.value = '',
    price.value = '';
}

const openAddModal = () => {
    resetFormData();
    isAddWeight.value = true;
    dialogVisible.value = true;
    editMode.value = false;
}

const addWeight = async () => {
    const data = {
        name: name.value,
        weight_g: weight_g.value,
        price: price.value
    }

    try {
        await router.post(route('admin.weights.store', data), {
            onSucces: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
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

const openEditModal = (weight) => {
    resetFormData();
    id.value = weight.id;
    name.value = weight.name;
    weight_g.value = weight.weight_g;
    price.value = weight.price;
    isAddWeight.name = false;
    editMode.value = true;
    dialogVisible.value = true;
}

const editWeight = async () => {
    const data = {
        id: id.value,
        name: name.value,
        weight_g: weight_g.value,
        price: price.value,
    }

    try {
        await router.put(route('admin.weights.update', { id: id.value }), data, {

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

const deleteWeight = (id, index) => {
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
                router.delete(route('admin.weights.delete', id), {
                    onSuccess: (page) => {
                        deleteWeight(product, index);
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

            }
        }
    });
}


const weightColor = (weightId) => {
    switch (weightId) {
        case 1:
            return '#FF5341';
        case 2:
            return '#FFA041';
        case 7:
            return '#FFF441';
        case 4:
            return '#A0FF41';
        case 5:
            return '#41FF6C';
        case 6:
            return '#41FFD4';
        case 3:
            return '#41AFFF';
        case 8:
            return '#4153FF';
        case 9:
            return '#9241FF';
        case 10:
            return '#E541FF';
        default:
            return '#FF41B7';
    }
};
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
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
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Add product
                        </button>

                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Nombre Tamaño</th>
                                <th scope="col" class="px-4 py-3">Peso/g</th>
                                <th scope="col" class="px-4 py-3">Precio €/Kg</th>
                                <th scope="col" class="px-4 py-3">Creado</th>
                                <th scope="col" class="px-4 py-3">Ultima Modificacion</th>
                                <th></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="weight in weights" :key="weight.id" class="border-b dark:border-gray-700">

                                <th class="px-4 py-3">{{ weight.id }}</th>

                                <td class="px-4 py-3">

                                    <span :style="{ backgroundColor: weightColor(weight.id) }"
                                        class="text-xs text-black font-medium me-2 px-2.5 py-0.5 rounded-full">{{
                                            weight.name }}</span>
                                </td>
                                <td class="px-4 py-3">{{ weight.weight_g }}</td>

                                <td class="px-4 py-3">{{ weight.price }}</td>

                                <td class="px-4 py-3">{{ new Date(weight.created_at).toLocaleDateString() }}</td>

                                <td class="px-4 py-3">{{ new Date(weight.updated_at).toLocaleDateString() }}</td>

                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button id="dropdown-button-{{ weight.id }}" :data-dropdown-toggle="weight.id"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="weight.id"
                                        class="hidden z-30 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            aria-labelledby="dropdown-button">

                                            <li>
                                                <a href="#" @click="openEditModal(weight)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deleteWeight(weight, index)"
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

    <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit Weight' : 'Add Weight'" width="700">
        <form @submit.prevent="editMode ? editWeight() : addWeight()">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Weight
                        Name</label>
                    <input v-model="name" type="text" name="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="1 KG" required="">
                </div>

                <div class="sm:col-span-2">
                    <label for="weight_g" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Peso en Gramos</label>
                    <input v-model="weight_g" type="number" name="weight_g" id="weight_g"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="1000g" required="">
                </div>

                <div class="sm:col-span-2">
                    <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio/Kg</label>
                    <label class="text-gray-500 text-xs">*Esto indica por cuanto se multiplica el precio del producto según el peso</label>
                    <label class="text-gray-500 text-xs"> Ej: 1Kg = 1.00, 500g = 0.50 ..*</label>
                    <input v-model="price" type="number" name="price" id="price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="0.50" required="">
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
                    Confirmar
                </button>
            </div>

        </form>

    </el-dialog>
</template>