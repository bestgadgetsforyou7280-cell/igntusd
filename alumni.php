<?php 
require_once 'includes/header.php'; 

// Check if a specific year has been selected from the URL
$selected_year = isset($_GET['year']) ? (int)$_GET['year'] : null;

if ($selected_year) {
    // If a year is selected, fetch alumni for that year, including the student's email
    // !! FIX: Added s.email to the SELECT statement !!
    $alumni_sql = "SELECT s.name, s.photo, s.email, a.* 
                   FROM alumni a 
                   JOIN students s ON a.student_id = s.id 
                   WHERE a.passing_year = ? 
                   ORDER BY s.name";
    $stmt = $conn->prepare($alumni_sql);
    $stmt->bind_param("i", $selected_year);
    $stmt->execute();
    $alumni_result = $stmt->get_result();
} else {
    // If no year is selected, fetch all distinct passing years for the initial view
    $years_sql = "SELECT DISTINCT passing_year FROM alumni ORDER BY passing_year DESC";
    $years_result = $conn->query($years_sql);
}
?>

<style>
/* ... (आपकी पिछली CSS वैसी ही रहेगी, यहाँ कोई बदलाव नहीं) ... */
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 60px 20px; }
.alumni-section { padding: 80px 0; }
.year-selection-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; max-width: 900px; margin: 0 auto; }
.year-card { display: block; background: var(--white); padding: 40px 20px; text-align: center; border-radius: var(--radius); box-shadow: var(--shadow-sm); text-decoration: none; font-size: 2rem; font-weight: 700; color: var(--primary-color); transition: all 0.3s ease; }
.year-card:hover { transform: translateY(-10px); box-shadow: var(--shadow-md); background-color: var(--primary-color); color: var(--white); }
.alumni-dashboard-header { text-align: center; margin-bottom: 50px; }
.alumni-dashboard-header h2 { font-size: 2.5rem; color: var(--primary-color); margin: 0; }
.back-to-years-btn { display: inline-block; margin-top: 10px; background-color: var(--bg-light); color: var(--secondary-color); padding: 8px 18px; border-radius: 50px; font-weight: 700; text-decoration: none; border: 1px solid #ddd; }
.alumni-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
.alumni-card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow-sm); padding: 25px; display: flex; flex-direction: column; }
.alumni-card-header { display: flex; align-items: center; gap: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
.alumni-card-header img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; }
.alumni-info h3 { margin: 0; font-size: 1.3rem; color: var(--primary-color); }
.alumni-info p { margin: 5px 0 0; color: #777; }
.alumni-card-body .detail-item { margin-bottom: 10px; font-size: 0.95rem; word-break: break-word; }
.alumni-card-body strong { color: var(--primary-color); }
/* New style for email link */
.alumni-email-link { color: var(--secondary-color); text-decoration: none; font-weight: 500; }
.alumni-email-link:hover { text-decoration: underline; }
</style>

<div class="page-header">
    <div class="container"><h1>Our Alumni Network</h1><p>Graduates Making an Impact in the Tech World</p></div>
</div>

<section class="alumni-section">
    <div class="container">

        <?php if ($selected_year): // View 2: Show Alumni for the selected year ?>
            
            <div class="alumni-dashboard-header">
                <h2>Alumni Batch of <?php echo htmlspecialchars($selected_year); ?></h2>
                <a href="alumni.php" class="back-to-years-btn">&larr; Back to All Batches</a>
            </div>

            <div class="alumni-grid">
                <?php if ($alumni_result && $alumni_result->num_rows > 0): ?>
                    <?php while($alumnus = $alumni_result->fetch_assoc()): ?>
                        <div class="alumni-card">
                            <div class="alumni-card-header">
                                <img src="uploads/students/<?php echo htmlspecialchars($alumnus['photo'] ?? 'default.png'); ?>" alt="<?php echo htmlspecialchars($alumnus['name']); ?>">
                                <div class="alumni-info">
                                    <h3><?php echo htmlspecialchars($alumnus['name']); ?></h3>
                                    <p>Passing Year: <?php echo htmlspecialchars($alumnus['passing_year']); ?></p>
                                </div>
                            </div>
                            <div class="alumni-card-body">
                                <p class="detail-item"><strong>Currently at:</strong> <?php echo htmlspecialchars($alumnus['current_company'] ?? 'N/A'); ?></p>
                                <p class="detail-item"><strong>Designation:</strong> <?php echo htmlspecialchars($alumnus['current_designation'] ?? 'N/A'); ?></p>
                                <!-- !! NEW: Displaying the email !! -->
                                <?php if (!empty($alumnus['email'])): ?>
                                <p class="detail-item">
                                    <strong>Contact:</strong> 
                                    <a href="mailto:<?php echo htmlspecialchars($alumnus['email']); ?>" class="alumni-email-link"><?php echo htmlspecialchars($alumnus['email']); ?></a>
                                </p>
                                <?php endif; ?>
                                <p class="detail-item"><strong>Skills:</strong> <?php echo htmlspecialchars($alumnus['skills'] ?? 'Core Java, SQL, Web Development'); ?></p>
                                <p class="detail-item"><strong>Experience:</strong> "<?php echo htmlspecialchars($alumnus['testimonial'] ?? 'Gained valuable industry experience.'); ?>"</p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align:center; grid-column: 1 / -1;">No alumni records found for this year.</p>
                <?php endif; ?>
            </div>

        <?php else: // View 1: Show all passing years ?>

            <h2 class="section-title">Select a Batch Year</h2>
            <div class="year-selection-grid">
                <?php if ($years_result && $years_result->num_rows > 0): ?>
                    <?php while($year_row = $years_result->fetch_assoc()): ?>
                        <a href="alumni.php?year=<?php echo $year_row['passing_year']; ?>" class="year-card">
                            <?php echo $year_row['passing_year']; ?>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align:center;">No alumni batches found yet.</p>
                <?php endif; ?>
            </div>

        <?php endif; ?>
        
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>