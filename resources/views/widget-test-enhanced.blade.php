<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Dashboard Widget Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #ffffff;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            padding: 2rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1><i class="fas fa-plane me-3"></i>Enhanced Dashboard Widgets Test</h1>
                <p class="text-muted">Testing the new widgets created for the enhanced phpVMS7 dashboard</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Performance Metrics Widget Test -->
            <div class="col-lg-6">
                <div class="card bg-dark border-secondary">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Performance Metrics Widget</h5>
                    </div>
                    <div class="card-body">
                        @try
                            {{ Widget::PerformanceMetrics() }}
                        @catch(Exception $e)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Widget Error: {{ $e->getMessage() }}
                            </div>
                        @endtry
                    </div>
                </div>
            </div>

            <!-- Financial Dashboard Widget Test -->
            <div class="col-lg-6">
                <div class="card bg-dark border-secondary">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-pie me-2"></i>Financial Dashboard Widget</h5>
                    </div>
                    <div class="card-body">
                        @try
                            {{ Widget::FinancialDashboard() }}
                        @catch(Exception $e)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Widget Error: {{ $e->getMessage() }}
                            </div>
                        @endtry
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-3">
            <!-- Quick Flight Finder Widget Test -->
            <div class="col-12">
                <div class="card bg-dark border-secondary">
                    <div class="card-header">
                        <h5><i class="fas fa-search me-2"></i>Quick Flight Finder Widget</h5>
                    </div>
                    <div class="card-body">
                        @try
                            {{ Widget::QuickFlightFinder() }}
                        @catch(Exception $e)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Widget Error: {{ $e->getMessage() }}
                            </div>
                        @endtry
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Widget Implementation Summary</h6>
                    <p class="mb-0">
                        <strong>Created:</strong> PerformanceMetrics, FinancialDashboard, and QuickFlightFinder widgets<br>
                        <strong>Enhanced Dashboard:</strong> index_enhanced.blade.php with sample file styling integrated<br>
                        <strong>Features:</strong> Animated backgrounds, comprehensive statistics, professional styling
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>