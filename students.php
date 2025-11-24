<?php 
require_once 'includes/header.php'; 

// Get the selected semester from the URL, default to 1 if not set
$selected_semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 1;

// Fetch students for the selected semester
$students_sql = "SELECT * FROM students WHERE semester = ? AND status = 'active' ORDER BY name";
$stmt = $conn->prepare($students_sql);
$stmt->bind_param("i", $selected_semester);
$stmt->execute();
$students_result = $stmt->get_result();
?>

<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px; }
.students-section { padding: 80px 0; }
.semester-filters { text-align: center; margin-bottom: 50px; }
.semester-filters .btn { margin: 5px; background-color: #f0f0f0; color: var(--primary-color); }
.semester-filters .btn.active { background-color: var(--secondary-color); color: #fff; }
.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 30px;
}
.student-card {
    background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;
}
.student-card img {
    width: 100%; height: 220px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;
}
.student-info { padding: 15px; }
.student-info h3 { font-size: 1.2rem; margin: 10px 0 5px; color: var(--primary-color); }
.student-info p { margin: 0; color: #666; font-size: 0.9rem; }
</style>

<div class="page-header">
    <div class="container">
        <h1>Our Students</h1>
        <p>The bright minds shaping the future of technology.</p>
    </div>
</div>

<section class="students-section">
    <div class="container">
        <div class="semester-filters">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <a href="students.php?semester=<?php echo $i; ?>" class="btn <?php if ($i == $selected_semester) echo 'active'; ?>">
                    Semester <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>

        <h2 class="section-title">Students of Semester <?php echo $selected_semester; ?></h2>
        
        <div class="students-grid">
            <?php if ($students_result && $students_result->num_rows > 0): ?>
                <?php while($student = $students_result->fetch_assoc()): ?>
                    <div class="student-card">
                        <img src="uploads/students/<?php echo htmlspecialchars($student['photo'] ?? 'default.png'); ?>" 
                             alt="<?php echo htmlspecialchars($student['name']); ?>"
                             onerror="this.onerror=null;this.src='uploads/students/default.png';">
                        <div class="student-info">
                            <h3><?php echo htmlspecialchars($student['name']); ?></h3>
                            <p>Roll No: <?php echo htmlspecialchars($student['roll_number']); ?></p>
                            <p><?php echo htmlspecialchars($student['email']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; grid-column: 1 / -1;">No students found for this semester.</p>
            <?php endif; ?>
            <?php $stmt->close(); ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>