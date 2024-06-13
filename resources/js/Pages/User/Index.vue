<script setup nonce="eqNs+HnGaFV+voWddZzFbyjWBavWOXyTLot12uoaAeGDjmNp">
import { Link } from '@inertiajs/inertia-vue3';
import UserLayout from './Layouts/UserLayout.vue';
import { router } from '@inertiajs/vue3';
import Hero from './Layouts/Hero.vue';
import PrimaryBanner from './Components/PrimaryBanner.vue'
import CategoryCard from './Components/CategoryCard.vue'

import { computed, ref, watch } from 'vue'
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

const props = defineProps({
    products: Array,
    weights: Array,
    flavours: Array,
    stockProducts: Array,
})

const open = ref(false)
const selectedProduct = ref(null)
const selectedWeight = ref(null)
const selectedFlavour = ref(null)
const selectedQuantity = ref(1)
const selectedStockProduct = ref([]);
const productPrice = ref(0)

const handleOpenDialog = (product) => {
    selectedProduct.value = product;
    selectedWeight.value = null;
    selectedFlavour.value = null;
    selectedQuantity.value = 1;
    selectedStockProduct.value = props.stockProducts.filter(stock => stock.product_id === product.id);
    productPrice.value = calculatedPrice();
    open.value = true;
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
}
</script>

<template>
    <UserLayout>


        <PrimaryBanner></PrimaryBanner>

        <CategoryCard></CategoryCard>

    
        <div class="bg-white">
            <section class="bg-white dark:bg-gray-900">
                <div class=" px-1 mx-auto max-w-screen-xl">
                    <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                        <p class="my-24 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                            Productos Recomendados</p>
                        
                    </div>

                    <div class="lg:grid  lg:grid-cols-4 sm:gap-6 xl:gap-10 lg:space-y-0">
                        <!-- Pricing Card -->
                        <div v-for="product in products" :key="product.id"
                            class="flex flex-col items-center mx-auto max-w-lg text-center pb-12 text-gray-900 dark:text-white">
                            <Link :href="route('product.overview', [product.slug])">
                            <div
                                class="aspect-h-1 aspect-w-1 w-full overflow-hidden lg:aspect-none group-hover:opacity-75 lg:h-72">
                                <img v-if="product.product_images.length > 0"
                                :src="`/storage/${product.product_images[0].image}`"  :alt="product.imageAlt"
                                    class=" object-cover object-center h-60 w-auto" />

                                <img v-else
                                    src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg"
                                    :alt="product.imageAlt" class="h-full w-full object-cover object-center" />
                            </div>
                            <h3 class="mb-4 text-lg font-semibold">{{ product.name }}</h3>
                            <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">{{ product.brand.name }}
                            </p>
                            <div class="flex justify-center items-baseline my-4">
                                <span v-if="product.promotion_id !== null" class="mr-2 text-xl font-extrabold text-red-500">{{ (Number(product.price) - (Number(product.price) * product.promotion.porcentual_discount)).toFixed(2) }} €</span>
                                <span v-if="product.promotion_id !== null" class="text-lg font-light line-through text-gray-500">{{ product.price }} €</span>
                                <span v-else class="mr-2 text-xl font-extrabold">{{ product.price }} €</span>
                            </div>
                            </Link>
                            <a id="open-dialog" @click.prevent="handleOpenDialog(product)"
                                class="flex justify-center text-white bg-cyan-900 hover:bg-cyan-full0 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-md px-5 py-2.5 text-center dark:text-white dark:focus:ring-cyan-900 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                Add to Cart
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-center items-center text-center pb-10">
                        <Link :href="route('products.index')" class="p-2 border-2 hover:bg-cyan-900 hover:text-white transition">Ver todos</Link>
                    </div>
                </div>
            </section>
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
                                            <img :src="selectedProduct.product_images[0]?.image || 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg'"
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
                                                            calculatedPrice() }} €</p>
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
                                                                            }}g
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
                                                                v-bind:hidden="!stockProducts.some(stock => stock.flavour_id === flavour.id && stock.weight_id === selectedWeight?.id)"
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


        <!--START HERO SECTION-->
        <Hero></Hero>
        <!--END HERO SECTION-->

    </UserLayout>
</template>
