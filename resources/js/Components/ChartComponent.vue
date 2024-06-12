<template>
    <canvas ref="chartCanvas"></canvas>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from "vue";
  import { Chart } from "chart.js/auto";
  
  const props = defineProps({
    chartData: Object,
    chartOptions: Object,
  });
  
  const chartCanvas = ref(null);
  let chartInstance = null;
  
  const renderChart = () => {
    if (chartInstance) {
      chartInstance.destroy();
    }
    chartInstance = new Chart(chartCanvas.value, {
      type: "bar",
      data: props.chartData,
      options: props.chartOptions,
    });
  };
  
  onMounted(() => {
    renderChart();
  });
  
  watch(() => props.chartData, () => {
    renderChart();
  });
  </script>
  
  <style scoped>
  /* Aquí puedes agregar estilos para el canvas si lo deseas */
  </style>
  