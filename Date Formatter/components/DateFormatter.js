import $ from 'jquery';

const defaults = {
  date: undefined,
  start: {
    month: undefined,
    day: undefined,
    year: undefined,
  },
  end: {
    month: undefined,
    day: undefined,
    year: undefined,
  },
  hasStartTime: false,
  hasEndTime: false,
};

export default class DateFormatter {
  constructor(element, options) {
    this.element = element;
    this.$element = $(element);
    this.settings = { ...defaults, ...options };
    this.fixDate();
    this.ready();
  }

  fixDate() {
    const { element, $element, settings } = this;
    const hasPostDate = $element.find('.post-date').length > 0;
    const isEvent = $element.find('.post-date').is('.upcoming');
    const isTwitterFeed = $element.find('.handle + .date').length > 0;

    // Check if node has a post date, but is NOT an event
    if (hasPostDate && !isEvent) {
      const dateContent = element.querySelector('.post-date time').childNodes[0].nodeValue;
      this.extractDate(dateContent);
      $element.find('.post-date time').html(`<span class="date-time-rendered">${settings.start.month}.${settings.start.day}.${settings.start.year}</span>`);
      $element.find('.post-date').show();

    // Check if node is from a Twitter feed
    } else if (isTwitterFeed) {
      const tweetContent = element.querySelector('.handle + .date time').childNodes[0].nodeValue;
      // Extract date data
      this.extractDate(tweetContent);
      $element.find('.handle + .date').html(`${settings.start.month}.${settings.start.day}.${settings.start.year}`);
    } else {
      let newDateString;

      // Check if event date exists
      const hasEventDate = element.querySelector('.upcoming.first-upcoming time > span[class^="date-display-"]').childNodes[0].nodeValue;
      if (hasEventDate) {
        const isMultiDay = $element.find('time > .date-display-start').length > 0;
        const isMultiTime = $element.find('.date-display-single > .date-display-start').length > 0;

        // Check if event is more than one day
        if (isMultiDay) {
          const startDate = element.querySelector('.date-display-start').childNodes[0].nodeValue;
          const endDate = element.querySelector('.date-display-end').childNodes[0].nodeValue;
          // Extract start date data
          this.extractDate(startDate);
          // Extract end date data
          this.extractDate(endDate, true);
          newDateString = `<span class="date-time-rendered">${settings.start.month}.${settings.start.day}${(settings.start.year !== settings.end.year) ? `.${settings.start.year}` : ''} - ${settings.end.month}.${settings.end.day}.${(settings.start.year !== settings.end.year) ? settings.start.year : settings.end.year}</span>`;

        // Does event have a start and end time
        } else if (isMultiTime) {
          const hasEndTime = element.querySelector('.date-display-end').childNodes[0].nodeValue.length > -1;
          const startTime = element.querySelector('.date-display-start').childNodes[0].nodeValue;
          const date = element.querySelector('.date-display-single').childNodes[0].nodeValue;
          // Extract end date data
          this.extractDate(date);
          // Extract start time data
          this.extractTime(startTime);
          if (hasEndTime) {
            const endTime = element.querySelector('.date-display-end').childNodes[0].nodeValue;
            // Extract end time data
            this.extractTime(endTime, true);
          }
          newDateString = `<span class="date-time-rendered">${settings.start.month}.${settings.start.day}.${settings.start.year} ${settings.start.time}${(hasEndTime) ? `-${settings.end.time}` : ''}</span>`;

        // Basic date data
        } else {
          const eventContent = element.querySelector('.upcoming.first-upcoming time > span[class^="date-display-"]').childNodes[0].nodeValue;
          this.extractDate(eventContent);
          newDateString = `<span class="date-time-rendered">${settings.start.month} ${settings.start.day}</span>`;
        }
      }

      // Replace date/time with formatted version
      $element.find('.post-date time').html(newDateString);
    }
  }

  // Separate date string into usable variables
  extractDate(dateText, isEnd) {
    const newDateArr = dateText.split(' ');
    const newMonth = newDateArr[0];
    const arrLength = newDateArr.length;
    const hasTime = newDateArr[arrLength - 1].includes(':');
    const e = (isEnd) ? 'end' : 'start';
    this.settings[e].month = this.abbrevMonth(newMonth);
    this.settings[e].day = newDateArr[1].substring(0, newDateArr[1].length - 1);
    this.settings[e].year = newDateArr[2].substring(0, newDateArr[2].length);
    if (hasTime) {
      this.extractTime(newDateArr[arrLength - 1], isEnd);
    }
  }

  // Separate time string into usable variables
  extractTime(timeText, isEnd) {
    const newTimeArr = timeText.split(':');
    const newHour = newTimeArr[0];
    const temp = newTimeArr[1];
    let newMinute = (temp) ? temp.substring(0, 2) : '';
    const ampm = (temp) ? temp.substring(2, 4) : '';
    newMinute = (newMinute === '00') ? ampm : `:${newMinute}${ampm}`;
    const e = (isEnd) ? 'end' : 'start';
    this.settings[e].time = `${newHour}${newMinute}`;
  }

  // Change formatting of month
  /* eslint-disable */
  abbrevMonth(month) {
    let newMonth;
    switch (month) {
      case 'January': newMonth = '1'; break;
      case 'February': newMonth = '2'; break;
      case 'March': newMonth = '3'; break;
      case 'April': newMonth = '4'; break;
      case 'May': newMonth = '5'; break;
      case 'June': newMonth = '6'; break;
      case 'July': newMonth = '7'; break;
      case 'August': newMonth = '8'; break;
      case 'September': newMonth = '9'; break;
      case 'October': newMonth = '10'; break;
      case 'November': newMonth = '11'; break;
      case 'December': newMonth = '12'; break;
      default: newMonth = 'N/A';
    }
    return newMonth;
  }
  /* eslint-enable */

  // Add ready class to block
  ready() {
    this.element.classList.add('ready');
  }
}
