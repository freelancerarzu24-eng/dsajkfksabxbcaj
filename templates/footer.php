    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="fw-bold text-warning mb-3">PLAYPULSE</h4>
                    <p class="text-secondary">Your ultimate destination for sports news, live scores, and streaming guides. Stay updated with every game, every moment.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3"><?php echo $texts['sports']; ?></h5>
                    <ul class="list-unstyled">
                        <?php foreach(array_slice($nav_categories, 0, 5) as $f_cat): ?>
                            <li><a href="<?php echo SITE_URL; ?>/category/index.php?slug=<?php echo e($f_cat['slug']); ?>" class="text-secondary text-decoration-none"><?php echo e($f_cat['name_' . $current_lang]); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled text-secondary">
                        <li><a href="<?php echo SITE_URL; ?>/match-center/index.php" class="text-secondary text-decoration-none"><?php echo $texts['live_scores']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/where-to-watch/index.php" class="text-secondary text-decoration-none"><?php echo $texts['streaming']; ?></a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Terms of Use</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3"><?php echo $texts['newsletter']; ?></h5>
                    <p class="text-secondary">Subscribe to our newsletter for latest updates.</p>
                    <form action="<?php echo SITE_URL; ?>/includes/subscribe.php" method="POST" class="input-group"> <?php csrf_field(); ?>
                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        <button type="submit" class="btn btn-warning"><?php echo $texts['subscribe']; ?></button>
                    </form>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-secondary">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. <?php echo $texts['all_rights_reserved']; ?></p>
            </div>
        </div>
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
