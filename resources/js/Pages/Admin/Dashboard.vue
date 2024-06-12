<script setup>
import { onMounted, ref, watch } from "vue";
import { initFlowbite } from "flowbite";
import AdminLayout from './Components/AdminLayout.vue';
import ChartComponent from '@/Components/ChartComponent.vue';

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});

const props = defineProps({
    users: Array,
    userAddress: Array,
    brands: Array,
    categories: Array,
    products: Array,
    orders: Array,
    orderItems: Array,
    payment: Array,
    countUsers: Number,
    countOrders: Number,
    paymentIncome: Number,
    countSellProducts: Number
});

const chartData = ref({
  labels: [],
  datasets: [
    {
      label: 'Sales per Month',
      data: [],
      fill: false,
      borderColor: 'rgb(75, 192, 192)',
      tension: 0.1
    }
  ]
});

const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
    title: {
      display: true,
      text: 'Sales per Month'
    }
  }
};

const selectedProductId = ref(props.products[0]?.id || '');

const processOrderData = (productId) => {
  const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  const salesCounts = Array(12).fill(0);

  props.orderItems.forEach(orderItem => {
    if (orderItem.product_id === productId) {
      const orderDate = new Date(orderItem.created_at);
      const month = orderDate.getMonth();
      salesCounts[month] += orderItem.quantity;
    }
  });

  chartData.value.labels = months;
  chartData.value.datasets[0].data = salesCounts;
};

onMounted(() => {
  processOrderData(selectedProductId.value);
});

watch(selectedProductId, (newProductId) => {
  processOrderData(newProductId);
});
</script>

<template>
    <AdminLayout>
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-48">
                    <div class="flex items-center justify-center h-full ">
                        <div class="text-center">
                            <div class="text-2xl font-semibold justify-center flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-auto h-14 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>

                            </div>
                            <div class="text-2xl ">{{ countUsers }}</div>
                            <div class="text-xl font-semibold">Total Usuarios</div>
                        </div>
                    </div>
                </div>

                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-48">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="text-2xl font-semibold justify-center flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-auto h-14 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>


                            </div>
                            <div class="text-2xl ">{{ countOrders }}</div>
                            <div class="text-xl font-semibold">Pedidos Enviados</div>
                        </div>
                    </div>
                </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-48">
                    <div class="flex items-center justify-center h-full ">
                        <div class="text-center">
                            <div class="text-2xl font-semibold justify-center flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-auto h-14 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.25 7.756a4.5 4.5 0 1 0 0 8.488M7.5 10.5h5.25m-5.25 3h5.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>


                            </div>
                            <div class="text-2xl ">{{ paymentIncome }}</div>
                            <div class="text-xl font-semibold">Ingresos Generados</div>
                        </div>
                    </div>
                </div>

                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-48">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="text-2xl font-semibold justify-center flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-auto h-14 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3.75v16.5M2.25 12h19.5M6.375 17.25a4.875 4.875 0 0 0 4.875-4.875V12m6.375 5.25a4.875 4.875 0 0 1-4.875-4.875V12m-9 8.25h16.5a1.5 1.5 0 0 0 1.5-1.5V5.25a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v13.5a1.5 1.5 0 0 0 1.5 1.5Zm12.621-9.44c-1.409 1.41-4.242 1.061-4.242 1.061s-.349-2.833 1.06-4.242a2.25 2.25 0 0 1 3.182 3.182ZM10.773 7.63c1.409 1.409 1.06 4.242 1.06 4.242S9 12.22 7.592 10.811a2.25 2.25 0 1 1 3.182-3.182Z" />
                                </svg>
                            </div>
                            <div class="text-2xl ">{{ countSellProducts }}</div>
                            <div class="text-xl font-semibold">Productos Vendidos</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center w-full border-2 rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4">
                <ChartComponent :chartData="chartData" :chartOptions="chartOptions" />
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                    <select v-model="selectedProductId" class="p-2 border rounded">
                        <option v-for="product in props.products" :key="product.id" :value="product.id">
                            {{ product.name }}
                        </option>
                    </select>
                    <ChartComponent :chartData="chartData" :chartOptions="chartOptions" />
                </div>
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
            </div>
            <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4"></div>
            <div class="grid grid-cols-2 gap-4">
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
                <div class="border-2 rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72"></div>
            </div>
        </div>
    </AdminLayout>
</template>
