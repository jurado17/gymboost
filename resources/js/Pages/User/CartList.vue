<script setup>
import { computed, ref } from 'vue'
import UserLayout from "./Layouts/UserLayout.vue";
import PeopleAlsoBought from "./Layouts/PeopleAlsoBought.vue";
import { router, usePage, Link } from '@inertiajs/vue3';
import axios from 'axios'; // Importar axios para las solicitudes HTTP

const cartItems = computed(() => usePage().props.cartItems || []);

const discount = ref(0); // Variable reactiva para almacenar el descuento aplicado
const discountIsAplied = ref(false);

const totalValue = computed(() => {
    let total = cartItems.value.reduce((total, item) => {
        return total + Number(item.final_price);
    }, 0);
    return (total * (1 - discount.value )).toFixed(2); // Aplicar el descuento
});

const totalIva = computed(() => parseFloat((totalValue.value * 0.21).toFixed(2)));
const envio = computed(() => totalValue.value > 25.90 ? 0 : 4.95);
const total = computed(() => (parseFloat(totalValue.value) + parseFloat(totalIva.value) + parseFloat(envio.value)).toFixed(2));


const update = (cartItem, quantity) => {
    router.patch(route('cart.update', { product: cartItem.product.id, weight: cartItem.weight.id, flavour: cartItem.flavour.id }), {
        quantity,
    });
};

const remove = (cartItem) => {
    router.delete(route('cart.delete', { product: cartItem.product.id, weight: cartItem.weight.id, flavour: cartItem.flavour.id }));
};

const voucher = ref('');

const applyCoupon = async () => {
    try {
        const response = await axios.post(route('cart.applyCoupon'), { discount_code: voucher.value });
        discount.value = response.data.discount; // Actualizar el descuento
        console.log(response.data.message, discount.value);
        discountIsAplied.value = true;
    } catch (error) {
        console.log(error);
    }
};

</script>

<template>
    <UserLayout>
        <section class="bg-gray-100  py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                    Tu cesta
                </h2>

                <div class=" mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
                    <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
                        <div class="space-y-6">

                            <div v-if="cartItems.length === 0">
                                <p class="text-3xl text-center">
                                    Tu Cesta Esta Vacia
                                </p>

                                <Link :href="route('user.home')" type="button"
                                    class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Volver a la Tienda
                                </Link>
                            </div>

                            <div v-else v-for="cartItem in cartItems" :key="cartItem.id"
                                class="rounded-lg border border-gray-00 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                    <a href="#" class="shrink-0 md:order-1">
                                        <img v-if="cartItem.product.product_images && cartItem.product.product_images.length > 0"
                                            class="h-32 w-auto dark:hidden"
                                            :src="`/storage/${cartItem.product.product_images[0].image}`" alt="product image" />
                                        <img v-else
                                            src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                            alt="No Image Available"
                                            class="h-32 w-32 object-cover object-center lg:h-36 lg:w-36" />
                                    </a>

                                    <label for="counter-input" class="sr-only">Choose quantity:</label>
                                    <div class="flex items-center justify-between md:order-3 md:justify-end">
                                        <div class="flex items-center">
                                            <button @click.prevent="update(cartItem, cartItem.quantity - 1)"
                                                :disabled="cartItem.quantity <= 1" type="button" id="decrement-button"
                                                data-input-counter-decrement="counter-input"
                                                class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                                <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                </svg>
                                            </button>
                                            <input v-model="cartItem.quantity" type="text" id="counter-input"
                                                data-input-counter
                                                class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white"
                                                placeholder="" value="1" required readonly />
                                            <button @click.prevent="update(cartItem, cartItem.quantity + 1)"
                                                type="button" id="increment-button"
                                                data-input-counter-increment="counter-input"
                                                class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                                <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-end md:order-4 md:w-32">
                                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                                {{ cartItem.final_price }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                        <a href="#"
                                            class="text-base font-medium text-gray-900 hover:underline dark:text-white">{{
                                                cartItem.product.name }} - {{ cartItem.weight.weight_g }}g - {{
                                            cartItem.flavour.name }}</a>


                                        <div class="flex items-center gap-4">
                                            <button @click="remove(cartItem)" type="button"
                                                class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                                <svg class="me-1.5 h-5 w-5" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18 17.94 6M18 18 6.06 6" />
                                                </svg>
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="pt-24 hidden xl:pt-0">
                            <div class="mt-6 sm:flex sm:items-center sm:justify-between">
                                <Link :href="route('user.home')" type="button"
                                    class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Continue Shopping
                                </Link>
                            </div>
                        </div>

                        <!--People also bought section-->
                        <PeopleAlsoBought></PeopleAlsoBought>
                        <!--People also bought end section-->
                    </div>

                    <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                        <div
                            class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">


                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Precio Base
                                        </dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ totalValue }}
                                        </dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                                        <dd class="text-base font-medium text-green-600">-$299.00</dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Envio
                                        </dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ envio }}</dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">IVA (21%)
                                        </dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">{{ totalIva }}
                                        </dd>
                                    </dl>
                                </div>

                                <dl
                                    class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                    <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                    <dd class="text-base font-bold text-gray-900 dark:text-white">{{ total }}</dd>
                                </dl>
                            </div>

                            <Link :href="route('checkout.index', { total: total })"
                                class="flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Ir al Checkout</Link>

                            <div class="flex items-center justify-center gap-2">
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> o </span>
                                <a :href="route('user.home')" title=""
                                    class="inline-flex items-center gap-2 text-sm font-medium text-blue-700 underline hover:no-underline dark:text-blue-500">
                                    Continuar Comprando
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div
                            class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                            <form @submit.prevent="applyCoupon" class="space-y-4">
                                <div>
                                    <label for="voucher"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">¿Tienes un
                                        cupón o tarjeta de regalo?</label>
                                    <input v-model="voucher" type="text" id="voucher"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                        placeholder="" required />
                                </div>
                                <button type="submit"
                                    class="flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Aplicar Cupón
                                </button>
                                <p v-if="discountIsAplied">Cupon Aplicado</p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </UserLayout>
</template>
