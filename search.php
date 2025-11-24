<?php 
require_once 'includes/header.php'; 

// URL से सर्च क्वेरी को सुरक्षित रूप से प्राप्त करें
$search_query = isset($_GET['query']) ? sanitize_input($_GET['query']) : '';

// SQL LIKE क्वेरी के लिए सर्च टर्म तैयार करें
$search_term = '%' . $search_query . '%';

// --- सभी महत्वपूर्ण टेबल में खोजें ---

$all_results = [];

if (!empty($search_query)) {
    // 1. Notices में खोजें
    $notices_sql = "SELECT 'Notice' as type, title, description, 'notices.php' as link FROM notices WHERE title LIKE ? OR description LIKE ? OR notice_type LIKE ?";
    $stmt_notices = $conn->prepare($notices_sql);
    $stmt_notices->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt_notices->execute();
    $notices_result = $stmt_notices->get_result();
    while ($row = $notices_result->fetch_assoc()) { $all_results[] = $row; }

    // 2. Faculty में खोजें
    $faculty_sql = "SELECT 'Faculty' as type, name as title, designation as description, 'faculty.php' as link FROM faculty WHERE name LIKE ? OR specialization LIKE ? OR designation LIKE ?";
    $stmt_faculty = $conn->prepare($faculty_sql);
    $stmt_faculty->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt_faculty->execute();
    $faculty_result = $stmt_faculty->get_result();
    while ($row = $faculty_result->fetch_assoc()) { $all_results[] = $row; }

    // 3. Curriculum में खोजें
    $curriculum_sql = "SELECT 'Curriculum' as type, subject_name as title, CONCAT('Semester: ', semester, ' | Type: ', subject_type) as description, 'curriculum.php' as link FROM curriculum WHERE subject_name LIKE ? OR subject_code LIKE ? OR topics LIKE ?";
    $stmt_curriculum = $conn->prepare($curriculum_sql);
    $stmt_curriculum->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt_curriculum->execute();
    $curriculum_result = $stmt_curriculum->get_result();
    while ($row = $curriculum_result->fetch_assoc()) { $all_results[] = $row; }
    
    // !! NEW: 4. Students में खोजें (नाम या रोल नंबर से) !!
    $students_sql = "SELECT 'Student' as type, name as title, CONCAT('Semester: ', semester, ' | Roll No: ', roll_number) as description, 'students.php' as link FROM students WHERE status = 'active' AND (name LIKE ? OR roll_number LIKE ?)";
    $stmt_students = $conn->prepare($students_sql);
    $stmt_students->bind_param("ss", $search_term, $search_term);
    $stmt_students->execute();
    $students_result = $stmt_students->get_result();
    while ($row = $students_result->fetch_assoc()) { $all_results[] = $row; }

    // !! NEW: 5. Alumni में खोजें (नाम, कंपनी, या पद से) !!
    $alumni_sql = "SELECT 'Alumni' as type, s.name as title, CONCAT('Company: ', a.current_company, ' | Designation: ', a.current_designation) as description, 'alumni.php' as link 
                   FROM alumni a 
                   JOIN students s ON a.student_id = s.id 
                   WHERE s.name LIKE ? OR a.current_company LIKE ? OR a.current_designation LIKE ?";
    $stmt_alumni = $conn->prepare($alumni_sql);
    $stmt_alumni->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt_alumni->execute();
    $alumni_result = $stmt_alumni->get_result();
    while ($row = $alumni_result->fetch_assoc()) { $all_results[] = $row; }
}

?>

<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px; }
.search-results-section { padding: 80px 0; }
.search-result-card {
    background: var(--white); border: 1px solid #eee; border-left: 4px solid var(--secondary-color);
    padding: 25px; margin-bottom: 20px; border-radius: var(--radius); box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}
.search-result-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
.result-type { display: inline-block; background: var(--secondary-color); color: var(--white); padding: 5px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; }
.result-title { margin: 15px 0 5px 0; font-size: 1.4rem; color: var(--primary-color); }
.result-description { color: #556070; margin-bottom: 15px; }
.result-link { font-weight: 700; color: var(--primary-color); text-decoration: none; }
.result-link:hover { text-decoration: underline; }
</style>

<div class="page-header">
    <div class="container">
        <h1>Search Results</h1>
        <?php if (!empty($search_query)): ?>
            <p>Showing results for: "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
        <?php endif; ?>
    </div>
</div>

<section class="search-results-section">
    <div class="container" style="max-width: 900px;">
        
        <?php if (!empty($search_query) && count($all_results) > 0): ?>
            <p style="margin-bottom: 30px; font-size: 1.1rem;">Found <strong><?php echo count($all_results); ?></strong> matching result(s).</p>
            <?php foreach ($all_results as $result): ?>
                <div class="search-result-card">
                    <span class="result-type"><?php echo htmlspecialchars($result['type']); ?></span>
                    <h3 class="result-title"><?php echo htmlspecialchars($result['title']); ?></h3>
                    <p class="result-description">
                        <?php echo htmlspecialchars(substr($result['description'], 0, 150)) . '...'; ?>
                    </p>
                    <a href="<?php echo htmlspecialchars($result['link']); ?>" class="result-link">View Details &rarr;</a>
                </div>
            <?php endforeach; ?>
        <?php elseif (!empty($search_query)): ?>
            <p style="text-align:center; font-size: 1.2rem;">No results found for "<strong><?php echo htmlspecialchars($search_query); ?></strong>". Please try a different search term.</p>
        <?php else: ?>
            <p style="text-align:center; font-size: 1.2rem;">Please enter a search term in the header to find content on the site.</p>
        <?php endif; ?>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>