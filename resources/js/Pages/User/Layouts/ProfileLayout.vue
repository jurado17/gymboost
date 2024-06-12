<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="common-layout">
        <el-container class="space-y-6">
            <el-aside class="pl-10 py-20 space-y-6 border-r-2">
                <div class="pl-5 ">
                    <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">Tu Cuenta</h2>
                    <ul class="pl-10 max-w-md space-y-5 text-gray-500 dark:text-gray-400">
                        <li>
                            <Link :href="route('user.profile')">
                            Vista General
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('user.profileUserData')">
                            Mis Datos
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('user.profile.useraddress')">
                            Direcciones
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('user.profile.order')">
                            Mis Pedidos
                            </Link>
                        </li>
                    </ul>
                </div>
            </el-aside>
            <el-main>

                <slot />

            </el-main>
        </el-container>
    </div>

</template>