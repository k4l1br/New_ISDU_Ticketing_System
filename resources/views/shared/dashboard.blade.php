@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Ticket Status Overview</h1>
    <small class="text-muted">Last updated: <span id="lastUpdated"></span></small>
@stop

@section('content')
<!-- Hidden element to store dashboard data for JavaScript -->
<div id="dashboard-data" data-info="{{ json_encode($data) }}" style="display: none;"></div>

<div class="row">
    <!-- Info Boxes -->
    <div class="col-md-4">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">In Progress</span>
                <span class="info-box-number" id="in_progress_count">{{ $data['in_progress'] ?? 0 }}</span>
                <div class="progress">
                    <div class="progress-bar" id="in_progress_progress"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-thumbs-up"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">No Action</span>
                <span class="info-box-number" id="no_action_count">{{ $data['no_action'] ?? 0 }}</span>
                <div class="progress">
                    <div class="progress-bar" id="no_action_progress"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-flag"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Completed</span>
                <span class="info-box-number" id="completed_count">{{ $data['completed'] ?? 0 }}</span>
                <div class="progress">
                    <div class="progress-bar" id="completed_progress"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i>Ticket Status Distribution
                </h3>
                <div class="card-tools">
                    <span class="badge badge-primary" id="totalTickets">0 Total</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Chart Container -->
                <div class="d-flex justify-content-center align-items-center" style="height: 280px; margin-bottom: 20px;">
                    <canvas id="ticketPieChart"></canvas>
                </div>
                
                <!-- AdminLTE Style Legend -->
                <div class="row text-center">
                    <div class="col-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-warning">
                                <i class="fas fa-circle"></i>
                            </span>
                            <h5 id="progress-legend" class="description-header">0</h5>
                            <span class="description-text">In Progress</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-secondary">
                                <i class="fas fa-circle"></i>
                            </span>
                            <h5 id="noaction-legend" class="description-header">0</h5>
                            <span class="description-text">No Action</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block">
                            <span class="description-percentage text-success">
                                <i class="fas fa-circle"></i>
                            </span>
                            <h5 id="completed-legend" class="description-header">0</h5>
                            <span class="description-text">Completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i>Completed Tasks Trend
                </h3>
                <div class="card-tools">
                    <select id="reportRange" class="form-control form-control-sm" style="width: auto;">
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>
            <div class="card-body" style="height: 400px;">
                <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                    <canvas id="taskLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tickets per Admin User -->
<div class="card mt-4">
    <div class="card-header">Tickets per Admin User</div>
    <div class="card-body" style="min-height: 340px; max-height: 500px; overflow-y: auto;">
        <table id="perUnitTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Assigned Admin</th>
                    <th>In Progress</th>
                    <th>No Action</th>
                    <th>Completed</th>
                    <th>Total</th>
                    <th>Total Percentage </th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/datatables.bootstrap4.min.css') }}">
@stop

