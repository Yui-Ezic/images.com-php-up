function toggleElement(element, time = 0) {
    if ($(element).is(":visible")) {
        $(element).hide(time);
        console.log("Hide " + element);
    } else {
        $(element).show(time);
        console.log("Show " + element);
    }
}

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        if ($("#toTop").is(":hidden")) {
            $("#toTop").css("display", "flex").hide().show("slow");
        }
    } else {
        if ($("#toTop").is(":visible")) {
            $("#toTop").hide("slow");
        }
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}


jQuery(document).ready(function($) {
    // Control search in header
    $("#searchButton").click(function() {
        toggleElement("#searchForm", 500);
    });

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() { scrollFunction() };
});