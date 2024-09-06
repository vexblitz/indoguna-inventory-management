// Chart.js Area Chart Configuration
var ctx = document.getElementById("myAreaChart").getContext("2d");

// Initial Data and Configuration for Chart.js
var myAreaChart = new Chart(ctx, {
  type: "line", // Set to 'line' to mimic area chart (Chart.js uses 'line' with background)
  data: {
    labels: [], // Dates or periods will go here
    datasets: [
      {
        label: "Jumlah Stok",
        lineTension: 0.3,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(78, 115, 223, 1)",
        pointBorderColor: "rgba(78, 115, 223, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        data: [], // Data points for stock levels
      },
    ],
  },
  options: {
    maintainAspectRatio: false,
    scales: {
      x: {
        type: "time", // Use 'time' scale for dates
        time: {
          unit: "day", // Unit for the x-axis, can be 'day', 'month', etc.
          tooltipFormat: "MM/DD/YYYY",
          displayFormats: {
            day: "MM/DD",
          },
        },
        grid: {
          display: false,
        },
        ticks: {
          maxTicksLimit: 7,
        },
      },
      y: {
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
        },
        grid: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2],
        },
      },
    },
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        backgroundColor: "rgb(255,255,255)",
        bodyColor: "#858796",
        titleMarginBottom: 10,
        titleColor: "#6e707e",
        titleFontSize: 14,
        borderColor: "#dddfeb",
        borderWidth: 1,
        padding: 15,
        displayColors: false,
        mode: "index",
        intersect: false,
        caretPadding: 10,
      },
    },
  },
});

// Fetch Data and Update Chart
function getData(bulan) {
  const url = `/home/apiData/${bulan}`;
  $.getJSON(url, function (data) {
    const labels = [];
    const stokData = [];
    data.forEach((item) => {
      labels.push(item.tanggal); // Assuming 'tanggal' is a date field
      stokData.push(item.jumlah); // Assuming 'jumlah' is the stock count
    });

    // Update Chart Data
    myAreaChart.data.labels = labels;
    myAreaChart.data.datasets[0].data = stokData;

    // Redraw chart
    myAreaChart.update();
  });
}

// Event listener for selecting month
$("#bulan").change(function () {
  const val = $(this).val();
  getData(val);
});

// Initial load for a specific month (e.g., April)
getData(4);
