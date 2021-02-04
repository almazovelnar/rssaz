import CustomSlider from "./slider";

const slider = new CustomSlider();
const sliderTemplate = `
    <div class="custom-slider"><div class="slider-track"></div><div class="prev-slide"></div><div class="next-slide"></div></div>
    <style>.custom-slider{margin:0 auto;position:relative;overflow:hidden;z-index:1}.custom-slider *{box-sizing:border-box}.custom-slider.is-mobile{overflow-x:auto;overflow:-moz-scrollbars-none;-ms-overflow-style:none}.custom-slider.is-mobile .prev-slide,.custom-slider.is-mobile .next-slide{display:none}.custom-slider.is-mobile::-webkit-scrollbar{width:0!important}.custom-slider.reached-end.is-mobile{opacity:.7;user-select:none;pointer-events:none}.custom-slider.reached-end .next-slide{user-select:none;pointer-events:none;opacity:.7}.custom-slider.reached-end .slide:nth-last-of-type(-n+2){user-select:none;pointer-events:none}.custom-slider .slider-track{position:relative;display:flex;width:100%;height:100%;z-index:1;transition:transform .3s ease}.custom-slider .slider-track .slide{margin:0 15px 0 0;height:220px}.custom-slider .slider-track .slide:last-child{margin:0}.custom-slider .slide-post{position:relative;display:block;min-width:190px;width:190px;height:100%;background:#000;border-radius:4px;overflow:hidden}.custom-slider .slide-post a{display:block;text-decoration:none}.custom-slider .slide-post .slide-post__image{position:absolute;top:0;left:0;width:100%;height:80%;background:no-repeat center;background-size:cover}.custom-slider .slide-post .gradient{position:absolute;bottom:0;left:0;width:100%;height:55%;background-image:linear-gradient(to bottom,rgba(0,0,0,0),rgba(0,0,0,0.8),black,black)}.custom-slider .slide-post .slide-post__info{display:flex;align-items:flex-end;position:relative;font-family:Arial,"Helvetica Neue",Helvetica,sans-serif;font-size:14px;line-height:1.3;font-weight:600;height:100%;color:#fff;padding:15px 15px 12px 15px;z-index:2}.custom-slider .next-slide,.custom-slider .prev-slide{position:absolute;top:calc(50% - 34px);left:-70px;width:42px;height:68px;border-radius:3px;background:#fff;box-shadow:0 0 20px 0 rgba(0,0,0,0.18);cursor:pointer;z-index:5}.custom-slider .prev-slide{transform:rotate(-180deg);display:none}.custom-slider .next-slide:before,.custom-slider .prev-slide:before{content:"";position:absolute;top:calc(50% - 12px);left:16px;border-top:12px solid transparent;border-bottom:12px solid transparent;border-left:12px solid #474747}.custom-slider .next-slide{left:auto;right:-70px}.custom-slider:hover .prev-slide{left:15px}.custom-slider:hover .next-slide{right:15px}.custom-slider.vertical{min-height:270px;max-height:841px;height:100%!important;}.custom-slider.vertical .slider-track{display:block}.custom-slider.vertical .slide{position:relative;margin:0 0 15px 0;height:270px}.custom-slider.vertical .slide-post{position:absolute;top:0;left:0;width:100%!important;height:100%}.custom-slider.vertical .prev-slide{display:none;top:-70px;left:calc(50% - 21px)!important;transform:rotate(-90deg)}.custom-slider.vertical .next-slide{display:block;top:auto;bottom:-70px;right:auto;left:calc(50% - 21px);transform:rotate(90deg)}.custom-slider.vertical:hover .prev-slide{top:0}.custom-slider.vertical:hover .next-slide{bottom:0}</style>
`;

let sid;
let blockId = "{blockId}";
let mode = "{bannersDirection}"
const sliderContainer = document.getElementById("slider-" + blockId);
let cssOptions = '{cssOptions}';
blockId += Math.floor((Math.random() * 100) + 1);
sliderContainer.setAttribute('id',  "slider-" + blockId);

