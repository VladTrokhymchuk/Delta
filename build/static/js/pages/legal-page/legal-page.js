"use strict";

$(document).ready(function () {
  $('.table').basictable();
  const thead = $('thead span');
  thead.each(function (iH, oH) {
    if (this.innerText.length == 0) {
      $(this).parent()[0].style.padding = "0";
    }
  });
  const tbody = $('tbody');
  const td = tbody.find('td');
  const id = td.data('th');
  td.each(function (index, option) {
    if (option.dataset.th.length >= 2) {
      $(option)[0].style.cssText = "--data-th-padding:1rem; --data-th-width:9em";
    }
  });
});