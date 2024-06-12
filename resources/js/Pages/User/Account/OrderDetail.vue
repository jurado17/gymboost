<script setup>
import UserLayout from "../Layouts/UserLayout.vue";
import ProfileLayout from "../Layouts/ProfileLayout.vue";

const props = defineProps({
    order:Object,
    orderItem: Array,
})

const totalPrice = props.order[0].total_price;
const tax = (totalPrice * 0.21).toFixed(2)
const ogPrice = totalPrice-tax;

</script>
<template>
    <UserLayout>
        <ProfileLayout>
            <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Pedido Nº {{ props.orderItem[0].order_id }}</h2>

                    <div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
                        <div class="w-full divide-y divide-gray-200 overflow-hidden rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700 lg:max-w-xl xl:max-w-2xl">
                            <div v-for="item in orderItem" :key="item.id"
                            class="space-y-4 p-6">
                                <div class="flex items-center gap-6">
                                    <a href="#" class="h-20 w-20 shrink-0">
                                        <img class="h-full w-full dark:hidden"
                                            :src="`/${item.product.product_images[0].image}`"
                                            alt="imac image" />
                                    </a>

                                    <a href="#"
                                        class="min-w-0 flex-1 font-medium text-gray-900 hover:underline dark:text-white">
                                        {{ item.product.name }}-{{ item.product.brand.name }}-{{ item.product.category.name }}-{{ item.stock_item.weight.name }}-{{ item.stock_item.flavour.name }}
                                     </a>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400"><span
                                            class="font-medium text-gray-900 dark:text-white">Product ID:</span>
                                            {{ item.product.id }}</p>

                                    <div class="flex items-center justify-end gap-4">
                                        <p class="text-base font-normal text-gray-900 dark:text-white">x{{ item.quantity }}</p>

                                        <p class="text-xl font-bold leading-tight text-gray-900 dark:text-white">{{ item.unit_price }}
                                        </p>
                                    </div>
                                </div>
                            </div>



                            <div class="space-y-4 bg-gray-50 p-6 dark:bg-gray-800">
                                <div class="space-y-2">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="font-normal text-gray-500 dark:text-gray-400">Original price</dt>
                                        <dd class="font-medium text-gray-900 dark:text-white">{{ogPrice}} €</dd>
                                    </dl>

                                    <!-- <dl class="flex items-center justify-between gap-4">
                                        <dt class="font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                                        <dd class="text-base font-medium text-green-500">-$299.00</dd>
                                    </dl> -->

                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="font-normal text-gray-500 dark:text-gray-400">Tax (21%)</dt>
                                        <dd class="font-medium text-gray-900 dark:text-white">{{ tax }}€</dd>
                                    </dl>
                                </div>

                                <dl
                                    class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                    <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                                    <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ totalPrice }} €</dd>
                                </dl>
                            </div>
                        </div>


                    </div>
                </div>
            </section>
        </ProfileLayout>
    </UserLayout>
</template>