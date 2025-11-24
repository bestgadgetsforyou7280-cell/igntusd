<?php 
require_once 'includes/header.php'; 

// Fetch active notices, newest first
$notices_sql = "SELECT * FROM notices WHERE status = 'active' ORDER BY notice_date DESC";
$notices_result = $conn->query($notices_sql);
?>
<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px; }
.notices-section { padding: 80px 0; }
.notice-card {
    background: #fff; border: 1px solid #ddd; border-left-width: 5px;
    padding: 20px; margin-bottom: 20px; border-radius: 5px;
}
.notice-card.important { border-left-color: #e67e22; }
.notice-card.general { border-left-color: var(--secondary-color); }
.notice-card h3 { margin-top: 0; color: var(--primary-color); }
.notice-meta { font-size: 0.9rem; color: #777; margin-bottom: 10px; }
.notice-meta span { margin-right: 15px; }
.notice-content a { color: var(--secondary-color); text-decoration: none; font-weight: bold; }
</style>

<div class="page-header">
    <div class="container">
        <h1>Notices & Announcements</h1>
        <p>Stay updated with the latest news and events.</p>
    </div>
</div>

<section class="notices-section">
    <div class="container">
        <?php if ($notices_result && $notices_result->num_rows > 0): ?>
            <?php while($notice = $notices_result->fetch_assoc()): ?>
                <div class="notice-card <?php echo $notice['is_important'] ? 'important' : 'general'; ?>">
                    <h3><?php echo htmlspecialchars($notice['title']); ?></h3>
                    <div class="notice-meta">
                        <span><i class="fas fa-calendar-alt"></i> Posted on: <?php echo date('d M, Y', strtotime($notice['notice_date'])); ?></span>
                        <span><i class="fas fa-tag"></i> Type: <?php echo ucfirst(htmlspecialchars($notice['notice_type'])); ?></span>
                    </div>
                    <div class="notice-content">
                        <p><?php echo nl2br(htmlspecialchars(substr($notice['description'], 0, 200))); ?>...</p>
                        <?php if(!empty($notice['attachment'])): ?>
                            <a href="uploads/notices/<?php echo htmlspecialchars($notice['attachment']); ?>" target="_blank">Download Attachment</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">No notices to display at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>