import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
Alpine.start();

// ----- Mock Line Chart -----
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById("line-chart-products")){
        const lineOptions={
        chart: { type: 'line', height: 300, toolbar: { show: false } },
        series: [{
            name: 'Products Created',
            data: [5, 12, 8, 15, 10, 20, 25]
        }],
        xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'] },
        stroke: { curve: 'smooth' },
        colors: ['#3b82f6'], // Tailwind blue-500
    };
    new ApexCharts(document.getElementById("line-chart-products"), lineOptions).render();
  }

    // ----- Mock Donut Chart -----
    if (document.getElementById("donut-chart")){
        const donutOption={
        chart: { type: 'donut', height: 300 },
        series: [25, 15, 20, 10, 30], // mock percentages
        labels: ['A', 'B', 'C', 'D', 'E'],
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'], // Tailwind colors
        legend: { position: 'bottom' },
    };
    new ApexCharts(document.getElementById("donut-chart"), donutOption).render();
  }

//click/cpc chart
if (document.getElementById("line-chart-clicks")) {
    const clicksOptions = {
      chart: { type: 'line', height: 300, toolbar: { show: false } },
      series: [
        { name: "Clicks", data: [6500, 6418, 6456, 6526, 6356, 6456] },
        { name: "CPC", data: [6456, 6356, 6526, 6332, 6418, 6500] },
      ],
      xaxis: { categories: ['01 Feb','02 Feb','03 Feb','04 Feb','05 Feb','06 Feb'] },
      stroke: { width: 6, curve: 'smooth' },
      colors: ['#1A56DB','#7E3AF2'],
      legend: { show: false }
    };
    new ApexCharts(document.getElementById("line-chart-clicks"), clicksOptions).render();
  }

});
