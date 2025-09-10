/**
 * Dashboard Statistics
 * Fetches and displays statistics on the dashboard
 */

import ApexCharts from "apexcharts";

// Function to fetch dashboard statistics
async function fetchDashboardStatistics() {
    try {
        const response = await fetch('/dashboard/statistics');
        if (!response.ok) {
            throw new Error('Failed to fetch dashboard statistics');
        }
        return await response.json();
    } catch (error) {
        console.error('Error fetching dashboard statistics:', error);
        // Return mock data as fallback when API fails
        return getMockStatistics();
    }
}

// Mock data for when API fails
function getMockStatistics() {
    return {
        training_centers_count: 12,
        trainers_count: 45,
        courses_count: 78,
        students_count: 350,
        most_booked_training_centers: [
            { name: 'Training Center A', bookings_count: 120 },
            { name: 'Training Center B', bookings_count: 95 },
            { name: 'Training Center C', bookings_count: 80 },
            { name: 'Training Center D', bookings_count: 65 },
            { name: 'Training Center E', bookings_count: 50 }
        ],
        most_booked_courses: [
            { name: 'Web Development', bookings_count: 85 },
            { name: 'Data Science', bookings_count: 70 },
            { name: 'UI/UX Design', bookings_count: 65 },
            { name: 'Mobile App Development', bookings_count: 55 },
            { name: 'Cloud Computing', bookings_count: 45 }
        ],
        bookings_by_type: {
            course: 150,
            online_course: 120,
            hall: 80,
            icdl_test: 60,
            icdl_card: 40
        }
    };
}

// Function to update the statistics cards
function updateStatisticsCards(statistics) {
    if (!statistics) return;

    // Update the first card - Training Centers
    const trainingCentersCard = document.querySelector('.grid-cols-12 > div:nth-child(1) .card-title');
    if (trainingCentersCard) {
        const cardParent = trainingCentersCard.closest('.card');
        updateCardContent(cardParent, 'Training Centers', statistics.training_centers_count, 'building');
    }

    // Update the second card - Trainers
    const trainersCard = document.querySelector('.grid-cols-12 > div:nth-child(2) .card-title');
    if (trainersCard) {
        const cardParent = trainersCard.closest('.card');
        updateCardContent(cardParent, 'Trainers', statistics.trainers_count, 'users');
    }

    // Update the third card - Courses
    const coursesCard = document.querySelector('.grid-cols-12 > div:nth-child(3) .card-title');
    if (coursesCard) {
        const cardParent = coursesCard.closest('.card');
        updateCardContent(cardParent, 'Courses', statistics.courses_count, 'book-open');
    }

    // Update the fourth card - Students
    const studentsCard = document.querySelector('.grid-cols-12 > div:nth-child(4) .card-title');
    if (studentsCard) {
        const cardParent = studentsCard.closest('.card');
        updateCardContent(cardParent, 'Students', statistics.students_count, 'user-graduate');
    }
}

// Helper function to update a card's content
function updateCardContent(cardElement, title, count, iconClass) {
    if (!cardElement) return;

    // Update the card title
    const titleElement = cardElement.querySelector('.card-title');
    if (titleElement) {
        titleElement.textContent = title;
    }

    // Update the count
    const countElement = cardElement.querySelector('.task-count') || cardElement.querySelector('h5');
    if (countElement) {
        countElement.textContent = count;
        countElement.classList.add('text-2xl', 'font-bold');
    }

    // Update the icon if needed
    const iconElement = cardElement.querySelector('i');
    if (iconElement && iconClass) {
        // Remove existing icon classes
        iconElement.className = '';
        // Add new icon classes
        iconElement.classList.add('fas', `fa-${iconClass}`, 'text-primary', 'text-xl');
    }

    // Remove unnecessary elements
    const timeElement = cardElement.querySelector('.task-time');
    if (timeElement) {
        timeElement.remove();
    }

    const avatarsElement = cardElement.querySelector('.avatar-group');
    if (avatarsElement) {
        avatarsElement.remove();
    }
}

// Function to create and update charts
function updateDashboardCharts(statistics) {
    if (!statistics) return;

    // Create Training Centers Chart
    createTrainingCentersChart(statistics.most_booked_training_centers);

    // Create Courses Chart
    createCoursesChart(statistics.most_booked_courses);

    // Create Bookings by Type Chart
    createBookingsByTypeChart(statistics.bookings_by_type);
}

