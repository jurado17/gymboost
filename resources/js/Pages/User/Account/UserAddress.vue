<script setup>
import UserLayout from "../Layouts/UserLayout.vue";
import ProfileLayout from "../Layouts/ProfileLayout.vue";
import { ref, computed } from 'vue'
import { router } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    userAddress: Array,
})

const isAddAddress = ref(false);
const dialogVisible = ref(false);
const editMode = ref(false);

const id = ref('');
const type = ref('');
const address1 = ref('');
const city = ref('');
const province = ref('');
const zip_code = ref('');
const country_code = ref('');

const resetFormData = () => {
    id.value = '';
    type.value = '';
    address1.value = '';
    city.value = '';
    province.value = '';
    zip_code.value = '';
    country_code.value = '';
}

const openAddModal = () => {
    resetFormData();
    isAddAddress.value = true;
    dialogVisible.value = true;
    editMode.value = false;
}

const addAddress = async () => {
    const data = {
        type: type.value,
        address1: address1.value,
        city: city.value,
        province: province.value,
        zip_code: zip_code.value,
        country_code: country_code.value,
        user_id: props.user.id
    }

    try {
        await router.post(route('user.profile.useraddress.store', data), {
            onSucces: page => {
                Swal.fire({
                    toast: true,
                    position: 'bottom-start',
                    showConfirmButton: false,
                    text: page.props.flash.success,
                    timer: 1500
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

const openEditModal = (address) => {
    resetFormData();
    console.log(address);
    id.value = address.id;
    type.value = address.type;
    address1.value = address.address1;
    city.value = address.city;
    province.value = address.province;
    zip_code.value = address.zip_code;
    country_code.value = address.country_code;
    isAddAddress.value = false;
    dialogVisible.value = true;
    editMode.value = true;
}

const updateAddress = async () => {
    const data = {
        id: id.value,
        type: type.value,
        address1: address1.value,
        city: city.value,
        province: province.value,
        zip_code: zip_code.value,
        country_code: country_code.value,
        user_id: props.user.id
    }

    try {
        await router.put(route('user.profile.useraddress.update', { id: id.value }), data, {
            onSucces: page => {
                Swal.fire({
                    toast: true,
                    position: 'bottom-start',
                    showConfirmButton: false,
                    text: page.props.flash.success,
                    timer: 1500
                })
                dialogVisible.value = false;
                resetFormData();
            }
        })
    } catch (error) {
        console.log(RTCPeerConnectionIceErrorEvent);
    }
    dialogVisible.value = false;

}

const deleteAddress = (id, index) => {
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
                router.delete(route('user.profile.useraddress.delete', id), {
                    onSuccess: (page) => {
                        deleteFlavour(product, index);
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

</script>

<template>
    <UserLayout>
        <ProfileLayout>
            <div>
                <h1 class="text-3xl font-bold">Direcciones</h1>
                <h1>Añade y modifica tus direcciones. Estas servira tanto como dirección de envio como de facturación
                </h1>

                <div class="grid grid-cols-3 ">
                    <button @click="openAddModal()" class="mt-10 mx-20 mb-20 p-10 border-2 ">
                        <div class="bg-white border border-gray-200  shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                            <div class="justify-center flex items-center text-center">

                                <svg xmlns="http:www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <p>Añadir Direccion</p>
                        </div>
                    </button>
                    <div class="p-10" v-for="address in props.userAddress" :key="address.id">
                        <div
                            class="w-full max-w-sm bg-white border border-gray-200  shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                            <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400">{{ address.type }}
                            </h5>
                            <div class="flex items-baseline text-gray-900 dark:text-white">
                                <span class="text-2xl font-semibold">{{ props.user.name }} {{ props.user.last_name
                                    }}</span>
                            </div>
                            <ul role="list" class="space-y-3 my-7">
                                <li class="flex items-center">
                                    <span
                                        class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3">
                                        {{ address.address1 }}
                                    </span>
                                </li>
                                <li class="flex items-center">
                                    <span
                                        class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3">
                                        {{ address.city }}
                                    </span>
                                </li>
                                <li class="flex items-center">
                                    <span
                                        class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3">
                                        {{ address.province }}
                                    </span>
                                </li>
                                <li class="flex items-center">
                                    <span
                                        class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3">
                                        {{ address.zip_code }}
                                    </span>
                                </li>
                                <li class="flex items-center">
                                    <span
                                        class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400 ms-3">
                                        {{ address.country_code }}
                                    </span>
                                </li>

                            </ul>
                            <div class="grid grid-cols-2">
                                <button type="button" @click="deleteAddress(address)"
                                    class="flex items-center justify-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 transition"><svg
                                        xmlns="http:www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>

                                    Borrar
                                </button>

                                <button type="button" @click="openEditModal(address)"
                                    class="flex items-center justify-center text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 transition">

                                    <svg xmlns="http:www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>

                                    Modificar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </ProfileLayout>
    </UserLayout>

    <!--MODAL-->
    <el-dialog v-model="dialogVisible" :title="editMode ? 'Modificar Direccion' : 'Añadir Direccion'" width="700">
        <form @submit.prevent="editMode ? updateAddress() : addAddress()">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <div class="sm:col-span-2">
                    <div class="mb-4">
                        <label for="address1"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Dirección*</label>
                        <input type="text" id="address1" v-model="address1"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            required />
                    </div>
                </div>

                <div>
                    <label for="city" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Población
                        *</label>
                    <input type="text" id="city" v-model="city"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" />
                </div>


                <div>
                    <label for="zip_code" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Codigo
                        Postal *</label>
                    <input type="text" id="zip_code" v-model="zip_code"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        required />
                </div>

                <div>
                    <label for="province" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Provincia
                        *</label>
                    <input type="text" id="province" v-model="province"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        required />
                </div>



                <div>
                    <div class="mb-2 flex items-center gap-2">
                        <label for="select-country-input-3"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Pais*</label>
                    </div>
                    <select id="select-country-input-3" v-model="country_code"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                        <option disabled selected>Escoge un País</option>
                        <option value="US">Estados Unidos</option>
                        <option value="AS">Australia</option>
                        <option value="FR">Francia</option>
                        <option value="ES">España</option>
                        <option value="UK">Inglaterra</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 mt-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http:www.w3.org/2000/svg">
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