// Initial load posts. 
jsLoad("{urlToData}", () => {
    waitForData().then((data) => {
        sliderContainer.setAttribute("data-sid", data.sid);
        renderTemplate(sliderTemplate);
        handleJsLoadResponse(data.blocks, () => slider.init(sliderContainer.querySelector(".custom-slider"), {
            mode: mode
        }));
        window.rssData.blocks = [];
    });
});

// Load posts on nextSlide click.
sliderContainer.addEventListener("click", function(e) {
    const target = e.target;
    if (target.classList.contains("next-slide")) {
        if (typeof sid === "undefined") sid = target.closest(`#slider-${blockId}`).getAttribute("data-sid");
        slider.reachedEnd && jsLoad("{urlToData}", () => {
            waitForData().then((data) => {
                handleJsLoadResponse(data.blocks, () => slider.update());
                window.rssData.blocks = [];
            });
        }, {sid});
    }
});

// Load posts on scroll
sliderContainer.addEventListener("scroll", function(e) {
    const target = e.target;
    if (target.classList.contains("custom-slider")) {
        slider.scrollhandler();
        if (slider.scrolledEnd && !slider.loadingData) {
            slider.loadingData = true;
            if (typeof sid === "undefined") sid = target.closest(`#slider-${blockId}`).getAttribute("data-sid");
            jsLoad("{urlToData}", () => {
                waitForData().then((data) => {
                    handleJsLoadResponse(data.blocks, () => slider.update());
                    window.rssData.blocks = [];
                });
            }, {sid});
        }
    }
}, true);

function jsLoad(src, callback, parameters= {}) {
    const htmlTag = document.documentElement;
    let symbol = '?';
    if (htmlTag.hasAttribute("lang")) {
        src += "?lang=" + htmlTag.lang.replace(/\W+/, '');
        symbol = '&';
    }
    if (parameters.hasOwnProperty("sid"))
        src += symbol + "sid=" + parameters.sid;

    removeElementBySrc(src);
    let scriptEl = document.createElement("script");
    scriptEl.type = "text/javascript";
    scriptEl.async = true;
    scriptEl.src = src;
    let s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(scriptEl, s);

    callback();
}

function removeElementBySrc(src) {
    const el = document.querySelector("script[src='"+ src +"']");
    if (el !== null) el.parentNode.removeChild(el);
}

function handleJsLoadResponse(data, callback) {
    let slides = "";
    for (let i = 0; i < data.length; i++) {
        let block = data[i];
        let color = block.color;
        let titleColor = checkColorDarkness(color) ? "black" : "white";

        slides += `
            <div class="slide">
                <div class="slide-post" style="background-color: rgb(${color});">
                    <div class="slide-post__image" style="background-image: url(${block.image})"></div>
                    <div
                        class="gradient"
                        style="background-image: linear-gradient(to bottom, rgba(${color}, 0), rgba(${color}, .8), rgba(${color}, 1), rgba(${color}, 1))"></div>
                    <a class="slide-post__info" href="${block.url}" target="_blank" style="color: ${titleColor} !important;">
                        <span>${block.title}</span>
                    </a>
                </div>
            </div>
        `;
    }
    sliderContainer.querySelector(".slider-track").innerHTML += slides;
    callback();
}
 
function renderTemplate(template) {
    template += applyStyles();
    sliderContainer.innerHTML = template;
}

function applyStyles() {
    const styleObj = JSON.parse(cssOptions);
    const { fontFamily, fontWeight, fontSize, width } = styleObj;

    let styles = `
        .slide-post {width: ${width};}
        .slide-post span {font-family: ${fontFamily}; font-size: ${fontSize}; font-weight: ${fontWeight};}
    `;
    return `<style>${styles}</style>`;
}

function checkColorDarkness(color) {
    if (color.length === 0) return false;
    const darkness = (color[0] * 0.299) + (color[1] * 0.587) + (color[2] * 0.114);
    return (darkness > 186);
}

function waitForData(timeout = 500){
    // Setting default value for data.
    if (!window.hasOwnProperty('rssData')) window.rssData = [];
    if (!window.rssData.hasOwnProperty('blocks')) window.rssData.blocks = [];

    return new Promise((resolve, reject) => {
        if (window.rssData.blocks.length === 0) {
            setTimeout(() => resolve(waitForData()), timeout);
        } else {
            return resolve(window.rssData);
        }
    });
}
