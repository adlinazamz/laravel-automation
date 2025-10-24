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
            events: {
              // ApexCharts event name is dataPointSelection (fix typo)
              dataPointSelection: function(event, chartContext, config){
                try {
                  const selectedLabel = chartContext.w.globals.labels[config.dataPointIndex];
                  const selectedValue = chartContext.w.globals.series[config.dataPointIndex];
                  chartContext.updateOptions({
                    plotOptions: {
                      pie: {
                        donut: {
                          labels: {
                            total: {
                              label: selectedLabel,
                              formatter: function(){
                                return selectedValue + " items";
                              }
                            }
                          }
                        }
                      }
                    }
                  });
                } catch (err) {
                  // defensive: ignore update failures
                }
              }
            }
        },
        stroke:{
          colors: ["transparent"]
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
                  color: "#e5e7eb",
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
                  color: "#e5e7eb",
                  fontFamily: "Inter, sans-serif",
                  offsetY: -20,
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
  colors: ["#1C64F2", "#16BDCA", "#FDBA8C", "#E74694", '#d8b4fe'],
  legend: { position: 'bottom', labels: { colors: ['#e5e7eb'], useSeriesColors: false } },
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

    // Guard: don't recreate the chart if already present (hot-reload/turbo-safe)
    if (!window.donutChart) {
      window.donutChart = new ApexCharts(document.getElementById("donut-chart"), donutOptions);
      window.donutChart.render();
    }

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

// Export full report button - make sure it sends the chart image and selected range
const exportBtn = document.getElementById('exportReportButton');
if (exportBtn) {
  exportBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    exportBtn.textContent = 'Generating...';
    exportBtn.classList.add('opacity-50');
    exportBtn.disabled = true;
    try {
      // Wait for chart to be available
      if (!window.apexChart) throw new Error('Line chart not ready');
      const data = await window.apexChart.dataURI({ type: 'png', quality: 1 });
      const form = new FormData();
      form.append('range', exportBtn.dataset.range || '7');
      form.append('chart', data.imgURI);
      const token = document.querySelector('meta[name="csrf-token"]').content;
  const response = await fetch('/reports/export/full', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        body: form
      });
      if (!response.ok) throw new Error(await response.text());
      const blob = await response.blob();
      const filename = `full-report-${exportBtn.dataset.range || '7'}-${new Date().toISOString().slice(0,10)}.pdf`;
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = filename; document.body.appendChild(a);
      a.click(); a.remove(); URL.revokeObjectURL(url);
    } catch (err) {
      alert('Export failed: ' + err.message);
    } finally {
      exportBtn.textContent = 'View Full Report';
      exportBtn.classList.remove('opacity-50');
      exportBtn.disabled = false;
    }
  });
}

  if (document.getElementById("line-chart")) {
    const lineOptions = {
      chart: {
        type: 'line',
        height: 350,
        fontFamily: 'Inter, sans-serif',
        toolbar: { show: false },
        zoom: { enabled: true },
        animations: { enabled: true, easing: 'easeinout', speed: 800 }
      },
      stroke: { curve: 'smooth', width: 6 },
      series: [
        { name: 'Updated', data: Array.isArray(productUpdated) ? productUpdated : [], color: '#1A56DB' },
        { name: 'Created', data: Array.isArray(productCreated) ? productCreated : [], color: '#7E3AF2' }
      ],
      xaxis: {
        // Use formatted labels for display but keep raw ISO dates available for tooltip mapping
        categories: (Array.isArray(productDates) && productDates.length) ? productDates : (Array.isArray(productDatesRaw) ? productDatesRaw : []),
        categoriesRaw: Array.isArray(productDatesRaw) ? productDatesRaw : [],
        labels: { style: { fontFamily: 'Inter, sans-serif', cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400' }, formatter: function(val) {
            // try to show short date like '23 Oct' if ISO date provided
            try {
              var d = new Date(val);
              if (!isNaN(d)) {
                return d.toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
              }
            } catch (err) {}
            return val;
          }
        },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: { show: false },
      grid: { strokeDashArray: 4, padding: { left: 2, right: 2, top: -26 } },
      dataLabels: { enabled: false },
      legend: { show: false },
      tooltip: { shared: true, intersect: false, theme: 'dark', x: { show: true } ,
        custom: function ({ series, dataPointIndex, w }) {
          if (typeof dataPointIndex !== 'number' || dataPointIndex < 0) return '';
          // First try Apex runtime categoryLabels, then prefer our productDatesRaw fallback, finally the displayed categories
          var dateRaw = '';
          if (w && w.globals && Array.isArray(w.globals.categoryLabels) && w.globals.categoryLabels[dataPointIndex]) {
            dateRaw = w.globals.categoryLabels[dataPointIndex];
          } else if (typeof productDatesRaw !== 'undefined' && Array.isArray(productDatesRaw) && productDatesRaw[dataPointIndex]) {
            dateRaw = productDatesRaw[dataPointIndex];
          } else if (w && w.config && w.config.xaxis && Array.isArray(w.config.xaxis.categories) && w.config.xaxis.categories[dataPointIndex]) {
            dateRaw = w.config.xaxis.categories[dataPointIndex];
          }
          var dateLabel = dateRaw;
          try {
            var d = new Date(dateRaw);
            if (!isNaN(d)) dateLabel = d.toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
          } catch (err) {}

          const rows = (w && w.config && Array.isArray(w.config.series) ? w.config.series : []).map((s, i) => {
            const val = (series && Array.isArray(series[i]) && series[i][dataPointIndex] !== undefined) ? series[i][dataPointIndex] : 0;
            const color = (s && (s.color || (w.config && w.config.colors && w.config.colors[i]))) || '#fff';
            return `<div style="display:flex;align-items:center;margin:4px 0;"><span style="display:inline-block;width:10px;height:10px;background:${color};border-radius:50%;margin-right:6px;"></span><span style="font-weight:500;">${s.name}: <strong style="font-weight:700;">${val}</strong></span></div>`;
          }).join('');
          return `<div class="custom-values-tooltip" style="background:#1f2937;color:#fff;padding:8px 12px;border-radius:6px;"><div style="margin-bottom:6px;font-size:12px;font-weight:600;">${dateLabel}</div>${rows}</div>`;
        }
      }
    };

    window.apexChart = new ApexCharts(document.getElementById('line-chart'), lineOptions);
    window.apexChart.render();
  }
 
  });

  // Image modal handler
  document.querySelectorAll('.js-image-modal').forEach(function(img) {
    img.addEventListener('click', function () {
      var src = img.getAttribute('data-src') || img.src;
      var modal = document.getElementById('image-modal');
      var modalImg = document.getElementById('image-modal-img');
      modalImg.src = src;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    });
  });

  var imageModalClose = document.getElementById('image-modal-close');
  if (imageModalClose) {
    imageModalClose.addEventListener('click', function () {
      var modal = document.getElementById('image-modal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
      var modalImg = document.getElementById('image-modal-img');
      modalImg.src = '';
    });
  }

  // Close on ESC or click outside
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      var modal = document.getElementById('image-modal');
      if (modal && modal.classList.contains('flex')) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.getElementById('image-modal-img').src = '';
      }
    }
  });

  var imageModal = document.getElementById('image-modal');
  if (imageModal) {
    imageModal.addEventListener('click', function (e) {
      if (e.target === imageModal) {
        imageModal.classList.remove('flex');
        imageModal.classList.add('hidden');
        document.getElementById('image-modal-img').src = '';
      }
    });
  }

  // Datepicker toggle and normalization
  // Clicking the calendar icon should focus the input and open the datepicker (if available).
  // Support both legacy and new toggle classes so virtual/templated markup works
  document.querySelectorAll('.js-datepicker-toggle, .datepicker-toggle').forEach(function(btn) {
    btn.addEventListener('click', function (e) {
      var targetSelector = btn.getAttribute('data-target');
      if (!targetSelector) return;
      var input = document.querySelector(targetSelector);
      if (!input) return;
      input.focus();

      // If bootstrap-datepicker is attached, show it programmatically
      try {
        if (typeof jQuery !== 'undefined' && typeof jQuery(input).datepicker === 'function') {
          jQuery(input).datepicker('show');
          return;
        }
      } catch (err) {
        // ignore
      }
    });
  });

  // Normalize date input to DD-MM-YYYY on blur/change. Accept common formats.
  // Central normalize function that converts many common inputs into ISO (YYYY-MM-DD).
  function normalizeDateInput(input) {
    if (!input) return;
    var val = (input.value || '').trim();
    if (!val) return;

    var iso = null;
    try {
      // If flatpickr instance exists, try parsing with a few common format tokens
      if (input._flatpickr && typeof flatpickr !== 'undefined') {
        var tryFormats = ['Y-m-d','d-m-Y','d/m/Y','m-d-Y','m/d/Y','d.m.Y'];
        for (var i = 0; i < tryFormats.length; i++) {
          try {
            var dt = flatpickr.parseDate(val, tryFormats[i]);
            if (dt instanceof Date && !isNaN(dt)) {
              var yyyy = dt.getFullYear();
              var mm = String(dt.getMonth() + 1).padStart(2, '0');
              var dd = String(dt.getDate()).padStart(2, '0');
              iso = yyyy + '-' + mm + '-' + dd;
              break;
            }
          } catch (e) { /* ignore parse errors */ }
        }
      }

      // Try moment.js if available (many projects include it)
      if (!iso && typeof moment !== 'undefined') {
        var m = moment(val, ['DD-MM-YYYY','D-M-YYYY','YYYY-MM-DD','DD/MM/YYYY','D/M/YYYY','MM-DD-YYYY','MM/DD/YYYY'], true);
        if (!m.isValid()) m = moment(val);
        if (m.isValid()) iso = m.format('YYYY-MM-DD');
      }
    } catch (err) {
      // continue to regex fallbacks
    }

    // Regex fallbacks
    if (!iso) {
  var ymd = val.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);
      var dmy = val.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
      if (ymd) {
        var y = ymd[1], m = ymd[2].padStart(2, '0'), d = ymd[3].padStart(2, '0');
        iso = y + '-' + m + '-' + d;
      } else if (dmy) {
        var d = dmy[1].padStart(2, '0'), m = dmy[2].padStart(2, '0'), y = dmy[3];
        iso = y + '-' + m + '-' + d;
      }
    }

    if (iso) {
      // Prefer to set via flatpickr instance so UI stays in sync
      try {
        if (input._flatpickr && typeof input._flatpickr.setDate === 'function') {
          input._flatpickr.setDate(iso, true, 'Y-m-d');
        } else {
          input.value = iso;
        }
      } catch (e) {
        input.value = iso;
      }
    }
  }

  // Attach listeners to existing and future datepicker inputs
  function attachDatepickerListeners(root) {
    root = root || document;
    root.querySelectorAll && root.querySelectorAll('input.datepicker').forEach(function(input) {
      // avoid attaching multiple times
      if (input.dataset._dateNormAttached === 'true') return;
      input.dataset._dateNormAttached = 'true';
      input.addEventListener('blur', function () { normalizeDateInput(input); });
      input.addEventListener('change', function () { normalizeDateInput(input); });
    });
  }

  attachDatepickerListeners(document);

  // Also normalize before any form submit so virtual forms are handled
  document.addEventListener('submit', function (e) {
    try {
      e.target.querySelectorAll && e.target.querySelectorAll('input.datepicker').forEach(function(input) {
        normalizeDateInput(input);
      });
    } catch (err) { /* ignore */ }
  }, true);

  // Theme toggle (light/dark) persisted in localStorage
  (function() {
    var themeToggle = document.getElementById('theme-toggle');
    if (!themeToggle) return;

    var lightIcon = document.getElementById('theme-toggle-light-icon');
    var darkIcon = document.getElementById('theme-toggle-dark-icon');

    function setTheme(dark) {
      var html = document.documentElement;
      if (dark) html.classList.add('dark'); else html.classList.remove('dark');
      if (lightIcon && darkIcon) {
        if (dark) { lightIcon.classList.remove('hidden'); darkIcon.classList.add('hidden'); }
        else { lightIcon.classList.add('hidden'); darkIcon.classList.remove('hidden'); }
      }
    }

    // initialize from localStorage or OS preference
    var saved = localStorage.getItem('theme');
    if (saved === 'dark') setTheme(true);
    else if (saved === 'light') setTheme(false);
    else setTheme(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);

    themeToggle.addEventListener('click', function () {
      var isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      setTheme(isDark);
    });
  })();
