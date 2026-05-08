<?php
$loggedIn = $data['is_logged_in'] ?? false;
$avatar = $data['avatar'] ?? 'https://placehold.co/40x40';
$notifications = $data['notifications'] ?? [];
$chats = $data['chat_users'] ?? [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $data['page_title'] ?? 'NABTA' ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital@1&family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top border-bottom border-success-subtle">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2" href="/">
      <span class="fw-bold font-serif">🌿 NABTA</span>
    </a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav" type="button">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/marketplace">Marketplace</a></li>
        <li class="nav-item"><a class="nav-link" href="/community">Community</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <button class="icon-btn spring" id="themeToggle" title="Toggle theme">🌞</button>
        <?php if (!$loggedIn): ?>
          <a href="/auth/register" class="btn btn-link text-decoration-none">Register</a>
          <a href="/auth/login" class="btn btn-clay rounded-pill px-3">Login</a>
        <?php else: ?>
          <a href="/item/closet" class="btn btn-sage-outline rounded-pill px-3">My Closet</a>
          <a href="/order" class="btn btn-sage-outline rounded-pill px-3">Orders</a>
          <a href="/swap" class="btn btn-sage-outline rounded-pill px-3">Swaps</a>
          <button class="icon-btn spring" data-bs-toggle="offcanvas" data-bs-target="#chatOffcanvas" title="Chat">💬</button>
          <div class="dropdown">
            <button class="icon-btn spring" data-bs-toggle="dropdown" title="Notifications">🔔</button>
            <div class="dropdown-menu dropdown-menu-end p-0 notifications-dropdown card-eco border-0">
              <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <strong>Notifications</strong>
                <div class="d-flex gap-2 align-items-center">
                  <form method="post" action="/notification/read-all" class="d-inline"><button class="btn btn-sm btn-link p-0" type="submit">Mark all as read</button></form>
                </div>
              </div>
              <div class="p-3" style="max-height:300px;overflow-y:auto;">
                <?php foreach ($notifications as $notification): ?>
                  <div class="card card-body mb-2 spring">
                    <div class="small">
                      <div><?= is_array($notification) ? ($notification['msg_text'] ?? '') : '' ?></div>
                      <div class="text-muted"><?= is_array($notification) ? ($notification['created_at'] ?? 'Now') : 'Now' ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (empty($notifications)): ?>
                  <p class="small text-muted mb-0">No new notifications.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <a href="/profile" class="icon-btn spring p-0 overflow-hidden" title="Profile">
            <img src="<?= $avatar ?>" alt="Avatar" width="36" height="36" class="rounded-circle object-fit-cover">
          </a>
          <a href="/auth/logout" class="btn btn-sm btn-outline-secondary rounded-pill">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="chatOffcanvas">
  <div class="offcanvas-header border-bottom">
    <h5 class="mb-0">Chat</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="chat-pane d-flex h-100">
      <div class="border-end p-3 chat-list" style="min-width: 36%;">
        <?php foreach ($chats as $chat): ?>
          <button class="btn w-100 text-start mb-2 card-eco p-2">
            <div class="d-flex justify-content-between">
              <span><?= $chat['name'] ?? 'NABTA User' ?></span>
              <span class="online-dot"></span>
            </div>
            <small class="text-muted"><?= $chat['last_message'] ?? 'Ready to swap?' ?></small>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="flex-grow-1 p-3 d-flex flex-column">
        <div class="flex-grow-1 card-eco p-3 mb-2 small">Conversation window</div>
        <div class="input-group">
          <input class="form-control" placeholder="Type a message...">
          <button class="btn btn-clay">Send</button>
        </div>
      </div>
    </div>
  </div>
</div>

<main class="container py-4">