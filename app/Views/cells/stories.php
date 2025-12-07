<section class="stories">
    <div class="section-header">
        <h2><?= $title ?></h2>
        <p><?= $subtitle ?></p>
    </div>

    <div class="testimonials-carousel">
        <?php foreach ($testimonials as $testimonial): ?>
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="<?= $testimonial['avatar'] ?>" alt="<?= $testimonial['name'] ?>" class="testimonial-avatar">
                    <div class="testimonial-user">
                        <h4><?= $testimonial['name'] ?></h4>
                        <p><?= $testimonial['role'] ?></p>
                    </div>
                </div>

                <div class="testimonial-rating">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span class="star <?= $i < $testimonial['rating'] ? 'filled' : '' ?>">★</span>
                    <?php endfor; ?>
                </div>

                <p class="testimonial-quote"><?= $testimonial['quote'] ?></p>

                <div class="testimonial-footer">
                    <span class="badge"><?= $testimonial['badge'] ?></span>
                    <span class="testimonial-date"><?= $testimonial['date'] ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="carousel-nav">
        <button class="carousel-prev">←</button>
        <button class="carousel-next">→</button>
    </div>
</section>
