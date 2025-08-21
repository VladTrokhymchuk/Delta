import { params } from "./getUTM.js";

export default () => {
  document.addEventListener('wpcf7mailsent', event => {
    event.preventDefault();
    const formID = event.detail.contactFormId;
    const formInput = document.querySelector(`input[value="${formID}"]`);
  
    const container = formInput.closest('form.wpcf7-form');
    const thanksName = container.querySelector('[data-thanks]');
  
    const popupThanks = document.querySelector('#popup-thanks');
  
    const thankOpen = elem => {
      document.querySelectorAll('.popup').forEach(popup => popup.classList.remove('show'));
      document.body.classList.add('scroll-disable');
      document.querySelector('.popup-bg').classList.add('show');
      elem?.classList.add('show');
    };

    if (formID) {
      ga('send', {
        hitType: 'event',
        eventCategory: 'form_submit', // Значення параметру "event_category"
        eventAction: 'send', // Значення параметру "event_name"
        eventLabel: `Form ID: ${formID}`
      });
    }
  
    // if (thanksName !== null) {
    //   thankOpen(document.querySelector(`#popup-${thanksName.dataset.thanks}`));
    // } else 
    if (popupThanks !== undefined) {
      thankOpen(popupThanks);
    }
  
    const thankValue = formInput ? formInput.value : formID;
    window.history.pushState('1', 'Thank-you', `?thank-you=${thankValue}`);
  }, false);

  // Заборонити повторне відправлення форми CF7
  document.querySelector('.wpcf7-form').addEventListener('submit', (event) => {
    event.preventDefault(); // Зупиняємо стандартну поведінку форми
    const submitButton = event.target.querySelector('.wpcf7-submit');
    submitButton.disabled = true;
  });

  if (params['thank-you']) {
    const url = new URL(document.location);
    const searchParams = url.searchParams;
    searchParams.delete('thank-you');
    window.history.pushState({}, '', url.toString());
  }
}
