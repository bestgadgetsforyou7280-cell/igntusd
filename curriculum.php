<?php 
require_once 'includes/header.php'; 

// Fetch all curriculum subjects from the database, ordered by semester and code
$curriculum_sql = "SELECT * FROM curriculum ORDER BY semester, subject_code";
$curriculum_result = $conn->query($curriculum_sql);

// Group subjects by semester
$semesters = [];
if ($curriculum_result && $curriculum_result->num_rows > 0) {
    while($row = $curriculum_result->fetch_assoc()) {
        $semesters[$row['semester']][] = $row;
    }
}
?>

<!-- इस पेज के लिए विशेष CSS स्टाइल्स -->
<style>
.page-header {
    background-color: var(--primary-color);
    color: #fff;
    text-align: center;
    padding: 60px 20px;
    background-image: linear-gradient(rgba(13, 45, 82, 0.8), rgba(0, 86, 179, 0.7)), url('assets/images/hero-background.jpg');
    background-size: cover;
    background-position: center;
}
.page-header h1 {
    font-size: 2.5rem;
    margin: 0;
}
.curriculum-section {
    padding: 80px 0;
}
.program-layout-table {
    width: 100%;
    margin: 0 auto 60px;
    border-collapse: collapse;
    box-shadow: var(--shadow-sm);
    text-align: left;
    border-radius: var(--radius);
    overflow: hidden; /* Important for border-radius */
}
.program-layout-table th, .program-layout-table td {
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
}
.program-layout-table thead th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 700;
}
.program-layout-table tbody tr:nth-child(even) {
    background-color: var(--white);
}
.program-layout-table tbody tr:nth-child(odd) {
    background-color: var(--bg-light);
}

.semester-accordion .semester-card {
    background: var(--white);
    margin-bottom: 15px;
    border: 1px solid #e0e0e0;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: box-shadow 0.3s ease;
}
.semester-accordion .semester-card:hover {
    box-shadow: var(--shadow-md);
}
.semester-header {
    background-color: var(--white);
    padding: 20px;
    cursor: pointer;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.3s;
}
.semester-header:hover {
    background-color: var(--bg-light);
}

.subject-table {
    width: 100%;
    border-collapse: collapse;
}
.subject-table th, .subject-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
}
.subject-table thead th {
    background-color: var(--bg-light);
    font-weight: 700;
    text-align: left;
}
.subject-table tbody tr:last-child td {
    border-bottom: none;
}
</style>

<div class="page-header">
    <div class="container">
        <h1>Program Curriculum</h1>
        <p>A comprehensive, industry-aligned syllabus for holistic development.</p>
    </div>
</div>

<section class="curriculum-section">
    <div class="container">
        <h2 class="section-title">Program Layout</h2>
        <table class="program-layout-table">
            <thead>
                <tr>
                    <th>Duration</th>
                    <th>Semesters Covered</th>
                    <th>Award</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1 Year</td><td>Semester I & II</td><td>Diploma</td></tr>
                <tr><td>2 Years</td><td>Semester I – IV</td><td>Advanced Diploma</td></tr>
                <tr><td>3 Years</td><td>Semester I – VI</td><td>B.Voc. Degree</td></tr>
            </tbody>
        </table>

        <h2 class="section-title" style="margin-top: 80px;">Detailed Semester-wise Syllabus</h2>
        <div class="semester-accordion">
            <?php if (!empty($semesters)): ?>
                <?php foreach ($semesters as $semester_num => $subjects): ?>
                    <div class="semester-card">
                        <div class="semester-header">
                            <span>Semester <?php echo $semester_num; ?></span>
                            <span class="icon"></span>
                        </div>
                        <div class="semester-details">
                            <table class="subject-table">
                                <thead>
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Type</th>
                                        <th>Credits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($subject['subject_type'])); ?></td>
                                        <td><?php echo htmlspecialchars($subject['credits']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">Syllabus details are not available at the moment. Please check back later.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>