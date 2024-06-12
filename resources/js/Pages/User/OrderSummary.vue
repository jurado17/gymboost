<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import NavCheckout from './Components/NavCheckout.vue';
import CheckOutProgress from '@/Pages/User/Components/CheckOutProgress.vue';

const props = defineProps({
    userAddress: {
        type: Object,
        required: true,
    },
    cartData: {
        type: Array,
        required: true,
    },
    order: {
        type: Object,
        required: true,
    }
});

const show = () => {
    console.log(props.cartData);
}
</script>

<template>
    <NavCheckout></NavCheckout>
    <section class="bg-gray-100 py-8 antialiased dark:bg-gray-900 md:py-16">
        <form action="#" class="mx-auto max-w-screen-xl px-4 2xl:px-0 ">
            <CheckOutProgress></CheckOutProgress>
            <div class="mx-auto max-w-3xl mt-6 bg-white rounded-xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Order summary</h2>
                <div class="mt-6 space-y-4 border-b border-t border-gray-200 py-8 dark:border-gray-700 sm:mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Billing & Delivery information</h4>
                    <dl>
                        <dt class="text-base font-medium text-gray-900 dark:text-white">{{ userAddress.type || '' }}</dt>
                        <dd class="mt-1 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ userAddress.province || '' }}, {{ userAddress.city || '' }}, {{ userAddress.country_code || '' }}, {{ userAddress.zip_code || '' }}, {{ userAddress.address1 || '' }}
                        </dd>
                    </dl>
                </div>
                <div class="mt-6 sm:mt-8">
                    <div class="relative overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                        <table class="w-full text-left font-medium text-gray-900 dark:text-white md:table-fixed">
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                <tr v-for="item in cartData" :key="item.id">
                                    <td class="whitespace-nowrap py-4 md:w-[384px]">
                                        <div class="flex items-center gap-4">
                                            <a v-if="item.product" href="#" class="hover:underline">{{ item.product.name }}</a>
                                            <span v-else>Nombre de Producto no disponible</span>
                                        </div>
                                    </td>

                                    <td v-if="item.quantity" class="p-4 text-base font-normal text-gray-900 dark:text-white">{{ item.quantity }} unds</td>
                                    <td v-else class="p-4 text-base font-normal text-gray-900 dark:text-white">Cantidad no disponible</td>

                                    <td v-if="item.final_price" class="p-4 text-right text-base font-bold text-gray-900 dark:text-white">${{ item.final_price }}</td>
                                    <td v-else class="p-4 text-right text-base font-bold text-gray-900 dark:text-white">$Precio no disponible</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 space-y-6">
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Order summary</h4>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Original price</dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white">${{ order.total_price || '0.00' }}</dd>
                                </dl>
                                <!-- Other summary items can go here -->
                            </div>
                            <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-gray-900 dark:text-white">${{ order.total_price || '0.00' }}</dd>
                            </dl>
                        </div>
                        <div class="flex items-start sm:items-center">
                            <label for="terms-checkbox-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Se ha enviado una factura a su correo</label>
                        </div>
                        <div class="gap-4 sm:flex sm:items-center">
                            <Link :href="route('user.home')" type="button" class="w-full flex justify-center text-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                Volver a la Tienda
                            </Link>
                            <button @click.prevent="show()" class="mt-4 flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:mt-0">
                                Descargar Factura
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</template>
