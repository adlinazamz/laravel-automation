import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {

//product type donut
  if (document.getElementById("donut-chart")) {
    const donutOptions = {
        chart: {
            type: 'donut',
            height: 360,
            fontFamily: "Inter, sans-serif",
            events:{dataPointSeelection: function(event, chartContext, config){
              const selectedlabel=chartContext.w.globals.labels[config.dataPointIndex]; 
              const selectedValue = chartContext.w.globals.series[config.dataPointIndex];
              chartContext.updateoptions({
                plotOption:{
                  pie:{
                    donut:{
                      labels:{
                        total:{
                          label:selectedlabel,
                          formatter: function(){
                            return selectedValue + " items";
                          }
                        }
                      }
                    }
                  }
                }
              });
            }
          }
        },
        stroke:{
          colors: ["transparent"],
          linecap: "",  
        },
        plotOptions:{
          pie:{
            donut:{
              labels:{
                show:true,
                name:{
                  show:true,
                  fontFamily:"Inter, sans-serif",
                  offsetY:20,
                  color: "#ffff",
                }, 
                total:{
                  showAlways:true,
                  show:true,
                  label:"Products",
                  fontFamily: "Inter, sans-serif",
                  formatter: function(w){
                    const sum =w.globals.seriesTotals.reduce((a,b)=>{
                      return a+b
                    }, 0)
                    return sum
                  },
                },
                value:{
                  show:true,
                  fontfamily: "Inter, sans-serif",
                  offsetY :-20,
                  formatter: function(value){
                    return value +" items";
                  },
                },
              },
              size:"65%",
            },
          },
        },
        grid:{
          padding:{
            top:-2,
        },
      },

        series: productTypeData,
        labels: productTypeLabels,
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
        legend: { position: 'bottom' },
        tooltip: {
            enabled: true,
            custom: function({ series, seriesIndex, w }) {
                const val = series[seriesIndex];
                const label = w.config.labels[seriesIndex];
                const total = series.reduce((a, b) => a + b, 0);
                const percent = ((val / total) * 100).toFixed(1);
                const color = w.config.colors[seriesIndex];

                return `
                    <div style="background:#1f2937;padding:10px 14px;border-radius:8px;min-width:140px;">
                        <div style="display:flex;align-items:center;margin-bottom:6px;">
                            <span style="display:inline-block;width:12px;height:12px;background:${color};border-radius:50%;margin-right:8px;"></span>
                            <span style="font-weight:600;">${label}</span>
                        </div>
                        <div style="font-size:13px;">Total ${label}: <strong>${val}</strong></div>
                        <div style="font-size:13px;">Percentage: <strong>${percent}%</strong></div>
                    </div>
                `;
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { height: 250 },
                legend: { position: 'bottom' }
            }
        }]
    };

    window.donutChart = new ApexCharts(document.getElementById("donut-chart"), donutOptions);
    window.donutChart.render();

    const donutDownloadBtn = document.querySelector('[data-tooltip-target="data-tooltip"]');
    if (donutDownloadBtn) {
        donutDownloadBtn.addEventListener("click", async () => {
  try {
    const imgData = await window.donutChart.dataURI();
    const imgURI = imgData.imgURI;

    if (!imgURI || !imgURI.startsWith("data:image/png;base64,")) {
      throw new Error("Invalid image URI format");
    }

    const link = document.createElement("a");
    link.href = imgURI;
    link.download = "donut-chart.png";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (err) {
    alert("Failed to export donut chart: " + err.message);
  }
});
    }
}

  if (document.getElementById("line-chart")) {
    const lineOptions = {
        chart: {
            type: "line",
            height: 350,
            fontFamily: "Inter, sans-serif",
            toolbar: { show: false },
            zoom: { enabled: true },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
            },
        },
        stroke: {
            curve: "smooth",
            width: 3
        },
        series: [
            { name: "Updated", data: productUpdated, color: "#3b82f6" }, // blue
            { name: "Created", data: productCreated, color: "#10b981" }  // green
        ],
        xaxis: {
    show: false,
    categories: productDates,
    labels: {
        style: {
            fontFamily: "Inter, sans-serif",
            cssClass: "text-xs font-normal fill-gray-500 dark:fill-gray-400"
        }
    },
    axisBorder: { show: true },
    axisTicks: { show: true },
    tooltip: { enabled: true },
    crosshairs: {
        show: true,
        stroke: {
            color: '#94a3b8', // slate-400
            width: 1,
            dashArray: 4
        }
    }
},
        yaxis: {
            show: false,
            labels: {
                style: {
                    fontFamily: "Inter, sans-serif",
                    cssClass: "text-xs font-normal fill-gray-500 dark:fill-gray-400"
                }
            },
            tooltip: {
                enabled: true
            }
        },
        grid: {
            strokeDashArray: 4,
            padding: { left: 2, right: 2, top: -26 }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: {
            shared: true,
            intersect: false,
            theme: "dark",
            custom: function ({ series, dataPointIndex, w }) {
                if (dataPointIndex < 0) return "";

                const date = w.globals.categoryLabels[dataPointIndex];
                const rows = w.config.series.map((s, i) => {
                    const val = series[i][dataPointIndex] ?? 0;
                    const color = s.color || w.config.colors[i] || "#fff";
                    return `
                        <div style="display:flex;align-items:center;margin:4px 0;">
                            <span style="display:inline-block;width:10px;height:10px;background:${color};border-radius:50%;margin-right:6px;"></span>
                            <span style="font-weight:500;">${s.name}: <strong style="font-weight:700;">${val}</strong></span>
                        </div>
                    `;
                }).join("");

                return `
                    <div class="custom-values-tooltip" style="background:#1f2937;color:#fff;padding:8px 12px;border-radius:6px;">
                        <div style="margin-bottom:6px;font-size:12px;font-weight:600;">
                            ${date}
                        </div>
                        ${rows}
                    </div>
                `;
            }
        }
    };

    window.apexChart = new ApexCharts(document.getElementById("line-chart"), lineOptions);
    window.apexChart.render();

    // Line chart export to PDF
    
}
 
  });
