<?php 
require_once 'includes/header.php'; 

$message_status = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $subject = sanitize_input($_POST['subject']);
    $message = sanitize_input($_POST['message']);

    // Basic validation
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, ip_address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $stmt->bind_param("ssssss", $name, $email, $phone, $subject, $message, $ip_address);
        
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Your message has been sent successfully. We will get back to you shortly.";
        } else {
            $_SESSION['flash_message'] = "Sorry, there was an error sending your message. Please try again later.";
        }
        $stmt->close();
        redirect('contact.php');
    } else {
        $message_status = 'Please fill in all required fields correctly.';
    }
}
?>

<style>
.page-header { background-color: var(--primary-color); color: #fff; text-align: center; padding: 40px 20px; }
.contact-section { padding: 80px 0; }
.contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
.contact-info p { font-size: 1.1rem; }
.contact-info i { color: var(--secondary-color); width: 25px; }
.map-container { width: 100%; height: 400px; border-radius: 8px; overflow: hidden; }
.contact-form .form-group { margin-bottom: 20px; }
.contact-form label { display: block; margin-bottom: 5px; font-weight: bold; }
.contact-form input, .contact-form textarea {
    width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px;
}
.flash-message { padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #d4edda; color: #155724; }
@media (max-width: 768px) { .contact-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Get in touch with us.</p>
    </div>
</div>

<section class="contact-section">
    <div class="container">
        <?php 
        if (isset($_SESSION['flash_message'])) {
            echo '<div class="flash-message">' . $_SESSION['flash_message'] . '</div>';
            unset($_SESSION['flash_message']);
        }
        if (!empty($message_status)) {
             echo '<div class="flash-message" style="background-color:#f8d7da; color:#721c24;">' . $message_status . '</div>';
        }
        ?>
        <div class="contact-grid">
            <div class="contact-info">
                <h3>Department Address</h3>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($settings['site_address']); ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($settings['site_email']); ?></p>
                <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($settings['site_phone']); ?></p>
                
                <h3 style="margin-top: 40px;">Find Us On Map</h3>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3671.916001018318!2d81.6706830149673!3d23.02672498495147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39845a46740b9913%3A0xeb3f4c2d4a7541b!2sIndira%20Gandhi%20National%20Tribal%20University!5e0!3m2!1sen!2sin!4v1616161616161" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            <div class="contact-form">
                <h3>Send Us a Message</h3>
                <form id="contactForm" action="contact.php" method="POST">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>