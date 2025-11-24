<?php 
require_once 'includes/header.php'; 
?>

<!-- इस पेज के लिए विशेष, नए और बेहतर CSS स्टाइल्स -->
<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 60px 20px; background-image: linear-gradient(rgba(13, 45, 82, 0.8), rgba(0, 86, 179, 0.7)), url('assets/images/banner2.jpg'); background-size: cover; background-position: center; }
.page-header h1 { font-size: 2.5rem; margin: 0; }
section { padding: 80px 0; }
.bg-white { background-color: var(--white); }

/* Vision & Mission Section */
.vision-mission-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; max-width: 1100px; margin: 0 auto; }
.vision-mission-grid img { width: 100%; border-radius: var(--radius); box-shadow: var(--shadow-md); }
.vision-mission-content h2 { font-size: 2rem; color: var(--primary-color); margin-bottom: 15px; border-bottom: 3px solid var(--secondary-color); padding-bottom: 10px; display: inline-block; }
.vision-mission-content p { color: #556070; line-height: 1.8; }

/* Program Features Section */
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
.feature-card { background: var(--white); padding: 30px; text-align: center; border-radius: var(--radius); box-shadow: var(--shadow-sm); border: 1px solid #eee; transition: all 0.3s ease; }
.feature-card:hover { transform: translateY(-10px); box-shadow: var(--shadow-md); }
.feature-card .icon { font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 20px; }
.feature-card h3 { font-size: 1.3rem; color: var(--primary-color); margin-bottom: 10px; }

/* Career Pathways Section */
.pathways-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
.pathways-list ul { list-style: none; padding: 0; }
.pathways-list li { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; font-size: 1.1rem; }
.pathways-list .icon { color: var(--secondary-color); font-size: 1.2rem; }

/* Benefits Timeline Section */
.benefits-timeline { position: relative; max-width: 900px; margin: 30px auto; }
.benefits-timeline::after { content: ''; position: absolute; width: 4px; background-color: var(--secondary-color); opacity: 0.3; top: 0; bottom: 0; left: 50%; margin-left: -2px; }
.benefit-container { padding: 10px 40px; position: relative; width: 50%; box-sizing: border-box; }
body { counter-reset: step; }
.benefit-container::before { content: counter(step); counter-increment: step; position: absolute; width: 40px; height: 40px; line-height: 40px; background-color: var(--white); border: 3px solid var(--secondary-color); border-radius: 50%; z-index: 1; font-weight: 700; text-align: center; color: var(--primary-color); }
.benefit-container.left { left: 0; }
.benefit-container.right { left: 50%; }
.left::before { right: 20px; }
.right::before { left: 20px; }
.benefit-content { padding: 20px 30px; background-color: var(--secondary-color); color: var(--white); position: relative; border-radius: var(--radius); box-shadow: var(--shadow-sm); }
.benefit-content.alt-color { background-color: var(--primary-color); }
.benefit-content h3 { margin-top: 0; font-size: 1.2rem; }

/* Premium Map Section */
.map-section {
    padding-bottom: 80px; 
}
.map-container {
    max-width: 1100px;
    margin: 0 auto;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    position: relative;
}
.map-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, rgba(13, 45, 82, 0.1), transparent);
    pointer-events: none;
}
.map-container iframe {
    width: 100%;
    height: 450px;
    border: none;
    display: block;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .vision-mission-grid, .pathways-grid { grid-template-columns: 1fr; }
    .vision-mission-grid .vision-mission-image { grid-row: 1; }
    .benefits-timeline::after { left: 31px; }
    .benefit-container { width: 100%; padding-left: 70px; padding-right: 15px; }
    .benefit-container.right { left: 0%; }
    .left::before, .right::before { left: 11px; }
}
</style>

<div class="page-header">
    <div class="container"><h1>About The Program</h1><p>A Skill-Based Degree for the Modern Tech Industry</p></div>
</div>

<!-- ... (Vision, Mission, Features, Benefits के सेक्शन वैसे ही रहेंगे) ... -->
<section class="about-vision-mission">
    <div class="container">
        <div class="vision-mission-grid">
            <div class="vision-mission-content"><h2>Our Vision</h2><p><?php echo htmlspecialchars($settings['about_vision'] ?? 'To be a center of excellence...'); ?></p></div>
            <div class="vision-mission-image"><img src="assets/images/vision.jpg" alt="Our Vision"></div>
        </div>
        <div class="vision-mission-grid" style="margin-top: 60px;">
            <div class="vision-mission-image"><img src="assets/images/mission.jpg" alt="Our Mission"></div>
            <div class="vision-mission-content"><h2>Our Mission</h2><p><?php echo htmlspecialchars($settings['about_mission'] ?? 'To provide skill-based education...'); ?></p></div>
        </div>
    </div>
</section>
<section id="program-features" class="bg-white">
    <div class="container">
        <h2 class="section-title">About the B.Voc Degree</h2>
        <p style="text-align: center; max-width: 800px; margin: -20px auto 50px auto;">The Bachelor of Vocation (B.Voc) degree is an innovative program launched by the UGC to bridge the gap between academia and industry.</p>
        <div class="features-grid">
            <div class="feature-card"><div class="icon"><i class="fas fa-check-circle"></i></div><h3>Fully Recognized</h3><p>Recognized by UGC & AICTE, graduates are eligible for postgraduate studies like M.Voc, MBA, and Ph.D. programs.</p></div>
            <div class="feature-card"><div class="icon"><i class="fas fa-laptop-code"></i></div><h3>Skill-Focused</h3><p>Designed with 60-70% practical components, including lab work, internships, and hands-on training.</p></div>
            <div class="feature-card"><div class="icon"><i class="fas fa-sign-out-alt"></i></div><h3>Multiple Exits</h3><p>Allows students to exit with a Diploma or Advanced Diploma, providing flexibility to earn while they learn.</p></div>
            <div class="feature-card"><div class="icon"><i class="fas fa-industry"></i></div><h3>Industry Integrated</h3><p>Developed in partnership with industry leaders to meet market demands and enhance placement opportunities.</p></div>
        </div>
    </div>
</section>
<section id="bvoc-benefits">
    <div class="container">
        <h2 class="section-title">Benefits of B.Voc Program</h2>
        <div class="benefits-timeline">
            <div class="benefit-container left"><div class="benefit-content"><h3>Based On German Education System</h3><p>Based on Germany's Dual VET education system, the most advanced in the world.</p></div></div>
            <div class="benefit-container right"><div class="benefit-content alt-color"><h3>Based On NEP 2020</h3><p>Specially mentioned in National Education Policy 2020 and promoted by the Government.</p></div></div>
            <div class="benefit-container left"><div class="benefit-content"><h3>UGC Approved</h3><p>Directly approved with University Grants Commission and NSQF both.</p></div></div>
            <div class="benefit-container right"><div class="benefit-content alt-color"><h3>More Practical, Less Theory</h3><p>Features 60% Practical Experience Based learning and only 40% theory.</p></div></div>
            <div class="benefit-container left"><div class="benefit-content"><h3>Highest Employability</h3><p>Graduates come out with experience, are never considered freshers, and get placed easily.</p></div></div>
            <div class="benefit-container right"><div class="benefit-content alt-color"><h3>Earn While You Learn</h3><p>Students have the opportunity to earn a stipend during their internships at companies.</p></div></div>
        </div>
    </div>
</section>



<?php require_once 'includes/footer.php'; ?>