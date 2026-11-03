<?php
// Gemeinsame Funktionen für die Cordis-Group-Webseite.
// Hier muss normalerweise nichts geändert werden.

declare(strict_types=1);

const DATA_DIR    = __DIR__ . '/data';
const POSTS_FILE  = DATA_DIR . '/posts.json';
const CONFIG_FILE = DATA_DIR . '/config.php'; // als PHP-Datei, damit sie auch ohne .htaccess nicht lesbar ist
const UPLOAD_DIR  = __DIR__ . '/uploads';
const CATS = ['cordis' => 'Cordis Group', 'cafe' => 'Campus Café', 'rando' => 'Schülerrando'];

function h(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

function load_posts(): array {
    if (!is_file(POSTS_FILE)) return [];
    $d = json_decode((string)file_get_contents(POSTS_FILE), true);
    return is_array($d) ? $d : [];
}

function save_posts(array $posts): void {
    usort($posts, function ($a, $b) { return ($b['date'] ?? 0) <=> ($a['date'] ?? 0); });
    $tmp = POSTS_FILE . '.tmp';
    file_put_contents($tmp, json_encode(array_values($posts), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
    rename($tmp, POSTS_FILE);
}

function load_config(): array {
    if (!is_file(CONFIG_FILE)) return [];
    $d = include CONFIG_FILE;
    return is_array($d) ? $d : [];
}

function save_config(array $c): void {
    file_put_contents(CONFIG_FILE, "<?php\nreturn " . var_export($c, true) . ";\n", LOCK_EX);
    if (function_exists('opcache_invalidate')) @opcache_invalidate(CONFIG_FILE, true);
}

function start_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Strict', 'secure' => $secure]);
    session_name('cordis_admin');
    session_start();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . h($_SESSION['csrf']) . '">'; }

function check_csrf(): bool { return hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? '')); }

/** Speichert ein hochgeladenes Foto verkleinert als JPEG (entfernt dabei auch GPS-/Kameradaten). */
function store_image(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > 15 * 1024 * 1024) throw new RuntimeException('Das Foto ist zu groß (max. 15 MB).');
    $info = @getimagesize($file['tmp_name']);
    if (!$info) throw new RuntimeException('Die Datei ist kein Bild.');
    switch ($info[2]) {
        case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($file['tmp_name']); break;
        case IMAGETYPE_PNG:  $src = @imagecreatefrompng($file['tmp_name']); break;
        case IMAGETYPE_WEBP: $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file['tmp_name']) : false; break;
        case IMAGETYPE_GIF:  $src = @imagecreatefromgif($file['tmp_name']); break;
        default: $src = false;
    }
    if (!$src) throw new RuntimeException('Dieses Bildformat wird nicht unterstützt (JPG, PNG, WebP oder GIF verwenden).');
    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $ex = @exif_read_data($file['tmp_name']); $o = is_array($ex) && isset($ex['Orientation']) ? $ex['Orientation'] : 1;
        if ($o == 3) $src = imagerotate($src, 180, 0);
        if ($o == 6) $src = imagerotate($src, -90, 0);
        if ($o == 8) $src = imagerotate($src, 90, 0);
    }
    $w = imagesx($src); $hgt = imagesy($src);
    $scale = min(1, 1400 / max($w, $hgt));
    $nw = (int)round($w * $scale); $nh = (int)round($hgt * $scale);
    $dst = imagecreatetruecolor($nw, $nh);
    imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $hgt);
    $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.jpg';
    imagejpeg($dst, UPLOAD_DIR . '/' . $name, 82);
    return 'uploads/' . $name;
}

function delete_image(?string $path): void {
    if ($path && strpos($path, 'uploads/') === 0 && strpos($path, '..') === false) {
        @unlink(__DIR__ . '/' . $path);
    }
}
