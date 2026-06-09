/**
 * nopost — theme.js
 * Mobile nav, search bar toggle, share copy, scroll reveal
 */
(function () {
  'use strict';

  // ── MOBILE NAV BURGER ────────────────────────────────────
  var burger = document.querySelector('.nav-burger');
  var nav    = document.querySelector('.site-nav');

  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.classList.toggle('is-open', open);
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.style.overflow = open ? 'hidden' : '';
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        nav.classList.remove('is-open');
        burger.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  }

  // ── SEARCH BAR TOGGLE ────────────────────────────────────
  var searchToggle = document.querySelector('.nav-search-toggle');
  var searchBar    = document.querySelector('.nav-search-bar');

  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', function () {
      var hidden = searchBar.hasAttribute('hidden');
      if (hidden) {
        searchBar.removeAttribute('hidden');
        var input = searchBar.querySelector('.nav-search-input');
        if (input) input.focus();
        searchToggle.setAttribute('aria-expanded', 'true');
      } else {
        searchBar.setAttribute('hidden', '');
        searchToggle.setAttribute('aria-expanded', 'false');
      }
    });

    // Close search on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !searchBar.hasAttribute('hidden')) {
        searchBar.setAttribute('hidden', '');
        searchToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ── SHARE — COPY URL ─────────────────────────────────────
  document.querySelectorAll('.np-share-copy').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url = btn.dataset.url || window.location.href;
      if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function () {
          flashBtn(btn);
        }).catch(function () {
          fallbackCopy(url, btn);
        });
      } else {
        fallbackCopy(url, btn);
      }
    });
  });

  function flashBtn(btn) {
    var original = btn.style.color;
    btn.style.color = '#22c55e';
    btn.setAttribute('aria-label', 'Lien copié !');
    setTimeout(function () {
      btn.style.color = original;
      btn.setAttribute('aria-label', 'Copier le lien');
    }, 1800);
  }

  function fallbackCopy(text, btn) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try {
      document.execCommand('copy');
      flashBtn(btn);
    } catch (e) {}
    document.body.removeChild(ta);
  }

  // ── SCROLL REVEAL ────────────────────────────────────────
  if ('IntersectionObserver' in window) {
    var revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(function (el) {
      revealObserver.observe(el);
    });
  } else {
    // Fallback: show all immediately
    document.querySelectorAll('.reveal').forEach(function (el) {
      el.classList.add('is-visible');
    });
  }

})();
