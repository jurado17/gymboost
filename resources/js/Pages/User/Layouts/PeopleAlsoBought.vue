<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import {
    Dialog,
    DialogPanel,
    RadioGroup,
    RadioGroupLabel,
    RadioGroupOption,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const products = ref([]);
const weights = ref([]);
const flavours = ref([]);

onMounted(() => {
    axios.get('/api/products')
        .then(response => {
            products.value = response.data;
            console.log(products);
        })
        .catch(error => {
            console.error("Error fetching products:", error);
        });

        axios.get('/api/weights')
        .then(response => {
            weights.value = response.data;
            console.log(weights);
        })
        .catch(error => {
            console.error("Error fetching weights:", error);
        });

        axios.get('/api/flavours')
        .then(response => {
            flavours.value = response.data;
            console.log(flavours);
        })
        .catch(error => {
            console.error("Error fetching flavours:", error);
        });
});




const open = ref(false)
const selectedProduct = ref(null)
const selectedWeight = ref(null)
const selectedFlavour = ref(null)
const selectedQuantity = ref(1)
const selectedStockProduct = ref([]);
const productPrice = ref(0)

const handleOpenDialog = (product) => {
    console.log(product);
    selectedProduct.value = product;
    selectedWeight.value = null;
    selectedFlavour.value = null;
    selectedQuantity.value = 1;
    selectedStockProduct.value = product.stock_products.filter(stock => stock.product_id === product.id);
    productPrice.value = calculatedPrice();
    open.value = true;
}

const originalPrice = () => {
    if (selectedWeight.value) {
        return (Number(selectedProduct.value.price) * (selectedWeight.value.weight_g / 1000)).toFixed(2);
    }

}
const calculatedPrice = () => {

    if (selectedProduct.value.promotion_id == null) {
        if (selectedWeight.value) {
            return (Number(selectedProduct.value.price) * (selectedWeight.value.weight_g / 1000)).toFixed(2);
        }
        return parseFloat(selectedProduct.value.price).toFixed(2);
    } else {
        let discountPrice = Number(selectedProduct.value.price) - (Number(selectedProduct.value.price) * selectedProduct.value.promotion.porcentual_discount);

        if (selectedWeight.value) {

            return (Number(discountPrice) * (selectedWeight.value.weight_g / 1000)).toFixed(2);
        }
        return discountPrice.toFixed(2);
    }
}

const totalPrice = computed(() => {
    return (productPrice.value * selectedQuantity.value).toFixed(2);
});

watch([selectedWeight, selectedQuantity], () => {
    productPrice.value = calculatedPrice();
});

const availableQuantities = computed(() => {
    if (selectedWeight.value && selectedFlavour.value) {
        const stock = selectedStockProduct.value.find(stock => stock.weight_id === selectedWeight.value.id && stock.flavour_id === selectedFlavour.value);
        if (stock) {
            const maxQuantity = Math.min(stock.quantity, 10);
            return Array.from({ length: maxQuantity }, (_, i) => i + 1);
        }
    }
    return [];
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
                    icon: 'success',
                    position: "top-end",
                    showConfirmation: false,
                    title: page.props.flash.success,
                })
                open.value = false
            }
        }
    });
    open.value = false
}
</script>

