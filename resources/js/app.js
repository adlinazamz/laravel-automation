import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    
  if (document.getElementById("donut-chart-test")){
        const donutOption={
        chart: { type: 'donut', height: 300 },
        series: productTypeData,
        labels: productTypeLabels,
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'], // Tailwind colors
        legend: {
  position: 'bottom',
  labels: {
    colors: ['#374151', '#374151', '#374151'], // force dark gray for all
  }
},

    };
    new ApexCharts(document.getElementById("donut-chart-test"), donutOption).render();
  }

if (document.getElementById("line-chart")) {
  const clicksOptions = {
    chart: {
      type: "line",
      width: 450,
      fontFamily: "Inter, sans-serif",
      dropShadow: { enabled: false },
      toolbar: { show: false },
    },
    tooltip: {
      enabled: true,
      theme: "dark", // dark tooltip background
      x: { show: false },
      style: {
        fontSize: "14px",
        fontFamily: "Inter, sans-serif",
      },
      y: {
        formatter: function (val) {
          return val.toLocaleString(); // adds commas like 6,500
        },
      },
    },
    dataLabels: { enabled: false },
    stroke: { width: 6, curve: "smooth" },
    grid: {
      show: true,
      strokeDashArray: 4,
      padding: { left: 2, right: 2, top: -26 },
    },
    series: [
      {
        name: "Updated",
        data: productUpdated,
        color: "#1A56DB",
      },
      {
        name: "Created",
        data: productCreated,
        color: "#7E3AF2",
      },
    ],
    legend: {
      show: true,
      labels: { colors: "#fff" }, // white legend text
    },
    xaxis: {
      categories: productDates,
      labels: {
        show: true,
        style: {
          fontFamily: "Inter, sans-serif",
          cssClass: "text-xs font-normal fill-gray-400",
        },
      },
      axisBorder: { show: false },
      axisTicks: { show: false },
    },
    yaxis: { show: false },
  };

  new ApexCharts(document.getElementById("line-chart"), clicksOptions).render();
}



});
