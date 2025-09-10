/*
Template Name: Konrix - Responsive 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Dashboard js
*/

import ApexCharts from "apexcharts";

var colors = ["#3073F1", "#0acf97"];
var projectStatisticsElement = document.querySelector("#crm-project-statistics");
var dataColors = projectStatisticsElement ? projectStatisticsElement.dataset.colors : null;

if (dataColors) {
    colors = dataColors.split(",");
}

var options = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            endingShape: 'rounded',
            columnWidth: '25%',
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 3,
        colors: ['transparent']
    },
    colors: colors,
    series: [{
        name: 'Projects',
        data: [56, 38, 85, 72, 28, 69, 55, 52, 69]
    }, {
        name: 'Working Hours',
        data: [176, 185, 256, 240, 187, 205, 191, 114, 194]
    }],
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    legend: {
        offsetY: 7,
    },
    fill: {
        opacity: 1

    },
    grid: {
        row: {
            colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.2
        },
        borderColor: '#9ca3af20',
        padding: {
            bottom: 5,
        }
    }
}

if (projectStatisticsElement) {
    var chart = new ApexCharts(
        projectStatisticsElement,
        options
    );
    
    chart.render();
}



//
var colors = ["#3073F1", "#0acf97"];
var monthlyTargetElement = document.querySelector("#monthly-target");
var dataColors = monthlyTargetElement ? monthlyTargetElement.dataset.colors : null;

if (dataColors) {
    colors = dataColors.split(",");
}

var options = {
    chart: {
        height: 280,
        type: 'donut',
    },
    legend: {
        show: false
    },
    stroke: {
        colors: ['transparent']
    },
    series: [82, 37],
    labels: ["Done Projects", "Pending Projects"],
    colors: colors,
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
}

if (monthlyTargetElement) {
    var chart = new ApexCharts(
        monthlyTargetElement,
        options
    );
    
    chart.render();
}


//
var colors = ["#3073F1", "#0acf97", "#fa5c7c", "#ffbc00"];
var projectOverviewElement = document.querySelector("#project-overview-chart");
var dataColors = projectOverviewElement ? projectOverviewElement.dataset.colors : null;
if (dataColors) {
    colors = dataColors.split(",");
}
var options = {
    chart: {
        height: 350,
        type: 'radialBar'
    },
    colors: colors,
    series: [85, 70, 80, 65],
    labels: ['Product Design', 'Web Development', 'Illustration Design', 'UI/UX Design'],
    plotOptions: {
        radialBar: {
            track: {
                margin: 5,
            }
        }
    }
}

// Check if the project-overview-chart element exists before rendering
var projectOverviewChartElement = document.querySelector("#project-overview-chart");
if (projectOverviewChartElement) {
    var chart = new ApexCharts(
        projectOverviewChartElement,
        options
    );
    
    chart.render();
}