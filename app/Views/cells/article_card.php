<div class="article-card">
    <div class="article-image">
        <img src="<?= $image ?>" alt="<?= $title ?>" loading="lazy">
    </div>
    <div class="article-content">
        <div class="article-category"><?= $category ?></div>
        <h3 class="article-title"><?= $title ?></h3>
        <p class="article-excerpt"><?= $excerpt ?></p>
        <div class="article-meta">
            <span class="article-author"><?= $author ?></span>
            <span class="article-date"><?= $date ?></span>
        </div>
        <a href="<?= $readMoreUrl ?>" class="article-link">Read More →</a>
    </div>
</div>