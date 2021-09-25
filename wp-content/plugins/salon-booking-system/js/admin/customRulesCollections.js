"use strict";

jQuery(function() {
    jQuery(".sln-booking-rules").each(function() {
        sln_initBookingRules(jQuery(this));
    });
    jQuery(".sln-booking-holiday-rules").each(function() {
        sln_initBookingHolidayRules(jQuery(this));
    });

    jQuery("body").on("change", "[data-unhide]", function() {
        jQuery(jQuery(this).data("unhide")).toggle(
            jQuery(this).is(":checked") ? false : true
        );
        if (jQuery(this).is(":checked")) {
            console.log("checked");
            jQuery(jQuery(this).data("unhide")).removeClass(
                "sln-box--appeared"
            );
        } else {
            console.log("not checked");
            jQuery(jQuery(this).data("unhide")).addClass("sln-box--appeared");
        }
    });
    jQuery("[data-unhide]").trigger('change');

    jQuery("body").on(
        "change",
        ".sln-disable-second-shift input",
        sln_toggleSecondShift
    );
    jQuery(".sln-disable-second-shift input").trigger("change");
});

function sln_bindRemoveFunction() {
    jQuery(this)
        .parent()
        .parent()
        .parent()
        .remove();
    return false;
}

function sln_bindRemove() {
    jQuery('button[data-collection="remove"]')
        .off("click", sln_bindRemoveFunction)
        .on("click", sln_bindRemoveFunction);
}

function sln_bindDisableSecondShift() {
    jQuery(".sln-disable-second-shift input")
	.off("change", sln_bindDisableSecondShiftFunction)
        .on("change", sln_bindDisableSecondShiftFunction);
}

function sln_bindDisableSecondShiftFunction() {
    jQuery(this)
        .closest(".sln-booking-rule")
        .find(
            ".sln-second-shift .slider-time-input-from, .sln-second-shift .slider-time-input-to"
        )
        .prop("disabled", jQuery(this).prop("checked"));
}

function sln_initBookingRules(elem) {
    var prototype = elem.find('div[data-collection="prototype"]');
    var wrapper = elem.find(".sln-booking-rules-wrapper");
    var html = prototype.html();
    var count = prototype.data("count");
    prototype.remove();

    jQuery('button[data-collection="addnew"]', elem).on('click', function(e) {
        count++;
        e.preventDefault();
        wrapper.append(html.replace(/__new__/g, count));
        sln_bindRemove();
        sln_bindDisableSecondShift();

        sln_initDatepickers(jQuery);
        sln_initTimepickers(jQuery);
        sln_customSliderRange(jQuery, jQuery(".slider-range"));
        jQuery("[data-unhide]", elem).trigger('change');
        jQuery(".sln-booking-rule:last-child input", elem).prop( "checked", true );
    });
    sln_bindRemove();
    sln_bindDisableSecondShift();
}

function sln_initBookingHolidayRules(elem) {
    var prototype = elem.find('div[data-collection="prototype"]');
    var html = prototype.html();
    var count = prototype.data("count");
    var wrapper = elem.find(".sln-booking-holiday-rules-wrapper");
    prototype.remove();

    jQuery('button[data-collection="addnewholiday"]', elem).on('click', function(e) {
        e.preventDefault();
        wrapper.append(html.replace(/__new__/g, count));
        count++;
        sln_initDatepickers(jQuery);
        sln_initTimepickers(jQuery);
        sln_bindRemove();
    });
    sln_bindRemove();
}

function sln_toggleSecondShift(e) {
    var disable = jQuery(this).prop("checked");
    if (disable) {
        jQuery(this)
            .parent()
            .parent()
            .parent()
            .find(
                'input[name="salon_settings[availabilities][1][from][1]"],input[name="salon_settings[availabilities][1][to][1]"]'
            )
            .attr("disabled", "disabled");
        jQuery(this)
            .parent()
            .parent()
            .parent()
            .find(".sln-slider-wrapper-second-shift")
            .removeClass("sln-slider--disabled")
            .removeAttr("hidden")
            .find(".sln-slider__inner")
            .hide();
    } else {
        jQuery(this)
            .parent()
            .parent()
            .parent()
            .find(".sln-slider-wrapper-second-shift")
            .find(
                'input[name="salon_settings[availabilities][1][from][1]"],input[name="salon_settings[availabilities][1][to][1]"]'
            )
            .removeAttr("disabled");
        jQuery(this)
            .parent()
            .parent()
            .parent()
            .find(".sln-slider-wrapper-second-shift")
            .addClass("sln-slider--disabled")
            .find(".sln-slider__inner")
            .show();
    }
}
