const $ = require('jquery');
const Hammer = require('hammerjs');

const mc = new Hammer(document.body);

const liquidList = $("#liquid-list");
const flavourList = $("#flavour-list");

mc.on('swipeleft swiperight', ev => {

  if (window.innerWidth <= 1024) {
    if (ev.type === "swipeleft") {
      if (liquidList.hasClass("open")) {
        liquidList.removeClass("open");
      } else {
        flavourList.addClass("open");
      }
    } else if (ev.type === "swiperight") {
      if (flavourList.hasClass("open")) {
        flavourList.removeClass("open");
      } else {
        liquidList.addClass("open");
      }
    }
  }
});