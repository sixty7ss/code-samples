import $ from 'jquery';

const defaults = {
  title: '.node-header h1',
  divider: '<br>',
};

function StringSplit(element, options) {
  this.element = element;
  this.$element = $(element);
  this.settings = Object.assign(defaults, options);
  this.init();
  this.ready();
}

StringSplit.prototype = {
  init() {
    // hack in differences in font size when a specific selector or character is present
    $(this.settings.title, this.element).each((i, title) => {
      const html = $(title).html();
      const divider = new RegExp(this.settings.divider);
      if (divider.test(html)) {
        const a = html.split(this.settings.divider);
        $(title).addClass('splitText').html(`<span>${a[0]}</span><span>${a[1]}</span>`);
      }
    });
  },
  ready() {
    this.$element.addClass('ready');
  },
};

export default StringSplit;
