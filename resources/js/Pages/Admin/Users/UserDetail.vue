<script setup>
import AdminLayout from '../Components/AdminLayout.vue';

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
    if (product && product.product_images && product.product_images.length > 0) {
        return product.product_images[0].image;
    }
    return 'https://flowbite.s3.amazonaws.com/blocks/e-commerce/apple-watch-light.svg'; // URL de imagen de reserva
}
</script>

<template>
    <AdminLayout>
        <div class="container mx-auto p-6">
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
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
                            id="settings-tab" data-tabs-target="#settings" type="button" role="tab"
                            aria-controls="settings" aria-selected="false">Pedidos</button>
                    </li>
                </ul>
            </div>

            <div id="default-tab-content">
                <!--Datos Personales-->
                <div class="hidden p-4 rounded-lg dark:bg-gray-800" id="profile" role="tabpanel"
                    aria-labelledby="profile-tab">
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ user.name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.birth_date }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.tel }}</p>
                    </div>
                </div>

                <!--Direcciones-->
                <div class="hidden p-4 rounded-lg dark:bg-gray-800" id="dashboard" role="tabpanel"
                    aria-labelledby="dashboard-tab">
                    <div v-for="address in userAddress" :key="address.id" class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
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
                    <div v-for="order in ordersUser" :key="order.id" class="mb-10">
                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <div class="space-y-2">
                                    <div>
                                        <label for="dateOrder" class="font-semibold">Fecha de Pedido:</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.created_at }}</p>
                                    </div>
                                    <div>
                                        <label for="totalOrder" class="font-semibold">Total:</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.total_price }}</p>
                                    </div>
                                    <div>
                                        <label for="statusOrder" class="font-semibold">Estado:</label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.status }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <label for="numOrder" class="font-semibold">Pedido Nº:</label>
                                    <a class="text-sm text-gray-500 dark:text-gray-400">{{ order.id }}</a>
                                </div>
                            </div>

                            <div v-for="orderItem in getOrderItems(order.id)" :key="orderItem.id"
                                class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6 mb-4">
                                <div class="md:flex md:items-center md:justify-between">
                                    <div class="flex-shrink-0">
                                        <img :src="`/storage/${getProductImage(orderItem.product)}`" alt="Product Image" class="h-20 w-20 object-cover rounded-lg">
                                    </div>
                                    <div class="flex-grow md:ml-6">
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">{{ orderItem.product.name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Cant: {{ orderItem.quantity }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Precio: {{ orderItem.unit_price }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
