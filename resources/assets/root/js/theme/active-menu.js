$(function () {
  let current = location.pathname;

  let prefix = current.split("/")[1];

  $('.root-menu li a').each(function () {
    let $this = $(this);

    // If the current path is like this link, make the <li> active.
    if ($this.attr('href').indexOf(prefix) !== -1) {
      $this.parent().addClass('active');
    }
  });
});
