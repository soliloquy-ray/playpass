document.addEventListener('DOMContentLoaded', function() {
    
    const payBtn = document.getElementById('btn-pay');
    
    // 1. Handle Payment Submission
    document.getElementById('checkout-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // UX: Disable button to prevent double-click (Idempotency)
        payBtn.disabled = true;
        payBtn.innerText = 'Processing...';

        const payload = {
            user_id: document.getElementById('user-id').value,
            product_id: document.getElementById('product-id').value,
            recipient: document.getElementById('recipient').value,
            voucher_code: document.getElementById('voucher-code').value
        };

        try {
            // Add CSRF Token to headers (CI4 Security Requirement)
            const headers = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            headers[csrfName] = csrfHash;

            const response = await fetch(`${baseUrl}app/checkout/process`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            // Handle Response
            if (result.status === 'success') {
                // SCENARIO A: Instant Success (e-PIN received)
                Swal.fire({
                    title: 'Purchase Successful!',
                    html: `Your PIN is: <h2 style="color:#4CAF50">${result.pin_code}</h2><br>Saved to your account.`,
                    icon: 'success',
                    background: '#1a1a1a', // Match your dark theme
                    color: '#fff'
                }).then(() => {
                    window.location.href = `${baseUrl}app/account`;
                });

            } else if (result.status === 'pending') {
                // SCENARIO B: Async Processing
                Swal.fire({
                    title: 'Processing...',
                    text: result.message,
                    icon: 'info',
                    background: '#1a1a1a',
                    color: '#fff'
                });

            } else {
                // SCENARIO C: Error (Stock, Funds, etc.)
                throw new Error(result.message || 'Unknown error occurred');
            }

        } catch (error) {
            Swal.fire({
                title: 'Transaction Failed',
                text: error.message,
                icon: 'error',
                background: '#1a1a1a',
                color: '#fff'
            });
            payBtn.disabled = false;
            payBtn.innerText = 'CONFIRM PAYMENT';
        }
    });

    // 2. Simple Voucher Logic (Optional: Separate Endpoint)
    // You can add a fetch call here to validate the voucher *before* checkout
    // to update the "Total" display dynamically.
});