<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="d-flex flex-column align-items-center justify-content-center text-center">
        <?= sprintf(file_get_contents(ROOT_PATH . ASSETS_URL_PATH . 'images/thank_you.svg'), 'var(--primary)', 'col-10 col-md-6 col-lg-4 mb-4') ?>

        <h1><?= l('pay_thank_you.header') ?></h1>

        <?php if(isset($_GET['code_days'])): ?>
            <p class="text-muted"><?= sprintf(l('pay_thank_you.plan_redeemed'), (int) $_GET['code_days']) ?></p>
        <?php elseif(isset($_GET['payment_processor']) && in_array($_GET['payment_processor'], ['paypal', 'stripe', 'coinbase', 'payu', 'paystack', 'razorpay', 'mollie', 'yookassa', 'crypto_com', 'paddle', 'midtrans', 'flutterwave'])): ?>
            <p class="text-muted"><?= l('pay_thank_you.plan_custom_will_start') ?></p>
        <?php elseif(isset($_GET['payment_processor']) && $_GET['payment_processor'] == 'offline_payment'): ?>
            <p class="text-muted"><?= l('pay_thank_you.plan_custom_pending') ?></p>
        <?php else: ?>
            <p class="text-muted"><?= sprintf(l('pay_thank_you.plan_trial_start'), (int) $_GET['trial_days']) ?></p>
        <?php endif ?>

        <a id="dashboard" href="<?= url('dashboard') ?>" class="btn btn-outline-primary mt-4"><?= l('pay_thank_you.button') ?></a>
    </div>
</div>

<?php ob_start() ?>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-confetti@2.12.0/tsparticles.confetti.bundle.min.js"></script>

<script>
    'use strict';

    let rect = document.querySelector('#dashboard').getBoundingClientRect();

    confetti({
        particleCount: 100,
        spread: 70,
        origin: {
            x: (rect.x + (rect.width / 2)) / window.innerWidth,
            y: rect.y / window.innerHeight
        },
    });
</script>


<script>
    'use strict';

    let current_url = new URL(window.location.href);

    /* Here you could add your thank you page affiliate tracker code and use the already ready variables from below */
    let plan_id = current_url.searchParams.get('plan_id');

    /* The payment gateway name (ex: stripe) */
    let payment_processor = current_url.searchParams.get('payment_processor');

    /* The payment frequency (monthly, annual, lifetime) */
    let payment_frequency = current_url.searchParams.get('payment_frequency');

    /* The payment type (one_time, recurring) */
    let payment_type = current_url.searchParams.get('payment_type');

    /* Discount code, if any */
    let code = current_url.searchParams.get('code');

    /* Paid amount */
    let total_amount = current_url.searchParams.get('total_amount');

    /* Unique random identifier for this transaction */
    let unique_transaction_identifier = current_url.searchParams.get('unique_transaction_identifier');

    /* User id of the current logged in user */
    let user_id = current_url.searchParams.get('user_id');

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