@section('js')
{{-- AdminLTE already includes jQuery, so we don't need to load it again --}}

<!-- Primary Chart.js from local assets -->
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<!-- Fallback to CDN if local asset fails -->
<script>
    window.Chart || document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"><\/script>');
</script>

<!-- Primary DataTables scripts from local assets -->
<script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Fallback to CDN if local assets fail -->
<script>
    $.fn.DataTable || document.write('<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"><\/script><script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"><\/script>');
</script>
<script>
// Global variables
let pieChart, taskLineChart, perUnitTable;

// Dashboard data from server - pass data through a data attribute instead
window.dashboardData = JSON.parse(document.getElementById('dashboard-data').getAttribute('data-info'));
console.log('Dashboard data:', window.dashboardData);

function updateProgressBars(total) {
    if (total > 0) {
        $('#in_progress_progress').css('width', ($('#in_progress_count').text()/total*100)+'%');
        $('#no_action_progress').css('width', ($('#no_action_count').text()/total*100)+'%');
        $('#completed_progress').css('width', ($('#completed_count').text()/total*100)+'%');
    }
}

// Update legend numbers
function updateLegend() {
    const data = window.dashboardData;
    document.getElementById('progress-legend').textContent = data.in_progress || 0;
    document.getElementById('noaction-legend').textContent = data.no_action || 0;
    document.getElementById('completed-legend').textContent = data.completed || 0;
    
    const total = (data.in_progress || 0) + (data.no_action || 0) + (data.completed || 0);
    document.getElementById('totalTickets').textContent = total + ' Total';
}

function initCharts() {
    try {
        const data = window.dashboardData;
        console.log('Initializing charts with data:', data);
        
        // Check if Chart.js is available
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded!');
            return;
        }
        
        // Check if canvas elements exist
        const pieCanvas = document.getElementById('ticketPieChart');
        if (!pieCanvas) {
            console.error('Pie chart canvas not found!');
            return;
        }
        
        // Pie Chart
        console.log('Creating pie chart...');
        const pieCtx = pieCanvas.getContext('2d');
        pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['In Progress', 'No Action', 'Completed'],
                datasets: [{
                    data: [data.in_progress || 0, data.no_action || 0, data.completed || 0],
                    backgroundColor: ['#ffc107', '#6c757d', '#28a745'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
        console.log('Pie chart created successfully');
        
        const total = (data.in_progress || 0) + (data.no_action || 0) + (data.completed || 0);
        document.getElementById('totalTickets').textContent = total + ' Total';
        document.getElementById('progress-legend').textContent = data.in_progress || 0;
        document.getElementById('noaction-legend').textContent = data.no_action || 0;
        document.getElementById('completed-legend').textContent = data.completed || 0;
        updateProgressBars(total);
        
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
}

function initPerUnitTable() {
    try {
        console.log('Initializing DataTable...');
        
        // Check if DataTables is available
        if (!$.fn.DataTable) {
            console.error('DataTables library not loaded!');
            return;
        }
        
        perUnitTable = $('#perUnitTable').DataTable({
            ajax: {
                url: '/dashboard-per-unit',
                dataSrc: 'data',
                error: function(xhr, error, thrown) {
                    console.error('DataTable AJAX error:', xhr.responseText);
                    $('#perUnitTable tbody').html(
                        '<tr><td colspan="6" class="text-center text-danger">' +
                        'Error loading data: ' + (xhr.responseJSON?.error || 'Unknown error') +
                        '</td></tr>'
                    );
                }
            },
            columns: [
                { data: 'unit' },
                { data: 'in_progress', className: 'text-center' },
                { data: 'no_action', className: 'text-center' },
                { data: 'completed', className: 'text-center' },
                { data: 'total', className: 'text-center' },
                { 
                    data: null,
                    className: 'text-center',
                    render: function(data) {
                        const percentage = data.total > 0 ? Math.round((data.completed / data.total) * 100) : 0;
                        return percentage + '%';
                    }
                }
            ],
            paging: false,
            searching: false,
            info: false
        });
        
        console.log('DataTable initialized successfully');
        
    } catch (error) {
        console.error('Error initializing DataTable:', error);
    }
}

function refreshPerUnit() {
    perUnitTable.ajax.reload(null, false);
}

function updateDashboard() {
    console.log('Updating dashboard...');
    fetch('/dashboard-data')
        .then(res => {
            console.log('Dashboard data response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Dashboard data received:', data);
            document.getElementById('in_progress_count').textContent = data.in_progress || 0;
            document.getElementById('no_action_count').textContent = data.no_action || 0;
            document.getElementById('completed_count').textContent = data.completed || 0;
            
            const total = (data.in_progress || 0) + (data.no_action || 0) + (data.completed || 0);
            document.getElementById('totalTickets').textContent = total + ' Total';
            
            if (pieChart) {
                pieChart.data.datasets[0].data = [data.in_progress || 0, data.no_action || 0, data.completed || 0];
                pieChart.update();
            }
            
            updateProgressBars(total);
            document.getElementById('lastUpdated').textContent = new Date().toLocaleString();
            refreshPerUnit();
        })
        .catch(error => {
            console.error('Dashboard update error:', error);
            document.getElementById('lastUpdated').textContent = 'Error updating - ' + new Date().toLocaleString();
        });
}

function loadReport(range = 'weekly') {
    console.log('Loading report for range:', range);
    fetch(`/dashboard-tasks-report?range=${range}`)
        .then(res => {
            console.log('Report response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Report data received:', data);
            renderLineChart(data.completed || [], data.created || [], data.no_action || [], data.labels || []);
        })
        .catch(error => {
            console.error('Report load error:', error);
            // Show error message in chart area
            const chartContainer = document.getElementById('taskLineChart').parentElement;
            chartContainer.innerHTML = `
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle"></i>
                    Unable to load chart data. Please try refreshing the page.
                </div>
            `;
        });
}

function renderLineChart(completed, created, no_action, labels) {
    try {
        console.log('Rendering line chart with data:', { completed, created, no_action, labels });
        
        const lineCanvas = document.getElementById('taskLineChart');
        if (!lineCanvas) {
            console.error('Line chart canvas not found!');
            return;
        }
        
        const ctx = lineCanvas.getContext('2d');
        if (taskLineChart) {
            taskLineChart.destroy();
        }
        
        taskLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Completed Tasks',
                        data: completed,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40,167,69,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tickets Created',
                        data: created,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0,123,255,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'No Action',
                        data: no_action,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255,193,7,0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
        
        console.log('Line chart created successfully');
        
    } catch (error) {
        console.error('Error creating line chart:', error);
        const chartContainer = document.getElementById('taskLineChart').parentElement;
        chartContainer.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i>
                Chart error: ${error.message}
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('Dashboard DOM loaded, initializing...');
    
    // Check library availability
    console.log('jQuery available:', typeof $ !== 'undefined');
    console.log('Chart.js available:', typeof Chart !== 'undefined');
    console.log('DataTables available:', typeof $.fn.DataTable !== 'undefined');
    
    try {
        // Initialize components
        initCharts();
        initPerUnitTable();
        loadReport();
        updateLegend();

        // Set up auto-refresh
        setInterval(() => {
            updateDashboard();
        }, 10000);

        // Set up event listeners
        const reportRangeElement = document.getElementById('reportRange');
        if (reportRangeElement) {
            reportRangeElement.addEventListener('change', function() {
                loadReport(this.value);
            });
        }

        // Initial update
        updateDashboard();
        
        // Update timestamp
        const timestampElement = document.getElementById('lastUpdated');
        if (timestampElement) {
            timestampElement.textContent = new Date().toLocaleString();
        }
        
        console.log('Dashboard initialization completed');
        
    } catch (error) {
        console.error('Error during dashboard initialization:', error);
        
        // Show error message in chart areas if there's an issue
        const chartContainers = document.querySelectorAll('canvas');
        chartContainers.forEach(container => {
            if (container && container.parentElement) {
                container.parentElement.innerHTML = 
                    '<div class="alert alert-warning">Error initializing charts. Please refresh the page.</div>';
            }
        });
    }
});
</script>
@stop