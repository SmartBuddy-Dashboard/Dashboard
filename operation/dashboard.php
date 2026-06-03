<?php 
require_once('include/header.php');

    require_once('include/navbar.php');
    require_once('include/config.php');


// -----------------------------
// FETCH DASHBOARD COUNTS
// -----------------------------
$totalMachines = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total 
         FROM machines 
         WHERE client_name IS NOT NULL 
           AND TRIM(client_name) <> ''"
    )
)['total'];

$todaysUse = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM trans WHERE date_time >= CURDATE() AND date_time < (CURDATE() + INTERVAL 1 DAY)")
)['total'];

$successCount        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM trans WHERE status='success' AND date_time >= CURDATE() AND date_time < (CURDATE() + INTERVAL 1 DAY)"))['total'];
$failedCount         = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM trans WHERE status='failed' AND date_time >= CURDATE() AND date_time < (CURDATE() + INTERVAL 1 DAY)"))['total'];
$totalProjects       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT project_name) AS total FROM projects"))['total'];
$totalClients        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM clients"))['total'];
$assignedMachines    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM machines WHERE client_name='' OR client_name IS NULL"))['total'];
$underMaintenance    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM machines WHERE status='maintenance'"))['total'];
 ?>
 <head>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

/* Dashboard Modern Stats Cards */
.stat-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
    background: var(--bg-card);
    border-radius: 15px;
    box-shadow: var(--card-shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid var(--border-color);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.card-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-main);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* ICON COLORS */
.icon-orange { background: linear-gradient(135deg, #f39c12, #e67e22); }
.icon-blue   { background: linear-gradient(135deg, #3498db, #2980b9); }
.icon-green  { background: linear-gradient(135deg, #2ecc71, #27ae60); }
.icon-red    { background: linear-gradient(135deg, #e74c3c, #c0392b); }
.icon-purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

/* QUICK ACTIONS – LEFT SIDE */
.quick-actions {
    margin-top: 25px;
    display: flex;
    justify-content: flex-start; /* LEFT SIDE */
    gap: 18px;
    flex-wrap: wrap;
}

/* SMALLER QUICK ACTION CARDS */
.action-card {
    width: 150px;
    padding: 18px;
    border-radius: 14px;
    text-align: center;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-card:hover {
    transform: translateY(-6px) scale(1.04);
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.action-card i {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.action-card h4 {
    font-size: 15px;
    margin-top: 5px;
    color: var(--text-main);
}

/* ICON COLORS */
.action-card:nth-child(1) i { color: #4CAF50; }
.action-card:nth-child(2) i { color: #2196F3; }
.action-card:nth-child(3) i { color: #FF9800; }
.action-card:nth-child(4) i { color: #F44336; }

.action-card:hover i,
.action-card:hover h4 {
    color: #fff;
}

/* TITLES */
.dashboard-heading {
    text-align: left;
    margin-bottom: 30px;
    margin-top: 20px;
    font-weight: bold;
    font-size: 26px;
    color: #333;
}

.quick-actions-title {
    text-align: left; /* LEFT SIDE */
    margin-bottom: 20px;
    margin-top: 20px;
    font-weight: bold;
    font-size: 24px;
    color: #333;
}
</style>
 </head>

    <div id="content-wrapper" class="d-flex flex-column">
       <div id="content">

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                         <h3 class="dashboard-heading">Welcome to <span style="color:#007bff;">Dashboard</span></h3>                        
                    </div>
                    <div class="card-body">
                         <!-- ROW 1 -->
        <div class="row g-3 mb-3">

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Total Clients</div>
                        <div class="stat-number"><?= $totalClients ?></div>
                    </div>
                    <div class="card-icon icon-blue"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Total Projects</div>
                        <div class="stat-number"><?= $totalProjects ?></div>
                    </div>
                    <div class="card-icon icon-orange"><i class="fas fa-project-diagram"></i></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Total Machines</div>
                        <div class="stat-number"><?= $totalMachines ?></div>
                    </div>
                    <div class="card-icon icon-purple"><i class="fas fa-cogs"></i></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Unassigned Machines</div>
                        <div class="stat-number"><?= $assignedMachines ?></div>
                    </div>
                    <div class="card-icon icon-orange"><i class="fas fa-tag"></i></div>
                </div>
            </div>

        </div>

        <!-- ROW 2 -->
        <div class="row g-3 mt-3">

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Machine Used (Today)</div>
                        <div class="stat-number"><?= $todaysUse ?></div>
                    </div>
                    <div class="card-icon icon-purple"><i class="fas fa-robot"></i></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Success (Today)</div>
                        <div class="stat-number"><?= $successCount ?></div>
                    </div>
                    <div class="card-icon icon-green"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Failed (Today)</div>
                        <div class="stat-number"><?= $failedCount ?></div>
                    </div>
                    <div class="card-icon icon-red"><i class="fas fa-times-circle"></i></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="card-info">
                        <div class="stat-label">Maintenance (Today)</div>
                        <div class="stat-number"><?= $underMaintenance ?></div>
                    </div>
                    <div class="card-icon icon-red"><i class="fas fa-wrench"></i></div>
                </div>
            </div>

        </div>

        <!-- QUICK ACTIONS -->
        <h3 class="quick-actions-title">Quick Actions</h3>

        <div class="quick-actions">
            <div class="action-card" onclick="window.location.href='list_clientdetails.php'">
                <i class="fas fa-users"></i>
                                <h4> Client List</h4>
            </div>

            <div class="action-card" onclick="window.location.href='list_projectdet.php'">
                   <i class="fas fa-file-alt"></i>

                <h4> Project List</h4>
            </div>

            

            <div class="action-card" onclick="window.location.href='list_machine.php'">
                <i class="fas fa-cogs"></i>
                <h4>Machine List</h4>
            </div>
        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php
    include('include/scripts.php');
    include('include/footer.php');
?>
<script>
// Refresh every 1 minute
setTimeout(() => location.reload(), 60000);
</script>



