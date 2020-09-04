import StringSplit from '../components/StringSplit';

export default class Block_X {
  constructor(element) {
    this.element = element;
    this.strSplit();
    this.ready();
  }

  strSplit() {
    this.element.querySelectorAll('.block-title').forEach((news) => {
      if (!news.strSplit) {
        news.strSplit = new StringSplit(news, {
          title: '.block-title-text',
          divider: '|',
        });
      }
    });
  }

  ready() {
    this.element.classList.add('ready');
  }
}
