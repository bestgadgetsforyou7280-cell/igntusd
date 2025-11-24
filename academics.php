<?php 
require_once 'includes/header.php'; 

// Fetch collaboration partners from the database
$collab_sql = "SELECT * FROM collaborations ORDER BY company_name";
$collab_result = $conn->query($collab_sql);
?>

<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 60px 20px; }
.page-header h1 { font-size: 2.5rem; margin: 0; }
section { padding: 80px 0; }
.bg-white { background-color: var(--white); }

/* Collaborations Section */
.logos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 30px;
}
.logo-card {
    background: var(--white);
    padding: 20px;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100px;
    transition: all 0.3s ease;
}
.logo-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
.logo-card img { max-width: 100%; max-height: 60px; object-fit: contain; filter: grayscale(100%); opacity: 0.8; transition: all 0.3s ease; }
.logo-card:hover img { filter: grayscale(0%); opacity: 1; }

/* Entrepreneurship Section */
.entrepreneurship-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
.entrepreneurship-grid img { width: 100%; border-radius: var(--radius); }
.entrepreneurship-list ul { list-style: none; padding: 0; }
.entrepreneurship-list li { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 20px; font-size: 1.1rem; }
.entrepreneurship-list .icon { color: var(--secondary-color); font-size: 1.4rem; margin-top: 4px; }

@media (max-width: 768px) {
    .entrepreneurship-grid { grid-template-columns: 1fr; }
}
</style>

<div class="page-header">
    <div class="container"><h1>Academics & Industry Interface</h1><p>Bridging Theory with Practical Application</p></div>
</div>

<!-- Industrial Collaborations Section -->
<section id="collaborations">
    <div class="container">
        <h2 class="section-title">Industrial Collaborations</h2>
        <p style="text-align: center; max-width: 800px; margin: -20px auto 50px auto;">Our curriculum is designed and updated in collaboration with leading industry partners and Sector Skill Councils (SSCs) to ensure our students learn the most relevant technologies and skills demanded by the modern workforce.</p>
        <div class="logos-grid">
            <?php if ($collab_result && $collab_result->num_rows > 0): ?>
                <?php while($partner = $collab_result->fetch_assoc()): ?>
                    <a href="<?php echo htmlspecialchars($partner['website_url']); ?>" target="_blank" class="logo-card" title="<?php echo htmlspecialchars($partner['company_name']); ?>">
                        <img src="uploads/collaborations/<?php echo htmlspecialchars($partner['company_logo']); ?>" alt="<?php echo htmlspecialchars($partner['company_name']); ?>">
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Industry partner details are being updated.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Entrepreneurship Support Section -->
<section id="entrepreneurship" class="bg-white">
    <div class="container">
        <h2 class="section-title">Fostering Entrepreneurship</h2>
        <div class="entrepreneurship-grid">
            <div class="entrepreneurship-content">
                <p>Beyond preparing students for jobs, our B.Voc program actively encourages an entrepreneurial mindset. We provide the resources, mentorship, and foundational skills necessary to transform innovative ideas into successful business ventures.</p>
                <div class="entrepreneurship-list">
                    <ul>
                        <li><span class="icon"><i class="fas fa-lightbulb"></i></span><div><strong>Innovation Hub:</strong> A dedicated space for students to brainstorm, collaborate, and develop their startup ideas.</div></li>
                        <li><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span><div><strong>Expert Mentorship:</strong> Access to successful entrepreneurs and industry veterans who provide guidance and support.</div></li>
                        <li><span class="icon"><i class="fas fa-cogs"></i></span><div><strong>Skill Development:</strong> Courses on business planning, financial management, and marketing to equip students with essential business acumen.</div></li>
                    </ul>
                </div>
            </div>
            <div class="entrepreneurship-image">
                <img src="assets/images/entrepreneurship.jpg" alt="Students collaborating on a project">
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>