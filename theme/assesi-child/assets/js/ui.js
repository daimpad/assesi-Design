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
  });

  /* Mobile-Menü */
  var burger = document.querySelector('.nav__burger');
  var panel = document.getElementById('navPanel');
  if (burger && panel) {
    function close() { panel.classList.remove('is-open'); burger.setAttribute('aria-expanded', 'false'); }
    burger.addEventListener('click', function () {
      var open = panel.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    panel.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', close); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  }
});
