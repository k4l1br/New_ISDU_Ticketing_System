@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Ticket Status Overview</h1>
    <small class="text-muted">Last updated: <span id="lastUpdated">{{ now()->format('Y-m-d H:i:s') }}</span></small>
@stop

@section('content')
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
                <span>Ticket Status Distribution</span>
                <span class="badge badge-primary float-right" id="totalTickets">{{ ($data['in_progress'] ?? 0) + ($data['no_action'] ?? 0) + ($data['completed'] ?? 0) }} Total</span>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="ticketPieChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Completed Tasks</span>
                <select id="reportRange" class="form-control form-control-sm w-auto">
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="taskLineChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tickets per Unit -->
<div class="card mt-4">
    <div class="card-header">Tickets per Unit</div>
    <div class="card-body">
        <table id="perUnitTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Unit</th>
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
let pieChart, taskLineChart, perUnitTable;

function updateProgressBars(total) {
    if (total > 0) {
        $('#in_progress_progress').css('width', ($('#in_progress_count').text()/total*100)+'%');
        $('#no_action_progress').css('width', ($('#no_action_count').text()/total*100)+'%');
        $('#completed_progress').css('width', ($('#completed_count').text()/total*100)+'%');
    }
}

function initCharts() {
    const pieCtx = document.getElementById('ticketPieChart').getContext('2d');
    const total = ($('#in_progress_count').text()*1) + ($('#no_action_count').text()*1) + ($('#completed_count').text()*1);
    pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['In Progress', 'No Action', 'Completed'],
            datasets: [{
                data: [$('#in_progress_count').text(), $('#no_action_count').text(), $('#completed_count').text()],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
                borderWidth: 1
            }]
        },
        options: { 
            responsive: true, 
            plugins: { 
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            } 
        }
    });
    updateProgressBars(total);
}

function renderLineChart(data, labels) {
    const ctx = document.getElementById('taskLineChart').getContext('2d');
    if (taskLineChart) taskLineChart.destroy();
    taskLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Completed Tasks',
                data: data,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { precision: 0 } 
                } 
            }
        }
    });
}

function initPerUnitTable() {
    perUnitTable = $('#perUnitTable').DataTable({
        ajax: {
            url: '/dashboard-per-unit',
            dataSrc: 'data',
            error: function(xhr, error, thrown) {
                console.error('AJAX Error:', xhr.responseText);
                $('#perUnitTable tbody').html(
                    '<tr><td colspan="6" class="text-center text-danger">' +
                    'Error loading data. Status: ' + xhr.status + 
                    ' - ' + (thrown || 'Unknown error') +
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
                    // Ensure we always show a percentage (even when 0)
                    const percentage = data.total > 0 
                        ? Math.round((data.completed / data.total) * 100)
                        : 0; // Explicit 0 when no tickets
                    
                    // Use warning color when 0%, success otherwise
                    const barClass = percentage > 0 ? 'bg-success' : 'bg-warning';
                    
                    return `<div class="progress">
                              <div class="progress-bar ${barClass}" 
                                   role="progressbar" 
                                   style="width: ${percentage}%"
                                   aria-valuenow="${percentage}" 
                                   aria-valuemin="0" 
                                   aria-valuemax="100">
                                ${percentage}%
                              </div>
                            </div>`;
                }
            }
        ],
        paging: false,
        searching: false,
        info: false,
        responsive: true,
        order: [[4, 'desc']], // Sort by total descending
        language: {
            zeroRecords: "No units found",
            infoEmpty: "No units available"
        }
    });
}

function refreshPerUnit() {
    perUnitTable.ajax.reload(null, false);
}

function updateDashboard() {
    fetch('/dashboard-data')
        .then(res => res.json())
        .then(data => {
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
        })
        .catch(error => console.error('Dashboard update error:', error));
}

function loadReport(range = 'weekly') {
    fetch(`/dashboard-tasks-report?range=${range}`)
        .then(res => res.json())
        .then(data => {
            renderLineChart(data.values || [], data.labels || []);
        })
        .catch(error => console.error('Report load error:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    initCharts();
    initPerUnitTable();
    loadReport();

    setInterval(() => {
        updateDashboard();
        refreshPerUnit();
    }, 10000);

    document.getElementById('reportRange').addEventListener('change', function() {
        loadReport(this.value);
    });

    updateDashboard();
});
</script>
@stop