@extends('admin.index')
@section('content')
<style>
    .dashboard-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <h2>Total Accounts: {{ $totalAccounts }}</h2>
                    <a href="/admin/users" class="btn btn-primary">View User List</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container py-5">
    <h1 class="display-4 text-center mb-5">Sales Dashboard</h1>

    <form id="dateRangeForm" method="GET" class="row justify-content-center align-items-center g-3 mb-5">
        <div class="col-auto">
            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
        </div>
        <div class="col-auto">
            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <h2 class="h4 mb-4">Sales and Commission Over Time</h2>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <h2 class="h4 mb-4">Top 5 Products by Quantity Sold</h2>
                <div class="chart-container">
                    <canvas id="productChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="dashboard-card">
                <h2 class="h4 mb-4">Top 5 Vendors by Sales</h2>
                <div class="chart-container" style="height: 400px;">
                    <canvas id="vendorChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Data
        var salesData = [
            <?php
            foreach ($salesData as $data) {
                echo "{";
                echo "date: '" . addslashes($data->date) . "',";
                echo "totalSales: " . floatval($data->total_sales) . ",";
                echo "totalCommission: " . floatval($data->total_commission);
                echo "},";
            }
            ?>
        ];

        // Product Data
        var productData = [
            <?php
            foreach ($productSales as $product) {
                echo "{";
                echo "productName: '" . addslashes($product->product_name) . "',";
                echo "totalQuantity: " . intval($product->total_quantity);
                echo "},";
            }
            ?>
        ];

        // Vendor Data
        var vendorData = [
            <?php
            foreach ($vendorSales as $vendor) {
                echo "{";
                echo "vendorName: '" . addslashes($vendor->vendor_name) . "',";
                echo "totalSales: " . floatval($vendor->total_sales);
                echo "},";
            }
            ?>
        ];

        // Sales and Commission Chart
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: salesData.map(item => item.date),
                datasets: [{
                    label: 'Total Sales',
                    data: salesData.map(item => item.totalSales),
                    borderColor: 'rgb(0, 123, 255)',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.1,
                    fill: true
                }, {
                    label: 'Total Commission',
                    data: salesData.map(item => item.totalCommission),
                    borderColor: 'rgb(220, 53, 69)',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Top Products Chart
        new Chart(document.getElementById('productChart'), {
            type: 'bar',
            data: {
                labels: productData.map(item => item.productName),
                datasets: [{
                    label: 'Quantity Sold',
                    data: productData.map(item => item.totalQuantity),
                    backgroundColor: 'rgba(40, 167, 69, 0.6)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Top Vendors Chart
        new Chart(document.getElementById('vendorChart'), {
            type: 'pie',
            data: {
                labels: vendorData.map(item => item.vendorName),
                datasets: [{
                    data: vendorData.map(item => item.totalSales),
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(111, 66, 193, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    });
</script>
@endsection