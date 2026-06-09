/**
 * nopost — ads.js
 * Adblock detection via bait element
 */
(function () {
  'use strict';

  var bait = document.createElement('div');
  bait.setAttribute('class', 'ad adsbox doubleclick ad-placement carbon-ads');
  bait.style.cssText = 'position:absolute;top:-9999px;left:-9999px;width:1px;height:1px;opacity:0;pointer-events:none;';
  document.body.appendChild(bait);

  window.addEventListener('load', function () {
    var detected = false;
    if (!bait || bait.offsetHeight === 0 || bait.offsetParent === null) {
      detected = true;
    }
    if (typeof getComputedStyle !== 'undefined') {
      try {
        var style = window.getComputedStyle(bait);
        if (style && (style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0')) {
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
  });

})();
