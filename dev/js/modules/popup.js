import getCookie from "./getCookie.js";

const PopupBG = document.querySelector('.popup-bg');
const Popups = document.querySelectorAll('.popup');
const closeBttn = document.querySelectorAll('.popup__bttn-close');
const windowHash = window.location.hash;
const windowWidth = window.innerWidth;
const documentWidth = document.body.clientWidth;

const showPopup = (id, dataset) => {
  const Popup = document.querySelector(id) ? document.querySelector(id) : false;

  if (Popup) {
    Popup.classList.add('show');
    PopupBG.classList.add('show');
    document.body.classList.add('scroll-disable');
    document.body.style.paddingRight = windowWidth - documentWidth + 'px';

    if(dataset) {
      const dataTitle = dataset.popupTitle;
      const dataSubtitle = dataset.popupSubtitle;
      const dataDescr = dataset.popupDescr;

      const PopupTitle = Popup.querySelector('.popup__title');
      const PopupSubtitle = Popup.querySelector('.popup__subtitle');
      const PopupDescr = Popup.querySelector('.popup__descr');

      PopupTitle && (PopupTitle.textContent = dataTitle ? dataTitle : PopupTitle.dataset.text);
      PopupSubtitle && (PopupSubtitle.textContent = dataSubtitle ? dataSubtitle : PopupSubtitle.dataset.text);
      PopupDescr && (PopupDescr.textContent = dataDescr ? dataDescr : PopupDescr.dataset.text);
    }
  }
};

const hidePopup = () => {
  PopupBG.classList.remove('show');
  document.body.classList.remove('scroll-disable');
  Popups.forEach(Popup => Popup.classList.remove('show'));
  document.body.style.paddingRight = 0;
}

if(windowHash.includes('#popup-')) {
  document.addEventListener('DOMContentLoaded', showPopup(windowHash));
}

PopupBG.addEventListener('click', hidePopup);
closeBttn.forEach(bttn => bttn.addEventListener('click', hidePopup));

Popups.forEach((Popup) => {
  const timeout = Number(Popup.dataset.timeout * 1000);
  const showAgainTimeout = Number(Popup.dataset.againTimeout);
  const id = Popup.id;

  Popup.addEventListener('click', e => e.stopPropagation());
  Popup.querySelector('.popup__close').addEventListener('click', hidePopup);

  const PopupTitle = Popup.querySelector('.popup__title');
  const PopupSubtitle = Popup.querySelector('.popup__subtitle');
  const PopupDescr = Popup.querySelector('.popup__descr');

  PopupTitle && (PopupTitle.dataset.text = PopupTitle.textContent);
  PopupSubtitle && (PopupSubtitle.dataset.text = PopupSubtitle.textContent);
  PopupDescr && (PopupDescr.dataset.text = PopupDescr.textContent);

  if(showAgainTimeout < 1 && getCookie(id) == 'true') {
    const pastExpirationDate = new Date(0);
    document.cookie = id+'=; expires=' + pastExpirationDate.toUTCString() + '; path=/';
  }

  if(timeout >= 1000 && getCookie(id) != 'true' && !windowHash) {
    setTimeout(() => {
      hidePopup();
      showPopup('#'+id);

      let expirationDate = new Date();
      expirationDate.setDate(expirationDate.getDate() + showAgainTimeout);
  
      document.cookie = id+'=true; expires=' + expirationDate.toUTCString() + '; path=/';
    }, timeout);
  }
});

document.querySelectorAll('a').forEach((link) => {
  if(link.href.includes('#popup-')) {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      
      showPopup(e.currentTarget.hash, e.target.dataset);
    })
  }
});