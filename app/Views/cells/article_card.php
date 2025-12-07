<div class="article-card">
    <div class="article-content">
        <div class="article-date"><?= $date ?></div>
        <h3 class="article-title">
            <a href="/article/<?= esc($article['slug']) ?>">
                <?= esc($article['title']) ?>
            </a>
        </h3>
        <p class="article-excerpt">
            <?= esc($excerpt) ?>
        </p>
        <a href="/article/<?= esc($article['slug']) ?>" class="article-link">
            Read Story &rarr;
        </a>
    </div>
</div>