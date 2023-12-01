"use strict";
var KTWizard = function() {
    const steppers = () => {
        var element = document.querySelector("#kt_stepper_basic");
        if(element != null)
        {
            var stepper = new KTStepper(element);
            console.log(stepper);
            stepper.on("kt.stepper.next", function (stepper) {
                stepper.goNext();
            });
            stepper.on("kt.stepper.previous", function (stepper) {
                stepper.goPrevious();
            });
        }
    }
    return {
        init: function() {
            steppers();
        }
    }
}();
KTUtil.onDOMContentLoaded((function() {
    KTWizard.init();
}));