/* ASSESI — UI: Accordion (Timeline) + Mobile-Menü
   Vanilla JS, zugänglich (aria-expanded, Tastatur, Escape). */
document.addEventListener('DOMContentLoaded', function () {

  /* Accordion: eine Sektion offen */
  document.querySelectorAll('[data-acc]').forEach(function (acc) {
    var heads = Array.prototype.slice.call(acc.querySelectorAll('.acc__head'));
    function setOpen(head, open) {
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
      var panel = head.nextElementSibling;
      panel.style.maxHeight = open ? panel.scrollHeight + 'px' : null;
    }
    heads.forEach(function (h) {
      if (h.getAttribute('aria-expanded') === 'true') setOpen(h, true);
      h.addEventListener('click', function () {
        var isOpen = h.getAttribute('aria-expanded') === 'true';
        heads.forEach(function (o) { setOpen(o, false); });
        setOpen(h, !isOpen);
      });
    });
    /* Bei Resize die max-height des offenen Panels neu messen (sonst clippt
       umgebrochener Text auf schmaleren Breiten). */
    window.addEventListener('resize', function () {
      heads.forEach(function (h) {
        if (h.getAttribute('aria-expanded') === 'true') setOpen(h, true);
      });
    });
  });

  /* Mobile-Menü */
  var burger = document.querySelector('.nav__burger');
  var panel = document.getElementById('navPanel');
  if (burger && panel) {
    function close(returnFocus) {
      panel.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
      if (returnFocus) burger.focus();
    }
    burger.addEventListener('click', function () {
      var open = panel.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    panel.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { close(false); }); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && panel.classList.contains('is-open')) close(true);
    });
    // Beim Wechsel auf Desktop (Burger ausgeblendet) Panel-Zustand zurücksetzen.
    window.addEventListener('resize', function () {
      if (window.innerWidth > 920 && panel.classList.contains('is-open')) close(false);
    });
  }
});
