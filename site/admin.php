<?php
// Redaktionsbereich: Blogbeiträge schreiben, bearbeiten, löschen.
require __DIR__ . '/lib.php';
start_session();
header('X-Frame-Options: DENY');
header('X-Robots-Tag: noindex');

$config = load_config();
$msg = ''; $err = '';
$needsSetup = empty($config['password_hash']);

// --- Ersteinrichtung: Passwort festlegen ---
if ($needsSetup && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'setup') {
    $setupFile = DATA_DIR . '/setup-code.php';
    $code = is_file($setupFile) ? (string)(include $setupFile) : '';
    if (!check_csrf()) { $err = 'Sitzung abgelaufen. Bitte noch einmal versuchen.'; }
    elseif ($code !== '' && !hash_equals($code, trim($_POST['code'] ?? ''))) { $err = 'Der Einrichtungscode stimmt nicht.'; }
    elseif (strlen($_POST['pw'] ?? '') < 10) { $err = 'Das Passwort muss mindestens 10 Zeichen lang sein.'; }
    elseif (($_POST['pw'] ?? '') !== ($_POST['pw2'] ?? '')) { $err = 'Die beiden Passwörter sind nicht gleich.'; }
    else {
        $config['password_hash'] = password_hash($_POST['pw'], PASSWORD_DEFAULT);
        save_config($config);
        @unlink($setupFile);
        session_regenerate_id(true);
        $_SESSION['auth'] = true; $needsSetup = false; $msg = 'Passwort gespeichert. Du bist angemeldet.';
    }
}

