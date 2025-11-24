<?php 
require_once 'includes/header.php'; 

// Handle form submission
$message = '';
$message_type = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $batch_year = sanitize_input($_POST['batch_year']);
    $story = sanitize_input($_POST['story']);
    $image_name = null;

    // Basic validation
    if (empty($name) || empty($batch_year) || empty($story)) {
        $message = "Please fill in all required fields.";
        $message_type = 'error';
    } else {
        // Handle optional image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = 'uploads/memories/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = time() . '_' . uniqid() . '.' . $file_extension;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                $image_name = null; // Reset if upload fails
            }
        }
        
        $sql = "INSERT INTO memories (name, batch_year, story, image_path) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $batch_year, $story, $image_name);
        
        if ($stmt->execute()) {
            $message = "Thank you for sharing your memory! It will be visible on the gallery after admin approval.";
            $message_type = 'success';
        } else {
            $message = "Sorry, there was an error submitting your memory. Please try again.";
            $message_type = 'error';
        }
    }
}
?>

<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px; }
.form-section { padding: 80px 0; }
.memory-form { max-width: 700px; margin: 0 auto; background: var(--white); padding: 30px; border-radius: var(--radius); box-shadow: var(--shadow-md); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 700; color: var(--primary-color); }
.form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
.form-group textarea { min-height: 150px; resize: vertical; }
.form-message { padding: 15px; margin-bottom: 20px; border-radius: 5px; }
.form-message.success { background-color: #d4edda; color: #155724; }
.form-message.error { background-color: #f8d7da; color: #721c24; }
</style>

<div class="page-header">
    <div class="container">
        <h1>Share Your Department Memory</h1>
        <p>Your stories and pictures make our community stronger.</p>
    </div>
</div>

<section class="form-section">
    <div class="container">
        <form class="memory-form" action="share_memory.php" method="POST" enctype="multipart/form-data">
            <h2 class="section-title" style="font-size: 2rem;">Tell Us Your Story</h2>
            
            <?php if (!empty($message)): ?>
                <div class="form-message <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Your Full Name *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="batch_year">Your Batch (e.g., 2022-2025) *</label>
                <input type="text" id="batch_year" name="batch_year" required>
            </div>
            <div class="form-group">
                <label for="story">Your Story / Memory *</label>
                <textarea id="story" name="story" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Upload a Picture (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Submit Memory</button>
        </form>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>