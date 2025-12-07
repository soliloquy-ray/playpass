<div class="article-card" style="flex:1; min-width:280px; background-color:#1a1a1a; border-radius:8px; overflow:hidden; border:1px solid #333;">
    <div style="padding:16px;">
        <span style="font-size:0.75rem; color:#888; text-transform:uppercase; letter-spacing:1px;"><?= $date ?></span>
        <h3 style="margin:8px 0; font-size:1.1rem; color:#fff;">
            <a href="/article/<?= esc($article['slug']) ?>" style="color:inherit; text-decoration:none;">
                <?= esc($article['title']) ?>
            </a>
        </h3>
        <p style="color:#ccc; font-size:0.9rem; line-height:1.5;">
            <?= esc($excerpt) ?>
        </p>
        <a href="/article/<?= esc($article['slug']) ?>" style="color:#d8369f; font-size:0.9rem; text-decoration:none; font-weight:bold;">
            Read Story &rarr;
        </a>
    </div>
</div>