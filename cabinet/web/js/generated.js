!function(t){var e={};function i(s){if(e[s])return e[s].exports;var o=e[s]={i:s,l:!1,exports:{}};return t[s].call(o.exports,o,o.exports,i),o.l=!0,o.exports}i.m=t,i.c=e,i.d=function(t,e,s){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:s})},i.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var s=Object.create(null);if(i.r(s),Object.defineProperty(s,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)i.d(s,o,function(e){return t[e]}.bind(null,o));return s},i.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="",i(i.s=12)}({12:function(t,e,i){"use strict";function s(){this.el=null}i.r(e),s.prototype.init=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};this.el=t,this.isMobile=!1,this.mode=e.mode||"horizontal",this.checkIfMobile(),this.slide=this.el.querySelector(".slide"),this.track=this.el.querySelector(".slider-track"),this.slidesLength=this.el.querySelectorAll(".slide").length,this.prevButton=this.el.querySelector(".prev-slide"),this.nextButton=this.el.querySelector(".next-slide"),this.currentMoveQty=0,this.reachedStart=!1,this.reachedEnd=!1,this.scrolledEnd=!1,this.loadingData=!1,this.isVertical()&&!this.isMobile?this.initVertical():this.initHorizontal();var i=this;this.prevButton.addEventListener("click",(function(){i.slidePrev()})),this.nextButton.addEventListener("click",(function(){i.reachedEnd||i.slideNext()}))},s.prototype.initHorizontal=function(){this.slideWidth=this.slide.getBoundingClientRect().width,this.trackWidth=(this.slideWidth+15)*this.slidesLength-15,this.moveQty=this.slideWidth+15,this.setTrackWidth()},s.prototype.initVertical=function(){this.el.classList.add("vertical"),this.slideHeight=this.slide.getBoundingClientRect().height,this.trackHeight=(this.slideHeight+15)*this.slidesLength-15,this.moveQty=this.slideHeight+15,this.setTrackHeight()},s.prototype.scrollhandler=function(){var t=this.el.getBoundingClientRect().right.toFixed(2);(this.track.getBoundingClientRect().right.toFixed(2)-t).toFixed(2)<10&&(this.el.classList.add("reached-end"),this.scrolledEnd=!0)},s.prototype.update=function(){this.reachedEnd=!1,this.scrolledEnd=!1,this.loadingData=!1,this.slidesLength=this.el.querySelectorAll(".slide").length,this.isVertical()&&!this.isMobile?this.updateVertical():this.updateHorizontal(),this.el.classList.remove("reached-end")},s.prototype.updateHorizontal=function(){this.trackWidth=(this.slideWidth+15)*this.slidesLength-15,this.track.style.width=this.trackWidth+"px"},s.prototype.updateVertical=function(){this.trackHeight=(this.slideHeight+15)*this.slidesLength-15,this.track.style.height=this.trackHeight+"px"},s.prototype.setTrackWidth=function(){this.track.style.width=this.trackWidth+"px"},s.prototype.setTrackHeight=function(){this.track.style.height=this.trackHeight+"px"},s.prototype.slidePrev=function(){this.reachedStart=this.checkIfReachedStart(),this.reachedEnd=!1,this.el.classList.remove("reached-end"),this.reachedStart||(this.currentMoveQty-=this.moveQty,this.translateTo(this.currentMoveQty))},s.prototype.slideNext=function(){this.reachedEnd||(this.currentMoveQty+=this.moveQty,this.translateTo(this.currentMoveQty)),this.showEl(this.prevButton),this.reachedEnd=this.checkIfReachedEnd()},s.prototype.checkIfReachedStart=function(){var t=this.isVertical()?this.calculateDelta("top"):this.calculateDelta("left");return Math.abs(t)<=this.moveQty&&(this.currentMoveQty=0,this.translateTo(this.currentMoveQty),this.hideEl(this.prevButton),!0)},s.prototype.checkIfReachedEnd=function(){return(this.isVertical()?this.calculateDelta("bottom"):this.calculateDelta("right"))<2*this.moveQty&&(this.el.classList.add("reached-end"),!0)},s.prototype.calculateDelta=function(t){var e=this.el.getBoundingClientRect()[t].toFixed(2);return this.track.getBoundingClientRect()[t].toFixed(2)-e},s.prototype.translateTo=function(t){var e=this.isVertical()?"Y":"X";this.track.style.transform="translate".concat(e,"(").concat(-t,"px)")},s.prototype.hideEl=function(t){t.style.display="none"},s.prototype.showEl=function(t){t.style.display="block"},s.prototype.isVertical=function(){return"vertical"===this.mode},s.prototype.checkIfMobile=function(){navigator.userAgent.match(/(iPad|iPhone|iPod|Android|playbook|silk|BlackBerry|BB10|Tizen|webOS|Opera Mini|HTC_)/i)&&(this.isMobile=!0,this.el.classList.add("is-mobile"))};var o,r=new s,n="{blockId}",l=document.getElementById("slider-"+n);function a(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},s=document.documentElement,o="?";s.hasAttribute("lang")&&(t+="?lang="+s.lang.replace(/\W+/,""),o="&"),i.hasOwnProperty("sid")&&(t+=o+"sid="+i.sid),c(t);var r=document.createElement("script");r.type="text/javascript",r.async=!0,r.src=t;var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(r,n),e()}function c(t){var e=document.querySelector("script[src='"+t+"']");null!==e&&e.parentNode.removeChild(e)}function d(t,e){for(var i="",s=0;s<t.length;s++){var o=t[s],r=o.color,n=h(r)?"black":"white";i+='\n            <div class="slide">\n                <div class="slide-post" style="background-color: rgb('.concat(r,');">\n                    <div class="slide-post__image" style="background-image: url(').concat(o.image,')"></div>\n                    <div\n                        class="gradient"\n                        style="background-image: linear-gradient(to bottom, rgba(').concat(r,", 0), rgba(").concat(r,", .8), rgba(").concat(r,", 1), rgba(").concat(r,', 1))"></div>\n                    <a class="slide-post__info" href="').concat(o.url,'" target="_blank" style="color: ').concat(n,' !important;">\n                        <span>').concat(o.title,"</span>\n                    </a>\n                </div>\n            </div>\n        ")}l.querySelector(".slider-track").innerHTML+=i,e()}function h(t){return 0!==t.length&&.299*t[0]+.587*t[1]+.114*t[2]>186}function u(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:500;return window.hasOwnProperty("rssData")||(window.rssData=[]),window.rssData.hasOwnProperty("blocks")||(window.rssData.blocks=[]),new Promise((function(e,i){if(0!==window.rssData.blocks.length)return e(window.rssData);setTimeout((function(){return e(u())}),t)}))}n+=Math.floor(100*Math.random()+1),l.setAttribute("id","slider-"+n),a("{urlToData}",(function(){u().then((function(t){var e,i,s,o,n,a,c;l.setAttribute("data-sid",t.sid),e='\n    <div class="custom-slider"><div class="slider-track"></div><div class="prev-slide"></div><div class="next-slide"></div></div>\n    <style>.custom-slider{margin:0 auto;position:relative;overflow:hidden;z-index:1}.custom-slider *{box-sizing:border-box}.custom-slider.is-mobile{overflow-x:auto;overflow:-moz-scrollbars-none;-ms-overflow-style:none}.custom-slider.is-mobile .prev-slide,.custom-slider.is-mobile .next-slide{display:none}.custom-slider.is-mobile::-webkit-scrollbar{width:0!important}.custom-slider.reached-end.is-mobile{opacity:.7;user-select:none;pointer-events:none}.custom-slider.reached-end .next-slide{user-select:none;pointer-events:none;opacity:.7}.custom-slider.reached-end .slide:nth-last-of-type(-n+2){user-select:none;pointer-events:none}.custom-slider .slider-track{position:relative;display:flex;width:100%;height:100%;z-index:1;transition:transform .3s ease}.custom-slider .slider-track .slide{margin:0 15px 0 0;height:220px}.custom-slider .slider-track .slide:last-child{margin:0}.custom-slider .slide-post{position:relative;display:block;min-width:190px;width:190px;height:100%;background:#000;border-radius:4px;overflow:hidden}.custom-slider .slide-post a{display:block;text-decoration:none}.custom-slider .slide-post .slide-post__image{position:absolute;top:0;left:0;width:100%;height:80%;background:no-repeat center;background-size:cover}.custom-slider .slide-post .gradient{position:absolute;bottom:0;left:0;width:100%;height:55%;background-image:linear-gradient(to bottom,rgba(0,0,0,0),rgba(0,0,0,0.8),black,black)}.custom-slider .slide-post .slide-post__info{display:flex;align-items:flex-end;position:relative;font-family:Arial,"Helvetica Neue",Helvetica,sans-serif;font-size:14px;line-height:1.3;font-weight:600;height:100%;color:#fff;padding:15px 15px 12px 15px;z-index:2}.custom-slider .next-slide,.custom-slider .prev-slide{position:absolute;top:calc(50% - 34px);left:-70px;width:42px;height:68px;border-radius:3px;background:#fff;box-shadow:0 0 20px 0 rgba(0,0,0,0.18);cursor:pointer;z-index:5}.custom-slider .prev-slide{transform:rotate(-180deg);display:none}.custom-slider .next-slide:before,.custom-slider .prev-slide:before{content:"";position:absolute;top:calc(50% - 12px);left:16px;border-top:12px solid transparent;border-bottom:12px solid transparent;border-left:12px solid #474747}.custom-slider .next-slide{left:auto;right:-70px}.custom-slider:hover .prev-slide{left:15px}.custom-slider:hover .next-slide{right:15px}.custom-slider.vertical{min-height:270px;max-height:841px;height:100%!important;}.custom-slider.vertical .slider-track{display:block}.custom-slider.vertical .slide{position:relative;margin:0 0 15px 0;height:270px}.custom-slider.vertical .slide-post{position:absolute;top:0;left:0;width:100%!important;height:100%}.custom-slider.vertical .prev-slide{display:none;top:-70px;left:calc(50% - 21px)!important;transform:rotate(-90deg)}.custom-slider.vertical .next-slide{display:block;top:auto;bottom:-70px;right:auto;left:calc(50% - 21px);transform:rotate(90deg)}.custom-slider.vertical:hover .prev-slide{top:0}.custom-slider.vertical:hover .next-slide{bottom:0}</style>\n',e+=(i=JSON.parse("{cssOptions}"),s=i.fontFamily,o=i.fontWeight,n=i.fontSize,a=i.width,c="\n        .slide-post {width: ".concat(a,";}\n        .slide-post span {font-family: ").concat(s,"; font-size: ").concat(n,"; font-weight: ").concat(o,";}\n    "),"<style>".concat(c,"</style>")),l.innerHTML=e,d(t.blocks,(function(){return r.init(l.querySelector(".custom-slider"),{mode:"{bannersDirection}"})})),window.rssData.blocks=[]}))})),l.addEventListener("click",(function(t){var e=t.target;e.classList.contains("next-slide")&&(void 0===o&&(o=e.closest("#slider-".concat(n)).getAttribute("data-sid")),r.reachedEnd&&a("{urlToData}",(function(){u().then((function(t){d(t.blocks,(function(){return r.update()})),window.rssData.blocks=[]}))}),{sid:o}))})),l.addEventListener("scroll",(function(t){var e=t.target;e.classList.contains("custom-slider")&&(r.scrollhandler(),r.scrolledEnd&&!r.loadingData&&(r.loadingData=!0,void 0===o&&(o=e.closest("#slider-".concat(n)).getAttribute("data-sid")),a("{urlToData}",(function(){u().then((function(t){d(t.blocks,(function(){return r.update()})),window.rssData.blocks=[]}))}),{sid:o})))}),!0)}});