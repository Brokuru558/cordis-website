<?php
require __DIR__ . '/lib.php';
$posts = array_map(function ($p) { return [
  'id' => $p['id'], 'title' => $p['title'], 'body' => $p['body'], 'cat' => $p['cat'],
  'date' => $p['date'] * 1000, 'img' => $p['img'] ?? null
]; }, load_posts());
$mail = 'schuelerfirma@gmscc.lernsax.de';
$orderLink = 'mailto:' . $mail . '?subject=' . rawurlencode('Bestellung Schülerrando') . '&body=' . rawurlencode("Hallo Schülerrando-Team,\n\nich möchte bestellen:\n- \n\nLieferung am (Datum): \nin der: 1. Hofpause / 2. Hofpause\nRaum: \n\nName: \n\nVielen Dank!");
?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cordis Group – Die Schülerfirma am Campus Cordis Dresden</title>
<meta name="description" content="Die Cordis Group ist die Schülerfirma der Gemeinschaftsschule Campus Cordis in Dresden. Wir betreiben das Campus Café und den Lieferservice Schülerrando.">
<meta name="theme-color" content="#1F2C6B">
<link rel="icon" href="assets/cordis-herz.png">
<link rel="preload" href="assets/fonts/bricolage-grotesque-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<a class="skip" href="#inhalt">Zum Inhalt springen</a>
<div class="stripe" aria-hidden="true"></div>

<header class="nav">
  <div class="wrap">
    <a class="brand" href="#top"><img src="assets/cordis-herz.png" alt="">Cordis Group</a>
    <button class="burger" id="burger" aria-expanded="false" aria-controls="menu" aria-label="Menü öffnen">☰</button>
    <ul id="menu">
      <li><a href="#cafe">Campus Café</a></li>
      <li><a href="#rando">Schülerrando</a></li>
      <li><a href="#einblicke">Einblicke</a></li>
      <li><a href="#schicht">Das Team</a></li>
      <li><a href="#ueber-uns">Über uns</a></li>
      <li><a href="#blog">Neuigkeiten</a></li>
      <li><a href="#kontakt">Kontakt</a></li>
    </ul>
    <a class="btn btn-ink" href="<?= h($orderLink) ?>">Bestellen</a>
  </div>
</header>

<main id="inhalt">
<section class="hero" id="top">
  <div class="wrap">
    <div class="hero-copy">
      <a class="live" href="#oeffnungszeiten" id="state" aria-live="polite"><span class="dot"></span><span><b id="stateText">Campus Café</b> <span id="next"></span></span></a>
      <h1>Unsere Schule hat eine eigene Firma.</h1>
      <p class="lead">Die Cordis Group ist die Schülerfirma der Gemeinschaftsschule Campus Cordis in Dresden. Schüler:innen der Klasse 9 betreiben hier das Campus Café und den Lieferservice Schülerrando – mit echtem Geld, echten Schichten und echter Verantwortung.</p>
      <div class="actions">
        <a class="btn btn-ink" href="#cafe">Zum Campus Café</a>
        <a class="btn btn-line" href="#rando">Bei Schülerrando bestellen</a>
      </div>
    </div>
    <div class="collage" aria-hidden="true">
      <figure class="polaroid p1"><img src="assets/fotos/cafe-tresen.jpg" alt=""><figcaption>Hofpause im Campus Café</figcaption></figure>
      <figure class="polaroid p2"><img src="assets/fotos/cafe-team.jpg" alt=""><figcaption>Schicht-Vorbereitung</figcaption></figure>
      <img class="heart-sticker" src="assets/cordis-herz.png" alt="">
      <span class="badge b1">Mi + Fr<br><small>beide Hofpausen</small></span>
      <span class="badge b2">100 %<br><small>von Schüler:innen</small></span>
      <span class="badge b3">seit<br><small>2025/26</small></span>
    </div>
  </div>
</section>

<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <span>Frische Sandwiches</span><i></i><span>Eistee</span><i></i><span>Lieferservice ins Lehrerzimmer</span><i></i><span>Gummibärchen</span><i></i><span>Schulbedarf</span><i></i><span>Vegetarisch</span><i></i><span>Mittwoch und Freitag</span><i></i>
    <span>Frische Sandwiches</span><i></i><span>Eistee</span><i></i><span>Lieferservice ins Lehrerzimmer</span><i></i><span>Gummibärchen</span><i></i><span>Schulbedarf</span><i></i><span>Vegetarisch</span><i></i><span>Mittwoch und Freitag</span><i></i>
  </div>
