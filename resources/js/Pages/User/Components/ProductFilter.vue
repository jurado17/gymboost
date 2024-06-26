<script setup>
import { ref, watch } from 'vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, MinusIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
import { router, useForm } from '@inertiajs/vue3'

const sortOptions = [
    { name: 'Nuevo', href: '#', current: false },
    { name: 'Precio: Mas Bajo Primero', href: '#', current: false },
    { name: 'Precio: Mas Alto Primero', href: '#', current: false },
]

const props = defineProps({
    categories: Array,
    brands: Array
});

const mobileFiltersOpen = ref(false)

const priceFilter = useForm({
    prices: [0, 1000]
})

const selectedBrands = ref([]);
const selectedCategories = ref([]);

let debounceTimeout = null;

watch([selectedBrands, selectedCategories], () => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        updateFilterProducts();
    }, 300);
});

function filterPrices(event) {
    event.preventDefault();

    if (priceFilter.prices[0] > priceFilter.prices[1]) {
        alert('El precio mínimo no puede ser mayor que el precio máximo');
        return;
    }

    priceFilter.transform((data) => {
        return {
            ...data,
            prices: {
                from: data.prices[0],
                to: data.prices[1]
            }
        }
    }).get('products', {
        preserveState: true,
        preserveScroll: true, // To preserve the scroll position
        replace: true
    }).catch(error => {
        console.error('Error fetching products:', error);
    });
}

function updateFilterProducts(event) {
    if (event) {
        event.preventDefault();
    }
    router.get('products', {
        brands: selectedBrands.value,
        categories: selectedCategories.value
    }, {
        preserveState: true,
        preserveScroll: true, // To preserve the scroll position
        replace: true
    })
}


function clearFilters() {
    selectedBrands.value = [];
    selectedCategories.value = [];
    priceFilter.prices = [0, 1000];
    updateFilterProducts();
}
</script>



