<script setup>
import AdminLayout from '../Components/AdminLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    user: Object,
    userAddress: Array,
    ordersUser: Array,
    orderItemUser: Array,
});

const getOrderItems = (orderId) => {
    return props.orderItemUser.filter(item => item.order_id === orderId);
}

const getProductImage = (product) => {
    // Verifica si la relación product_images está definida en el producto
    if (product.product_images && product.product_images.length > 0) {
        // Retorna la URL de la primera imagen del producto
        
        return product.product_images[0].image;
        
    } 
}


</script>

<template>
    <AdminLayout>

        <div class=" mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab"
                        data-tabs-target="#profile" type="button" role="tab" aria-controls="profile"
                        aria-selected="false">Datos</button>
                </li>
                <li class="me-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab"
                        aria-controls="dashboard" aria-selected="false">Direcciones</button>
                </li>
                <li class="me-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings"
                        aria-selected="false">Pedidos</button>
                </li>
                <li role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts"
                        aria-selected="false">Productos Comprados</button>
                </li>
            </ul>
        </div>

        <div id="default-tab-content" class="">

            <!--Datos Personales-->
            <div class="hidden p-4 rounded-lg  dark:bg-gray-800" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.birth_date }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.tel }}</p>
            </div>

            <!--Direcciones-->
            <div class="hidden p-4 rounded-lg  dark:bg-gray-800" id="dashboard" role="tabpanel"
                aria-labelledby="dashboard-tab">
                <div v-for="address in userAddress" :key="address.id">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ address.type }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ address.address1 }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ address.city }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ address.zip_code }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ address.porvince }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ address.country_code }}</p>
                </div>
            </div>

            <!--Pedidos-->
            <div class="hidden p-4 rounded-lg dark:bg-gray-800" id="settings" role="tabpanel"
                aria-labelledby="settings-tab">
                <div v-for="order in ordersUser" :key="order.id" class="mb-20 mx-32">
                    <div class="flex justify-between w-full">
                        <div class="inline-flex space-x-10">
                            <div>
                                <label for="dateOrder">Fecha de Pedido</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.created_at }}</p>
                            </div>
                            <div>
                                <label for="dateOrder">Total</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.total_price }}</p>
                            </div>
                            <div>
                                <label for="dateOrder">Estado</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.status }}</p>
                            </div>
                        </div>
                        <div class="block">
                            <label class="" for="numOrder">Pedido Nº </label>
                            <a class="dark:text-gray-400">{{ order.id }}</a>
                        </div>
                    </div>

                    <div class="mt-7">
                        <div v-for="orderItem in getOrderItems(order.id)" :key="orderItem.id"
                            class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">


                                <div v-if="orderItem.product"> <!-- Verifica si orderItem.product está definido -->
                                    <!-- Muestra la imagen del producto -->
                                    <img :src="getProductImage(orderItem.product)" alt="Product Image">
                                </div>
                                <div v-else>
                                    <a href="#" class="shrink-0 md:order-1">
                                        <img class="h-20 w-20 dark:hidden"
                                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/apple-watch-light.svg"
                                            alt="imac image" />
                                    </a>
                                </div>

                                <div class="flex items-center justify-between md:order-3 md:justify-end">

                                    <div class="text-end md:order-4 md:w-32">
                                        <p class="text-base font-bold text-gray-900 dark:text-white">Cant: {{
                                            orderItem.quantity }}</p>
                                    </div>

                                    <div class="text-end md:order-4 md:w-32">
                                        <p class="text-base font-bold text-gray-900 dark:text-white">{{
                                            orderItem.unit_price }}</p>
                                    </div>
                                </div>

                                <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                    <a href="#"
                                        class="text-base font-medium text-gray-900 hover:underline dark:text-white">{{
                                        orderItem.product.name }}</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Productos Comprados-->
            <div class="hidden p-4 rounded-lg  dark:bg-gray-800" id="contacts" role="tabpanel"
                aria-labelledby="contacts-tab">

            </div>
        </div>

    </AdminLayout>
</template>