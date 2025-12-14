<section class="how-it-works" style="margin-bottom: 40px;">
    <h2 style="color: #3b82f6; margin-bottom: 25px; font-weight: bold;">HOW IT WORKS</h2>

    <div class="steps-container" style="display: flex; flex-direction: column; gap: 20px;">
        <?php foreach ($steps as $step): ?>
            <div class="step-item" style="display: flex; gap: 15px; align-items: flex-start;">
                
                <div style="
                    background: white; 
                    border-radius: 8px; 
                    width: 50px; 
                    height: 50px; 
                    flex-shrink: 0; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center;
                    font-size: 1.5rem;">
                    <?= $step['icon'] ?? '😀' ?> 
                </div>

                <div>
                    <h3 style="color: #ff0055; margin: 0 0 5px 0; font-size: 1rem; font-weight: bold;">
                        <?= $step['title'] ?>
                    </h3>
                    <p style="color: #ccc; margin: 0; font-size: 0.85rem; line-height: 1.4;">
                        <?= $step['description'] ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>