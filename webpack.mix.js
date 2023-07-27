const mix = require("laravel-mix");
require('laravel-mix-string-replace');

mix
    .setPublicPath("nictitate/")
    .stringReplace(
        [
            {
                test: /style\.scss$/,
                loader: "string-replace-loader",
                options: {
                    search: "STYLE_VERSION",
                    replace: `2.0.${Math.floor(Date.now() / 1000)}`,
                },
            },
            {
                test: /owl.carousel\.css$/,
                loader: "string-replace-loader",
                options: {
                    search: "owl.video.play.png",
                    replace: "./images/owl-carousel/owl.video.play.png"
                },
            }
        ]
    ).options({
    processCssUrls: false
});

mix
    .sass("style.scss", "nictitate/")
    .sass("assets/sass/typography/default.scss", "nictitate/typography/")
    .sass("assets/sass/skin/default.scss", "nictitate/skin/")
    .sass("assets/sass/skin/custom.scss", "nictitate/skin/")
    .sass(
        "node_modules/bootstrap/scss/bootstrap.scss",
        "nictitate/css/bootstrap.css"
    )
    .css(
        "node_modules/@fortawesome/fontawesome-free/css/all.css",
        "nictitate/css/fontawesome.css"
    )
    .css(
        "node_modules/owl.carousel/dist/assets/owl.carousel.css",
        "nictitate/css/owl.carousel.css"
    )
    .css(
        "node_modules/owl.carousel/dist/assets/owl.theme.default.css",
        "nictitate/css/owl.theme.default.css"
    )
    .copy(
        "node_modules/owl.carousel/dist/owl.carousel.js",
        "nictitate/js/owl.carousel.js"
    )
    .css(
        "node_modules/superfish/dist/css/superfish.css",
        "nictitate/css/superfish.css"
    )
    .copy(
        "node_modules/superfish/dist/js/superfish.js",
        "nictitate/js/superfish.js"
    )
    .copy(
        "node_modules/Navgoco/src/jquery.navgoco.css",
        "nictitate/css/jquery.navgoco.css"
    )
    .copy(
        "node_modules/Navgoco/src/jquery.navgoco.js",
        "nictitate/js/jquery.navgoco.js"
    )
    .copy(
        "node_modules/jquery-poptrox/src/js/jquery.poptrox.js",
        "nictitate/js/jquery.poptrox.js"
    )
    .copy(
        "node_modules/jquery-poptrox/src/css/jquery.poptrox.css",
        "nictitate/css/jquery.poptrox.css"
    )
    .copy(
        "node_modules/jquery-slidebars/src/jquery.slidebars.css",
        "nictitate/css/jquery.slidebars.css"
    )
    .copy(
        "node_modules/jquery-slidebars/src/jquery.slidebars.js",
        "nictitate/js/jquery.slidebars.js"
    );
