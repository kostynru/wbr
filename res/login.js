$(function() {
    var bg_arr = [
        "bg-prof.jpg",
        "sativa.png",
        "space.jpg",
        "stardust.png",
        "retina_wood.png",
        "space2.jpg"
    ];
    var bg = Math.floor(Math.random() * (bg_arr.length-1) + 1);
    $('body').css('background-image', "url(/wbr/res/" + bg_arr[bg] + ")");
});