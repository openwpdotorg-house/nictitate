<?php

$kopa_layout = array(
    'home-page-1' => array(
        'title' => 'Home Page 1',
        'thumbnails' => 'home-page.jpg',
        'positions' => array(
            'position_1',
            'position_2',
            'position_3',
            'position_4',
            'position_5',
            'position_6',
            'position_7',
            'position_8',
            'position_9',
            'position_10',
            'position_11',
            'position_12'
        ),
    ),
    'home-page-2' => array(
        'title' => 'Home Page 2',
        'thumbnails' => 'home-page-2.jpg',
        'positions' => array(
            'position_1',
            'position_2',
            'position_13',
            'position_5',
            'position_17',
            'position_10',
            'position_11',
            'position_12'
        ),
    ),
    'home-page-3' => array(
        'title' => 'Home Page 3',
        'thumbnails' => 'home-page-3.jpg',
        'positions' => array(
            'position_1',
            'position_2',
            'position_5',
            'position_16',
            'position_10',
            'position_11',
            'position_12'
        ),
    ),
    'blog-right-sidebar' => array(
        'title' => 'Blog 1',
        'thumbnails' => 'blog-1.jpg',
        'positions' => array(
            'position_14',
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'blog-2-right-sidebar' => array(
        'title' => 'Blog 2',
        'thumbnails' => 'blog-2.jpg',
        'positions' => array(
            'position_14',
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'page-right-sidebar' => array(
        'title' => 'Page Right Sidebar',
        'thumbnails' => 'page.jpg',
        'positions' => array(
            'position_14',
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'page-fullwidth' => array(
        'title' => 'Page Full Width',
        'thumbnails' => 'page-fullwidth.jpg',
        'positions' => array(
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'page-fullwidth-widgets' => array(
        'title' => 'Page Full Width Widgets',
        'thumbnails' => 'page-fullwidth-widgets.jpg',
        'positions' => array(
            'position_2',
            'position_17',
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'single-right-sidebar' => array(
        'title' => 'Single 1',
        'thumbnails' => 'single-1.jpg',
        'positions' => array(
            'position_14',
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'single-2-right-sidebar' => array(
        'title' => __('Single 2', kopa_get_domain()),
        'thumbnails' => 'single-2.jpg',
        'positions' => array(
            'position_14',
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'error-404' => array(
        'title' => '404',
        'thumbnails' => '404.jpg',
        'positions' => array(
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'portfolio' => array(
        'title' => 'Portfolio',
        'thumbnails' => 'portfolio.jpg',
        'positions' => array(
            'position_15',
            'position_10',
            'position_11',
            'position_12'
        )
    ),
    'shop' => array(
        'title' => 'Shop',
        'thumbnails' => 'shop.jpg',
        'positions' => array(
            'position_16',
            'position_10',
            'position_11',
            'position_12'
        )
    )
);

$kopa_sidebar_position = array(
    'position_1' => array('title' => 'Widget Area 1'),
    'position_2' => array('title' => 'Widget Area 2'),
    'position_3' => array('title' => 'Widget Area 3'),
    'position_4' => array('title' => 'Widget Area 4'),
    'position_5' => array('title' => 'Widget Area 5'),
    'position_6' => array('title' => 'Widget Area 6'),
    'position_7' => array('title' => 'Widget Area 7'),
    'position_8' => array('title' => 'Widget Area 8'),
    'position_9' => array('title' => 'Widget Area 9'),
    'position_10' => array('title' => 'Widget Area 10'),
    'position_11' => array('title' => 'Widget Area 11'),
    'position_12' => array('title' => 'Widget Area 12'),
    'position_13' => array('title' => 'Widget Area 13'),
    'position_14' => array('title' => 'Widget Area 14'),
    'position_15' => array('title' => 'Widget Area 15'),
    'position_16' => array('title' => 'Widget Area 16'),
    'position_17' => array('title' => 'Widget Area 17'),
);

$kopa_template_hierarchy = array(
    'home' => array(
        'title' => 'Home',
        'layout' => array('blog-right-sidebar', 'blog-2-right-sidebar')
    ),
    'front-page' => array(
        'title' => 'Front Page',
        'layout' => array('home-page-1', 'home-page-2', 'home-page-3')
    ),
    'post' => array(
        'title' => 'Post',
        'layout' => array('single-right-sidebar', 'single-2-right-sidebar')
    ),
    'page' => array(
        'title' => 'Page',
        'layout' => array('home-page-1', 'home-page-2', 'home-page-3', 'page-right-sidebar', 'page-fullwidth', 'page-fullwidth-widgets')
    ),
    'taxonomy' => array(
        'title' => 'Taxonomy',
        'layout' => array('blog-right-sidebar', 'blog-2-right-sidebar')
    ),
    'search' => array(
        'title' => 'Search',
        'layout' => array('blog-right-sidebar', 'blog-2-right-sidebar')
    ),
    'archive' => array(
        'title' => 'Archive',
        'layout' => array('blog-right-sidebar', 'blog-2-right-sidebar')
    ),
    '_404' => array(
        'title' => '404',
        'layout' => array('error-404')
    ),
    'portfolio' => array(
        'title' => 'Portfolio',
        'layout' => array('portfolio')
    )
);

define('KOPA_INIT_VERSION', 'nictitate-setting-version-21');
define('KOPA_LAYOUT', serialize($kopa_layout));
define('KOPA_SIDEBAR_POSITION', serialize($kopa_sidebar_position));
define('KOPA_TEMPLATE_HIERARCHY', serialize($kopa_template_hierarchy));

function kopa_initial_database() {
    $kopa_is_database_setup = get_option('kopa_is_database_setup');
    if ($kopa_is_database_setup !== KOPA_INIT_VERSION) {
        $kopa_setting = array(
            'home' => array(
                'layout_id' => 'blog-right-sidebar',
                'sidebars'  => array(
                    'sidebar_14',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            'front-page' => array(
                'layout_id' => 'home-page-1',
                'sidebars'  => array(
                    'sidebar_1',
                    'sidebar_2',
                    'sidebar_3',
                    'sidebar_4',
                    'sidebar_5',
                    'sidebar_6',
                    'sidebar_7',
                    'sidebar_8',
                    'sidebar_9',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            'post' => array(
                'layout_id' => 'single-right-sidebar',
                'sidebars'  => array(
                    'sidebar_14',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            'page' => array(
                'layout_id' => 'page-right-sidebar',
                'sidebars'  => array(
                    'sidebar_14',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            'taxonomy' => array(
                'layout_id' => 'blog-right-sidebar',
                'sidebars'  => array(
                    'sidebar_14',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            'search' => array(
                'layout_id' => 'blog-right-sidebar',
                'sidebars'  => array(
                    'sidebar_14',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            'archive' => array(
                'layout_id' => 'blog-right-sidebar',
                'sidebars'  => array(
                    'sidebar_14',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            '_404' => array(
                'layout_id' => 'error-404',
                'sidebars'  => array(
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            ),
            'portfolio' => array(
                'layout_id' => 'portfolio',
                'sidebars'  => array(
                    'sidebar_15',
                    'sidebar_10',
                    'sidebar_11',
                    'sidebar_12'
                ),
            )
        );
        $kopa_sidebar = array(
            'sidebar_hide' => '-- None --',
            'sidebar_1'    => 'Sidebar 1',
            'sidebar_2'    => 'Sidebar 2',
            'sidebar_3'    => 'Sidebar 3',
            'sidebar_4'    => 'Sidebar 4',
            'sidebar_5'    => 'Sidebar 5',
            'sidebar_6'    => 'Sidebar 6',
            'sidebar_7'    => 'Sidebar 7',
            'sidebar_8'    => 'Sidebar 8',
            'sidebar_9'    => 'Sidebar 9',
            'sidebar_10'   => 'Sidebar 10',
            'sidebar_11'   => 'Sidebar 11',
            'sidebar_12'   => 'Sidebar 12',
            'sidebar_13'   => 'Sidebar 13',
            'sidebar_14'   => 'Sidebar 14',
            'sidebar_15'   => 'Sidebar 15',
            'sidebar_16'   => 'Sidebar 16',
            'sidebar_17'   => 'Sidebar 17',
        );
        update_option('kopa_setting', $kopa_setting);
        update_option('kopa_sidebar', $kopa_sidebar);
        update_option('kopa_is_database_setup', KOPA_INIT_VERSION);
    }
}

/* Register widget areas */
add_action( 'widgets_init', 'kopa_sidebars_init' );

function kopa_sidebars_init() {
    $kopa_sidebar = get_option('kopa_sidebar');

    if ( empty( $kopa_sidebar ) || ! is_array( $kopa_sidebar ) ) {
        return;
    }

    foreach ($kopa_sidebar as $key => $value) {
        if ('sidebar_hide' != $key) {
            register_sidebar(array(
                'name'          => $value,
                'id'            => $key,
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title"><span></span>',
                'after_title'   => '</h2>'
            ));
        }
    }
}

/* ==============================================================================
 * Iconfont
  ============================================================================= */
$kopa_icon = array(
    "glass" => "&#xf000",
    "music" => "&#xf001",
    "search" => "&#xf002",
    "envelope-o" => "&#xf003",
    "heart" => "&#xf004",
    "star" => "&#xf005",
    "star-o" => "&#xf006",
    "user" => "&#xf007",
    "film" => "&#xf008",
    "th-large" => "&#xf009",
    "th" => "&#xf00a",
    "th-list" => "&#xf00b",
    "check" => "&#xf00c",
    "times" => "&#xf00d",
    "search-plus" => "&#xf00e",
    "search-minus" => "&#xf010",
    "power-off" => "&#xf011",
    "signal" => "&#xf012",
    "cog" => "&#xf013",
    "trash-o" => "&#xf014",
    "home" => "&#xf015",
    "file-o" => "&#xf016",
    "clock-o" => "&#xf017",
    "road" => "&#xf018",
    "download" => "&#xf019",
    "arrow-circle-o-down" => "&#xf01a",
    "arrow-circle-o-up" => "&#xf01b",
    "inbox" => "&#xf01c",
    "play-circle-o" => "&#xf01d",
    "repeat" => "&#xf01e",
    "refresh" => "&#xf021",
    "list-alt" => "&#xf022",
    "lock" => "&#xf023",
    "flag" => "&#xf024",
    "headphones" => "&#xf025",
    "volume-off" => "&#xf026",
    "volume-down" => "&#xf027",
    "volume-up" => "&#xf028",
    "qrcode" => "&#xf029",
    "barcode" => "&#xf02a",
    "tag" => "&#xf02b",
    "tags" => "&#xf02c",
    "book" => "&#xf02d",
    "bookmark" => "&#xf02e",
    "print" => "&#xf02f",
    "camera" => "&#xf030",
    "font" => "&#xf031",
    "bold" => "&#xf032",
    "italic" => "&#xf033",
    "text-height" => "&#xf034",
    "text-width" => "&#xf035",
    "align-left" => "&#xf036",
    "align-center" => "&#xf037",
    "align-right" => "&#xf038",
    "align-justify" => "&#xf039",
    "list" => "&#xf03a",
    "outdent" => "&#xf03b",
    "indent" => "&#xf03c",
    "video-camera" => "&#xf03d",
    "picture-o" => "&#xf03e",
    "pencil" => "&#xf040",
    "map-marker" => "&#xf041",
    "adjust" => "&#xf042",
    "tint" => "&#xf043",
    "pencil-square-o" => "&#xf044",
    "share-square-o" => "&#xf045",
    "check-square-o" => "&#xf046",
    "arrows" => "&#xf047",
    "step-backward" => "&#xf048",
    "fast-backward" => "&#xf049",
    "backward" => "&#xf04a",
    "play" => "&#xf04b",
    "pause" => "&#xf04c",
    "stop" => "&#xf04d",
    "forward" => "&#xf04e",
    "fast-forward" => "&#xf050",
    "step-forward" => "&#xf051",
    "eject" => "&#xf052",
    "chevron-left" => "&#xf053",
    "chevron-right" => "&#xf054",
    "plus-circle" => "&#xf055",
    "minus-circle" => "&#xf056",
    "times-circle" => "&#xf057",
    "check-circle" => "&#xf058",
    "question-circle" => "&#xf059",
    "info-circle" => "&#xf05a",
    "crosshairs" => "&#xf05b",
    "times-circle-o" => "&#xf05c",
    "check-circle-o" => "&#xf05d",
    "ban" => "&#xf05e",
    "arrow-left" => "&#xf060",
    "arrow-right" => "&#xf061",
    "arrow-up" => "&#xf062",
    "arrow-down" => "&#xf063",
    "share" => "&#xf064",
    "expand" => "&#xf065",
    "compress" => "&#xf066",
    "plus" => "&#xf067",
    "minus" => "&#xf068",
    "asterisk" => "&#xf069",
    "exclamation-circle" => "&#xf06a",
    "gift" => "&#xf06b",
    "leaf" => "&#xf06c",
    "fire" => "&#xf06d",
    "eye" => "&#xf06e",
    "eye-slash" => "&#xf070",
    "exclamation-triangle" => "&#xf071",
    "plane" => "&#xf072",
    "calendar" => "&#xf073",
    "random" => "&#xf074",
    "comment" => "&#xf075",
    "magnet" => "&#xf076",
    "chevron-up" => "&#xf077",
    "chevron-down" => "&#xf078",
    "retweet" => "&#xf079",
    "shopping-cart" => "&#xf07a",
    "folder" => "&#xf07b",
    "folder-open" => "&#xf07c",
    "arrows-v" => "&#xf07d",
    "arrows-h" => "&#xf07e",
    "bar-chart-o" => "&#xf080",
    "twitter-square" => "&#xf081",
    "facebook-square" => "&#xf082",
    "camera-retro" => "&#xf083",
    "key" => "&#xf084",
    "cogs" => "&#xf085",
    "comments" => "&#xf086",
    "thumbs-o-up" => "&#xf087",
    "thumbs-o-down" => "&#xf088",
    "star-half" => "&#xf089",
    "heart-o" => "&#xf08a",
    "sign-out" => "&#xf08b",
    "linkedin-square" => "&#xf08c",
    "thumb-tack" => "&#xf08d",
    "external-link" => "&#xf08e",
    "sign-in" => "&#xf090",
    "trophy" => "&#xf091",
    "github-square" => "&#xf092",
    "upload" => "&#xf093",
    "lemon-o" => "&#xf094",
    "phone" => "&#xf095",
    "square-o" => "&#xf096",
    "bookmark-o" => "&#xf097",
    "phone-square" => "&#xf098",
    "twitter" => "&#xf099",
    "facebook" => "&#xf09a",
    "github" => "&#xf09b",
    "unlock" => "&#xf09c",
    "credit-card" => "&#xf09d",
    "rss" => "&#xf09e",
    "hdd-o" => "&#xf0a0",
    "bullhorn" => "&#xf0a1",
    "bell" => "&#xf0f3",
    "certificate" => "&#xf0a3",
    "hand-o-right" => "&#xf0a4",
    "hand-o-left" => "&#xf0a5",
    "hand-o-up" => "&#xf0a6",
    "hand-o-down" => "&#xf0a7",
    "arrow-circle-left" => "&#xf0a8",
    "arrow-circle-right" => "&#xf0a9",
    "arrow-circle-up" => "&#xf0aa",
    "arrow-circle-down" => "&#xf0ab",
    "globe" => "&#xf0ac",
    "wrench" => "&#xf0ad",
    "tasks" => "&#xf0ae",
    "filter" => "&#xf0b0",
    "briefcase" => "&#xf0b1",
    "arrows-alt" => "&#xf0b2",
    "users" => "&#xf0c0",
    "link" => "&#xf0c1",
    "cloud" => "&#xf0c2",
    "flask" => "&#xf0c3",
    "scissors" => "&#xf0c4",
    "files-o" => "&#xf0c5",
    "paperclip" => "&#xf0c6",
    "floppy-o" => "&#xf0c7",
    "square" => "&#xf0c8",
    "bars" => "&#xf0c9",
    "list-ul" => "&#xf0ca",
    "list-ol" => "&#xf0cb",
    "strikethrough" => "&#xf0cc",
    "underline" => "&#xf0cd",
    "table" => "&#xf0ce",
    "magic" => "&#xf0d0",
    "truck" => "&#xf0d1",
    "pinterest" => "&#xf0d2",
    "pinterest-square" => "&#xf0d3",
    "google-plus-square" => "&#xf0d4",
    "google-plus" => "&#xf0d5",
    "money" => "&#xf0d6",
    "caret-down" => "&#xf0d7",
    "caret-up" => "&#xf0d8",
    "caret-left" => "&#xf0d9",
    "caret-right" => "&#xf0da",
    "columns" => "&#xf0db",
    "sort" => "&#xf0dc",
    "sort-asc" => "&#xf0dd",
    "sort-desc" => "&#xf0de",
    "envelope" => "&#xf0e0",
    "linkedin" => "&#xf0e1",
    "undo" => "&#xf0e2",
    "gavel" => "&#xf0e3",
    "tachometer" => "&#xf0e4",
    "comment-o" => "&#xf0e5",
    "comments-o" => "&#xf0e6",
    "bolt" => "&#xf0e7",
    "sitemap" => "&#xf0e8",
    "umbrella" => "&#xf0e9",
    "clipboard" => "&#xf0ea",
    "lightbulb-o" => "&#xf0eb",
    "exchange" => "&#xf0ec",
    "cloud-download" => "&#xf0ed",
    "cloud-upload" => "&#xf0ee",
    "user-md" => "&#xf0f0",
    "stethoscope" => "&#xf0f1",
    "suitcase" => "&#xf0f2",
    "bell-o" => "&#xf0a2",
    "coffee" => "&#xf0f4",
    "cutlery" => "&#xf0f5",
    "file-text-o" => "&#xf0f6",
    "building-o" => "&#xf0f7",
    "hospital-o" => "&#xf0f8",
    "ambulance" => "&#xf0f9",
    "medkit" => "&#xf0fa",
    "fighter-jet" => "&#xf0fb",
    "beer" => "&#xf0fc",
    "h-square" => "&#xf0fd",
    "plus-square" => "&#xf0fe",
    "angle-double-left" => "&#xf100",
    "angle-double-right" => "&#xf101",
    "angle-double-up" => "&#xf102",
    "angle-double-down" => "&#xf103",
    "angle-left" => "&#xf104",
    "angle-right" => "&#xf105",
    "angle-up" => "&#xf106",
    "angle-down" => "&#xf107",
    "desktop" => "&#xf108",
    "laptop" => "&#xf109",
    "tablet" => "&#xf10a",
    "mobile" => "&#xf10b",
    "circle-o" => "&#xf10c",
    "quote-left" => "&#xf10d",
    "quote-right" => "&#xf10e",
    "spinner" => "&#xf110",
    "circle" => "&#xf111",
    "reply" => "&#xf112",
    "github-alt" => "&#xf113",
    "folder-o" => "&#xf114",
    "folder-open-o" => "&#xf115",
    "smile-o" => "&#xf118",
    "frown-o" => "&#xf119",
    "meh-o" => "&#xf11a",
    "gamepad" => "&#xf11b",
    "keyboard-o" => "&#xf11c",
    "flag-o" => "&#xf11d",
    "flag-checkered" => "&#xf11e",
    "terminal" => "&#xf120",
    "code" => "&#xf121",
    "reply-all" => "&#xf122",
    "mail-reply-all" => "&#xf122",
    "star-half-o" => "&#xf123",
    "location-arrow" => "&#xf124",
    "crop" => "&#xf125",
    "code-fork" => "&#xf126",
    "chain-broken" => "&#xf127",
    "question" => "&#xf128",
    "info" => "&#xf129",
    "exclamation" => "&#xf12a",
    "superscript" => "&#xf12b",
    "subscript" => "&#xf12c",
    "eraser" => "&#xf12d",
    "puzzle-piece" => "&#xf12e",
    "microphone" => "&#xf130",
    "microphone-slash" => "&#xf131",
    "shield" => "&#xf132",
    "calendar-o" => "&#xf133",
    "fire-extinguisher" => "&#xf134",
    "rocket" => "&#xf135",
    "maxcdn" => "&#xf136",
    "chevron-circle-left" => "&#xf137",
    "chevron-circle-right" => "&#xf138",
    "chevron-circle-up" => "&#xf139",
    "chevron-circle-down" => "&#xf13a",
    "html5" => "&#xf13b",
    "css3" => "&#xf13c",
    "anchor" => "&#xf13d",
    "unlock-alt" => "&#xf13e",
    "bullseye" => "&#xf140",
    "ellipsis-h" => "&#xf141",
    "ellipsis-v" => "&#xf142",
    "rss-square" => "&#xf143",
    "play-circle" => "&#xf144",
    "ticket" => "&#xf145",
    "minus-square" => "&#xf146",
    "minus-square-o" => "&#xf147",
    "level-up" => "&#xf148",
    "level-down" => "&#xf149",
    "check-square" => "&#xf14a",
    "pencil-square" => "&#xf14b",
    "external-link-square" => "&#xf14c",
    "share-square" => "&#xf14d",
    "compass" => "&#xf14e",
    "caret-square-o-down" => "&#xf150",
    "caret-square-o-up" => "&#xf151",
    "caret-square-o-right" => "&#xf152",
    "eur" => "&#xf153",
    "gbp" => "&#xf154",
    "usd" => "&#xf155",
    "inr" => "&#xf156",
    "jpy" => "&#xf157",
    "rub" => "&#xf158",
    "krw" => "&#xf159",
    "btc" => "&#xf15a",
    "file" => "&#xf15b",
    "file-text" => "&#xf15c",
    "sort-alpha-asc" => "&#xf15d",
    "sort-alpha-desc" => "&#xf15e",
    "sort-amount-asc" => "&#xf160",
    "sort-amount-desc" => "&#xf161",
    "sort-numeric-asc" => "&#xf162",
    "sort-numeric-desc" => "&#xf163",
    "thumbs-up" => "&#xf164",
    "thumbs-down" => "&#xf165",
    "youtube-square" => "&#xf166",
    "youtube" => "&#xf167",
    "xing" => "&#xf168",
    "xing-square" => "&#xf169",
    "youtube-play" => "&#xf16a",
    "dropbox" => "&#xf16b",
    "stack-overflow" => "&#xf16c",
    "instagram" => "&#xf16d",
    "flickr" => "&#xf16e",
    "adn" => "&#xf170",
    "bitbucket" => "&#xf171",
    "bitbucket-square" => "&#xf172",
    "tumblr" => "&#xf173",
    "tumblr-square" => "&#xf174",
    "long-arrow-down" => "&#xf175",
    "long-arrow-up" => "&#xf176",
    "long-arrow-left" => "&#xf177",
    "long-arrow-right" => "&#xf178",
    "apple" => "&#xf179",
    "windows" => "&#xf17a",
    "android" => "&#xf17b",
    "linux" => "&#xf17c",
    "dribbble" => "&#xf17d",
    "skype" => "&#xf17e",
    "foursquare" => "&#xf180",
    "trello" => "&#xf181",
    "female" => "&#xf182",
    "male" => "&#xf183",
    "gittip" => "&#xf184",
    "sun-o" => "&#xf185",
    "moon-o" => "&#xf186",
    "archive" => "&#xf187",
    "bug" => "&#xf188",
    "vk" => "&#xf189",
    "weibo" => "&#xf18a",
    "renren" => "&#xf18b",
    "pagelines" => "&#xf18c",
    "stack-exchange" => "&#xf18d",
    "arrow-circle-o-right" => "&#xf18e",
    "arrow-circle-o-left" => "&#xf190",
    "caret-square-o-left" => "&#xf191",
    "dot-circle-o" => "&#xf192",
    "wheelchair" => "&#xf193",
    "vimeo-square" => "&#xf194",
    "try" => "&#xf195",
    "plus-square-o" => "&#xf196",
);
define('KOPA_ICON', serialize($kopa_icon));