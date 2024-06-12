<script setup>
import UserLayout from "../Layouts/UserLayout.vue";
import ProfileLayout from "../Layouts/ProfileLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";

defineProps({
    user: Object,

    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});


</script>

<template>
    <UserLayout>
        <ProfileLayout>
            <div class="space-y-5 p-5">
                <h1 class="text-3xl font-bold">Tus Datos</h1>
                <h1>Bienvenido a tu cuenta.
                    Desde aquí puedes revisar y actualizar tus datos de usuario
                    Gestiona tus contraseñas y cómo iniciar sesión.
                </h1>
            </div>

            <div>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                        <div class="grid grid-cols-2 border-b-2 pb-10">
                            <div class="grid grid-cols-1 space-y-10">

                                <div class="grid grid-cols-2">
                                    <div class="inline-flex space-x-5 items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="32" width="28"
                                            viewBox="0 0 448 512">
                                            <path
                                                d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z" />
                                        </svg>
                                        <div class="inline-block">
                                            <p class="text-lg font-bold">Nombre:</p>
                                            <p class="text-gray-900">{{ user.name }}</p>
                                        </div>
                                    </div>
                                    <div class="inline-block">
                                        <p class="text-lg font-bold">Apellidos:</p>
                                        <p class="text-gray-900">{{ user.last_name }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2">
                                    <div class="inline-block">
                                        <p class="text-lg font-bold">Correo Electrónico:</p>
                                        <p class="text-gray-900">{{ user.email }}</p>
                                    </div>
                                    <div class="inline-block">
                                        <p class="text-lg font-bold">Telefono Móvil:</p>
                                        <p class="text-gray-900">{{ user.tel }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1">

                                <div>
                                    <div class="inline-block">
                                        <p class="text-lg font-bold">Fecha Nacimiento:</p>
                                        <p class="text-gray-900">{{ user.birth_date }}</p>
                                    </div>
                                </div>

                                <div class="inline-flex">
                                    <button data-modal-target="user-data-modal" data-modal-toggle="user-data-modal"
                                        class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5  text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>

                                        Modificar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="w-full border-b-2 pb-10">
                            <div>
                                <div class="inline-block">
                                    <p class="text-lg font-bold">Cambiar Contraseña:</p>
                                    <p class="text-gray-900">{{ user.pass }}</p>
                                </div>

                                <button data-modal-target="user-password-modal" data-modal-toggle="user-password-modal"
                                    class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                        Modificar
                                </button>
                            </div>


                        </div>



                        <div class="py-4 sm:py-8">
                            <DeleteUserForm class="max-w-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </ProfileLayout>
    </UserLayout>

    <!-- user data modal -->
    <div id="user-data-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
            </div>
        </div>
    </div>

    <!-- user data modal -->
    <div id="user-password-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <UpdatePasswordForm class="max-w-xl" />
        </div>
    </div>


</template>
