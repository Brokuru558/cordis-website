// Cordis Group – Seitenlogik: Menü, Live-Öffnungsstatus, Neuigkeiten
(function () {
  const $ = s => document.querySelector(s);

  // Menü (mobil)
  const burger = $('#burger'), menu = $('#menu');
  if (burger) {
    burger.addEventListener('click', () => {
      const open = menu.classList.toggle('open');
      burger.setAttribute('aria-expanded', open);
    });
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
      menu.classList.remove('open'); burger.setAttribute('aria-expanded', false);
    }));
  }

  // Live-Status Campus Café: Mittwoch (3) und Freitag (5)
  const DAYS = [3, 5];
  const SLOTS = [{ id: 'p1', name: '1. Hofpause', from: [9, 35], to: [9, 55] },
                 { id: 'p2', name: '2. Hofpause', from: [11, 30], to: [11, 50] }];
  const WD = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
  const pad = n => String(n).padStart(2, '0');
  const at = (d, hm) => { const x = new Date(d); x.setHours(hm[0], hm[1], 0, 0); return x; };

  function updateStatus() {
    const st = $('#state'); if (!st) return;
    const now = new Date();
    document.querySelectorAll('.times tr').forEach(r => r.classList.remove('now'));
    let text, next;
    const today = DAYS.includes(now.getDay());
    const cur = today && SLOTS.find(s => now >= at(now, s.from) && now < at(now, s.to));
    if (cur) {
      st.classList.add('open'); text = 'Campus Café geöffnet';
      const t = at(now, cur.to);
      next = `bis <strong>${t.getHours()}:${pad(t.getMinutes())} Uhr</strong>`;
      $('#' + cur.id).classList.add('now');
    } else {
      st.classList.remove('open'); text = 'Campus Café';
      for (let i = 0; i < 8; i++) {
        const d = new Date(now); d.setDate(now.getDate() + i);
        if (!DAYS.includes(d.getDay())) continue;
        const s = SLOTS.find(s => at(d, s.from) > now);
        if (s) {
          const f = at(d, s.from);
          const day = i === 0 ? 'heute' : i === 1 ? 'morgen' : WD[d.getDay()] + ', ' + d.getDate() + '.' + (d.getMonth() + 1) + '.';
          next = `öffnet <strong>${day} um ${f.getHours()}:${pad(f.getMinutes())} Uhr</strong>`;
          break;
        }
      }
    }
    $('#stateText').textContent = text;
    $('#next').innerHTML = next || '';
  }
  updateStatus();
  setInterval(updateStatus, 30000);


  // Galerie
  const gal = $('#gallery');
  if (gal) {
    const slides = [...gal.querySelectorAll('.slide')];
    let i = 0, timer = null;
    const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const show = n => {
      slides[i].classList.remove('is-active');
      i = (n + slides.length) % slides.length;
      slides[i].classList.add('is-active');
      $('#galCount').textContent = (i + 1) + ' / ' + slides.length;
    };
    const play = () => { if (!reduce) { stop(); timer = setInterval(() => show(i + 1), 6000); } };
    const stop = () => { if (timer) clearInterval(timer); timer = null; };
    $('#galPrev').addEventListener('click', () => { show(i - 1); stop(); });
    $('#galNext').addEventListener('click', () => { show(i + 1); stop(); });
    gal.addEventListener('mouseenter', stop);
    let x0 = null;
    gal.addEventListener('touchstart', e => { x0 = e.touches[0].clientX; stop(); }, { passive: true });
    gal.addEventListener('touchend', e => { if (x0 === null) return; const dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 40) show(i + (dx < 0 ? 1 : -1)); x0 = null; });
    slides.forEach(s => { const im = s.querySelector('img'); im.loading = 'eager'; });
    play();
  }

  // Neuigkeiten
  const CATS = { cordis: 'Cordis Group', cafe: 'Campus Café', rando: 'Schülerrando' };
  const LOGO = { cordis: 'assets/cordis-herz.png', cafe: 'assets/campus-cafe.png', rando: 'assets/schuelerrando.png' };
  const BG = { cordis: '#E2E7F6', cafe: 'var(--sun-l)', rando: 'var(--leaf-l)' };
  const posts = (window.POSTS || []).slice().sort((a, b) => b.date - a.date);
  let filter = 'all';
  const el = (t, c, txt) => { const e = document.createElement(t); if (c) e.className = c; if (txt != null) e.textContent = txt; return e; };
  const fmt = t => new Date(t).toLocaleDateString('de-DE', { day: 'numeric', month: 'long', year: 'numeric' });
  const meta = p => { const m = el('div', 'meta'); m.append(el('span', 'cat cat-' + p.cat, CATS[p.cat] || 'Cordis Group'), el('span', null, fmt(p.date))); return m; };

  function render() {
    const box = $('#posts'); if (!box) return;
    box.innerHTML = '';
    const list = posts.filter(p => filter === 'all' || p.cat === filter);
    if (!list.length) { box.appendChild(el('div', 'empty', filter === 'all' ? 'Hier erscheinen bald unsere ersten Beiträge.' : 'Zu dieser Firma gibt es noch keine Beiträge.')); return; }
    list.forEach(p => {
      const c = el('button', 'post'); c.type = 'button';
      const im = el('div', 'img');
      if (p.img) im.style.backgroundImage = `url("${p.img}")`;
      else { im.style.background = BG[p.cat] || BG.cordis; const i = el('img'); i.src = LOGO[p.cat] || LOGO.cordis; i.alt = ''; im.appendChild(i); }
      const b = el('div', 'body');
      b.append(meta(p), el('h3', null, p.title), el('p', null, p.body));
      c.append(im, b);
      c.addEventListener('click', () => open(p));
      box.appendChild(c);
    });
  }

  const dlg = $('#reader');
  function open(p) {
    const body = $('#rbody'); body.innerHTML = '';
    if (p.img) { const i = el('img'); i.src = p.img; i.alt = ''; body.appendChild(i); }
    const inner = el('div', 'in'); const h = el('h2', null, p.title); h.id = 'r-title';
    inner.append(meta(p), h, el('div', 'text', p.body));
    body.appendChild(inner);
    if (dlg.showModal) dlg.showModal(); else dlg.setAttribute('open', '');
  }
  if (dlg) {
    $('#rclose').addEventListener('click', () => dlg.close());
    dlg.addEventListener('click', e => { if (e.target === dlg) dlg.close(); });
  }

  const filters = $('#filters');
  if (filters) filters.addEventListener('click', e => {
    const b = e.target.closest('.chip'); if (!b) return;
    filter = b.dataset.f;
    filters.querySelectorAll('.chip').forEach(c => c.setAttribute('aria-pressed', c === b));
    render();
  });
  render();
})();
