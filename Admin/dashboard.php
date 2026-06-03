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
/* PREMIUM INDUSTRIAL DASHBOARD STYLES */

/* SMALLER STAT CARDS */
.stat-card {
    background-color: var(--bg-card);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px 18px;
    box-shadow: var(--card-shadow);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-align: left;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    border-color: var(--border-highlight);
    box-shadow: var(--glow-primary);
}

.stat-card:hover::before {
    opacity: 1;
}

/* SMALL ICON */
.stat-icon {
    width: 55px;
    height: 55px;
    margin-bottom: 15px;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 24px;
    background: rgba(0,0,0,0.4);
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: inset 0 0 15px rgba(255,255,255,0.05);
}

/* LABEL */
.stat-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* NUMBER */
.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-main);
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

/* ICON COLORS - Glowing Neon Effects */
.icon-orange { color: #ffb800; text-shadow: 0 0 10px rgba(255, 184, 0, 0.6); border-color: rgba(255, 184, 0, 0.3); }
.icon-blue   { color: #00c3ff; text-shadow: var(--glow-primary); border-color: rgba(0, 195, 255, 0.3); }
.icon-green  { color: #00ff88; text-shadow: 0 0 10px rgba(0, 255, 136, 0.6); border-color: rgba(0, 255, 136, 0.3); }
.icon-red    { color: #ff3366; text-shadow: var(--glow-danger); border-color: rgba(255, 51, 102, 0.3); }
.icon-purple { color: #7000ff; text-shadow: 0 0 10px rgba(112, 0, 255, 0.6); border-color: rgba(112, 0, 255, 0.3); }

/* QUICK ACTIONS */
.quick-actions {
    margin-top: 25px;
    display: flex;
    justify-content: flex-start;
    gap: 20px;
    flex-wrap: wrap;
}

.action-card {
    width: 160px;
    padding: 22px 15px;
    border-radius: 16px;
    text-align: center;
    background-color: var(--bg-card);
    border: 1px solid var(--border-color);
    backdrop-filter: blur(12px);
    box-shadow: var(--card-shadow);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    color: var(--text-muted);
}

.action-card:hover {
    transform: translateY(-8px);
    background: linear-gradient(135deg, rgba(0, 240, 255, 0.1), rgba(112, 0, 255, 0.1));
    color: var(--text-main);
    border-color: var(--primary-color);
    box-shadow: var(--glow-primary);
}

.action-card i {
    font-size: 2.2rem;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.action-card h4 {
    font-size: 15px;
    margin-top: 5px;
    font-weight: 600;
}

.action-card:nth-child(1) i { color: #00ff88; }
.action-card:nth-child(2) i { color: #00c3ff; }
.action-card:nth-child(3) i { color: #ffb800; }
.action-card:nth-child(4) i { color: #ff3366; }

.action-card:hover i {
    transform: scale(1.1);
    color: var(--primary-color);
    text-shadow: var(--glow-primary);
}

/* TITLES */
.dashboard-heading {
    text-align: left;
    margin-bottom: 25px;
    margin-top: 25px;
    font-size: 28px;
    color: var(--text-main);
}

.quick-actions-title {
    text-align: left;
    margin-bottom: 25px;
    margin-top: 35px;
    font-size: 24px;
    color: var(--text-main);
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 10px;
    display: inline-block;
}
</style>
 </head>

    <div id="content-wrapper" class="d-flex flex-column">
       <div id="content">

            <div class="container-fluid">
                <div class="mb-4">
                     <h3 class="dashboard-heading">Overview</h3>
                     <p style="color: var(--text-muted);">Welcome back! Here's what's happening today.</p>
                </div>
                <div>
                         <!-- ROW 1 -->
        <div class="row g-3 mb-3">

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-blue"><i class="fas fa-users"></i></div>
                    <div class="stat-label">Total Clients</div>
                    <div class="stat-number"><?= $totalClients ?></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-orange"><i class="fas fa-project-diagram"></i></div>
                    <div class="stat-label">Total Projects</div>
                    <div class="stat-number"><?= $totalProjects ?></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-purple"><i class="fas fa-cogs"></i></div>
                    <div class="stat-label">Total Machines</div>
                    <div class="stat-number"><?= $totalMachines ?></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-orange"><i class="fas fa-tag"></i></div>
                    <div class="stat-label">Unassigned Machines</div>
                    <div class="stat-number"><?= $assignedMachines ?></div>
                </div>
            </div>

        </div>

        <!-- ROW 2 -->
        <div class="row g-3 mt-3">

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-purple"><i class="fas fa-robot"></i></div>
                    <div class="stat-label">Machine Used (Today)</div>
                    <div class="stat-number"><?= $todaysUse ?></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-label">Success (Today)</div>
                    <div class="stat-number"><?= $successCount ?></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-red"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-label">Failed (Today)</div>
                    <div class="stat-number"><?= $failedCount ?></div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-icon icon-red"><i class="fas fa-wrench"></i></div>
                    <div class="stat-label">Maintenance (Today)</div>
                    <div class="stat-number"><?= $underMaintenance ?></div>
                </div>
            </div>

        </div>

        <!-- QUICK ACTIONS -->
        <h3 class="quick-actions-title">Quick Actions</h3>

        <div class="quick-actions">
            <div class="action-card" onclick="window.location.href='add_client.php'">
                <i class="fas fa-user-plus"></i>
                <h4>Add Client</h4>
            </div>

            <div class="action-card" onclick="window.location.href='add_project.php'">
                <i class="fas fa-plus-circle"></i>
                <h4>New Project</h4>
            </div>

            

            <div class="action-card" onclick="window.location.href='list_unassignmachine.php'">
                <i class="fas fa-file-alt"></i>
                <h4>Assign</h4>
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



