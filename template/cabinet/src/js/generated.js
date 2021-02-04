function CustomSlider() {
    this.el = null;
};

CustomSlider.prototype.init = function($el, options = {}) {
    this.el = $el;
    this.isMobile = false;
    this.mode = options.mode || "horizonral";

    this.checkIfMobile();

    this.slide = this.el.querySelector(".slide");
    this.track = this.el.querySelector(".slider-track");
    this.slidesLength = this.el.querySelectorAll(".slide").length;

    this.prevButton = this.el.querySelector(".prev-slide");
    this.nextButton = this.el.querySelector(".next-slide");

    this.currentMoveQty = 0;

    this.reachedStart = false;
    this.reachedEnd = false;

    this.scrolledEnd = false;
    this.loadingData = false;

    (this.isVertical() && !this.isMobile)
        ? this.initVertical()
        : this.initHorizontal();

    const context = this;
  
    this.prevButton.addEventListener("click", function() {
        context.slidePrev();
    });

    this.nextButton.addEventListener("click", function() {        
        if (!context.reachedEnd) {
            context.slideNext();
        }
    });
};

CustomSlider.prototype.initHorizontal = function() {
    this.slideWidth = this.slide.getBoundingClientRect().width;
    this.trackWidth = ((this.slideWidth + 15) * this.slidesLength) - 15;
    this.moveQty = this.slideWidth + 15;
    this.setTrackWidth();
};

CustomSlider.prototype.initVertical = function() {
    this.el.classList.add("vertical");
    this.slideHeight = this.slide.getBoundingClientRect().height;
    this.trackHeight = ((this.slideHeight + 15) * this.slidesLength) - 15;
    this.moveQty = this.slideHeight + 15;
    this.setTrackHeight();
};

CustomSlider.prototype.scrollhandler = function() {
    const sliderRight = this.el.getBoundingClientRect().right.toFixed(2);
    const trackRight = this.track.getBoundingClientRect().right.toFixed(2);
    const delta = (trackRight - sliderRight).toFixed(2);

    if (delta < 10) {
        this.el.classList.add("reached-end");
        this.scrolledEnd = true;
    }
};

CustomSlider.prototype.update = function() {
    this.reachedEnd = false;
    this.scrolledEnd = false;
    this.loadingData = false;
    this.slidesLength = this.el.querySelectorAll(".slide").length;

    (this.isVertical() && !this.isMobile)
        ? this.updateVertical()
        : this.updateHorizontal();
    this.el.classList.remove("reached-end");
};

CustomSlider.prototype.updateHorizontal = function() {
    this.trackWidth = ((this.slideWidth + 15) * this.slidesLength) - 15;
    this.setTrackWidth();
}

CustomSlider.prototype.updateVertical = function() {
    this.trackHeight = ((this.slideHeight + 15) * this.slidesLength) - 15;
    this.setTrackHeight();
}

CustomSlider.prototype.slidePrev = function() {
    this.reachedStart = this.checkIfReachedStart();
    this.reachedEnd = false;
    this.el.classList.remove("reached-end");

    if (!this.reachedStart) {
        this.currentMoveQty -= this.moveQty;
        this.translateTo(this.currentMoveQty);
    }
};

CustomSlider.prototype.slideNext = function() {
    if (!this.reachedEnd) {
        this.currentMoveQty += this.moveQty;
        this.translateTo(this.currentMoveQty);
    }

    this.showEl(this.prevButton);
    this.reachedEnd = this.checkIfReachedEnd();
};

CustomSlider.prototype.checkIfReachedStart = function() {
    const delta = this.isVertical()
        ? this.calculateDelta("top")
        : this.calculateDelta("left");

    if (Math.abs(delta) <= this.moveQty) {
        this.currentMoveQty = 0;
        this.translateTo(this.currentMoveQty);
        this.hideEl(this.prevButton);
        return true;
    }

    return false;
};

CustomSlider.prototype.checkIfReachedEnd = function() {
    const delta = this.isVertical()
        ? this.calculateDelta("bottom")
        : this.calculateDelta("right");

    if (delta < (this.moveQty * 2)) {
        this.el.classList.add("reached-end");
        return true;
    }

    return false;
};

CustomSlider.prototype.setTrackWidth = function() {
    this.track.style.width = this.trackWidth + "px";
};

CustomSlider.prototype.setTrackHeight = function() {
    this.track.style.height = this.trackHeight + "px";
};

CustomSlider.prototype.calculateDelta = function(angle) {
    const sliderSide = this.el.getBoundingClientRect()[angle].toFixed(2);
    const trackSide = this.track.getBoundingClientRect()[angle].toFixed(2);
    return trackSide - sliderSide;
};

CustomSlider.prototype.translateTo = function(position) {
    const angle = this.isVertical() ? "Y" : "X";
    this.track.style.transform = `translate${angle}(${-position}px)`;
};

CustomSlider.prototype.hideEl = function(el) {
    el.style.display = "none";
};

CustomSlider.prototype.showEl = function(el) {
    el.style.display = "block";
};

CustomSlider.prototype.isVertical = function() {
    return this.mode === "vertical";
};

CustomSlider.prototype.checkIfMobile = function() {
    if (navigator.userAgent.match(/(iPad|iPhone|iPod|Android|playbook|silk|BlackBerry|BB10|Tizen|webOS|Opera Mini|HTC_)/i)) {
        this.isMobile = true;
        this.el.classList.add("is-mobile");
    }
};

// *****************************

const horizontalSlider = new CustomSlider();
const verticalSlider = new CustomSlider();

window.verticalSlider = verticalSlider;

horizontalSlider.init(document.querySelector(".horizontal-preview .custom-slider"));
verticalSlider.init(document.querySelector(".vertical-preview .custom-slider"), {
    mode: "vertical"
});

// $("select[name='direction']").on("change", function() {
//     const value = this.value;

//     if (value === "vertical") {
//         $(".horizontal-preview").removeClass("visible");
//         $(".vertical-preview").addClass("visible");
//     } else {
//         $(".vertical-preview").removeClass("visible");
//         $(".horizontal-preview").addClass("visible");
//     }
// });
