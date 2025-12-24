<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder to create initial site pages for Terms, Privacy Policy, and FAQ
 */
class SitePageSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'slug'             => 'terms',
                'title'            => 'Terms and Conditions',
                'content'          => $this->getTermsContent(),
                'meta_title'       => 'Terms and Conditions - Playpass',
                'meta_description' => 'Read our terms and conditions for using Playpass services.',
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'slug'             => 'privacy',
                'title'            => 'Privacy Policy',
                'content'          => $this->getPrivacyContent(),
                'meta_title'       => 'Privacy Policy - Playpass',
                'meta_description' => 'Learn how Playpass collects, uses, and protects your personal information.',
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'slug'             => 'faq',
                'title'            => 'Frequently Asked Questions',
                'content'          => $this->getFaqContent(),
                'meta_title'       => 'FAQ - Playpass',
                'meta_description' => 'Find answers to commonly asked questions about Playpass.',
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('site_pages')->insertBatch($data);
    }

    private function getTermsContent(): string
    {
        return <<<HTML
<h2>1. Acceptance of Terms</h2>
<p>By accessing and using Playpass, you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to these terms, please do not use our services.</p>

<h2>2. Use of Service</h2>
<p>Playpass provides a digital marketplace for gaming and entertainment products. You agree to use the service only for lawful purposes and in accordance with these Terms.</p>

<h2>3. User Account</h2>
<p>To access certain features, you may need to create an account. You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account.</p>

<h2>4. Purchases and Payments</h2>
<p>All purchases are final. Digital products are delivered electronically after successful payment. Refunds may be available in accordance with our refund policy.</p>

<h2>5. Points and Rewards</h2>
<p>Points earned through purchases or promotions are subject to expiration as stated in your account. Points have no cash value and cannot be transferred.</p>

<h2>6. Intellectual Property</h2>
<p>All content on Playpass, including text, graphics, logos, and software, is the property of Megamobile, Inc. or its licensors.</p>

<h2>7. Limitation of Liability</h2>
<p>Playpass is provided "as is" without warranties of any kind. We are not liable for any indirect, incidental, or consequential damages.</p>

<h2>8. Changes to Terms</h2>
<p>We reserve the right to modify these terms at any time. Continued use of the service after changes constitutes acceptance of the new terms.</p>

<h2>9. Contact Information</h2>
<p>For questions about these Terms, please contact us at our customer support.</p>
HTML;
    }

    private function getPrivacyContent(): string
    {
        return <<<HTML
<h2>1. Information We Collect</h2>
<p>We collect information you provide directly, such as your name, email address, phone number, and payment information when you create an account or make a purchase.</p>

<h2>2. How We Use Your Information</h2>
<p>We use your information to:</p>
<ul>
    <li>Process transactions and deliver products</li>
    <li>Send order confirmations and updates</li>
    <li>Provide customer support</li>
    <li>Send promotional offers (with your consent)</li>
    <li>Improve our services</li>
</ul>

<h2>3. Information Sharing</h2>
<p>We do not sell your personal information. We may share information with service providers who assist in our operations, subject to confidentiality agreements.</p>

<h2>4. Data Security</h2>
<p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure.</p>

<h2>5. Cookies</h2>
<p>We use cookies to improve your browsing experience, analyze site traffic, and personalize content. You can control cookie settings through your browser.</p>

<h2>6. Your Rights</h2>
<p>You have the right to access, correct, or delete your personal information. Contact us to exercise these rights.</p>

<h2>7. Children's Privacy</h2>
<p>Our services are not intended for children under 18. We do not knowingly collect information from minors.</p>

<h2>8. Changes to This Policy</h2>
<p>We may update this privacy policy periodically. We will notify you of significant changes through our website or email.</p>

<h2>9. Contact Us</h2>
<p>For privacy-related inquiries, please contact our customer support team.</p>
HTML;
    }

    private function getFaqContent(): string
    {
        return <<<HTML
<div class="faq-item">
    <div class="faq-question">How do I make a purchase?</div>
    <div class="faq-answer">Simply browse our products, add items to your cart, and proceed to checkout. We accept various payment methods including credit cards and e-wallets.</div>
</div>

<div class="faq-item">
    <div class="faq-question">How will I receive my digital products?</div>
    <div class="faq-answer">Digital products are delivered instantly via email and SMS after successful payment. You can also view your purchases in your account dashboard.</div>
</div>

<div class="faq-item">
    <div class="faq-question">What are Playpass Points?</div>
    <div class="faq-answer">Playpass Points are rewards you earn on purchases. You can use points to get discounts on future orders. Points are earned at a rate determined by active promotions.</div>
</div>

<div class="faq-item">
    <div class="faq-question">Do points expire?</div>
    <div class="faq-answer">Yes, points expire after 12 months of inactivity. Birthday bonus points expire at the end of your birthday month.</div>
</div>

<div class="faq-item">
    <div class="faq-question">Can I get a refund?</div>
    <div class="faq-answer">Due to the nature of digital products, all sales are generally final. However, if you experience issues with your purchase, please contact our customer support.</div>
</div>

<div class="faq-item">
    <div class="faq-question">How do I contact customer support?</div>
    <div class="faq-answer">You can reach us through the contact form on our website or call us at the number listed in our footer.</div>
</div>

<div class="faq-item">
    <div class="faq-question">Is my payment information secure?</div>
    <div class="faq-answer">Yes, we use industry-standard encryption and secure payment processors to protect your payment information.</div>
</div>

<div class="faq-item">
    <div class="faq-question">Can I send a gift to someone?</div>
    <div class="faq-answer">Yes! During checkout, you can choose to send your purchase as a gift. Enter the recipient's email and phone number, and they will receive the voucher directly.</div>
</div>
HTML;
    }
}
