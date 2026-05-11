</main>
<footer class="border-top border-success-subtle py-4">
  <div class="container d-flex flex-wrap justify-content-between gap-3 small">
    <span>&copy; <?= date('Y') ?> NABTA</span>
    <span class="eco-label">Sustainable fashion, second stories.</span>
  </div>
</footer>

<svg class="d-none">
  <symbol id="cursorPlant" viewBox="0 0 24 24">
    <path d="M12 20V8" stroke="#5C7A5C" stroke-width="2" stroke-linecap="round"/>
    <path d="M12 10c0-4 3-6 6-6-1 4-2 7-6 8z" fill="#A8B89A"/>
    <path d="M12 13c0-3-2-5-5-6 0 4 1.5 6 5 7z" fill="#D4A5A5"/>
  </symbol>
  <symbol id="leafTrail" viewBox="0 0 24 24">
    <path d="M21 4c-7 0-12 5-12 12 7 0 12-5 12-12z" fill="#A8B89A"/>
  </symbol>
</svg>

<div id="cursorPlantEl" class="cursor-plant">
  <svg viewBox="0 0 24 24"><use href="#cursorPlant"/></svg>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(() => {
  const body = document.body;
  const cursor = document.getElementById('cursorPlantEl');
  let leaves = [];
  let allowTrail = body.dataset.cursorTrail === 'hero';

  document.addEventListener('mousemove', (e) => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';
    if (!allowTrail) return;
    const leaf = document.createElement('div');
    leaf.className = 'leaf-trail';
    leaf.innerHTML = '<svg viewBox="0 0 24 24"><use href="#leafTrail"/></svg>';
    leaf.style.left = e.clientX + 'px';
    leaf.style.top = e.clientY + 'px';
    body.appendChild(leaf);
    leaves.push(leaf);
    if (leaves.length > 6) {
      const old = leaves.shift();
      old.remove();
    }
    setTimeout(() => leaf.remove(), 620);
  });

  const toggle = document.getElementById('themeToggle');
  
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('theme-dark');
    if (toggle) toggle.textContent = '🌙';
  }

  if (toggle) {
    toggle.addEventListener('click', () => {
      const dark = document.documentElement.classList.toggle('theme-dark');
      document.body.classList.toggle('theme-dark', dark);
      toggle.textContent = dark ? '🌙' : '🌞';
      localStorage.setItem('theme', dark ? 'dark' : 'light');
    });
  }

  document.querySelectorAll('[data-toggle-target]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const target = document.querySelector(btn.dataset.toggleTarget);
      if (!target) return;
      const state = target.dataset.active === '1';
      target.dataset.active = state ? '0' : '1';
      btn.classList.toggle('active', !state);
    });
  });

  document.querySelectorAll('[data-toggle-text]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const on = btn.dataset.onText || 'Following';
      const off = btn.dataset.offText || 'Follow';
      const active = btn.dataset.active === '1';
      btn.dataset.active = active ? '0' : '1';
      btn.textContent = active ? off : on;
    });
  });

  document.querySelectorAll('[data-count-target]').forEach((el) => {
    const base = Number(el.dataset.countBase || 0);
    const value = Math.round(base * 13.37);
    let n = 0;
    const inc = Math.max(1, Math.round(value / 60));
    const timer = setInterval(() => {
      n += inc;
      if (n >= value) {
        n = value;
        clearInterval(timer);
      }
      el.textContent = n.toLocaleString();
    }, 18);
  });

  const conditionBtns = document.querySelectorAll('.condition-btn');
  const conditionOut = document.getElementById('conditionResult');
  if (conditionBtns.length && conditionOut) {
    conditionBtns.forEach((btn) => btn.addEventListener('click', () => {
      btn.classList.toggle('active');
      const selected = document.querySelectorAll('.condition-btn.active').length;
      conditionOut.textContent = selected === 0 ? 'Like New' : (selected === 1 ? 'Good' : 'Fair');
    }));
  }

  window.updateSnakePlant = function updateSnakePlant(points = 0) {
    const plant = document.getElementById('snakePlantSvg');
    const leavesOut = document.getElementById('leafCount');
    if (!plant) return;
    const scale = Math.min(1.9, 0.85 + (Number(points) / 1200));
    const leavesCount = Math.min(10, 3 + Math.floor(Number(points) / 150));
    plant.style.transform = 'scale(' + scale.toFixed(2) + ')';
    if (leavesOut) leavesOut.textContent = String(leavesCount);
  };

  const bioSim = document.getElementById('retinaSim');
  if (bioSim) {
    setTimeout(() => {
      const target = bioSim.dataset.redirect;
      if (target) window.location.href = target;
    }, 2000);
  }

  window.togglePassword = function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      btn.textContent = 'Hide';
    } else {
      input.type = 'password';
      btn.textContent = 'See';
    }
  };

  const regPass = document.getElementById('registerPassword');
  if (regPass) {
    regPass.addEventListener('input', function() {
      const val = regPass.value;
      const setConstraint = (id, isValid) => {
        const el = document.getElementById(id);
        if (el) {
          if (isValid) {
            el.className = 'text-success';
            el.innerHTML = '&#10003; ' + el.innerHTML.substring(2);
          } else {
            el.className = 'text-danger';
            el.innerHTML = '&#10007; ' + el.innerHTML.substring(2);
          }
        }
      };
      setConstraint('lenConstraint', val.length >= 8);
      setConstraint('upperConstraint', /[A-Z]/.test(val));
      setConstraint('lowerConstraint', /[a-z]/.test(val));
      setConstraint('numConstraint', /\d/.test(val));
    });
  }
})();
</script>
</body>
</html>
