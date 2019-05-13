var slideshow = {
    previousElt: document.getElementById("prev"),
    nextElt: document.getElementById("next"),


    changeSlide: function(slide){
        this.slider(slideIndex += slide);
    },

    slider: function(slideNumber){
        var slides = document.getElementsByClassName("slide");
        if (slideNumber > slides.length){
            slideIndex = 1;
        }

        if (slideNumber < 1) {
            slideIndex = slides.length;
        }

        for (var i = 0; i < slides.length; i++){
            slides[i].style.display = "none";
            }
        
        slides[slideIndex-1].style.display = "block";
    },

    init: function(){
        this.slider(1);
        var _this = this;
        document.onkeydown = function(e) {
            switch (e.keyCode) {
                case 37:
                    _this.changeSlide(-1);
                    break;
                case 39:
                    _this.changeSlide(+1);
                    break;
            }
        };

        this.previousElt.addEventListener("click", function(){
            _this.changeSlide(-1);
        });

        this.nextElt.addEventListener("click", function(){
                _this.changeSlide(+1);
        });
    }
};

slideshow.init();