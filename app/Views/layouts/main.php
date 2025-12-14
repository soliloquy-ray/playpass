<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Playpass') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="position: relative;">

    <?= view_cell('App\Cells\HeaderCell::render') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= view_cell('App\Cells\FooterCell::render') ?>

    <script src="/assets/js/carousel.js"></script>
    <script src="/assets/js/ui.js"></script>
</body>
</html>