</div>

<section class="section" id="firmen" aria-labelledby="firmen-h">
  <div class="wrap">
    <div class="section-head">
      <h2 id="firmen-h">Zwei Firmen, ein Team</h2>
      <p>Beide gehören zur Cordis Group und werden komplett von Schüler:innen organisiert – vom Einkauf bis zur Abrechnung.</p>
    </div>

    <div class="firms">
      <article class="firm cafe" id="cafe">
        <div class="logo"><img src="assets/campus-cafe.png" alt="Logo Campus Café"></div>
        <h3 class="sr-only">Campus Café</h3>
        <p class="firm-lead">Das Pausencafé für alle in der Schule – frisch belegt, schnell an der Ausgabe und komplett von Schüler:innen geführt.</p>
        <dl class="facts">
          <dt>Angebot</dt><dd>Frisch belegte Sandwiches, Süßes, Getränke und Schulbedarf</dd>
          <dt>Für wen</dt><dd>Alle am Campus: Schüler:innen, Lehrkräfte und Personal</dd>
          <dt>Team</dt><dd>Fünf Schüler:innen pro Schicht: Kasse, Runner, Sandwich</dd>
        </dl>
        <div class="hours-box" id="oeffnungszeiten">
          <table class="times">
            <thead><tr><th scope="col">Mittwoch &amp; Freitag</th><th scope="col">Uhrzeit</th></tr></thead>
            <tbody>
              <tr id="p1"><td>1. Hofpause</td><td>9:35 – 9:55 Uhr</td></tr>
              <tr id="p2"><td>2. Hofpause</td><td>11:30 – 11:50 Uhr</td></tr>
            </tbody>
          </table>
          <p class="note">In den Ferien und an schulfreien Tagen geschlossen.</p>
        </div>
        <a class="btn btn-sun" href="#schicht">So läuft eine Schicht</a>
      </article>

      <article class="firm rando" id="rando">
        <div class="logo"><img src="assets/schuelerrando.png" alt="Logo Schülerrando"></div>
        <h3 class="sr-only">Schülerrando</h3>
        <p class="firm-lead">Der Lieferservice im Schulhaus.</p>
        <dl class="facts">
          <dt>Angebot</dt><dd>Alles aus dem Campus Café, direkt geliefert</dd>
          <dt>Für wen</dt><dd>Lehrkräfte und pädagogisches Personal</dd>
          <dt>Wann</dt><dd>Mittwoch und Freitag, in beiden Hofpausen</dd>
          <dt>Bezahlen</dt><dd>Bar bei der Lieferung</dd>
        </dl>
        <a class="btn btn-leaf" href="<?= h($orderLink) ?>">Bestellung schreiben</a>
      </article>
    </div>

    <div class="order" aria-labelledby="order-h">
      <div>
        <h3 id="order-h">So bestellen Sie bei Schülerrando</h3>
        <p>Für Lehrkräfte und pädagogisches Personal. Die Bestellung geht per Nachricht an die Schülerfirma.</p>
        <a class="btn btn-sun" href="<?= h($orderLink) ?>">Bestellung per E-Mail</a>
        <p class="mailhint">Oder direkt an <a href="mailto:<?= h($mail) ?>"><?= h($mail) ?></a></p>
      </div>
      <ol class="steps">
        <li><div><b>Nachricht schreiben</b><span>Was Sie möchten, an welchem Tag, in welcher Hofpause und in welchen Raum.</span></div></li>
        <li><div><b>Wir bringen es vorbei</b><span>Schülerrando liefert mittwochs oder freitags in der 1. Hofpause (9:35 – 9:55 Uhr) oder 2. Hofpause (11:30 – 11:50 Uhr).</span></div></li>
        <li><div><b>Bar bezahlen</b><span>Sie bezahlen direkt bei der Lieferung.</span></div></li>
      </ol>
    </div>
  </div>
</section>



