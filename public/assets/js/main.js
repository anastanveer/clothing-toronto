(function () {
    let initialized = false;

    function initializeGlamer() {
        if (initialized) {
            return;
        }
        initialized = true;

        // preloader
        const preloader = document.getElementById('preloader');
        preloader.style.display = 'none';
        document.body.style.position = 'static';

        initProductCardActions();

        function initProductCardActions() {
            const forms = Array.from(document.querySelectorAll('.js-product-action'));
            const shareWrappers = Array.from(document.querySelectorAll('.js-product-share'));
            let toastTimeout;

            if (forms.length === 0 && shareWrappers.length === 0) {
                return;
            }

            forms.forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    if (form.dataset.busy === 'true') {
                        return;
                    }

                    form.dataset.busy = 'true';

                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: (form.getAttribute('method') || 'POST').toUpperCase(),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (response.status === 401) {
                            window.location.href = form.dataset.loginUrl || '/login';
                            return;
                        }

                        if (!response.ok) {
                            throw new Error('Request failed');
                        }

                        const payload = await response.json();
                        handleActionSuccess(form, payload);
                    } catch (error) {
                        console.error(error);
                        showProductToast('Something went wrong. Please try again.');
                    } finally {
                        form.dataset.busy = 'false';
                    }
                });
            });

            shareWrappers.forEach((wrapper) => {
                const toggle = wrapper.querySelector('.js-share-toggle');
                if (!toggle) {
                    return;
                }

                toggle.addEventListener('click', (event) => {
                    event.preventDefault();

                    if (navigator.share) {
                        navigator.share({
                            title: wrapper.dataset.shareTitle,
                            url: wrapper.dataset.shareUrl,
                            text: wrapper.dataset.shareMessage,
                        }).catch(() => {});
                        return;
                    }

                    const isOpen = wrapper.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                    if (isOpen) {
                        closeAllShareMenus(wrapper);
                    }
                });

                wrapper.querySelectorAll('.ul-product-share__menu a').forEach((link) => {
                    link.addEventListener('click', () => {
                        wrapper.classList.remove('is-open');
                        toggle.setAttribute('aria-expanded', 'false');
                    });
                });
            });

            document.addEventListener('click', (event) => {
                const isShareToggle = event.target.closest('.js-share-toggle');
                if (isShareToggle) {
                    return;
                }

                if (!event.target.closest('.js-product-share')) {
                    closeAllShareMenus();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeAllShareMenus();
                }
            });

            function closeAllShareMenus(except) {
                document.querySelectorAll('.js-product-share.is-open').forEach((menu) => {
                    if (except && menu === except) {
                        return;
                    }

                    menu.classList.remove('is-open');
                    const toggle = menu.querySelector('.js-share-toggle');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            function handleActionSuccess(form, payload) {
                const action = form.dataset.action;
                const button = form.querySelector('button');
                const successLabel = form.dataset.successLabel;
                const activeLabel = form.dataset.activeLabel || successLabel;

                if (!button) {
                    return;
                }

                if (action === 'wishlist') {
                    const isActive = Boolean(payload && payload.in_wishlist);
                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    const message = payload && payload.message
                        ? payload.message
                        : (isActive ? successLabel : activeLabel);
                    showProductToast(message);
                } else if (action === 'cart') {
                    button.classList.add('is-active');
                    button.setAttribute('aria-pressed', 'true');
                    const message = payload && payload.message ? payload.message : successLabel;
                    showProductToast(message);
                }
            }

            function showProductToast(message) {
                if (!message) {
                    return;
                }

                let toast = document.querySelector('.glamer-toast');

                if (!toast) {
                    toast = document.createElement('div');
                    toast.className = 'glamer-toast';
                    toast.setAttribute('role', 'status');
                    toast.setAttribute('aria-live', 'polite');
                    document.body.appendChild(toast);
                }

                toast.textContent = message;
                toast.classList.add('is-visible');

                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => {
                    toast.classList.remove('is-visible');
                }, 2400);
            }
        }

    // HEADER NAV IN MOBILE
    if (document.querySelector(".ul-header-nav")) {
        const ulSidebar = document.querySelector(".ul-sidebar");
        const ulSidebarOpener = document.querySelector(".ul-header-sidebar-opener");
        const ulSidebarCloser = document.querySelector(".ul-sidebar-closer");
        const ulMobileMenuContent = document.querySelector(".to-go-to-sidebar-in-mobile");
        const ulHeaderNavMobileWrapper = document.querySelector(".ul-sidebar-header-nav-wrapper");
        const ulHeaderNavOgWrapper = document.querySelector(".ul-header-nav-wrapper");

        function updateMenuPosition() {
            if (window.innerWidth < 992) {
                ulHeaderNavMobileWrapper.appendChild(ulMobileMenuContent);
            }

            if (window.innerWidth >= 992) {
                ulHeaderNavOgWrapper.appendChild(ulMobileMenuContent);
            }
        }

        updateMenuPosition();

        window.addEventListener("resize", () => {
            updateMenuPosition();
        });

        ulSidebarOpener.addEventListener("click", () => {
            ulSidebar.classList.add("active");
        });

        ulSidebarCloser.addEventListener("click", () => {
            ulSidebar.classList.remove("active");
        });


        // menu dropdown/submenu in mobile
        const ulHeaderNavMobile = document.querySelector(".ul-header-nav");
        const ulHeaderNavMobileItems = ulHeaderNavMobile.querySelectorAll(".has-sub-menu");
        ulHeaderNavMobileItems.forEach((item) => {
            if (window.innerWidth < 992) {
                item.addEventListener("click", () => {
                    item.classList.toggle("active");
                });
            }
        });
    }

    // header search in mobile start
    const ulHeaderSearchOpener = document.querySelector(".ul-header-mobile-search-opener");
    const ulHeaderSearchCloser = document.querySelector(".ul-header-mobile-search-closer");
    if (ulHeaderSearchOpener) {
        ulHeaderSearchOpener.addEventListener("click", () => {
            document.querySelector(".ul-header-search-form-wrapper").classList.add("active");
        });
    }

    if (ulHeaderSearchCloser) {
        ulHeaderSearchCloser.addEventListener("click", () => {
            document.querySelector(".ul-header-search-form-wrapper").classList.remove("active");
        });
    }
    // header search in mobile end

    if (document.querySelector(".ul-header-top-slider")) {
        new Splide('.ul-header-top-slider', {
            arrows: false,
            pagination: false,
            type: 'loop',
            drag: 'free',
            focus: 'center',
            perPage: 9,
            autoWidth: true,
            gap: 15,
            autoScroll: {
                speed: 1.5,
            },
        }).mount(window.splide.Extensions);
    }

    // search category
    if (document.querySelector("#ul-header-search-category")) {
        new SlimSelect({
            select: '#ul-header-search-category',
            settings: {
                showSearch: false,
            }
        })
    }

    // banner image slider
    const bannerThumbSlider = new Swiper(".ul-banner-img-slider", {
        slidesPerView: 1.4,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        // slideToClickedSlide: true,
        // centeredSlides: true,
        breakpoints: {
            992: {
                spaceBetween: 15,
            },
            1680: {
                spaceBetween: 26,
            },
            1700: {
                spaceBetween: 30,
            }
        }
    });


    // BANNER SLIDER
    const bannerSlider = new Swiper(".ul-banner-slider", {
        slidesPerView: 1,
        loop: true,
        // slideToClickedSlide: true,
        // effect: "fade",
        autoplay: true,
        thumbs: {
            swiper: bannerThumbSlider,
        },
        navigation: {
            nextEl: ".ul-banner-slider-nav .next",
            prevEl: ".ul-banner-slider-nav .prev",
        },
    });

    // bannerThumbSlider.on('slideChange', function () {
    //     bannerSlider.slideTo(bannerThumbSlider.activeIndex);
    // });


    // products filtering 
    if (document.querySelector(".ul-filter-products-wrapper")) {
        mixitup('.ul-filter-products-wrapper');
    }


    // product slider
    new Swiper(".ul-products-slider-1", {
        slidesPerView: 3,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        navigation: {
            nextEl: ".ul-products-slider-1-nav .next",
            prevEl: ".ul-products-slider-1-nav .prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3,
            },
            1200: {
                spaceBetween: 20,
            },
            1400: {
                spaceBetween: 22,
            },
            1600: {
                spaceBetween: 26,
            },
            1700: {
                spaceBetween: 30,
            }
        }
    });

    // product slider
    new Swiper(".ul-products-slider-2", {
        slidesPerView: 3,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        navigation: {
            nextEl: ".ul-products-slider-2-nav .next",
            prevEl: ".ul-products-slider-2-nav .prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3,
            },
            1200: {
                spaceBetween: 20,
            },
            1400: {
                spaceBetween: 22,
            },
            1600: {
                spaceBetween: 26,
            },
            1700: {
                spaceBetween: 30,
            }
        }
    });

    // flash sale slider\
    new Swiper(".ul-flash-sale-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        breakpoints: {
            480: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 4,
            },
            1200: {
                spaceBetween: 20,
                slidesPerView: 4,
            },
            1680: {
                spaceBetween: 26,
                slidesPerView: 4,
            },
            1700: {
                spaceBetween: 30,
                slidesPerView: 4.7,
            }
        }
    })

    // reviews slider
    new Swiper(".ul-reviews-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            992: {
                spaceBetween: 20,
                slidesPerView: 3,
            },
            1200: {
                spaceBetween: 20,
                slidesPerView: 4,
            },
            1680: {
                slidesPerView: 4,
                spaceBetween: 26,
            },
            1700: {
                slidesPerView: 4,
                spaceBetween: 30,
            }
        }
    });

    // gallery slider
    new Swiper(".ul-gallery-slider", {
        slidesPerView: 2.2,
        loop: true,
        autoplay: true,
        centeredSlides: true,
        spaceBetween: 15,
        breakpoints: {
            480: {
                slidesPerView: 3.4,
            },
            576: {
                slidesPerView: 4,
            },
            768: {
                slidesPerView: 5,
            },
            992: {
                spaceBetween: 20,
                slidesPerView: 5.5,
            },
            1680: {
                spaceBetween: 26,
                slidesPerView: 5.5,
            },
            1700: {
                spaceBetween: 30,
                slidesPerView: 5.5,
            },
            1920: {
                spaceBetween: 30,
                slidesPerView: 6,
                centeredSlides: false,
            }
        }
    });

    // product page price filter
    var priceFilterSlider = document.getElementById('ul-products-price-filter-slider');

    if (priceFilterSlider && typeof noUiSlider !== 'undefined') {
        var sliderMin = parseFloat(priceFilterSlider.dataset.min || 0);
        var sliderMax = parseFloat(priceFilterSlider.dataset.max || 0);
        var startMin = parseFloat(priceFilterSlider.dataset.startMin || sliderMin);
        var startMax = parseFloat(priceFilterSlider.dataset.startMax || sliderMax);
        var minTarget = priceFilterSlider.dataset.minTarget ? document.querySelector(priceFilterSlider.dataset.minTarget) : null;
        var maxTarget = priceFilterSlider.dataset.maxTarget ? document.querySelector(priceFilterSlider.dataset.maxTarget) : null;
        var displayTarget = priceFilterSlider.dataset.displayTarget ? document.querySelector(priceFilterSlider.dataset.displayTarget) : null;
        var sliderForm = priceFilterSlider.closest('form');
        var minFieldName = minTarget ? (minTarget.dataset.fieldName || minTarget.name || 'price[min]') : null;
        var maxFieldName = maxTarget ? (maxTarget.dataset.fieldName || maxTarget.name || 'price[max]') : null;

        if (sliderMax > sliderMin) {
            noUiSlider.create(priceFilterSlider, {
                start: [startMin, startMax],
                connect: true,
                step: 1,
                range: {
                    'min': sliderMin,
                    'max': sliderMax
                }
            });

            priceFilterSlider.noUiSlider.on('update', function (values) {
                var minValue = Math.round(values[0]);
                var maxValue = Math.round(values[1]);

                if (minTarget) {
                    minTarget.value = minValue;
                }

                if (maxTarget) {
                    maxTarget.value = maxValue;
                }

                if (displayTarget) {
                    displayTarget.textContent = '$' + minValue + ' - $' + maxValue;
                }

                var atDefaults = minValue === Math.round(sliderMin) && maxValue === Math.round(sliderMax);

                if (minTarget) {
                    if (!atDefaults && minFieldName) {
                        minTarget.setAttribute('name', minFieldName);
                    } else {
                        minTarget.removeAttribute('name');
                    }
                }

                if (maxTarget) {
                    if (!atDefaults && maxFieldName) {
                        maxTarget.setAttribute('name', maxFieldName);
                    } else {
                        maxTarget.removeAttribute('name');
                    }
                }
            });

            priceFilterSlider.noUiSlider.on('change', function () {
                if (priceFilterSlider.dataset.autoSubmit === 'change' && sliderForm) {
                    if (typeof sliderForm.requestSubmit === 'function') {
                        sliderForm.requestSubmit();
                    } else {
                        sliderForm.submit();
                    }
                }
            });
        }
    }

    // auto-submit filters on change
    document.querySelectorAll('[data-filter-change-submit]').forEach(function (input) {
        input.addEventListener('change', function () {
            var formId = input.getAttribute('form');
            var targetForm = formId ? document.getElementById(formId) : input.closest('form');

            if (!targetForm) {
                return;
            }

            if (typeof targetForm.requestSubmit === 'function') {
                targetForm.requestSubmit();
            } else {
                targetForm.submit();
            }
        });
    });

    // filter dropdowns (colors, etc.)
    document.querySelectorAll('[data-filter-dropdown]').forEach(function (wrapper) {
        var toggle = wrapper.querySelector('[data-filter-dropdown-toggle]');
        var menu = wrapper.querySelector('.ul-filter-dropdown-menu');

        if (!toggle || !menu) {
            return;
        }

        var closeMenu = function () {
            menu.setAttribute('hidden', '');
            wrapper.classList.remove('is-open');
        };

        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            var isHidden = menu.hasAttribute('hidden');

            document.querySelectorAll('[data-filter-dropdown]').forEach(function (otherWrapper) {
                if (otherWrapper === wrapper) {
                    return;
                }
                var otherMenu = otherWrapper.querySelector('.ul-filter-dropdown-menu');
                if (otherMenu) {
                    otherMenu.setAttribute('hidden', '');
                    otherWrapper.classList.remove('is-open');
                }
            });

            if (isHidden) {
                menu.removeAttribute('hidden');
                wrapper.classList.add('is-open');
            } else {
                closeMenu();
            }
        });

        menu.querySelectorAll('input[type="radio"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                closeMenu();
            });
        });

        document.addEventListener('click', function (event) {
            if (!wrapper.contains(event.target)) {
                closeMenu();
            }
        });
    });

    // brand accordion toggles
    document.querySelectorAll('[data-filter-accordion]').forEach(function (accordion) {
        accordion.querySelectorAll('[data-filter-accordion-toggle]').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                var item = toggle.closest('[data-filter-accordion-item]');
                var menu = item ? item.querySelector('.ul-brand-menu') : null;

                if (!item || !menu) {
                    return;
                }

                var isHidden = menu.hasAttribute('hidden');

                accordion.querySelectorAll('[data-filter-accordion-item]').forEach(function (other) {
                    if (other === item) {
                        return;
                    }
                    var otherMenu = other.querySelector('.ul-brand-menu');
                    if (otherMenu) {
                        otherMenu.setAttribute('hidden', '');
                        other.classList.remove('is-open');
                    }
                });

                if (isHidden) {
                    menu.removeAttribute('hidden');
                    item.classList.add('is-open');
                } else {
                    menu.setAttribute('hidden', '');
                    item.classList.remove('is-open');
                }
            });
        });
    });

    // product details slider
    new Swiper(".ul-product-details-img-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 0,
        navigation: {
            nextEl: "#ul-product-details-img-slider-nav .next",
            prevEl: "#ul-product-details-img-slider-nav .prev",
        },
    });

    // search category
    if (document.querySelector("#ul-checkout-country")) {
        new SlimSelect({
            select: '#ul-checkout-country',
            settings: {
                showSearch: false,
                contentLocation: document.querySelector('.ul-checkout-country-wrapper')
            }
        })
    }

    // sidebar products slider
    new Swiper(".ul-sidebar-products-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 30,
        navigation: {
            nextEl: ".ul-sidebar-products-slider-nav .next",
            prevEl: ".ul-sidebar-products-slider-nav .prev",
        },
        breakpoints: {
            1400: {
                slidesPerView: 2,
            }
        }
    });


    // quantity field
    if (document.querySelector(".ul-product-quantity-wrapper")) {
        const quantityWrapper = document.querySelectorAll(".ul-product-quantity-wrapper");

        quantityWrapper.forEach((item) => {
            const quantityInput = item.querySelector(".ul-product-quantity");
            const quantityIncreaseButton = item.querySelector(".quantityIncreaseButton");
            const quantityDecreaseButton = item.querySelector(".quantityDecreaseButton");

            quantityIncreaseButton.addEventListener("click", function () {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            });
            quantityDecreaseButton.addEventListener("click", function () {
                if (quantityInput.value > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                }
            });
        })
    }

    // parallax effect
    const parallaxImage = document.querySelector(".ul-video-cover");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                window.addEventListener("scroll", parallaxEffect);
                parallaxEffect(); // Initialize position
            } else {
                window.removeEventListener("scroll", parallaxEffect);
            }
        });
    });

    if (parallaxImage) {
        observer.observe(parallaxImage);
    }

        function parallaxEffect() {
            const rect = parallaxImage.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const imageCenter = rect.top + rect.height / 2;
            const viewportCenter = windowHeight / 2;

            // Calculate offset from viewport center
            const offset = (imageCenter - viewportCenter) * -0.5; // Adjust speed with multiplier

            parallaxImage.style.transform = `translateY(${offset}px)`;
        }
    }

    function attemptInit() {
        if (document.querySelector('.ul-header')) {
            initializeGlamer();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attemptInit);
    } else {
        attemptInit();
    }

    document.addEventListener('ul:layout-ready', attemptInit);
})();
