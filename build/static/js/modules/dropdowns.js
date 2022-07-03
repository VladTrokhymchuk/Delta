"use strict";

document.addEventListener('DOMContentLoaded', () => {
  const dropdownList = document.querySelectorAll('[data-dropdown]');

  const setRealHeightVar = (elem, height) => {
    elem.style.setProperty('--real-height', `${height}px`);
  };

  dropdownList === null || dropdownList === void 0 ? void 0 : dropdownList.forEach(dropdown => {
    const dropdownTrigger = dropdown.querySelector('[data-dropdown-trigger]');
    const dropdownContent = dropdown.querySelector('[data-dropdown-cont]');
    setRealHeightVar(dropdownContent, dropdownContent.scrollHeight);

    if (dropdownTrigger !== null && dropdownContent !== null) {
      dropdownTrigger.addEventListener('click', () => {
        const relatedDropdowns = [...dropdown.parentElement.getElementsByClassName('dropdown')];

        if (relatedDropdowns.length > 1) {
          relatedDropdowns === null || relatedDropdowns === void 0 ? void 0 : relatedDropdowns.forEach(relatedDropdown => {
            if (relatedDropdown !== dropdown) {
              relatedDropdown.classList.remove('open');
            }
          });
        }

        dropdown.classList.toggle('open');
        setRealHeightVar(dropdownContent, dropdownContent.scrollHeight);
      });
    }
  });
});