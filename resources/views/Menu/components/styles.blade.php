<style>
    html {
        scroll-behavior: smooth;
    }

    .restaurant-menu {
        --menu-green: #174c3c;
        --menu-dark: #10382c;
        --menu-gold: #e8c98c;
        --menu-muted: #708078;
        scroll-behavior: smooth;
    }

    .restaurant-menu #categories {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x mandatory;
    }

    .restaurant-menu #categories>* {
        scroll-snap-align: start;
    }

    .restaurant-menu .menu-hero {
        background:
            radial-gradient(ellipse at 10% 15%, rgba(232, 201, 140, .13), transparent 32%),
            radial-gradient(ellipse at 85% 100%, rgba(94, 171, 126, .17), transparent 36%),
            linear-gradient(125deg, #09271e 0%, #174c3c 55%, #286b50 100%);
    }

    .restaurant-menu .menu-glass {
        border: 1px solid rgba(255, 255, 255, .15);
        background: rgba(255, 255, 255, .07);
        backdrop-filter: blur(12px);
    }

    .restaurant-menu .menu-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border-radius: 14px;
        padding: 13px 18px;
        font-size: 13px;
        font-weight: 800;
        transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .restaurant-menu .menu-btn:active {
        transform: scale(.97);
    }

    .restaurant-menu .menu-category {
        position: relative;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 1px solid #e5eae4;
        border-radius: 15px;
        background: #fff;
        padding: 12px 17px;
        color: #647168;
        font-size: 13px;
        font-weight: 800;
        transition: all .22s ease;
    }

    .restaurant-menu .menu-category:hover {
        border-color: #a9c5b2;
        color: var(--menu-green);
        transform: translateY(-1px);
    }

    .restaurant-menu .menu-category.active {
        border-color: var(--menu-green);
        background: var(--menu-green);
        color: #fff;
        box-shadow: 0 7px 18px rgba(23, 76, 60, .16);
    }

    .restaurant-menu .food-card {
        min-width: 0;
        border: 1px solid #e9eee8;
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(20, 55, 38, .025);
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }

    .restaurant-menu .food-card:hover {
        transform: translateY(-5px);
        border-color: #d5e3d8;
        box-shadow: 0 18px 38px rgba(20, 55, 38, .09);
    }

    .restaurant-menu .food-image-wrap {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #eef2eb, #e4eae1);
    }

    .restaurant-menu .food-image {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform .65s cubic-bezier(.2, .75, .25, 1);
    }

    .restaurant-menu .food-card:hover .food-image {
        transform: scale(1.07);
    }

    .restaurant-menu .food-card.hidden {
        display: none !important;
    }

    .restaurant-menu .menu-search {
        width: 100%;
        min-height: 54px;
        border: 1px solid transparent;
        border-radius: 15px;
        background: #f6f8f5;
        padding: 14px 48px 14px 45px;
        color: #1c392d;
        font-size: 14px;
        outline: none;
        transition: all .2s ease;
    }

    .restaurant-menu .menu-search:focus {
        border-color: rgba(23, 76, 60, .24);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(23, 76, 60, .055);
    }

    .restaurant-menu .menu-search::placeholder {
        color: #9aa69d;
    }

    .restaurant-menu .menu-section-title {
        color: #183d2e;
        font-weight: 900;
        letter-spacing: -.025em;
    }

    .restaurant-menu .menu-feature {
        border: 1px solid #e5eae3;
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 8px 30px rgba(20, 55, 38, .045);
    }

    .restaurant-menu .food-add {
        display: inline-flex;
        min-height: 42px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 12px;
        background: var(--menu-green);
        padding: 10px 13px;
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        transition: all .2s ease;
    }

    .restaurant-menu .food-add:hover {
        background: var(--menu-dark);
        box-shadow: 0 5px 14px rgba(23, 76, 60, .15);
    }

    .restaurant-menu .food-add:disabled {
        cursor: not-allowed;
        opacity: .6;
    }

    .restaurant-menu .menu-bottom-bar {
        padding-bottom: max(12px, env(safe-area-inset-bottom));
    }

    .restaurant-menu .menu-dot-pattern {
        background-image: radial-gradient(rgba(255, 255, 255, .15) 1px, transparent 1px);
        background-size: 17px 17px;
    }

    .restaurant-menu .menu-divider {
        height: 1px;
        background: linear-gradient(to left, #dfe8df, transparent);
    }

    #orders-modal {
        overscroll-behavior: contain;
    }

    #orders-modal .orders-dialog {
        max-height: calc(100dvh - 24px);
        animation: ordersModalIn .2s ease-out;
    }

    #orders-modal .confirm-order-button:disabled {
        cursor: not-allowed;
        opacity: .5;
        box-shadow: none;
    }

    @keyframes ordersModalIn {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @media (max-width: 640px) {
        .restaurant-menu .menu-category {
            padding: 11px 14px;
        }

        .restaurant-menu .food-card {
            border-radius: 18px;
        }

        .restaurant-menu .food-add {
            padding: 10px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        html,
        .restaurant-menu,
        .restaurant-menu #categories {
            scroll-behavior: auto !important;
        }

        .restaurant-menu *,
        .restaurant-menu *::before,
        .restaurant-menu *::after {
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
        }
    }


    #confirm-order-list {
        position: relative;
        z-index: 999999 !important;
        isolation: isolate;
        touch-action: none;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }

    #confirm-order-list,
    #confirm-order-list * {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        user-select: none;
        -webkit-user-drag: none;
    }

    #confirm-order-list::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 999999;
        background: transparent;
        border-radius: inherit;
        pointer-events: none;
    }

    /* منع قائمة تحميل الصورة على الهاتف */
    img {
        -webkit-touch-callout: none !important;
        -webkit-user-select: none !important;
        user-select: none !important;
        -webkit-user-drag: none !important;
        -webkit-user-modify: read-only;
    }

    /* زر تأكيد الطلب */
    #confirm-order-list {
        position: relative !important;
        z-index: 2147483647 !important;
        touch-action: none !important;
        -webkit-touch-callout: none !important;
        -webkit-user-select: none !important;
        user-select: none !important;
        -webkit-tap-highlight-color: transparent !important;
    }

    /* منع قائمة السياق */
    img,
    #confirm-order-list,
    #confirm-order-list * {
        -webkit-touch-callout: none !important;
    }

    img {
        pointer-events: none;
    }

    @keyframes food-added {
        0% {
            opacity: 0;
            transform: translateY(14px) scale(0.96);
            box-shadow: 0 0 0 0 rgba(23, 76, 60, 0);
        }

        55% {
            opacity: 1;
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 0 0 5px rgba(23, 76, 60, 0.12);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
            box-shadow: 0 0 0 0 rgba(23, 76, 60, 0);
        }
    }

    .food-added-animation {
        animation: food-added 550ms ease-out;
        transform-origin: center;
    }

    @media (prefers-reduced-motion: reduce) {
        .food-added-animation {
            animation: none;
        }
    }






    /*     code animation */
    .bill-code-card {
        isolation: isolate;
        background:
            radial-gradient(ellipse at 50% 100%, rgba(232, 201, 140, .09), transparent 55%),
            linear-gradient(135deg, #183e32 0%, #102f25 48%, #091e18 100%);
    }

    /* Ambient light */
    .bill-code-orb {
        position: absolute;
        z-index: -1;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        filter: blur(55px);
        pointer-events: none;
        opacity: .22;
        animation: billOrbMove 7s ease-in-out infinite alternate;
    }

    .bill-code-orb-one {
        top: -90px;
        left: -40px;
        background: #e8c98c;
    }

    .bill-code-orb-two {
        right: -70px;
        bottom: -100px;
        background: #30a783;
        animation-delay: -3.5s;
    }

    @keyframes billOrbMove {
        0% {
            transform: translate(0, 0) scale(.9);
            opacity: .12;
        }

        100% {
            transform: translate(30px, 25px) scale(1.2);
            opacity: .28;
        }
    }

    /* Icon animation */
    .bill-code-icon {
        animation: billIconFloat 3s ease-in-out infinite;
        box-shadow: 0 0 25px rgba(232, 201, 140, .04);
        transition: box-shadow .3s ease, border-color .3s ease;
    }

    .bill-code-card:hover .bill-code-icon {
        border-color: rgba(232, 201, 140, .7);
        box-shadow: 0 0 28px rgba(232, 201, 140, .15);
    }

    @keyframes billIconFloat {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-4px);
        }
    }

    /* Each code tile */
    .bill-code-tile {
        position: relative;
        width: clamp(34px, 8vw, 46px);
        height: clamp(42px, 10vw, 54px);
        flex: 0 1 auto;
        opacity: 0;
        perspective: 500px;
        animation: billTileEnter .75s cubic-bezier(.2, .85, .25, 1.25) forwards;
        animation-delay: calc(var(--i) * 100ms);
    }

    .bill-code-tile-inner {
        position: relative;
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid rgba(232, 201, 140, .28);
        border-radius: 11px;
        background: linear-gradient(145deg, rgba(255, 255, 255, .09), rgba(255, 255, 255, .025));
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, .06),
            0 5px 14px rgba(0, 0, 0, .12);
        transform-style: preserve-3d;
        transition:
            border-color .3s ease,
            background .3s ease,
            box-shadow .3s ease,
            transform .3s ease;
    }

    .bill-code-character {
        position: relative;
        z-index: 2;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: clamp(17px, 4vw, 22px);
        font-weight: 900;
        line-height: 1;
        color: #f3d9a5;
        text-shadow: 0 0 12px rgba(232, 201, 140, .18);
        animation: billCharacterGlow 3s ease-in-out infinite;
        animation-delay: calc(var(--i) * 120ms + 1s);
    }

    .bill-code-tile-shine {
        position: absolute;
        inset: -30% auto -30% -80%;
        z-index: 1;
        width: 55%;
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, .2),
                transparent);
        transform: skewX(-20deg);
        animation: billTileShine 4s ease-in-out infinite;
        animation-delay: calc(var(--i) * 130ms + 1.2s);
        pointer-events: none;
    }

    .bill-code-tile-glow {
        position: absolute;
        z-index: -1;
        right: 15%;
        bottom: -5px;
        left: 15%;
        height: 8px;
        border-radius: 50%;
        background: #e8c98c;
        filter: blur(10px);
        opacity: 0;
        animation: billTileGlow 3s ease-in-out infinite;
        animation-delay: calc(var(--i) * 120ms + 1s);
    }

    .bill-code-tile:hover .bill-code-tile-inner {
        transform: translateY(-6px) rotateX(8deg) scale(1.07);
        border-color: #e8c98c;
        background: rgba(232, 201, 140, .13);
        box-shadow:
            0 0 22px rgba(232, 201, 140, .14),
            inset 0 0 15px rgba(232, 201, 140, .05);
    }

    .bill-code-tile:hover .bill-code-character {
        color: #fff3d5;
        text-shadow: 0 0 16px rgba(232, 201, 140, .65);
    }

    @keyframes billTileEnter {
        0% {
            opacity: 0;
            transform: translateY(24px) rotateX(-65deg) scale(.65);
        }

        65% {
            opacity: 1;
            transform: translateY(-5px) rotateX(8deg) scale(1.08);
        }

        100% {
            opacity: 1;
            transform: translateY(0) rotateX(0) scale(1);
        }
    }

    @keyframes billCharacterGlow {

        0%,
        100% {
            text-shadow: 0 0 5px rgba(232, 201, 140, .12);
        }

        50% {
            text-shadow:
                0 0 8px rgba(232, 201, 140, .6),
                0 0 20px rgba(232, 201, 140, .25);
        }
    }

    @keyframes billTileShine {

        0%,
        65%,
        100% {
            left: -80%;
        }

        35% {
            left: 140%;
        }
    }

    @keyframes billTileGlow {

        0%,
        100% {
            opacity: 0;
            transform: scaleX(.5);
        }

        50% {
            opacity: .28;
            transform: scaleX(1);
        }
    }

    /* Light scans across the entire code */
    .bill-code-scan {
        position: absolute;
        z-index: 5;
        top: 18px;
        bottom: 35px;
        left: -35%;
        width: 20%;
        pointer-events: none;
        opacity: 0;
        background: linear-gradient(90deg,
                transparent,
                rgba(232, 201, 140, .08),
                rgba(255, 238, 197, .32),
                rgba(232, 201, 140, .08),
                transparent);
        filter: blur(5px);
        transform: skewX(-15deg);
        animation: billCodeScan 5s ease-in-out infinite;
        animation-delay: 1.8s;
    }

    @keyframes billCodeScan {

        0%,
        15% {
            left: -35%;
            opacity: 0;
        }

        25% {
            opacity: .8;
        }

        65% {
            opacity: .8;
        }

        85%,
        100% {
            left: 115%;
            opacity: 0;
        }
    }

    /* Progress line */
    .bill-code-progress {
        width: 35%;
        background: linear-gradient(90deg, transparent, #e8c98c, #fff1cb, transparent);
        box-shadow: 0 0 10px rgba(232, 201, 140, .4);
        animation: billProgressMove 3s ease-in-out infinite;
    }

    @keyframes billProgressMove {
        0% {
            transform: translateX(-150%);
        }

        100% {
            transform: translateX(400%);
        }
    }

    /* Status indicator */
    .bill-code-status-dot {
        box-shadow: 0 0 8px rgba(110, 231, 183, .6);
    }

    /* Animated border accent */
    .bill-code-border {
        position: absolute;
        top: 0;
        left: -70%;
        width: 45%;
        height: 1px;
        background: linear-gradient(90deg, transparent, #e8c98c, transparent);
        opacity: .65;
        animation: billBorderMove 6s linear infinite;
        pointer-events: none;
    }

    @keyframes billBorderMove {
        to {
            left: 130%;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .bill-code-card *,
        .bill-code-card *::before,
        .bill-code-card *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            scroll-behavior: auto !important;
        }

        .bill-code-tile {
            opacity: 1;
            transform: none;
        }
    }
</style>
