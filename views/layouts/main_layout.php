<?php
use App\Helpers\Url;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'DaNang PC - Linh Kiện & Phụ Kiện Máy Tính Đà Nẵng') ?></title>
  
  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= Url::asset('images/logo.svg') ?>">

  <!-- FontAwesome Icons (Free CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="<?= Url::asset('css/main.css') ?>">
</head>
<body>

  <!-- Site Header -->
  <header class="site-header">
    <?php include dirname(__DIR__) . '/layouts/header.php'; ?>
  </header>

  <!-- Flash Alerts -->
  <div class="container" style="margin-top: 20px;">
    <?php if (!empty($flashSuccess)): ?>
      <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <div><?= $flashSuccess ?></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
      <div class="alert alert-error">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div><?= $flashError ?></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($flashInfo)): ?>
      <div class="alert alert-info">
        <i class="fa-solid fa-circle-info"></i>
        <div><?= $flashInfo ?></div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Main Content -->
  <main class="main-content">
    <?= $content ?? '' ?>
  </main>

  <!-- Site Footer -->
  <?php include dirname(__DIR__) . '/layouts/footer.php'; ?>

  <!-- Main JavaScript -->
  <script src="<?= Url::asset('js/main.js') ?>"></script>
</body>
</html>