// --- Login / Logout ---
if (!$needsSetup && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $wait = ($_SESSION['fails'] ?? 0) >= 5 ? 30 - (time() - ($_SESSION['last_fail'] ?? 0)) : 0;
    if ($wait > 0) { $err = "Zu viele Versuche. Bitte $wait Sekunden warten."; }
    elseif (check_csrf() && password_verify($_POST['pw'] ?? '', $config['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['auth'] = true; $_SESSION['fails'] = 0;
    } else {
        $_SESSION['fails'] = ($_SESSION['fails'] ?? 0) + 1; $_SESSION['last_fail'] = time();
        $err = 'Falsches Passwort.';
    }
}
if (isset($_GET['logout'])) { $_SESSION = []; session_destroy(); header('Location: admin.php'); exit; }

$auth = !empty($_SESSION['auth']);
$posts = load_posts();
$edit = null;

// --- Aktionen (nur angemeldet) ---
if ($auth && $_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['action'] ?? '', ['save', 'delete', 'password'], true)) {
    if (!check_csrf()) { $err = 'Sitzung abgelaufen. Bitte die Seite neu laden.'; }
    else try {
        $action = $_POST['action'];
        if ($action === 'save') {
            $title = trim($_POST['title'] ?? ''); $body = trim($_POST['body'] ?? ''); $cat = $_POST['cat'] ?? 'cordis';
            if ($title === '' || $body === '') throw new RuntimeException('Bitte Überschrift und Text ausfüllen.');
            if (!isset(CATS[$cat])) $cat = 'cordis';
            $title = mb_substr($title, 0, 150); $body = mb_substr($body, 0, 10000);
            $id = $_POST['id'] ?? '';
            $img = store_image($_FILES['img'] ?? []);
            $found = false;
            foreach ($posts as &$p) {
                if ($p['id'] === $id) {
                    $found = true;
                    if ($img || !empty($_POST['remove_img'])) { delete_image($p['img'] ?? null); $p['img'] = $img; }
                    $p['title'] = $title; $p['body'] = $body; $p['cat'] = $cat;
                }
            }
            unset($p);
            if (!$found) $posts[] = ['id' => bin2hex(random_bytes(6)), 'title' => $title, 'body' => $body, 'cat' => $cat, 'date' => time(), 'img' => $img];
            save_posts($posts);
            $msg = $found ? 'Beitrag gespeichert.' : 'Beitrag veröffentlicht.';
        }
        if ($action === 'delete') {
            $id = $_POST['id'] ?? '';
            foreach ($posts as $p) if ($p['id'] === $id) delete_image($p['img'] ?? null);
            save_posts(array_filter($posts, function ($p) use ($id) { return $p['id'] !== $id; }));
            $msg = 'Beitrag gelöscht.';
        }
        if ($action === 'password') {
            if (!password_verify($_POST['old'] ?? '', $config['password_hash'])) throw new RuntimeException('Das alte Passwort stimmt nicht.');
            if (strlen($_POST['new'] ?? '') < 10) throw new RuntimeException('Das neue Passwort muss mindestens 10 Zeichen lang sein.');
            $config['password_hash'] = password_hash($_POST['new'], PASSWORD_DEFAULT); save_config($config);
            $msg = 'Passwort geändert.';
        }
        $posts = load_posts();
    } catch (RuntimeException $e) { $err = $e->getMessage(); }
}
if ($auth && isset($_GET['edit'])) {
    foreach ($posts as $p) if ($p['id'] === $_GET['edit']) $edit = $p;
}
?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Redaktion – Cordis Group</title>
<link rel="icon" href="assets/cordis-herz.png">
<link rel="stylesheet" href="assets/style.css">
<style>
body{background:var(--cream)} .wrap{max-width:860px}
.card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:26px;margin:22px 0}
.ok{background:#E3F2E8;color:#1B5E20;padding:12px 16px;border-radius:12px;margin-top:18px}
.bad{background:#FDE7E4;color:#B71C1C;padding:12px 16px;border-radius:12px;margin-top:18px}
table{width:100%;border-collapse:collapse} td{padding:10px 6px;border-top:1px solid var(--line);vertical-align:middle;font-size:15px}
.small{font-size:13px;color:var(--muted)} .inline{display:inline}
.hint{font-size:14px;color:var(--muted);background:var(--wash);border-radius:10px;padding:10px 12px;margin-top:14px}
.row-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px}
.linkbtn{background:none;border:0;color:var(--red);font-weight:600;cursor:pointer;font:inherit;padding:0}
details summary{cursor:pointer;font-weight:600}
</style>
</head>
<body>
<div class="stripe"></div>
<header class="nav"><div class="wrap">
  <a class="brand" href="index.php"><img src="assets/cordis-herz.png" alt="" style="height:34px">Redaktion</a>
  <?php if ($auth): ?><a href="?logout=1" class="small">Abmelden</a><?php endif; ?>
</div></header>
<div class="wrap">
<?php if ($msg): ?><div class="ok"><?= h($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="bad"><?= h($err) ?></div><?php endif; ?>

<?php if ($needsSetup): ?>
  <div class="card"><h2>Ersteinrichtung</h2>
    <p>Lege jetzt das Passwort für die Redaktion fest. Damit kann man Blogbeiträge schreiben. Mindestens 10 Zeichen.</p>
    <form class="form" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="setup">
      <?php if (is_file(DATA_DIR . '/setup-code.php')): ?><label>Einrichtungscode</label><input type="text" name="code" required autocomplete="off"><?php endif; ?>
      <label>Passwort</label><input type="password" name="pw" required minlength="10" autocomplete="new-password" >
      <label>Passwort wiederholen</label><input type="password" name="pw2" required minlength="10" autocomplete="new-password" >
      <p><button class="btn btn-ink">Passwort speichern</button></p>
    </form></div>

<?php elseif (!$auth): ?>
  <div class="card" style="max-width:420px;margin:60px auto"><h2>Anmelden</h2>
    <form class="form" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="login">
      <label>Passwort</label><input type="password" name="pw" required autofocus autocomplete="current-password" >
      <p><button class="btn btn-ink">Anmelden</button></p>
    </form></div>

<?php else: ?>
  <div class="card">
    <h2><?= $edit ? 'Beitrag bearbeiten' : 'Neuer Beitrag' ?></h2>
    <form class="form" method="post" enctype="multipart/form-data"><?= csrf_field() ?>
      <input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= h($edit['id'] ?? '') ?>">
      <label for="t">Überschrift</label><input id="t" type="text" name="title" maxlength="150" required value="<?= h($edit['title'] ?? '') ?>">
      <label for="c">Firma</label>
      <select id="c" name="cat"><?php foreach (CATS as $k => $v): ?><option value="<?= $k ?>" <?= ($edit['cat'] ?? 'cafe') === $k ? 'selected' : '' ?>><?= h($v) ?></option><?php endforeach; ?></select>
      <label for="b">Text</label><textarea id="b" name="body" maxlength="10000" required><?= h($edit['body'] ?? '') ?></textarea>
      <label for="i">Foto (optional)</label>
      <?php if (!empty($edit['img'])): ?><p class="small"><img src="<?= h($edit['img']) ?>" alt="" style="height:70px;border-radius:8px;vertical-align:middle"> <label class="inline"><input type="checkbox" name="remove_img" value="1"> Foto entfernen</label></p><?php endif; ?>
      <input id="i" type="file" name="img" accept="image/*">
      <div class="hint">🔒 Bitte nur Fotos ohne Gesichter und ohne Namen – Hände und Produkte sind super. Fotos werden automatisch verkleinert, Standortdaten werden entfernt.</div>
      <div class="row-actions">
        <?php if ($edit): ?><a class="btn btn-line" href="admin.php">Abbrechen</a><?php endif; ?>
        <button class="btn btn-ink"><?= $edit ? 'Speichern' : 'Veröffentlichen' ?></button>
      </div>
    </form>
  </div>

  <div class="card"><h2>Alle Beiträge (<?= count($posts) ?>)</h2>
    <?php if (!$posts): ?><p class="small">Noch keine Beiträge.</p><?php endif; ?>
    <table>
    <?php foreach ($posts as $p): ?>
      <tr>
        <td><span class="cat cat-<?= h($p['cat']) ?>"><?= h(CATS[$p['cat']] ?? '') ?></span></td>
        <td><b><?= h($p['title']) ?></b><br><span class="small"><?= date('d.m.Y', (int)$p['date']) ?></span></td>
        <td style="white-space:nowrap;text-align:right">
          <a href="?edit=<?= h($p['id']) ?>">Bearbeiten</a> &nbsp;
          <form class="form" method="post" class="inline" onsubmit="return confirm('Diesen Beitrag wirklich löschen?')"><?= csrf_field() ?>
            <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= h($p['id']) ?>">
            <button class="linkbtn">Löschen</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </table>
  </div>

  <div class="card"><details><summary>Passwort ändern</summary>
    <form class="form" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="password">
      <label>Altes Passwort</label><input type="password" name="old" required autocomplete="current-password" >
      <label>Neues Passwort (mind. 10 Zeichen)</label><input type="password" name="new" required minlength="10" autocomplete="new-password" >
      <p><button class="btn btn-line">Ändern</button></p>
    </form></details></div>
<?php endif; ?>
</div>
</body>
</html>