<section class="section gallery-sec" id="einblicke" aria-labelledby="gal-h">
  <div class="wrap">
    <div class="gal-grid">
      <div class="gal-text">
        <h2 id="gal-h">Einblicke ins Campus Café</h2>
        <p>Vom leeren Tresen zum Pausentreffpunkt: So sieht es bei uns aus, wenn das Team loslegt.</p>
        <div class="gal-controls">
          <button class="gal-btn" id="galPrev" aria-label="Vorheriges Bild">&#8592;</button>
          <span class="gal-count" id="galCount" aria-live="polite">1 / 3</span>
          <button class="gal-btn" id="galNext" aria-label="Nächstes Bild">&#8594;</button>
        </div>
      </div>
      <div class="gallery" id="gallery" aria-roledescription="Bildergalerie">
        <figure class="slide is-active">
          <img src="assets/fotos/cafe-tresen.jpg" alt="Hofpause im Campus Café: Schüler:innen bestellen am Tresen, das Team bedient." loading="lazy">
          <figcaption>Hofpause: Bestellen, Nummer bekommen, abholen.</figcaption>
        </figure>
        <figure class="slide">
          <img src="assets/fotos/cafe-team.jpg" alt="Das Schichtteam bereitet hinter dem Tresen alles für den Verkauf vor." loading="lazy">
          <figcaption>Vor der Pause: Das Schichtteam bereitet alles vor.</figcaption>
        </figure>
        <figure class="slide">
          <img src="assets/fotos/cafe-raum.jpg" alt="Der Tresen des Campus Café im Foyer der Schule." loading="lazy">
          <figcaption>Unser Café im Foyer des Campus Cordis.</figcaption>
        </figure>
      </div>
    </div>
  </div>
</section>

<section class="section menu-sec" id="angebot" aria-labelledby="menu-h" style="padding-top:0">
  <div class="wrap">
    <div class="menu">
      <div class="menu-head">
        <h2 id="menu-h">Was es bei uns gibt</h2>
        <p>Alles auch über Schülerrando bestellbar. Preise hängen am Tresen aus.</p>
      </div>
      <div class="menu-cols">
        <div><h3>Sandwiches</h3><ul><li>Mit Salami oder Schinken und Käse</li><li>Vegetarisch</li></ul></div>
        <div><h3>Süßes</h3><ul><li>Gummibärchen</li><li>Mentos</li><li>Kaugummi</li></ul></div>
        <div><h3>Getränke</h3><ul><li>Eistee</li><li>Cola und Limo</li></ul></div>
        <div><h3>Schulbedarf</h3><ul><li>Bleistifte und Kugelschreiber</li><li>Fineliner</li><li>Lineal und Geodreieck</li><li>Klebeband und Blöcke</li></ul></div>
      </div>
    </div>
  </div>
</section>