<template>
    <div class="bg-white pt-20">
        <div>
            <!-- Mobile filter dialog -->
            <TransitionRoot as="template" :show="mobileFiltersOpen">
                <Dialog as="div" class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
                    <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                        enter-from="opacity-0" enter-to="opacity-100"
                        leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 z-40 flex">
                        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                            enter-from="translate-x-full" enter-to="translate-x-0"
                            leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                            leave-to="translate-x-full">
                            <DialogPanel
                                class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                                <div class="flex items-center justify-between px-4">
                                    <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                    <button type="button"
                                        class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                        @click="mobileFiltersOpen = false">
                                        <span class="sr-only">Close menu</span>
                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                    </button>
                                </div>

                                <!-- Filters -->
                                <form class="mt-4 border-t border-gray-200">
                                    <h3 class="sr-only">Categories</h3>
                                    <ul role="list" class="px-2 py-3 font-medium text-gray-900">
                                        <li v-for="category in categories" :key="category.id">
                                            <div class="flex items-center">
                                                <input :id="`filter-mobile-category-${category.id}`" :value="category.id" type="checkbox" v-model="selectedCategories" @change="updateFilterProducts"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label :for="`filter-mobile-category-${category.id}`"
                                                    class="ml-3 min-w-0 flex-1 text-gray-500">{{ category.name }}</label>
                                            </div>
                                        </li>
                                    </ul>

                                    <Disclosure as="div" class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                        <h3 class="-mx-2 -my-3 flow-root">
                                            <DisclosureButton
                                                class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                                <span class="font-medium text-gray-900">Brands</span>
                                                <span class="ml-6 flex items-center">
                                                    <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </DisclosureButton>
                                        </h3>
                                        <DisclosurePanel class="pt-6">
                                            <div class="space-y-6">
                                                <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                                    <input :id="`filter-mobile-brand-${brand.id}`" :value="brand.id" type="checkbox" v-model="selectedBrands"
                                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @change="updateFilterProducts" />
                                                    <label :for="`filter-mobile-brand-${brand.id}`" class="ml-3 min-w-0 flex-1 text-gray-500">{{
                                                            brand.name }}</label>
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                    </Disclosure>

                                    <Disclosure as="div" class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                        <h3 class="-mx-2 -my-3 flow-root">
                                            <DisclosureButton
                                                class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                                <span class="font-medium text-gray-900">Price</span>
                                                <span class="ml-6 flex items-center">
                                                    <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </DisclosureButton>
                                        </h3>
                                        <DisclosurePanel class="pt-6">
                                            <div class="space-y-6">
                                                <div class="flex items-center justify-between space-x-3">
                                                    <div class="basis-1/3">
                                                        <label for="filter-mobile-price-from"
                                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                            MIN
                                                        </label>
                                                        <input type="number" v-model="priceFilter.prices[0]" id="filter-mobile-price-from"
                                                            placeholder="Min Price"
                                                            class="block w-full rounded-lg border border-gray-300 bg-gray-90 p-2.5 text-sm text-gray-900">
                                                    </div>
                                                    <div class="basis-1/3">
                                                        <label for="filter-mobile-price-to"
                                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                                            MAX
                                                        </label>
                                                        <input type="number" id="filter-mobile-price-to" v-model="priceFilter.prices[1]"
                                                            placeholder="Max Price"
                                                            class="block w-full rounded-lg border border-gray-300 bg-gray-90 p-2.5 text-sm text-gray-900">
                                                    </div>
                                                    <SecondaryButton @click="filterPrices" class="self-end">OK</SecondaryButton>
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                    </Disclosure>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </TransitionRoot>

            <main class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-1">
                <div class="flex items-baseline justify-between border-b border-gray-200 py-6">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Listado de Productos</h1>



                    <div class="flex items-center">
                        <Menu as="div" class="relative inline-block text-left">
                            

                            <transition enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1">
                                        <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                                        <a :href="option.href"
                                            :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">{{
                                                option.name }}</a>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>


                        <button type="button" class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden"
                            @click="mobileFiltersOpen = true">
                            <span class="sr-only">Filters</span>
                            <FunnelIcon class="h-5 w-5" aria-hidden="true" />
                        </button>
                    </div>
                </div>

                <section aria-labelledby="products-heading" class="pb-24 pt-6">
                    <h2 id="products-heading" class="sr-only">Products</h2>

                    <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                        <!-- Filters -->
                        <form class="hidden lg:block">
                            <h3>Precios</h3>
                            <!--Price Filter-->
                            <div class="flex items-center justify-between space-x-3 mb-3">
                                <div class="basis-1/3">
                                    <label for="filter-price-from"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        MIN
                                    </label>
                                    <input type="number" v-model="priceFilter.prices[0]" id="filter-price-from"
                                        placeholder="Min Price"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-90 p-2.5 text-sm text-gray-900">
                                </div>
                                <div class="basis-1/3">
                                    <label for="filter-price-to"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                        MAX
                                    </label>
                                    <input type="number" id="filter-price-to" v-model="priceFilter.prices[1]"
                                        placeholder="Max Price"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-90 p-2.5 text-sm text-gray-900">
                                </div>
                                <SecondaryButton @click="filterPrices()" class="self-end">OK</SecondaryButton>
                            </div>

                            <Disclosure as="div" 
                                class="border-y border-gray-200 py-6" v-slot="{ open }">
                                <h3 class="-my-3 flow-root">
                                    <DisclosureButton
                                        class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                        <span class="font-medium text-gray-900">Marcas</span>
                                        <span class="ml-6 flex items-center">
                                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </h3>
                                <DisclosurePanel class="pt-6">
                                    <div class="space-y-4">
                                        <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                            <input :id="`filter-${brand.id}`" :value="brand.id" type="checkbox" v-model="selectedBrands" @change="updateFilterProducts"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                            <label :for="`filter-${brand.id}`" class="ml-3 text-sm text-gray-600">{{
                                                brand.name }}</label>
                                        </div>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>

                            <Disclosure as="div" 
                                class="border-y border-gray-200 py-6" v-slot="{ open }">
                                <h3 class="-my-3 flow-root">
                                    <DisclosureButton
                                        class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                        <span class="font-medium text-gray-900">Categorias</span>
                                        <span class="ml-6 flex items-center">
                                            <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                            <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </DisclosureButton>
                                </h3>
                                <DisclosurePanel class="pt-6">
                                    <div class="space-y-4">
                                        <div v-for="category in categories" :key="category.id" class="flex items-center">  
                                            <input :id="`filter-${category.id}`" :value="category.id" type="checkbox" v-model="selectedCategories" @change="updateFilterProducts"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                            <label :for="`filter-${category.id}`" class="ml-3 text-sm text-gray-600">{{
                                                category.name }}</label>
                                        </div>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                            

                                <button type="button" @click="clearFilters" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear Filters
                            </button>
                        </form>

                        <!-- Product grid -->
                        <slot></slot>
                    </div>
                </section>
            </main>
        </div>
    </div>
</template>
