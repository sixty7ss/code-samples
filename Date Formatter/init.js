import FixDate from './components/DateFormatter';

export default class Block_X {
  constructor(element) {
    this.element = element;
    this.fixDate();
    this.ready();
  }

  fixDate() {
    this.element.querySelectorAll('.node').forEach((elem) => {
      if (!elem.formatDate) {
        elem.formatDate = new DateFormatter(elem);
      }
    });
  }

  ready() {
    this.element.classList.add('ready');
  }
}