<template>
    <div class="hidden xl:mt-24 xl:block">
        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Otros usuarios tambien vieron</h3>
        <div  class="mt-6 grid grid-cols-3 gap-4 sm:mt-8">
            <div v-for="product in products"
                class="space-y-6 overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <Link :href="route('product.overview', [product.slug])">
                    <a href="#" class="overflow-hidden rounded">

                        <img class="mx-auto h-44 w-44 dark:block"
                        :src="`/storage/${product.product_images[0].image}`"
                            alt="product image" /> 
                    </a>
                    <div>
                        <a href="#"
                            class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">
                            {{ product.name }}
                        </a>
                        <p class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ product.category.name }}
                        </p>
                        <p class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ product.brand.name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            <span v-if="product.promotion_id !== null" class="mr-2 text-xl font-extrabold text-red-500">{{ (Number(product.price) - (Number(product.price) * product.promotion.porcentual_discount)).toFixed(2) }} €</span>
                                    <span v-if="product.promotion_id !== null" class="text-lg font-light line-through text-gray-500">{{ product.price }} €</span>
                                    <span v-else class="mr-2 text-xl font-extrabold">{{ product.price }} €</span>
                        </p>
                    
                    </div>
                </Link>  
                <div class="mt-6 flex items-center gap-2.5">
                    
                    
                    <button type="button" @click.prevent="handleOpenDialog(product)"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-cyan-900 px-5 py-2.5 text-sm font-medium  text-white hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-300 dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800">
                        <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7h-1M8 7h-.688M13 5v4m-2-2h4" />
                        </svg>
                        Add to cart
                    </button>
                </div>
            </div>

        </div>
    </div>

    <TransitionRoot as="template" :show="open">
            <Dialog class="relative z-20" @close="open = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 hidden bg-gray-500 bg-opacity-75 transition-opacity md:block" />
                </TransitionChild>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto ">
                    <div
                        class="flex min-h-full items-stretch justify-center text-center md:items-center md:px-2 lg:px-4 ">
                        <TransitionChild as="template" enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
                            enter-to="opacity-100 translate-y-0 md:scale-100" leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 md:scale-100"
                            leave-to="opacity-0 translate-y-4 md:translate-y-0 md:scale-95">
                            <DialogPanel
                                class="flex w-full transform text-left text-base transition md:my-8 md:max-w-2xl md:px-4 lg:max-w-4xl ">
                                <div
                                    class="relative flex w-full items-center overflow-hidden bg-white px-4 pb-8 pt-14 shadow-2xl sm:px-6 sm:pt-8 md:p-6 lg:p-8">
                                    <button type="button"
                                        class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 sm:right-6 sm:top-8 md:right-6 md:top-6 lg:right-8 lg:top-8"
                                        @click="open = false">
                                        <span class="sr-only">Close</span>
                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                    </button>

                                    <div v-if="selectedProduct"
                                        class="grid w-full grid-cols-1 items-start gap-x-6 gap-y-8 sm:grid-cols-12 lg:gap-x-8 ">
                                        <div
                                            class="aspect-h-3 aspect-w-2 overflow-hidden rounded-lg bg-gray-100 sm:col-span-4 lg:col-span-5">
                                            
                                            <img :src="`/storage/${selectedProduct.product_images[0].image}` || 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg'"
                                                :alt="selectedProduct.imageAlt" class="object-cover object-center" />
                                        </div>
                                        <div class="sm:col-span-8 lg:col-span-7">
                                            <h2 class="text-2xl font-bold text-gray-900 sm:pr-12">{{
                                                selectedProduct.name }}</h2>

                                            <h2 class="text-xl font-bold text-gray-600 sm:pr-12">{{
                                                selectedProduct.brand.name }}</h2>

                                            <section aria-labelledby="information-heading" class="mt-2">
                                                <h3 id="information-heading" class="sr-only">Product information</h3>
                                                <div v-if="selectedProduct">
                                                    <p v-if="selectedProduct.promotion_id === null"
                                                        class="text-2xl text-gray-900">
                                                        {{ calculatedPrice() }} €
                                                    </p>
                                                    <div v-else class="flex items-center">
                                                        <p class="text-2xl text-red-500 mr-2">{{ productPrice }} €</p>
                                                        <p class="text-lg text-gray-500 line-through">{{
                                                            originalPrice() }} €</p>
                                                    </div>
                                                </div>
                                            </section>

                                            <section aria-labelledby="options-heading" class="mt-10">
                                                <h3 id="options-heading" class="sr-only">Product options</h3>
                                                <form>
                                                    <div class="mt-10">
                                                        <div class="flex items-center justify-between">
                                                            <h4 class="text-sm font-medium text-gray-900">Peso</h4>
                                                        </div>

                                                        <RadioGroup v-model="selectedWeight" class="mt-4">
                                                            <RadioGroupLabel class="sr-only">Choose a size
                                                            </RadioGroupLabel>
                                                            <div class="grid grid-cols-3 gap-4">
                                                                <RadioGroupOption as="template"
                                                                    v-for="weight in weights" :key="weight.id"
                                                                    :disabled="!selectedStockProduct.some(stock => stock.weight_id === weight.id)"
                                                                    :value="weight" v-slot="{ active, checked }">
                                                                    <div
                                                                        :class="[!selectedStockProduct.some(stock => stock.weight_id === weight.id) ? 'cursor-not-allowed bg-gray-50 text-gray-200' : 'cursor-pointer bg-white text-gray-900 shadow-sm', active ? 'ring-2 ring-cyan-500' : '', 'group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium hover:bg-gray-50 focus:outline-none sm:flex-1']">
                                                                        <RadioGroupLabel as="span">{{ weight.weight_g
                                                                            }}
                                                                        </RadioGroupLabel>
                                                                        <span
                                                                            :class="[active ? 'border' : 'border-2', checked ? 'border-cyan-500' : 'border-transparent', 'pointer-events-none absolute -inset-px rounded-md']"
                                                                            aria-hidden="true" />

                                                                    </div>
                                                                </RadioGroupOption>
                                                            </div>
                                                        </RadioGroup>
                                                    </div>
                                                    <!-- Sabores -->
                                                    <div class="py-3" v-if="selectedWeight != null">
                                                        <h4 class="text-sm font-medium text-gray-900">Sabores</h4>
                                                        <select v-model="selectedFlavour" name="select-flavour"
                                                            id="select-flavour" class="rounded">
                                                            <option v-for="flavour in flavours" :key="flavour.id"
                                                                v-bind:hidden="!selectedStockProduct.some(stock => stock.flavour_id === flavour.id && stock.weight_id === selectedWeight.id && selectedProduct.id === stock.product_id )"
                                                                :value="flavour.id">{{ flavour.name }}</option>
                                                        </select>
                                                    </div>

                                                    <!-- Cantidad -->
                                                    <div class="py-3" v-if="selectedFlavour != null">
                                                        <h4 class="text-sm font-medium text-gray-900">Cantidad</h4>
                                                        <select v-model="selectedQuantity" name="select-quantity"
                                                            id="select-quantity" class="rounded">
                                                            <option v-for="quantity in availableQuantities"
                                                                :key="quantity" :value="quantity">{{ quantity }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <button type="submit"
                                                        @click.prevent="addToCart(selectedProduct, selectedWeight, selectedFlavour, selectedQuantity, totalPrice)"
                                                        v-bind:disabled="!(selectedWeight != null && selectedFlavour != null && selectedQuantity != null)"
                                                        :class="{ 'cursor-not-allowed': !(selectedWeight != null && selectedFlavour != null && selectedQuantity != null) }"
                                                        class="mt-6 flex w-full items-center justify-center rounded-md border border-transparent bg-cyan-600 px-8 py-3 text-base font-medium text-white hover:bg-cyan-full0 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                                                        Añadir al Carrito -> {{ totalPrice }}
                                                    </button>
                                                </form>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
</template>