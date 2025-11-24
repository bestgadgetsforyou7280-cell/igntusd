<?php 
require_once 'includes/header.php'; 

// --- डेटाबेस से डेटा प्राप्त करें ---

// इस साल के प्लेसमेंट आँकड़े प्राप्त करें
$current_year = date('Y');
$stats_sql = "SELECT 
    COUNT(DISTINCT company_name) as total_companies,
    SUM(students_placed) as total_placed,
    MAX(package_max) as highest_package,
    AVG(package_average) as average_package
    FROM placements 
    WHERE placement_year = ?";
$stmt_stats = $conn->prepare($stats_sql);
$stmt_stats->bind_param("i", $current_year);
$stmt_stats->execute();
$stats_result = $stmt_stats->get_result();
$stats = $stats_result->fetch_assoc();
$stmt_stats->close();

// सभी प्लेसमेंट रिकॉर्ड्स प्राप्त करें (सबसे नए पहले)
$placements_sql = "SELECT * FROM placements ORDER BY placement_year DESC, company_name ASC";
$placements_result = $conn->query($placements_sql);
?>

<!-- इस पेज के लिए विशेष CSS स्टाइल्स -->
<style>
    .page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px; }
    .placements-section { padding: 80px 0; }
    .stats-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; background-color: var(--light-bg); padding: 30px; border-radius: 8px; margin-bottom: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .stat-box { text-align: center; }
    .stat-box .number { font-size: 2.5rem; font-weight: bold; color: var(--secondary-color); margin: 0; }
    .stat-box .label { font-size: 1rem; color: #555; margin-top: 5px; }
    .responsive-table-container { overflow-x: auto; }
    .placements-table { width: 100%; border-collapse: collapse; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .placements-table th, .placements-table td { padding: 15px; border: 1px solid #ddd; text-align: left; vertical-align: middle; }
    .placements-table thead th { background-color: var(--primary-color); color: white; }
    .placements-table tbody tr:nth-child(even) { background-color: #f9f9f9; }
    .placements-table tbody tr:hover { background-color: #f1f1f1; }
    .company-cell { display: flex; align-items: center; }
    .company-logo { width: 100px; height: auto; margin-right: 15px; }
</style>

<div class="page-header">
    <div class="container">
        <h1>Placement or Entrepreneurship</h1>
        <p>Launching successful careers in top technology companies.</p>
    </div>
</div>

<section class="placements-section">
    <div class="container">
        <h2 class="section-title">Placement Highlights (<?php echo $current_year; ?>)</h2>
        <div class="stats-bar">
            <div class="stat-box"> <p class="number"><?php echo (int)($stats['total_companies'] ?? 0); ?></p> <p class="label">Companies Visited</p> </div>
            <div class="stat-box"> <p class="number"><?php echo (int)($stats['total_placed'] ?? 0); ?></p> <p class="label">Students Placed</p> </div>
            <div class="stat-box"> <p class="number"><?php echo round($stats['highest_package'] ?? 0, 2); ?> LPA</p> <p class="label">Highest Package</p> </div>
            <div class="stat-box"> <p class="number"><?php echo round($stats['average_package'] ?? 0, 2); ?> LPA</p> <p class="label">Average Package</p> </div>
        </div>
        <h2 class="section-title">Comprehensive Placement Records</h2>
        <div class="responsive-table-container">
            <table class="placements-table">
                <thead> <tr> <th>Company</th> <th>Students Placed</th> <th>Average Package (LPA)</th> <th>Job Role</th> <th>Placement Year</th> </tr> </thead>
                <tbody>
                    <?php if ($placements_result && $placements_result->num_rows > 0): ?>
                        <?php while($row = $placements_result->fetch_assoc()): ?>
                        <tr>
                            <td> <div class="company-cell"> <img src="uploads/placements/<?php echo htmlspecialchars($row['company_logo'] ?? ''); ?>" alt="<?php echo htmlspecialchars($row['company_name'] ?? ''); ?> Logo" class="company-logo" onerror="this.style.display='none'"> <strong><?php echo htmlspecialchars($row['company_name'] ?? ''); ?></strong> </div> </td>
                            <td><?php echo htmlspecialchars($row['students_placed'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['package_average'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['job_role'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['placement_year'] ?? ''); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr> <td colspan="5" style="text-align:center;">No placement records are available.</td> </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>