<section class="section shift" id="schicht" aria-labelledby="shift-h">
  <div class="wrap">
    <div class="shift-head">
      <div class="section-head">
        <h2 id="shift-h">So läuft eine Schicht im Campus Café</h2>
        <p>Hinter jedem Sandwich steckt ein eingespieltes Team. Fünf Schüler:innen, feste Rollen und ein klarer Ablauf – jede Woche neu eingeteilt nach Schichtplan.</p>
      </div>
    </div>

    <div class="roles">
      <div class="role">
        <span class="count">1&times;</span>
        <h3>Kasse</h3>
        <p>Baut die Kasse auf, kassiert ab und gibt jeder Bestellung eine Nummer. Am Ende unterschreibt die Kasse den Hygieneplan.</p>
      </div>
      <div class="role">
        <span class="count">2&times;</span>
        <h3>Runner</h3>
        <p>Nehmen Bestellungen auf, geben sie an das Sandwich-Team weiter, bereiten Getränke und Süßes vor und organisieren die Ausgabe.</p>
      </div>
      <div class="role">
        <span class="count">2&times;</span>
        <h3>Sandwich</h3>
        <p>Belegen frisch: Butter, Käse, Belag – und geben den Runnern Bescheid, sobald eine Bestellung fertig ist.</p>
      </div>
    </div>

    <ol class="timeline">
      <li>
        <h3>Vorbereitung</h3>
        <p class="when">10 Minuten vor der Pause</p>
        <p>Hände waschen, Schürze und Kopfbedeckung an. Sandwiches vorbereiten, Teller, Gläser und Getränke bereitstellen, Spülmaschine ausräumen, Kasse einrichten.</p>
      </li>
      <li>
        <h3>Verkauf</h3>
        <p class="when">In der Hofpause</p>
        <p>Bestellen an der Kasse, Nummer bekommen, abholen. Das Team arbeitet Hand in Hand – und Schülerrando liefert gleichzeitig an Lehrkräfte aus.</p>
      </li>
      <li>
        <h3>Aufräumen</h3>
        <p class="when">Nach dem Verkauf</p>
        <p>Kühlware in den Kühlschrank, Geschirr in den Spüler. Gereinigt wird nach Hygieneplan: Kühlschrank, Oberflächen, Sandwichmaker, Boden, Müll.</p>
      </li>
    </ol>

    <div class="backstage">
      <h3>Was hinter dem Tresen passiert</h3>
      <dl>
        <div><dt>Einkauf</dt><dd>Vergleicht Anbieter, bestellt die Waren und kontrolliert jede Lieferung.</dd></div>
        <div><dt>Kalkulation</dt><dd>Für jedes Produkt rechnen wir den Wareneinsatz aus – vom Gramm Käse bis zum Becher Cola.</dd></div>
        <div><dt>Kassenbuch</dt><dd>Jede Einnahme und Ausgabe wird mit Beleg gebucht. Jede Woche.</dd></div>
        <div><dt>Hygiene</dt><dd>Fester Hygieneplan, nach jeder Schicht abgezeichnet.</dd></div>
      </dl>
    </div>


    <div class="learned">
      <h3>Was wir bisher gelernt haben</h3>
      <div class="learned-cols">
        <div>
          <h4>Das läuft richtig gut</h4>
          <ul>
            <li>Die Motivation im Team ist riesig.</li>
            <li>Wir bekommen viel positives Feedback.</li>
            <li>Echte Aufgaben statt Arbeitsblatt – und ein echtes Wir-Gefühl.</li>
          </ul>
        </div>
        <div>
          <h4>Das ist echte Arbeit</h4>
          <ul>
            <li>Logistik: Waren bestellen, Lieferungen annehmen, prüfen und reklamieren.</li>
            <li>Behörden: Kontakt zum Gesundheitsamt und Gesundheitszeugnisse fürs Team.</li>
            <li>Viele kleine Aufgaben: Spülmaschine, Wäsche, Putzen.</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="values">
      <h3>Worauf es uns ankommt</h3>
      <ul>
        <li><b>Zuverlässigkeit.</b> Wer nicht kann, organisiert selbst Ersatz.</li>
        <li><b>Gelassenheit.</b> Fehler passieren – wir lernen daraus.</li>
        <li><b>Eigene Ideen.</b> Neue Produkte und Verbesserungen sind immer willkommen.</li>
      </ul>
    </div>
  </div>
</section>

<section class="section" id="ueber-uns" aria-labelledby="about-h" style="padding-top:0">
  <div class="wrap">
    <div class="about">
      <div class="story">
        <h2 id="about-h" style="font-size:clamp(34px,4.4vw,54px);margin-bottom:24px">Über uns</h2>
        <p>Seit dem Schuljahr 2025/26 führen Schüler:innen der Klasse 9 im Profil „Unternehmerische Bildung“ eine eigene Firma. Wir entscheiden gemeinsam, kalkulieren Preise, kaufen ein, planen Schichten und rechnen ab.</p>
        <p>Die Cordis Group ist als Schülergenossenschaft organisiert: Alle, die mitarbeiten, haben eine Stimme. Und wer eine Schicht übernimmt, verdient mit – die Hälfte der Marge geht an das Schichtteam, die andere Hälfte bleibt in der Firma.</p>
      </div>
      <dl class="keyfacts">
        <div><dt>Gegründet</dt><dd>Schuljahr 2025/26</dd></div>
        <div><dt>Wer</dt><dd>Profil Klasse 9 (9/1 bis 9/4)</dd></div>
        <div><dt>Rechtsform</dt><dd>Schülergenossenschaft</dd></div>
        <div><dt>Firmen</dt><dd>Campus Café und Schülerrando</dd></div>
        <div><dt>Lohn</dt><dd>50 % der Marge ans Schichtteam</dd></div>
      </dl>
    </div>

    <div class="depts" aria-label="Abteilungen">
      <div class="dept"><h4><i style="background:#2563B0"></i>Vorstand</h4><p>Leitet die Firma, setzt Ziele und moderiert die Besprechungen.</p></div>
      <div class="dept"><h4><i style="background:#3DA748"></i>Finanzen</h4><p>Führt das Kassenbuch, kalkuliert Preise und rechnet Löhne ab.</p></div>
      <div class="dept"><h4><i style="background:#F5B400"></i>Personal</h4><p>Plant die Schichten und informiert die Klassen.</p></div>
      <div class="dept"><h4><i style="background:#E8442E"></i>Einkauf</h4><p>Vergleicht Anbieter, bestellt Waren und prüft den Wareneingang.</p></div>
      <div class="dept"><h4><i style="background:#8A5CC2"></i>Marketing</h4><p>Gestaltet Plakate, Social Media und das Café.</p></div>
    </div>

    <div class="official">
      <h3>Ein offizielles Schulprojekt</h3>
      <ul>
        <li>Kooperationsvereinbarung mit der Schulleitung</li>
        <li>Versichert über die Schule</li>
        <li>Feste Begleitlehrkraft</li>
        <li>Es gilt die Schulordnung</li>
      </ul>
    </div>

    <div style="margin-top:72px">
      <h3 style="font-size:26px;margin-bottom:8px">Was als Nächstes kommen kann</h3>
      <p style="color:var(--muted);max-width:44em">Unter dem Dach der Cordis Group können weitere Firmen entstehen. Diese Ideen liegen auf dem Tisch:</p>
      <div class="ideas">
        <span class="idea">Nachhaltige Kleidung mit Siebdruck</span>
        <span class="idea">Kunsthandwerk</span>
        <span class="idea">3D-Druck</span>
        <span class="idea">Catering</span>
        <span class="idea own">Deine Idee</span>
      </div>
    </div>
  </div>
