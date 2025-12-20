<section class="section-component" style="margin-bottom: 40px;">
    <div class="redeem-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 class="section-label" style="margin:0;">How To Redeem</h3>
        </div>

        <div class="redeem-steps-list">
            <div class="redeem-step">
                <div class="step-number-box"></div> <span class="step-text">Buy Pin</span>
            </div>
            <div class="redeem-step">
                <div class="step-number-box"></div> <span class="step-text">Receive Code via SMS/Email</span>
            </div>
            <div class="redeem-step">
                <div class="step-number-box"></div> <span class="step-text">Enter Code on <?= $brandName ?> App/Website. /Redeem.</span>
            </div>
        </div>

        <div style="position: absolute; bottom: 10px; right: 15px; color: #555;">
            <i class="fa-solid fa-chevron-up"></i>
        </div>
    </div>

    <button class="btn-checkout" onclick="handleCheckout()">Checkout</button>
</section>