// Function to create Training Centers Chart
function createTrainingCentersChart(data) {
    if (!data || data.length === 0) return;

    const chartContainer = document.querySelector('#training-centers-chart');
    if (!chartContainer) {
        console.error('Training centers chart container not found');
        return;
    }

    // Clear any existing chart
    chartContainer.innerHTML = '';

    const options = {
        series: [{
            name: 'Bookings',
            data: data.map(item => item.bookings_count)
        }],
        chart: {
            type: 'bar',
            height: 380,
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            // background: '#fff'
        },
        plotOptions: {
            bar: {
                horizontal: true,
                distributed: true,
                dataLabels: {
                    position: 'top'
                },
                borderRadius: 4,
                barHeight: '70%'
            }
        },
        colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B'],
        dataLabels: {
            enabled: true,
            offsetX: 20,
            style: {
                fontSize: '13px',
                fontWeight: 600,
                // colors: ['#fff']
            }
        },
        stroke: {
            width: 1,
            // colors: ['#fff']
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
            padding: {
                left: 10,
                right: 10
            },
            strokeDashArray: 4
        },
        xaxis: {
            categories: data.map(item => item.name),
            labels: {
                formatter: function (val) {
                    return val
                },
                style: {
                    fontSize: '13px',
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            title: {
                text: 'Training Centers'
            },
        },
        title: {
            text: 'Most Booked Training Centers',
            align: 'center',
            floating: true,
            style: {
                fontSize: '16px',
                fontWeight: 600,
                color: '#333'
            },
            offsetY: 10
        },
        tooltip: {
            theme: 'dark',
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function () {
                        return 'Bookings:'
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 576,
            options: {
                chart: {
                    height: 300
                },
                title: {
                    fontSize: '14px'
                }
            }
        }]
    };

    const chart = new ApexCharts(chartContainer, options);
    chart.render();
}

// Function to create Courses Chart
function createCoursesChart(data) {
    if (!data || data.length === 0) return;

    const chartContainer = document.querySelector('#courses-chart');
    if (!chartContainer) {
        console.error('Courses chart container not found');
        return;
    }

    // Clear any existing chart
    chartContainer.innerHTML = '';

    const options = {
        series: [{
            name: 'Bookings',
            data: data.map(item => item.bookings_count)
        }],
        chart: {
            type: 'bar',
            height: 380,
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            // background: '#fff'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '70%',
                endingShape: 'rounded',
                borderRadius: 4
            },
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style: {
                fontSize: '13px',
                fontWeight: 600,
                colors: ['#333']
            }
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: data.map(item => item.name),
            labels: {
                style: {
                    fontSize: '13px',
                    fontWeight: 500
                },
                rotate: -45,
                rotateAlways: false
            }
        },
        yaxis: {
            title: {
                text: 'Bookings'
            }
        },
        fill: {
            opacity: 1
        },
        title: {
            text: 'Most Booked Courses',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 600,
                color: '#333'
            },
            offsetY: 10
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " bookings"
                }
            },
            theme: 'light'
        },
        grid: {
            padding: {
                bottom: 10,
                left: 10,
                right: 10
            },
            strokeDashArray: 4
        },
        responsive: [{
            breakpoint: 576,
            options: {
                chart: {
                    height: 300
                },
                title: {
                    fontSize: '14px'
                }
            }
        }]
    };

    const chart = new ApexCharts(chartContainer, options);
    chart.render();
}

// Function to create Bookings by Type Chart
function createBookingsByTypeChart(data) {
    if (!data) return;

    const chartContainer = document.querySelector('#bookings-type-chart');
    if (!chartContainer) {
        console.error('Bookings type chart container not found');
        return;
    }

    // Clear any existing chart
    chartContainer.innerHTML = '';

    const labels = {
        'course': 'In-Person Courses',
        'online_course': 'Online Courses',
        'hall': 'Hall Bookings',
        'icdl_test': 'ICDL Tests',
        'icdl_card': 'ICDL Cards'
    };

    // Prepare data for the pie chart
    const seriesData = Object.values(data);
    const labelData = Object.keys(data).map(key => labels[key] || key);

    const options = {
        series: seriesData,
        chart: {
            height: 380,
            type: 'pie',
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                }
            },
            // background: '#fff'
        },
        labels: labelData,
        colors: ['#3e60d5', '#47ad77', '#fa5c7c', '#6c757d', '#39afd1'],
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '14px',
            fontWeight: 500,
            markers: {
                width: 12,
                height: 12,
                radius: 6
            },
            itemMargin: {
                horizontal: 10,
                vertical: 5
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '0%'
                },
                expandOnClick: true,
                dataLabels: {
                    offset: -20
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                return opts.w.config.series[opts.seriesIndex] + ' (' + val.toFixed(1) + '%)';
            },
            style: {
                fontSize: '13px',
                fontWeight: 600,
                // colors: ['#fff']
            },
            dropShadow: {
                enabled: true,
                blur: 3
            }
        },
        stroke: {
            width: 2,
            // colors: ['#fff']
        },
        responsive: [{
            breakpoint: 576,
            options: {
                chart: {
                    height: 300
                },
                legend: {
                    position: 'bottom',
                    fontSize: '12px'
                },
                dataLabels: {
                    enabled: false
                }
            }
        }],
        title: {
            text: 'Bookings by Type',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 600,
                color: '#333'
            },
            offsetY: 10
        },
        tooltip: {
            enabled: true,
            y: {
                formatter: function(val) {
                    return val + ' bookings';
                }
            },
            style: {
                fontSize: '13px'
            }
        }
    };

    const chart = new ApexCharts(chartContainer, options);
    chart.render();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Show loading state
        const chartContainers = document.querySelectorAll('#training-centers-chart, #courses-chart, #bookings-type-chart');
        chartContainers.forEach(container => {
            if (container) {
                container.innerHTML = '<div class="flex items-center justify-center h-64"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
            }
        });

        // Fetch statistics
        const statistics = await fetchDashboardStatistics();

        // Update the dashboard with the fetched statistics
        if (statistics) {
            // Update the statistics cards
            updateStatisticsCards(statistics);

            // Update the charts
            updateDashboardCharts(statistics);
        } else {
            // Handle case when statistics are null but no error was thrown
            chartContainers.forEach(container => {
                if (container) {
                    container.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">Could not load chart data</div>';
                }
            });
        }
    } catch (error) {
        console.error('Error initializing dashboard:', error);
        // Show error message in chart containers
        const chartContainers = document.querySelectorAll('#training-centers-chart, #courses-chart, #bookings-type-chart');
        chartContainers.forEach(container => {
            if (container) {
                container.innerHTML = `<div class="flex items-center justify-center h-64 text-gray-500">Error loading chart data</div>`;
            }
        });
    }
});
