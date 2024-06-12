<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <nav class="bg-gray-500">
        <div class="inline-flex w-full justify-between px-72 py-4 text-2xl text-white">
            <div>
                <Link :href="route('user.home')" class="">
                <b>GYMBOOST</b>
                </Link>
            </div>

            <div class="inline-flex space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd"
                        d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z"
                        clip-rule="evenodd" />
                </svg>

                <b>COMPRA SEGURA</b>
            </div>
        </div>
    </nav>
</template>