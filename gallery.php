<?php 
require_once 'includes/header.php'; 

// Fetch filter options (years and categories)
$years_result = $conn->query("SELECT DISTINCT YEAR(event_date) as year FROM gallery ORDER BY year DESC");
$categories_result = $conn->query("SELECT DISTINCT category FROM gallery ORDER BY category ASC");

// Build WHERE clause based on filters
$where_clauses = [];
$selected_year = 'all';
$selected_cat = 'all';

if (!empty($_GET['year']) && $_GET['year'] != 'all') {
    $selected_year = (int)$_GET['year'];
    $where_clauses[] = "YEAR(event_date) = $selected_year";
}
if (!empty($_GET['category']) && $_GET['category'] != 'all') {
    $selected_cat = $conn->real_escape_string($_GET['category']);
    $where_clauses[] = "category = '$selected_cat'";
}

$sql = "SELECT * FROM gallery";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY event_date DESC";

$gallery_result = $conn->query($sql);
?>
<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px; }
.gallery-section { padding: 80px 0; }
.gallery-filters { text-align: center; margin-bottom: 50px; display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; }
.filter-group { display: flex; align-items: center; gap: 10px; }
.filter-group label { font-weight: 700; }
.filter-group select { padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc; }
.gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
.gallery-item {
    background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow-sm);
    overflow: hidden; cursor: pointer; position: relative;
}
.gallery-item img { width: 100%; height: 250px; object-fit: cover; display: block; transition: transform 0.3s; }
.gallery-item:hover img { transform: scale(1.05); }
.gallery-item .overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: var(--white); padding: 40px 20px 20px 20px;
    transform: translateY(100%); transition: transform 0.3s;
}
.gallery-item:hover .overlay { transform: translateY(0); }
.gallery-item .overlay h3 { margin: 0; font-size: 1.2rem; }
.gallery-item .overlay p { margin: 5px 0 0; font-size: 0.9rem; opacity: 0.8; }
/* Lightbox styles */
.lightbox { display: none; position: fixed; z-index: 1001; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); justify-content: center; align-items: center; }
.lightbox img { max-width: 90%; max-height: 80%; }
.lightbox .close { position: absolute; top: 30px; right: 40px; color: #fff; font-size: 40px; cursor: pointer; }
</style>

<div class="page-header">
    <div class="container">
        <h1>Department Gallery</h1>
        <p>A collection of our cherished moments and achievements.</p>
    </div>
</div>

<section class="gallery-section">
    <div class="container">
        <form action="gallery.php" method="GET" class="gallery-filters">
            <div class="filter-group">
                <label for="year">Year:</label>
                <select name="year" id="year" onchange="this.form.submit()">
                    <option value="all">All Years</option>
                    <?php while($y = $years_result->fetch_assoc()) { echo "<option value='{$y['year']}' ".($selected_year == $y['year'] ? 'selected' : '').">{$y['year']}</option>"; } ?>
                </select>
            </div>
            <div class="filter-group">
                <label for="category">Category:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="all">All Categories</option>
                    <?php while($c = $categories_result->fetch_assoc()) { echo "<option value='{$c['category']}' ".($selected_cat == $c['category'] ? 'selected' : '').">{$c['category']}</option>"; } ?>
                </select>
            </div>
        </form>

        <div class="gallery-grid">
            <?php if ($gallery_result && $gallery_result->num_rows > 0): ?>
                <?php while($item = $gallery_result->fetch_assoc()): ?>
                    <div class="gallery-item" onclick="openLightbox('uploads/gallery/<?php echo $item['image_path']; ?>')">
                        <img src="uploads/gallery/<?php echo $item['image_path']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <div class="overlay">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p><?php echo date('F Y', strtotime($item['event_date'])); ?> | <?php echo htmlspecialchars($item['category']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; grid-column: 1 / -1;">No images found for the selected filters.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox">
    <span class="close" onclick="closeLightbox()">&times;</span>
    <img id="lightbox-img" src="">
</div>

<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').style.display = 'flex';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
}
</script>

<?php require_once 'includes/footer.php'; ?>