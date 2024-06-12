<script setup>
import UserLayout from './Layouts/UserLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupOption,
} from '@headlessui/vue';
import PeopleAlsoBought from './Layouts/PeopleAlsoBought.vue';

const props = defineProps({
    product: Object,
    weights: Array,
    flavours: Array,
    stockProduct: Array,
    discountedPrice: Number
});

const selectedQuantity = ref(null);
const selectedWeight = ref(null);
const selectedFlavour = ref(null);

const basePrice = computed(() => {
    return props.discountedPrice != null ? props.discountedPrice : props.product.price;
});

const calculatedPrice = computed(() => {
    if (selectedWeight.value) {
        return (parseFloat(basePrice.value) * (selectedWeight.value.weight_g / 1000)).toFixed(2);
    }
    return parseFloat(basePrice.value).toFixed(2);
});

const originalPrice = computed(() => {
    if (selectedWeight.value) {
        return (parseFloat(props.product.price) * (selectedWeight.value.weight_g / 1000)).toFixed(2);
    }
    return parseFloat(props.product.price).toFixed(2);
});

const totalPrice = computed(() => {
    return (calculatedPrice.value * selectedQuantity.value).toFixed(2);
});

const addToCart = (product, weight, flavour, quantity, price) => {
    router.post(route('cart.store', {
        product: product.id,
        weight: weight.id,
        flavour: flavour,
        quantity: quantity,
        price: price
    }), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmation: false,
                    title: page.props.flash.success,
                })
            }
        }
    });
}

watch([selectedWeight, selectedQuantity], () => {
    totalPrice.value = (calculatedPrice.value * selectedQuantity.value).toFixed(2);
});

const availableQuantities = computed(() => {
    if (selectedWeight.value && selectedFlavour.value) {
        const stock = props.stockProduct.find(stock => stock.weight_id === selectedWeight.value.id && stock.flavour_id === selectedFlavour.value);
        if (stock) {
            const maxQuantity = Math.min(stock.quantity, 10);
            return Array.from({ length: maxQuantity }, (_, i) => i + 1);
        }
    }
    return [];
});

const totalStock = computed(() => {
    if (selectedWeight.value && selectedFlavour.value) {
        const stock = props.stockProduct.find(stock => stock.weight_id === selectedWeight.value.id && stock.flavour_id === selectedFlavour.value);
        return stock ? stock.quantity : 0;
    }
    return 0;
});

</script>

<template>
    <UserLayout>
        <section class=" bg-gray-100 md:py-16 dark:bg-gray-900 antialiased">
            <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                    <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                        <img v-if="props.product.product_images.length > 0" class="w-full rounded dark:hidden"
                            :src="`/${props.product.product_images[0].image}`" alt="Imagen" />
                        <img v-else class="w-full hidden dark:block"
                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg"
                            alt="Imagen no encontrada" />
                        <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />
                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {{ props.product.description }}
                        </p>
                    </div>
                    <div class="mt-6 sm:mt-8 lg:mt-0">
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                            {{ props.product.name }}
                        </h1>
                        <p class="text-sm font-semibold text-gray-500 sm:text-lg dark:text-white">
                            {{ props.product.brand.name }}
                        </p>
                        <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                            <p v-if="props.discountedPrice == null"
                                class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white">
                                {{ calculatedPrice }} €
                            </p>
                            <span v-else class="inline-flex space-x-5 justify-center items-center">
                                <p class="text-2xl font-extrabold text-red-500 sm:text-3xl dark:text-white">
                                    {{ calculatedPrice }} €
                                </p>
                                <p class="text-2xl font-extrabold line-through text-gray-900 sm:text-xl dark:text-white">
                                    {{ originalPrice }} €
                                </p>
                            </span>
                        </div>
                        <div class="mt-10">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900">Peso</h4>
                            </div>
                            <RadioGroup v-model="selectedWeight" class="my-4">
                                <RadioGroupLabel class="sr-only">Elige un peso</RadioGroupLabel>
                                <div class="grid grid-cols-3 gap-4">
                                    <RadioGroupOption as="template" v-for="weight in weights" :key="weight.id"
                                        :value="weight"
                                        :disabled="!stockProduct.some(stock => stock.weight_id === weight.id)"
                                        v-slot="{ active, checked }">
                                        <div
                                            :class="[!stockProduct.some(stock => stock.weight_id === weight.id) ? 'cursor-not-allowed bg-gray-50 text-gray-200' : 'cursor-pointer bg-white text-gray-900 shadow-sm', active ? 'ring-2 ring-indigo-500' : '', 'group relative flex items-center justify-center rounded-md border py-3 text-sm font-medium hover:bg-gray-50 focus:outline-none sm:flex-1']">
                                            <RadioGroupLabel as="span">
                                                {{ weight.name }}
                                            </RadioGroupLabel>
                                            <span
                                                :class="[active ? 'border' : 'border-2', checked ? 'border-indigo-500' : 'border-transparent', 'pointer-events-none absolute -inset-px rounded-md']"
                                                aria-hidden="true" />
                                        </div>
                                    </RadioGroupOption>
                                </div>
                            </RadioGroup>
                        </div>
                        <div v-if="selectedWeight != null" class="inline-block w-full">
                            <div id="select_flavours">
                                <select v-model="selectedFlavour" name="select-flavour" id="select-flavour"
                                    class="my-5 rounded">
                                    <option v-for="flavour in flavours" :key="flavour.id" :value="flavour.id"
                                        v-bind:hidden="!stockProduct.some(stock => stock.flavour_id === flavour.id && stock.weight_id === selectedWeight?.id)">
                                        {{ flavour.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="py-4 my-1" v-if="selectedFlavour != null">
                                <select v-model="selectedQuantity" name="select-quantity" id="select-quantity"
                                    class="flex rounded justify-start ">
                                    <option v-for="quantity in availableQuantities" :key="quantity" :value="quantity">{{
                                        quantity }}</option>
                                </select>

                                <span v-if="totalStock < 30" class="text-red-500 mt-3">Quedan pocas unidades</span>
                            </div>
                        </div>
                        <div id="select_quantity"></div>
                        <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                            <button href="#" title=""
                                v-bind:disabled="!(selectedWeight != null && selectedFlavour != null && selectedQuantity != null)"
                                @click="addToCart(product, selectedWeight, selectedFlavour, selectedQuantity, totalPrice)"
                                class="w-full text-white text-xl mt-4 sm:mt-0 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 flex items-center justify-center"
                                :class="{ 'cursor-not-allowed': !(selectedWeight != null && selectedFlavour != null && selectedQuantity != null) }"
                                role="button">
                                <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                </svg>
                                Añadir al Carrito
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                </svg>
                                {{ totalPrice }} €
                            </button>
                        </div>
                    </div>
                </div>
                <PeopleAlsoBought></PeopleAlsoBought>
            </div>
            
        </section>
        
    </UserLayout>
</template>
