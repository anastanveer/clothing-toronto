(function () {
    let initialized = false;
    let glamerToastNode = null;
    let glamerToastTimeout = null;

    function showGlamerToast(message) {
        if (!message) {
            return;
        }

        if (!glamerToastNode) {
            glamerToastNode = document.createElement('div');
            glamerToastNode.className = 'glamer-toast';
            glamerToastNode.setAttribute('role', 'status');
            glamerToastNode.setAttribute('aria-live', 'polite');
            document.body.appendChild(glamerToastNode);
        }

        glamerToastNode.textContent = message;
        glamerToastNode.classList.add('is-visible');

        window.clearTimeout(glamerToastTimeout);
        glamerToastTimeout = window.setTimeout(() => {
            glamerToastNode.classList.remove('is-visible');
        }, 2400);
    }

    function initializeGlamer() {
        if (initialized) {
            return;
        }
        initialized = true;

        // preloader
        const preloader = document.getElementById('preloader');
        preloader.style.display = 'none';
        document.body.style.position = 'static';

        const headerElement = document.querySelector('.ul-header');
        const headerMetricsUrl = headerElement ? headerElement.dataset.headerMetricsUrl : null;
        let headerRefreshPromise = null;
        let headerRefreshTimer = null;

        function scheduleHeaderRefresh() {
            if (!headerMetricsUrl) {
                return;
            }

            if (headerRefreshTimer) {
                return;
            }

            headerRefreshTimer = window.setTimeout(() => {
                headerRefreshTimer = null;
                refreshHeaderMetrics();
            }, 120);
        }

        async function refreshHeaderMetrics() {
            if (!headerMetricsUrl) {
                return;
            }

            if (headerRefreshPromise) {
                return headerRefreshPromise;
            }

            headerRefreshPromise = fetch(headerMetricsUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then((response) => {
                    if (response.status === 401) {
                        return null;
                    }

                    if (!response.ok) {
                        throw new Error('Failed to refresh header metrics');
                    }

                    return response.json();
                })
                .then((data) => {
                    if (data) {
                        applyHeaderMetrics(data);
                    }
                })
                .catch((error) => {
                    console.warn('[glamer] header metrics update failed', error);
                })
                .finally(() => {
                    headerRefreshPromise = null;
                });

            return headerRefreshPromise;
        }

        function applyHeaderMetrics(data) {
            if (!data) {
                return;
            }

            const wishlistLinks = document.querySelectorAll('[data-header-wishlist]');
            wishlistLinks.forEach((wishlistLink) => {
                const wishlistCount = Number(data.wishlistCount || 0);
                wishlistLink.dataset.count = wishlistCount;
                wishlistLink.classList.toggle('is-active', wishlistCount > 0);

                let dot = wishlistLink.querySelector('.ul-header-icon__dot');
                if (wishlistCount > 0) {
                    if (!dot) {
                        dot = document.createElement('span');
                        dot.className = 'ul-header-icon__dot';
                        dot.setAttribute('aria-hidden', 'true');
                        wishlistLink.appendChild(dot);
                    }
                } else if (dot) {
                    dot.remove();
                }

                let srWishlistLabel = wishlistLink.querySelector('.js-wishlist-label');
                if (!srWishlistLabel) {
                    srWishlistLabel = document.createElement('span');
                    srWishlistLabel.className = 'visually-hidden js-wishlist-label';
                    wishlistLink.appendChild(srWishlistLabel);
                }

                if (wishlistCount === 0) {
                    srWishlistLabel.textContent = 'Wishlist is empty';
                } else {
                    const wishlistCountText = wishlistCount.toLocaleString();
                    srWishlistLabel.textContent = `${wishlistCountText} ${wishlistCount === 1 ? 'item' : 'items'} in wishlist`;
                }
            });

            const cartLinks = document.querySelectorAll('[data-header-cart]');
            cartLinks.forEach((cartLink) => {
                const cartCount = Number(data.cartCount || 0);
                cartLink.dataset.count = cartCount;

                let badge = cartLink.querySelector('.ul-header-icon__badge, .ul-footer-appbar__badge');
                if (cartCount > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = cartLink.classList.contains('ul-footer-appbar__btn')
                            ? 'ul-footer-appbar__badge'
                            : 'ul-header-icon__badge';
                        cartLink.appendChild(badge);
                    }
                    badge.textContent = cartCount.toLocaleString();
                    cartLink.classList.add('has-badge');
                } else if (badge) {
                    badge.remove();
                    cartLink.classList.remove('has-badge');
                } else {
                    cartLink.classList.remove('has-badge');
                }

                let srCartLabel = cartLink.querySelector('.js-cart-label');
                if (!srCartLabel) {
                    srCartLabel = document.createElement('span');
                    srCartLabel.className = 'visually-hidden js-cart-label';
                    cartLink.appendChild(srCartLabel);
                }

                if (cartCount === 0) {
                    srCartLabel.textContent = 'Bag is empty';
                } else {
                    const cartCountText = cartCount.toLocaleString();
                    srCartLabel.textContent = `${cartCountText} ${cartCount === 1 ? 'item' : 'items'} in your bag`;
                }
            });

            const loyaltyLinks = document.querySelectorAll('[data-header-loyalty]');
            loyaltyLinks.forEach((loyaltyLink) => {
                const loyaltyPoints = Number(data.loyaltyPoints || 0);
                const pendingPoints = Number(data.pendingPoints || 0);
                loyaltyLink.dataset.points = loyaltyPoints;
                loyaltyLink.dataset.pending = pendingPoints;

                const meter = loyaltyLink.querySelector('.ul-header-loyalty__meter');
                if (meter) {
                    const pointsNode = meter.querySelector('strong');
                    if (pointsNode) {
                        pointsNode.textContent = `${loyaltyPoints.toLocaleString()} pts`;
                    }

                    let pendingNode = meter.querySelector('.ul-header-loyalty__pending');
                    if (pendingPoints > 0) {
                        if (!pendingNode) {
                            pendingNode = document.createElement('span');
                            pendingNode.className = 'ul-header-loyalty__pending';
                            meter.appendChild(pendingNode);
                        }
                        pendingNode.textContent = `+${pendingPoints.toLocaleString()} pending`;
                    } else if (pendingNode) {
                        pendingNode.remove();
                    }
                }
            });
        }

        function initHeaderAlerts() {
            const alertsPanel = document.querySelector('[data-header-alerts-panel]');
            const toggles = Array.from(document.querySelectorAll('[data-header-alerts]'));

            if (!alertsPanel || toggles.length === 0) {
                return;
            }

            const closeButtons = alertsPanel.querySelectorAll('[data-header-alerts-close]');
            const alertsContent = alertsPanel.querySelector('[data-header-alerts-content]');
            const alertsLoading = alertsPanel.querySelector('[data-header-alerts-loading]');
            const backdrop = alertsPanel.querySelector('.ul-header-alerts__backdrop');

            const headerAlertsUrl = headerElement ? headerElement.dataset.headerAlertsUrl : null;
            const toggleAlertsUrl = toggles.map((btn) => btn.dataset.alertsUrl).find((value) => Boolean(value));
            const alertsUrl = headerAlertsUrl || toggleAlertsUrl || null;

            if (!alertsUrl) {
                return;
            }

            const dots = toggles
                .map((button) => button.querySelector('[data-alerts-dot]'))
                .filter((dot) => Boolean(dot));

            let alertsLoaded = false;
            let alertsRequest = null;

            function setDotState(hasAlerts) {
                dots.forEach((dot) => {
                    if (!dot) {
                        return;
                    }

                    if (hasAlerts) {
                        dot.classList.remove('is-idle');
                    } else {
                        dot.classList.add('is-idle');
                    }
                });
            }

            function setExpanded(isExpanded) {
                toggles.forEach((button) => {
                    button.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                });
            }

            function openAlerts() {
                alertsPanel.removeAttribute('hidden');
                document.body.style.overflow = 'hidden';
                setExpanded(true);
                window.setTimeout(() => {
                    alertsPanel.classList.add('is-active');
                }, 10);

                if (!alertsLoaded) {
                    fetchAlerts();
                }
            }

            function closeAlerts() {
                alertsPanel.classList.remove('is-active');
                setExpanded(false);

                window.setTimeout(() => {
                    alertsPanel.setAttribute('hidden', '');
                    document.body.style.overflow = '';
                }, 180);
            }

            function fetchAlerts(force) {
                if (!alertsUrl) {
                    return;
                }

                if (alertsLoaded && !force) {
                    return;
                }

                if (alertsRequest) {
                    return alertsRequest;
                }

                alertsLoading?.removeAttribute('hidden');
                alertsContent?.setAttribute('hidden', '');

                alertsRequest = fetch(alertsUrl, {
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Failed to load alerts');
                        }

                        return response.json();
                    })
                    .then((payload) => {
                        alertsLoaded = true;
                        renderAlerts(payload || {});
                    })
                    .catch((error) => {
                        console.warn('[glamer] alerts load failed', error);
                        if (alertsContent) {
                            alertsContent.innerHTML = '<p class="text-center text-secondary mb-0">We could not load alerts. Please retry shortly.</p>';
                            alertsContent.removeAttribute('hidden');
                        }
                    })
                    .finally(() => {
                        alertsRequest = null;
                        alertsLoading?.setAttribute('hidden', '');
                    });

                return alertsRequest;
            }

            function renderAlerts(data) {
                if (!alertsContent) {
                    return;
                }

                alertsContent.removeAttribute('hidden');

                const coupons = Array.isArray(data.coupons) ? data.coupons : [];
                const products = Array.isArray(data.products) ? data.products : [];
                const articles = Array.isArray(data.articles) ? data.articles : [];

                renderCoupons(coupons);
                renderProducts(products);
                renderArticles(articles);

                const hasAlerts = coupons.some((coupon) => coupon.status === 'available');
                setDotState(hasAlerts);
            }

            function renderCoupons(items) {
                const container = alertsPanel.querySelector('[data-alerts-list="coupons"]');
                if (!container) {
                    return;
                }

                container.innerHTML = '';

                if (!items.length) {
                    const empty = document.createElement('p');
                    empty.className = 'text-secondary mb-0';
                    empty.textContent = 'No perks just yet — keep an eye on this panel for fresh unlocks.';
                    container.appendChild(empty);
                    return;
                }

                const dateFormatter = new Intl.DateTimeFormat(undefined, {
                    month: 'short',
                    day: 'numeric',
                });

                items.forEach((item) => {
                    const perk = document.createElement('div');
                    perk.className = 'ul-header-alerts-perk';
                    if (item.status === 'pending') {
                        perk.classList.add('is-pending');
                    }

                    const body = document.createElement('div');

                    const title = document.createElement('p');
                    title.className = 'ul-header-alerts-perk__title';
                    const titleParts = [];
                    if (item.exclusive) {
                        titleParts.push('Exclusive');
                    }
                    titleParts.push(item.code || 'COUPON');
                    titleParts.push(item.title || '');
                    title.textContent = titleParts.filter(Boolean).join(' · ');
                    body.appendChild(title);

                    const meta = document.createElement('span');
                    meta.className = 'ul-header-alerts-perk__meta';

                    if (item.status === 'available') {
                        meta.textContent = 'Unlocked — tap apply in your bag when ready.';
                    } else if (item.status === 'pending') {
                        if (item.availableAt) {
                            meta.textContent = `Unlocks ${dateFormatter.format(new Date(item.availableAt))}`;
                        } else {
                            meta.textContent = 'Pending unlock — stay tuned.';
                        }
                    } else {
                        meta.textContent = 'Redeemed perk';
                    }

                    body.appendChild(meta);

                    if (item.minSpend && Number(item.minSpend) > 0) {
                        const spend = document.createElement('span');
                        spend.className = 'ul-header-alerts-perk__meta';
                        spend.textContent = `Min spend $${Number(item.minSpend).toFixed(2)}`;
                        body.appendChild(spend);
                    }

                    perk.appendChild(body);

                    const badge = document.createElement('span');
                    badge.className = 'ul-header-alerts-perk__badge';
                    const discountText = item.discountLabel
                        || (item.couponType === 'percent'
                            ? `${parseFloat(item.value || 0)}% off`
                            : `$${parseFloat(item.value || 0).toFixed(2)} off`);
                    badge.textContent = discountText;

                    perk.appendChild(badge);
                    container.appendChild(perk);
                });
            }

            function renderProducts(items) {
                const container = alertsPanel.querySelector('[data-alerts-list="products"]');
                if (!container) {
                    return;
                }

                container.innerHTML = '';

                if (!items.length) {
                    const empty = document.createElement('p');
                    empty.className = 'text-secondary mb-0';
                    empty.textContent = 'We are refreshing picks — check back for hand-priced essentials.';
                    container.appendChild(empty);
                    return;
                }

                items.forEach((item) => {
                    const card = document.createElement('a');
                    card.className = 'ul-header-alerts-product';
                    card.href = item.url;

                    const img = document.createElement('img');
                    img.alt = item.name || 'Product';
                    img.src = item.image;
                    card.appendChild(img);

                    const body = document.createElement('div');
                    body.className = 'ul-header-alerts-product__body';

                    const title = document.createElement('p');
                    title.className = 'ul-header-alerts-product__title';
                    title.textContent = item.name || 'Product';
                    body.appendChild(title);

                if (item.brand) {
                    const brand = document.createElement('span');
                    brand.className = 'ul-header-alerts-product__meta';
                    brand.textContent = item.brand;
                    body.appendChild(brand);
                }

                if (item.discountLabel) {
                    const discount = document.createElement('span');
                    discount.className = 'ul-header-alerts-product__deal';
                    discount.textContent = item.discountLabel;
                    body.appendChild(discount);
                }

                const price = document.createElement('span');
                price.className = 'ul-header-alerts-product__price';
                price.textContent = item.formattedPrice || '$0.00';
                body.appendChild(price);

                    card.appendChild(body);
                    container.appendChild(card);
                });
            }

            function renderArticles(items) {
                const container = alertsPanel.querySelector('[data-alerts-list="articles"]');
                if (!container) {
                    return;
                }

                container.innerHTML = '';

                if (!items.length) {
                    const empty = document.createElement('p');
                    empty.className = 'text-secondary mb-0';
                    empty.textContent = 'Fresh blog stories will roll in as soon as they publish.';
                    container.appendChild(empty);
                    return;
                }

                items.forEach((item) => {
                    const article = document.createElement('a');
                    article.className = 'ul-header-alerts-article';
                    article.href = item.url;

                    const meta = document.createElement('span');
                    meta.className = 'ul-header-alerts-article__meta';
                    if (item.publishedAt) {
                        const publishedDate = new Date(item.publishedAt);
                        meta.textContent = publishedDate.toLocaleDateString();
                    } else {
                        meta.textContent = 'New';
                    }
                    article.appendChild(meta);

                    const title = document.createElement('p');
                    title.className = 'ul-header-alerts-article__title';
                    title.textContent = item.title || 'Latest on the journal';
                    article.appendChild(title);

                    if (item.excerpt) {
                        const excerpt = document.createElement('p');
                        excerpt.className = 'ul-header-alerts-article__excerpt';
                        excerpt.textContent = typeof item.excerpt === 'string' ? item.excerpt : '';
                        article.appendChild(excerpt);
                    }

                    container.appendChild(article);
                });
            }

            toggles.forEach((button) => {
                button.addEventListener('click', () => {
                    const isActive = alertsPanel.classList.contains('is-active');
                    if (isActive) {
                        closeAlerts();
                    } else {
                        openAlerts();
                    }
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeAlerts);
            });

            if (backdrop) {
                backdrop.addEventListener('click', closeAlerts);
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && alertsPanel.classList.contains('is-active')) {
                    closeAlerts();
                }
            });

            setDotState(false);
            fetchAlerts();
        }

        initHeaderAlerts();
        initCartRemovalForms();
        initCheckoutGatewaySelector();
        initProductCardActions();

        function initCheckoutGatewaySelector() {
            const list = document.querySelector('.ul-checkout-gateway-list');

            if (!list) {
                return;
            }

            const chips = Array.from(list.querySelectorAll('.ul-checkout-gateway-chip'));
            const panes = Array.from(document.querySelectorAll('.ul-checkout-gateway-detail__pane'));
            const hiddenInput = document.getElementById('checkoutGatewayInput') || document.querySelector('input[name="payment_gateway"]');
            const cta = document.querySelector('.ul-btn--checkout');
            const ctaLabel = cta ? cta.querySelector('[data-gateway-label]') : null;

            function setActive(gateway, label) {
                chips.forEach((chip) => {
                    chip.classList.toggle('is-active', chip.dataset.gateway === gateway);
                });

                panes.forEach((pane) => {
                    pane.classList.toggle('is-active', pane.dataset.gateway === gateway);
                });

                if (hiddenInput) {
                    hiddenInput.value = gateway;
                }

                if (ctaLabel) {
                    ctaLabel.textContent = label ? `with ${label}` : '';
                }
            }

            chips.forEach((chip) => {
                chip.addEventListener('click', () => {
                    setActive(chip.dataset.gateway, chip.dataset.label);
                });
            });

            const initial = chips.find((chip) => chip.classList.contains('is-active')) || chips[0];

            if (initial) {
                setActive(initial.dataset.gateway, initial.dataset.label);
            }
        }

        function showConfirmDialog({ title, message, confirmLabel, cancelLabel }) {
            return new Promise((resolve) => {
                let modal = document.querySelector('.glamer-confirm');

                if (!modal) {
                    modal = document.createElement('div');
                    modal.className = 'glamer-confirm';
                    modal.innerHTML = `
                        <div class="glamer-confirm__backdrop" data-confirm-cancel></div>
                        <div class="glamer-confirm__dialog" role="dialog" aria-modal="true">
                            <h4 class="glamer-confirm__title"></h4>
                            <p class="glamer-confirm__message"></p>
                            <div class="glamer-confirm__actions">
                                <button type="button" class="glamer-confirm__cancel" data-confirm-cancel></button>
                                <button type="button" class="glamer-confirm__confirm"></button>
                            </div>
                        </div>`;
                    document.body.appendChild(modal);
                }

                const titleNode = modal.querySelector('.glamer-confirm__title');
                const messageNode = modal.querySelector('.glamer-confirm__message');
                const confirmBtn = modal.querySelector('.glamer-confirm__confirm');
                const cancelBtns = modal.querySelectorAll('[data-confirm-cancel]');

                titleNode.textContent = title || 'Confirm';
                messageNode.textContent = message || 'Are you sure?';
                confirmBtn.textContent = confirmLabel || 'Confirm';
                cancelBtns.forEach((btn) => {
                    if (btn.matches('.glamer-confirm__cancel')) {
                        btn.textContent = cancelLabel || 'Cancel';
                    }
                });

                function close(result) {
                    modal.classList.remove('is-visible');
                    document.body.style.overflow = '';
                    window.setTimeout(() => resolve(result), 180);
                    cancelBtns.forEach((btn) => btn.removeEventListener('click', onCancel));
                    confirmBtn.removeEventListener('click', onConfirm);
                    document.removeEventListener('keydown', onKeyDown, true);
                }

                function onCancel() {
                    close(false);
                }

                function onConfirm() {
                    close(true);
                }

                function onKeyDown(event) {
                    if (event.key === 'Escape') {
                        event.preventDefault();
                        onCancel();
                    }
                }

                cancelBtns.forEach((btn) => btn.addEventListener('click', onCancel, { once: true }));
                confirmBtn.addEventListener('click', onConfirm, { once: true });
                document.addEventListener('keydown', onKeyDown, true);

                modal.classList.add('is-visible');
                document.body.style.overflow = 'hidden';
                window.setTimeout(() => {
                    confirmBtn.focus({ preventScroll: true });
                }, 0);
            });
        }

        function initCartRemovalForms() {
            const removeForms = Array.from(document.querySelectorAll('.js-remove-form'));

            if (removeForms.length === 0) {
                return;
            }

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

            removeForms.forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    if (form.dataset.busy === 'true') {
                        return;
                    }

                    const confirmed = await showConfirmDialog({
                        title: form.dataset.confirmTitle,
                        message: form.dataset.confirm,
                        confirmLabel: form.dataset.confirmLabel,
                        cancelLabel: form.dataset.cancelLabel,
                    });

                    if (!confirmed) {
                        return;
                    }

                    form.dataset.busy = 'true';

                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: (form.getAttribute('method') || 'POST').toUpperCase(),
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken || formData.get('_token'),
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            throw new Error('Failed to remove item');
                        }

                        const payload = await response.json().catch(() => null);
                        scheduleHeaderRefresh();

                        const selector = form.dataset.removeSelector;
                        const removeTarget = selector ? form.closest(selector) : null;
                        if (removeTarget) {
                            removeTarget.classList.add('is-removing');
                            window.setTimeout(() => removeTarget.remove(), 220);
                        }

                        const type = form.dataset.removeType || 'generic';

                        if (type === 'cart') {
                            if (payload && payload.summary) {
                                updateCartSummary(payload);
                                if (payload.lines_count === 0) {
                                    window.location.reload();
                                    return;
                                }
                            } else {
                                window.location.reload();
                                return;
                            }
                        }

                        if (type === 'wishlist') {
                            if (payload && typeof payload.remaining !== 'undefined') {
                                const list = document.querySelector('[data-wishlist-list]');
                                const emptyState = document.querySelector('[data-wishlist-empty]');
                                if (payload.remaining === 0) {
                                    if (list) {
                                        list.classList.add('d-none');
                                    }
                                    if (emptyState) {
                                        emptyState.classList.remove('d-none');
                                    }
                                }
                            }
                        }

                        showGlamerToast(payload?.message || 'Removed.');
                    } catch (error) {
                        console.error(error);
                        window.location.reload();
                    } finally {
                        form.dataset.busy = 'false';
                    }
                });
            });
        }

        function updateCartSummary(payload) {
            const summary = payload.summary || {};

            function setText(attr, value) {
                const nodes = document.querySelectorAll(`[data-cart-summary="${attr}"]`);
                nodes.forEach((node) => {
                    node.textContent = value ?? '';
                });
            }

            function toggleBlock(name, show) {
                const blocks = document.querySelectorAll(`[data-cart-summary-block="${name}"]`);
                blocks.forEach((block) => {
                    block.classList.toggle('is-hidden', !show);
                });
            }

            setText('subtotal', summary.subtotal_formatted);
            setText('shipping', summary.shipping_formatted);
            setText('coupon-discount', summary.coupon_discount_formatted);
            setText('loyalty-discount', summary.loyalty_discount_formatted);
            setText('tax', summary.tax_formatted);
            setText('total', summary.total_formatted);
            setText('coupon-code', payload.coupon_code);
            setText('coupon-title', payload.coupon_title);
            setText('coupon-description', payload.coupon_description || 'Preferred guest savings active.');

            toggleBlock('coupon', Boolean(summary.coupon_discount > 0));
            toggleBlock('loyalty', Boolean(summary.loyalty_discount > 0));
            toggleBlock('coupon-card', Boolean(payload.coupon_applied));
            toggleBlock('coupon-pill', Boolean(payload.coupon_applied));

            const loyaltyBanner = document.querySelector('[data-loyalty-banner]');
            if (loyaltyBanner) {
                if (payload.loyalty_banner) {
                    loyaltyBanner.textContent = payload.loyalty_banner;
                    loyaltyBanner.classList.remove('d-none');
                } else {
                    loyaltyBanner.textContent = '';
                    loyaltyBanner.classList.add('d-none');
                }
            }

            const couponMessage = document.querySelector('[data-cart-summary="coupon-message"]');
            if (couponMessage) {
                if (payload.coupon_message && !payload.coupon_applied) {
                    couponMessage.textContent = payload.coupon_message;
                    couponMessage.classList.remove('d-none');
                } else {
                    couponMessage.textContent = '';
                    couponMessage.classList.add('d-none');
                }
            }
        }

        function initProductCardActions() {
            const forms = Array.from(document.querySelectorAll('.js-product-action'));
            const shareWrappers = Array.from(document.querySelectorAll('.js-product-share'));

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
                        showGlamerToast('Something went wrong. Please try again.');
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
                    const labelTarget = button.querySelector('[data-button-label]');
                    if (labelTarget) {
                        const activeText = form.dataset.labelActive;
                        const inactiveText = form.dataset.labelInactive;
                        if (isActive && activeText) {
                            labelTarget.textContent = activeText;
                        } else if (!isActive && inactiveText) {
                            labelTarget.textContent = inactiveText;
                        }
                    }
                    const message = payload && payload.message
                        ? payload.message
                        : (isActive ? successLabel : activeLabel);
                    showGlamerToast(message);
                } else if (action === 'cart') {
                    button.classList.add('is-active');
                    button.setAttribute('aria-pressed', 'true');
                    const message = payload && payload.message ? payload.message : successLabel;
                    showGlamerToast(message);
                }

                scheduleHeaderRefresh();
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
                autoplay: false,
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


    // arrivals sliders
    const arrivalsSwipers = {};
    [
        {
            key: 'women',
            selector: '.home-arrivals-slider--women',
            next: '.home-arrivals__nav--women .next',
            prev: '.home-arrivals__nav--women .prev',
        },
        {
            key: 'men',
            selector: '.home-arrivals-slider--men',
            next: '.home-arrivals__nav--men .next',
            prev: '.home-arrivals__nav--men .prev',
        },
    ].forEach(function (config) {
        if (document.querySelector(config.selector)) {
            arrivalsSwipers[config.key] = new Swiper(config.selector, {
                slidesPerView: 4,
                loop: false,
                spaceBetween: 20,
                navigation: {
                    nextEl: config.next,
                    prevEl: config.prev,
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    520: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                    1280: {
                        slidesPerView: 4,
                        spaceBetween: 22,
                    },
                    1600: {
                        slidesPerView: 4,
                        spaceBetween: 26,
                    },
                },
            });
        }
    });

    document.querySelectorAll("[data-arrivals]").forEach(function (section) {
        const toggles = section.querySelectorAll("[data-arrivals-toggle]");
        const panels = section.querySelectorAll("[data-arrivals-panel]");
        const select = section.querySelector("[data-arrivals-select]");

        const activate = function (target) {
            if (!target) {
                return;
            }

            toggles.forEach(function (button) {
                button.classList.toggle("is-active", button.dataset.arrivalsToggle === target);
            });

            panels.forEach(function (panel) {
                const isActive = panel.dataset.arrivalsPanel === target;
                panel.classList.toggle("is-active", isActive);
                if (isActive && arrivalsSwipers[target]) {
                    setTimeout(function () {
                        arrivalsSwipers[target].update();
                    }, 50);
                }
            });

            if (select && select.value !== target) {
                select.value = target;
            }
        };

        toggles.forEach(function (button) {
            button.addEventListener("click", function () {
                activate(button.dataset.arrivalsToggle);
            });
        });

        if (select) {
            select.addEventListener("change", function () {
                activate(select.value);
            });
        }
    });

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

    function initRewardModal() {
        const rewardModal = document.querySelector('[data-login-reward-modal]');
        if (!rewardModal) {
            return;
        }

        const dismissedKey = 'glamer.reward.modal.dismissed';
        const hideModal = function (persist) {
            rewardModal.classList.remove('is-visible');
            if (persist) {
                sessionStorage.setItem(dismissedKey, '1');
            }
            window.setTimeout(function () {
                rewardModal.hidden = true;
            }, 240);
        };

        const showModal = function () {
            if (sessionStorage.getItem(dismissedKey)) {
                return;
            }
            rewardModal.hidden = false;
            requestAnimationFrame(function () {
                rewardModal.classList.add('is-visible');
            });
        };

        rewardModal.querySelectorAll('[data-login-reward-action]').forEach(function (trigger) {
            trigger.addEventListener('click', function (event) {
                var action = trigger.getAttribute('data-login-reward-action');
                if (action === 'dismiss') {
                    event.preventDefault();
                    hideModal(true);
                }

                if (action === 'login') {
                    sessionStorage.setItem(dismissedKey, '1');
                }
            });
        });

        if (!sessionStorage.getItem(dismissedKey)) {
            window.setTimeout(showModal, 800);
        }
    }

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
        loop: false,
        autoplay: false,
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
                const max = parseInt(quantityInput.getAttribute('max') || '99', 10);
                const min = parseInt(quantityInput.getAttribute('min') || '1', 10);

                quantityIncreaseButton.addEventListener("click", function () {
                    const current = parseInt(quantityInput.value || min, 10);
                    if (current >= max) {
                        quantityInput.value = max;
                        return;
                    }
                    quantityInput.value = current + 1;
                });
                quantityDecreaseButton.addEventListener("click", function () {
                    const current = parseInt(quantityInput.value || min, 10);
                    if (current > min) {
                        quantityInput.value = current - 1;
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

        initRewardModal();
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
