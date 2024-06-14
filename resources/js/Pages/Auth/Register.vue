<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    last_name: '',
    email: '',
    tel:'',
    birth_date:'',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>


<section class=" dark:bg-gray-900">
        <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-10 w-auto" src="../../../../public/icon.png"
                    alt="Your Company" />
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your
                    account</h2>
            </div>

            <div class="bg-gray-100 rounded-lg p-5 mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nombre</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" v-model="form.name"  autofocus
                                placeholder="Nombre"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium leading-6 text-gray-900">Apellidos</label>
                        <div class="mt-2">
                            <input id="last_name" name="last_name" type="text" v-model="form.last_name"  autofocus
                                
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.last_name" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                        </label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" v-model="form.email"  autofocus
                                
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <label for="tel" class="block text-sm font-medium leading-6 text-gray-900">Telefono</label>
                        <div class="mt-2">
                            <input id="tel" name="tel" type="number" v-model="form.tel"  autofocus
                                placeholder="Nombre"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.tel" />
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium leading-6 text-gray-900">Fecha Nacimiento</label>
                        <div class="mt-2">
                            <input id="birth_date" name="birth_date" type="date" v-model="form.birth_date"  autofocus
                                placeholder="Nombre"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.birth_date" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password"
                                class="block text-sm font-medium leading-6 text-gray-900">Contraseña</label>
                            
                        </div>
                        <div class="mt-2">
                            <input v-model="form.password" id="password" name="password" type="password"
                                autocomplete="current-password"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password"
                                class="block text-sm font-medium leading-6 text-gray-900">Confirmar Contraseña</label>
                            
                        </div>
                        <div class="mt-2">
                            <input v-model="form.password_confirmation" id="password_confirmation" name="password_confirmation" type="password"
                                autocomplete="current-password"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-cyan-600 sm:text-sm sm:leading-6" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    
                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800">Registrarse</button>

                        <Link :href="route('admin.register')"
                            class="text-sm font-light text-gray-500 dark:text-gray-400">
                        ¿Ya tienes una cuenta? <a href="#"
                            class="font-medium text-cyan-600 hover:underline dark:text-cyan-500">Iniciar Sesion</a>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>
