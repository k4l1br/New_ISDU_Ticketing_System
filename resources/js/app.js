import './bootstrap';
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('ticketChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [ 'Inprogress', 'No Action', 'Completed'],
                datasets: [{
                    label: 'Tickets',
                    data: [0, 0, 0, 1], // Replace these with your dynamic values
                    backgroundColor: [
                        '#ffc107', // Yellow
                        '#dc3545', // Red
                        '#28a745' // Green
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }
});
