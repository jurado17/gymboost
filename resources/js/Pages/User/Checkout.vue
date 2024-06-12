<script setup>
import { reactive, ref, onMounted } from 'vue';
import CheckoutProgress from '@/Pages/User/Components/CheckOutProgress.vue';
import NavCheckout from '@/Pages/User/Components/NavCheckout.vue';
import { router } from '@inertiajs/vue3'; // Importa el router de Inertia.js
// Define props
const props = defineProps({
    total: {
        type: String,
        required: true,
    },
    userAddress: {
        type: Array,
        required: true,
    },
    cartItems: {
        type: Array,
        required: true,
    },
});

const form = reactive({
    country_code: '',
    city: '',
    province: '',
    address1: '',
    zip_code: '',
});

const selectedAddress = ref(null);
const showNewAddressForm = ref(false);

function toggleNewAddressForm() {
    showNewAddressForm.value = !showNewAddressForm.value;
}

const paypalLoaded = ref(false);
const paymentError = ref(null);

const loadPaypalScript = () => {
    if (window.paypal) {
        console.log("PayPal SDK already loaded.");
        createPaypalButton();
        return;
    }

    const script = document.createElement("script");
    script.src = `https://www.paypal.com/sdk/js?client-id=AV-Ixvvj8ZAyLBaJSsgsUmpuZ0txG8LXtpxirvvUMjGGeqqXa8efhTBSgs9IhsOwAJayeld13n76mdAU&currency=EUR`;
    script.onload = () => {
        console.log("PayPal SDK loaded successfully.");
        createPaypalButton();
    };
    document.body.appendChild(script);
};

const createPaypalButton = () => {
    const total = parseFloat(props.total).toFixed(2);
    window.paypal.Buttons({
        style: {
            color: 'blue',
            label: 'pay',
        },
        createOrder: (data, actions) => {
            return actions.order.create({
                purchase_units: [{
                    amount: { value: total }
                }]
            });
        },
        onApprove: async (data, actions) => {
            const order = await actions.order.capture();
            console.log(order);
            handlePostPayment();

        },
        onError: (err) => {
            console.error('Error en el pago con PayPal:', err);
            paymentError.value = 'Error en el pago con PayPal. Por favor, inténtalo de nuevo.';
        }
    }).render('#paypal-button-container');
};


const handlePostPayment = async (orderID) => {
    const data = {
        cartItems: props.cartItems,
        products: props.cartItems.map(item => item.product),
        total: props.total,
        address: selectedAddress.value ? selectedAddress.value : form,
    };
    console.log('Submitting data:', data);

    router.post(route('checkout.success'), data, {
        onSuccess: () => {
            console.log('Pago procesado correctamente.');
            
        },
        onError: (errors) => {
            console.error('Error al procesar el pago:', errors);
            paymentError.value = 'Error al procesar el pago. Por favor, inténtalo de nuevo.';
        }
    });
};

onMounted(() => {
    loadPaypalScript();
});
</script>

<template>
    <NavCheckout />

    <section class="py-8 antialiased dark:bg-gray-900 md:py-16">
        <form class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <CheckoutProgress />

            <div class="p-8 mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
                <div class="min-w-0 flex-1 space-y-8">
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white border-b-2">Dirección de Envío</h2>

                        <div v-for="address in userAddress" :key="address.id" class="grid grid-cols-1 p-7 rounded-xl bg-gray-100">
                            <div>
                                <input type="radio" :value="address" v-model="selectedAddress" />
                            </div>
                            <div>
                                <p>{{ address.user.name }} - {{ address.user.tel }}</p>
                                <p>{{ address.address1 }} - {{ address.city }} - {{ address.province }} - {{
                                    address.zip_code }} - {{ address.country_code }}</p>
                            </div>
                        </div>

                        <button type="button" @click="toggleNewAddressForm" class="mt-4 text-blue-500">Añadir nueva dirección</button>

                        <div v-if="showNewAddressForm" class="grid grid-cols-1 gap-4 sm:grid-cols-2 p-8 rounded-xl bg-white space-y-4 mt-4">
                            <div>
                                <div class="mb-2 flex items-center gap-2">
                                    <label for="select-country-input-3" class="block text-sm font-medium text-gray-900 dark:text-white">Pais*</label>
                                </div>
                                <select id="select-country-input-3" v-model="form.country_code" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                                    <option disabled selected>Escoge un País</option>
                                    <option value="US">Estados Unidos</option>
                                    <option value="AS">Australia</option>
                                    <option value="FR">Francia</option>
                                    <option value="ES">España</option>
                                    <option value="UK">Inglaterra</option>
                                </select>
                            </div>

                            <div>
                                <label for="city" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Población *</label>
                                <input type="text" id="city" v-model="form.city" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" />
                            </div>
                            

                            <div>
                                <label for="zip_code" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Codigo Postal *</label>
                                <input type="text" id="zip_code" v-model="form.zip_code" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required />
                            </div>

                            <div>
                                <label for="province" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Provincia *</label>
                                <input type="text" id="province" v-model="form.province" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required />
                            </div>

                            <div class="sm:col-span-2">
                                <div class="mb-4">
                                    <label for="address1" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Dirección*</label>
                                    <input type="text" id="address1" v-model="form.address1" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                    <div class="flow-root">
                        <div class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                            <dl class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Subtotal</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ Number(props.total) }}€</dd>
                            </dl>

                            <dl class=" items-center justify-between gap-4 py-3">
                                <dt v-for="item in cartItems" :key="item.id" class="flex w-full items-center justify-between">
                                    <span class="text-base font-normal text-gray-500 dark:text-gray-400">{{ item.product.name }}</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">x{{ item.quantity }}</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">{{ (item.final_price / item.quantity).toFixed(2) }}€</span>
                                </dt>
                            </dl>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div id="paypal-button-container"></div>
                        <div v-if="paymentError" class="text-red-500">{{ paymentError }}</div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</template>

<style scoped>
/* Agrega cualquier estilo personalizado aquí */
</style>
