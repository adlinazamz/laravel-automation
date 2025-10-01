import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    
  if (document.getElementById("donut-chart-test")) {
  const donutOptions = {
    chart: {
      type: 'donut',
      height: 300,
      fontFamily: "Inter, sans-serif",
    },
    series: productTypeData, // e.g., [30, 20, 50]
    labels: productTypeLabels, // e.g., ["Type A", "Type B", "Type C"]
    colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'], // Tailwind palette
    legend: {
      position: 'bottom',
      labels: {
        colors: ['#374151'], // dark gray text
      },
    },
    tooltip: {
      enabled: true,
      custom: function({ series, seriesIndex, w }) {
        const val = series[seriesIndex];
        const label = w.config.labels[seriesIndex];
        const total = series.reduce((a, b) => a + b, 0);
        const percent = ((val / total) * 100).toFixed(1);
        const color = w.config.colors[seriesIndex];

        return `
          <div style="background:#1f2937;color:#fff;padding:10px 14px;border-radius:8px;min-width:140px;">
            <div style="display:flex;align-items:center;margin-bottom:6px;">
              <span style="display:inline-block;width:12px;height:12px;background:${color};border-radius:50%;margin-right:8px;"></span>
              <span style="font-weight:600;">${label}</span>
            </div>
            <div style="font-size:13px;">Value: <strong>${val}</strong></div>
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

  new ApexCharts(document.getElementById("donut-chart-test"), donutOptions).render();
}

if (document.getElementById("line-chart")) {
  const clicksOptions = {
    chart: {
      type: "line",
      width: 450,
      fontFamily: "Inter, sans-serif",
      dropShadow: { enabled: false },
      toolbar: { show: false },
      events: {
        // Logs when you hover a data point
        dataPointMouseEnter: function(event, chartContext, config) {
          console.log('dataPointMouseEnter', config);
          const xax = document.querySelector('.apexcharts-xaxistooltip');
        },
        dataPointMouseLeave: function(event, chartContext, config) {
        }
      }
    },

    tooltip: {
      enabled: true,
      shared: true,      // show combined series context (helps x-axis tooltip)
      intersect: false,  // allow hover between points -> x-axis tooltip appears
      custom: function({ series, dataPointIndex, w }) {

  // safe date extraction
  const date = (w.config && w.config.xaxis && w.config.xaxis.categories && w.config.xaxis.categories[dataPointIndex]) || '';

  if (typeof dataPointIndex === 'undefined' || dataPointIndex < 0) {
    return 0;
  }

  // build rows for each series
  const rows = (w.config.series || []).map((s, i) => {
    const val = (series[i] && typeof series[i][dataPointIndex] !== 'undefined') ? series[i][dataPointIndex] : '0';
    const color = (s && s.color) || (w.config.colors && w.config.colors[i]) || '#fff';
    return `
      <div style="display:flex;align-items:center;margin:4px 0;">
        <span style="display:inline-block;width:10px;height:10px;background:${color};border-radius:50%;margin-right:6px;"></span>
        <span style="font-weight:500;">${s.name}: <strong style="font-weight:700;">${val}</strong></span>
      </div>
    `;
  }).join('');

  // final HTML - matches your custom dark style
  const html = `
    <div class="custom-values-tooltip" style="background:#1f2937;color:#fff;padding:8px 12px;border-radius:6px;">
      <div style="margin-bottom:6px;font-size:12px;font-weight:600;">
        ${date}
      </div>
      ${rows}
    </div>
  `;
// Use setTimeout(0) so it runs after Apex attaches DOM elements
  setTimeout(() => {
    const textEl = document.querySelector('.apexcharts-xaxistooltip-text');
    const parent = textEl ? textEl.closest('.apexcharts-xaxistooltip') || textEl.parentElement : null;

    if (parent) {
      // Inline style + !important to override sheet rules
      parent.style.setProperty('background', '#1f2937', 'important');
      parent.style.setProperty('padding', '8px 12px', 'important');
      parent.style.setProperty('border-radius', '6px', 'important');
      parent.style.setProperty('box-shadow', '0 4px 10px rgba(0,0,0,0.25)', 'important');
      parent.style.setProperty('color', '#fff', 'important');
      parent.style.setProperty('opacity', '1', 'important');
      parent.style.setProperty('visibility', 'visible', 'important');
      // style the inner text
      if (textEl) {
        textEl.style.setProperty('color', '#fff', 'important');
        textEl.style.setProperty('font-weight', '600', 'important');
        textEl.style.setProperty('font-family', 'Inter, sans-serif', 'important');
        textEl.style.setProperty('font-size', '12px', 'important');
      }
      console.log('FORCE-STYLED x-axis tooltip parent:', parent, 'innerHTML:', parent.innerHTML);
      const cs = window.getComputedStyle(parent);
      console.log('Computed (parent): background=', cs.backgroundColor, 'color=', cs.color, 'opacity=', cs.opacity, 'visibility=', cs.visibility);
    } else {
      console.warn('x-axis tooltip NOT found to style (will fallback to showing date inside custom tooltip)');
    }
  }, 0);

  return html;
}
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
      labels: { colors: "#fff" },
    },
    xaxis: {
  categories: productDates, 
  tooltip: {
    enabled: true,
    
},
      labels: {
        show: true,
        style: {
          fontFamily: "Inter, sans-serif",
          cssClass: "text-xs font-normal fill-gray-100",
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