</section>

<section class="section blog" id="blog" aria-labelledby="blog-h">
  <div class="wrap">
    <div class="blog-top">
      <div class="section-head">
        <h2 id="blog-h">Neuigkeiten</h2>
        <p>Was bei uns los ist – geschrieben von unserem Team.</p>
      </div>
      <div class="filters" id="filters" role="group" aria-label="Beiträge filtern">
        <button class="chip" data-f="all" aria-pressed="true">Alle</button>
        <button class="chip" data-f="cordis" aria-pressed="false">Cordis Group</button>
        <button class="chip" data-f="cafe" aria-pressed="false">Campus Café</button>
        <button class="chip" data-f="rando" aria-pressed="false">Schülerrando</button>
      </div>
    </div>
    <div class="posts" id="posts"><noscript><div class="empty">Bitte JavaScript aktivieren, um die Beiträge zu sehen.</div></noscript></div>
  </div>
</section>

<section class="section" id="kontakt" aria-labelledby="kontakt-h" style="padding-bottom:0">
  <div class="wrap">
    <div class="contact">
      <div>
        <h2 id="kontakt-h">Fragen, Ideen, Bestellungen?</h2>
        <p>Schreiben Sie uns. Wir antworten so schnell es der Stundenplan erlaubt.</p>
      </div>
      <a class="mail" href="mailto:<?= h($mail) ?>"><?= h($mail) ?></a>
    </div>
  </div>
</section>
</main>

<footer class="footer">
  <div class="wrap">
    <div>
      <img src="assets/cordis-group.png" alt="Cordis Group">
      <p><b style="color:var(--ink)">… weil Bildung Herzenssache ist.</b></p>
      <p>Die Schülerfirma der <a href="https://www.gmscc.de/">Gemeinschaftsschule Campus Cordis</a>, Stauffenbergallee 8, 01099 Dresden.</p>
    </div>
    <div>
      <h4>Unsere Firmen</h4>
      <ul><li><a href="#cafe">Campus Café</a></li><li><a href="#rando">Schülerrando</a></li><li><a href="#blog">Neuigkeiten</a></li></ul>
    </div>
    <div>
      <h4>Rechtliches</h4>
      <ul><li><a href="impressum.php">Impressum</a></li><li><a href="datenschutz.php">Datenschutz</a></li><li><a href="admin.php">Redaktion</a></li></ul>
    </div>
    <p class="small">© <?= date('Y') ?> Cordis Group – ein Projekt im Profilunterricht Klasse 9</p>
  </div>
</footer>

<dialog class="reader" id="reader" aria-labelledby="r-title">
  <button class="close" id="rclose" aria-label="Schließen">✕</button>
  <div id="rbody"></div>
</dialog>

<script>
window.POSTS = <?= json_encode($posts, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="assets/site.js"></script>
</body>
</html>
