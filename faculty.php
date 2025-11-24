<?php 
require_once 'includes/header.php'; 

// Fetch all active faculty members
$faculty_sql = "SELECT * FROM faculty WHERE status = 'active' ORDER BY designation";
$faculty_result = $conn->query($faculty_sql);
?>

<style>
/* --- Specific Styles for Faculty Page --- */
.page-header {
    background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px;
}
.faculty-section { padding: 80px 0; }
.faculty-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}
.faculty-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}
.faculty-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.faculty-photo {
    width: 100%;
    height: 250px;
    object-fit: cover;
    object-position: top;
}
.faculty-info { padding: 20px; }
.faculty-info h3 {
    margin-top: 0;
    font-size: 1.4rem;
    color: var(--primary-color);
}
.faculty-info .designation {
    font-weight: bold;
    color: var(--secondary-color);
    margin-bottom: 10px;
}
.faculty-info .expertise {
    font-style: italic;
    color: #666;
    margin-bottom: 15px;
}
.faculty-contact a {
    color: var(--text-color);
    margin: 0 10px;
    font-size: 0.9rem;
    text-decoration: none;
}
.faculty-contact a:hover { color: var(--secondary-color); }
</style>

<div class="page-header">
    <div class="container">
        <h1>Our Faculty</h1>
        <p>Meet our team of experienced and dedicated educators.</p>
    </div>
</div>

<section class="faculty-section">
    <div class="container">
        <div class="faculty-grid">
            <?php if ($faculty_result && $faculty_result->num_rows > 0): ?>
                <?php while($faculty = $faculty_result->fetch_assoc()): ?>
                    <div class="faculty-card">
                        <img src="uploads/faculty/<?php echo htmlspecialchars($faculty['photo'] ?? 'default.png'); ?>" 
                             alt="<?php echo htmlspecialchars($faculty['name']); ?>" 
                             class="faculty-photo"
                             onerror="this.onerror=null;this.src='uploads/faculty/default.png';">
                        <div class="faculty-info">
                            <h3><?php echo htmlspecialchars($faculty['name']); ?></h3>
                            <p class="designation"><?php echo htmlspecialchars($faculty['designation']); ?></p>
                            <p class="expertise"><?php echo htmlspecialchars($faculty['specialization']); ?></p>
                            <div class="faculty-contact">
                                <a href="mailto:<?php echo htmlspecialchars($faculty['email']); ?>"><i class="fas fa-envelope"></i> Email</a>
                                <a href="tel:<?php echo htmlspecialchars($faculty['phone']); ?>"><i class="fas fa-phone"></i> Call</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; grid-column: 1 / -1;">Faculty details are currently unavailable.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>