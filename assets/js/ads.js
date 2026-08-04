/**
 * nopost — ads.js
 * Adblock detection via bait element
 */
(function () {
  'use strict';

  function check() {
    var bait = document.createElement('div');
    bait.setAttribute('class', 'ad adsbox doubleclick ad-placement carbon-ads');
    bait.style.cssText = 'position:absolute;top:-9999px;left:-9999px;width:1px;height:1px;pointer-events:none;';
    document.body.appendChild(bait);

    // setTimeout, pas window.load : le chargement complet de la page (toutes
    // images/fonts) n'a rien à voir avec la présence d'un adblocker, et une
    // seule ressource lente/en échec empêcherait "load" de se déclencher.
    setTimeout(function () {
      var detected = false;
      if (!bait || bait.offsetHeight === 0 || bait.offsetParent === null) {
        detected = true;
      }
      if (typeof getComputedStyle !== 'undefined') {
        try {
          var style = window.getComputedStyle(bait);
          if (style && (style.display === 'none' || style.visibility === 'hidden')) {
            detected = true;
          }
        } catch (e) {}
      }
      if (!detected) {
        document.documentElement.classList.add('ads-ok');
      }
      if (bait && bait.parentNode) {
        bait.parentNode.removeChild(bait);
      }
    }, 100);
  }

  if (document.body) {
    check();
  } else {
    document.addEventListener('DOMContentLoaded', check);
  }

})();
