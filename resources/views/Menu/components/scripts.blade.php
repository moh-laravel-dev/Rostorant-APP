<script>
    // =============================================================
    // منع القائمة السياقية وسحب الصور وتحديد النصوص في العناصر المحددة
    // =============================================================

    document.addEventListener('contextmenu', function(event) {
        if (
            event.target.closest('#confirm-order-list') ||
            event.target.closest('img')
        ) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, true);

    document.addEventListener('dragstart', function(event) {
        if (event.target.closest('img')) {
            event.preventDefault();
        }
    }, true);

    document.addEventListener('selectstart', function(event) {
        if (
            event.target.closest('#confirm-order-list') ||
            event.target.closest('img')
        ) {
            event.preventDefault();
        }
    }, true);

    document.addEventListener('DOMContentLoaded', function() {

        // =========================================================
        // تحديد عناصر الصفحة والأقسام والأزرار
        // =========================================================

        const searchInput = document.getElementById('menu-search');
        const clearSearch = document.getElementById('clear-search');
        const statusFilter = document.getElementById('food-status-filter');

        const categoryButtons = [
            ...document.querySelectorAll('.menu-category')
        ];

        const cards = [...document.querySelectorAll('.food-card')];

        const noResults = document.getElementById('no-results');
        const foodCount = document.getElementById('food-count');
        const foodCountTop = document.getElementById('food-count-top');

        // عناصر ملخص الطلبات الحالية
        const currentOrderSummary = document.getElementById(
            'current-order-summary'
        );

        const currentOrderCount = document.getElementById(
            'current-order-count'
        );

        const currentOrderBadge = document.getElementById(
            'current-order-badge'
        );

        const currentOrderTotal = document.getElementById(
            'current-order-total'
        );

        // عناصر ملخص الطلبات السابقة
        const previousOrderSummary = document.getElementById(
            'previous-order-summary'
        );

        const previousOrderCount = document.getElementById(
            'previous-order-count'
        );

        const previousOrderBadge = document.getElementById(
            'previous-order-badge'
        );

        const previousOrderTotal = document.getElementById(
            'previous-order-total'
        );

        // عناصر عدد الطلبات والإجمالي
        const orderCount = document.getElementById('order-count');
        const orderBadge = document.getElementById('order-badge');
        const buttonOrderCount = document.getElementById('button-order-count');

        const orderTotal = document.getElementById('order-total');
        const orderTotalDesktop = document.getElementById(
            'order-total-desktop'
        );

        // عناصر نافذة عرض الطلبات
        const ordersModal = document.getElementById('orders-modal');
        const ordersList = document.getElementById('current-order-items');
        const ordersEmpty = document.getElementById('orders-empty');
        const requestBillButton = document.getElementById('request-bill');
        const requestBillHint = document.getElementById('request-bill-hint');

        const previousSummary = document.getElementById(
            'previous-order-summary'
        );

        const orderSummary = document.getElementById('order-summary');
        const previousOrders = document.getElementById('previousOrders');

        const currentOrdersSection = document.getElementById(
            'current-orders-section'
        );

        const previousOrdersSection = document.getElementById(
            'previous-orders-section'
        );

        const previousOrdersContainer = document.getElementById(
            'previous-order-items'
        );

        const previousOrdersCount = document.getElementById(
            'previous-orders-count'
        );

        // زر تحديث الطلبات السابقة وأيقونة التحديث
        const refreshPreviousOrdersButton = document.getElementById(
            'refresh-previous-orders'
        );

        const refreshPreviousOrdersIcon = document.getElementById(
            'refresh-previous-orders-icon'
        );

        // عناصر تأكيد إرسال الطلب
        const confirmOrderList = document.getElementById('confirm-order-list');
        const confirmOrderModal = document.getElementById('confirm-order-modal');

        const confirmOrderItemsCount = document.getElementById(
            'confirm-order-items-count'
        );

        const confirmOrderQuantity = document.getElementById(
            'confirm-order-quantity'
        );

        const confirmOrderTotal = document.getElementById(
            'confirm-order-total'
        );

        const approveConfirmOrder = document.getElementById(
            'approve-confirm-order'
        );

        const cancelConfirmOrder = document.getElementById(
            'cancel-confirm-order'
        );

        // إشعار نجاح إرسال الطلب
        const orderSuccessToast = document.getElementById(
            'order-success-toast'
        );

        // =========================================================
        // استلام البيانات والمسارات من Laravel
        // =========================================================

        const billNumber = @json($billNumber);
        const tableId = @json($tableId);

        const refreshUrl = @json(route('menu.index.data', ['number' => $billNumber]));

        const submitUrl = @json(route('bill.new.orders'));

        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content ?? '';

        // الخدمات والطلبات السابقة القادمة من الخادم
        let tableServices = @json($services ?? []);
        let oldOrders = @json($data ?? []);

        // تخزين الوجبات الجديدة قبل إرسالها
        const order = new Map();

        // حالة الفلترة والعناصر التي كان التركيز عليها
        let selectedCategory = 'all';
        let previousFocusedElement = null;

        // حالة عمليات الإرسال والتحديث
        let isSubmitting = false;
        let isRefreshingOrders = false;
        let refreshOrdersPromise = null;
        let refreshOrdersAgain = false;

        // متغيرات الضغط المطول على زر التأكيد
        let confirmPressTimer = null;
        let confirmAnimationFrame = null;
        let confirmPressStart = 0;
        let confirmPressTriggered = false;

        // مدة الضغط المطول بالميلي ثانية
        const CONFIRM_PRESS_DURATION = 1500;

        let confirmProgressLayer = null;

        // =========================================================
        // دوال مساعدة عامة
        // =========================================================

        function normalize(value) {
            return String(value ?? '')
                .toLocaleLowerCase('ar')
                .replace(/[أإآ]/g, 'ا')
                .replace(/ى/g, 'ي')
                .replace(/ة/g, 'ه')
                .replace(/ؤ/g, 'و')
                .replace(/ئ/g, 'ي')
                .trim();
        }

        function formatPrice(value) {
            const number = Number(value ?? 0);

            return (Number.isFinite(number) ? number : 0).toLocaleString(
                'en-US', {
                    maximumFractionDigits: 2
                }
            );
        }

        function formatCurrency(value) {
            return `${formatPrice(value)} ل.س`;
        }

        function setText(id, value) {
            const element = document.getElementById(id);

            if (element) {
                element.textContent = value;
            }
        }

        function showMessage(message, type = 'error') {
            if (typeof window.showToast === 'function') {
                window.showToast(message, type);
            } else {
                window.alert(message);
            }
        }

        function showOrderSuccessToast() {
            if (!orderSuccessToast) return;

            orderSuccessToast.classList.remove('show');
            void orderSuccessToast.offsetWidth;
            orderSuccessToast.classList.add('show');

            window.setTimeout(function() {
                orderSuccessToast.classList.remove('show');
            }, 1800);
        }

        function hideOrderSuccessToast() {
            orderSuccessToast?.classList.remove('show');
        }

        function toggleSection(element, shouldShow) {
            if (!element) return;

            element.classList.toggle('hidden', !shouldShow);

            if (shouldShow) {
                element.style.removeProperty('display');
            } else {
                element.style.display = 'none';
            }
        }

        function normalizeOrdersResponse(result) {
            if (Array.isArray(result)) {
                return result;
            }

            if (!result || typeof result !== 'object') {
                return null;
            }

            const possibleArrays = [
                result.orders,
                result.items,
                result.data,
                result.data?.orders,
                result.data?.items,
                result.data?.data,
                result.data?.data?.orders,
                result.data?.data?.items
            ];

            for (const value of possibleArrays) {
                if (Array.isArray(value)) {
                    return value;
                }
            }

            return null;
        }

        // =========================================================
        // دوال التعامل مع الطلبات السابقة
        // =========================================================

        function getExistingOrders() {
            return Array.isArray(oldOrders) ? oldOrders : [];
        }

        function getExistingOrderQuantity(item) {
            return Math.max(
                0,
                Number(item?.amount ?? item?.quantity ?? 0) || 0
            );
        }

        function getExistingOrderPrice(item) {
            const price = Number(item?.food_price ?? item?.price ?? 0);

            return Number.isFinite(price) ? price : 0;
        }

        function getExistingOrderTotal(item) {
            if (
                item?.total_price !== undefined &&
                item?.total_price !== null &&
                item?.total_price !== ''
            ) {
                const total = Number(item.total_price);

                if (Number.isFinite(total)) {
                    return total;
                }
            }

            return (
                getExistingOrderPrice(item) *
                getExistingOrderQuantity(item)
            );
        }

        function hasPreviousOrders() {
            return getExistingOrders().some(function(item) {
                return getExistingOrderQuantity(item) > 0;
            });
        }

        function areAllPreviousOrdersOnTable() {
            const items = getExistingOrders().filter(function(item) {
                return getExistingOrderQuantity(item) > 0;
            });

            return items.length > 0 && items.every(function(item) {
                const statuses = normalizeOrderStatuses(item);

                return statuses.length > 0 && statuses.every(function(status) {
                    return ['2', '3'].includes(String(status?.status_code ?? ''));
                });
            });
        }

        function updateRequestBillVisibility() {
            const hasOldOrders = hasPreviousOrders();
            const hasNewOrders = order.size > 0;
            const allPreviousOrdersOnTable = areAllPreviousOrdersOnTable();
            const canRequestBill = hasOldOrders &&
                !hasNewOrders &&
                allPreviousOrdersOnTable;

            if (requestBillButton) {
                requestBillButton.hidden = !canRequestBill;
                requestBillButton.classList.toggle('hidden', !canRequestBill);
            }

            if (requestBillHint) {
                requestBillHint.hidden = canRequestBill;
                requestBillHint.textContent = hasNewOrders ?
                    'أرسل الطلبات الجديدة أولًا؛ سيظهر زر «طلب الحساب» بعد تأكيدها، عند وجود طلبات سابقة.' :
                    hasOldOrders && !allPreviousOrdersOnTable ?
                        'لا يظهر زر «طلب الحساب» إلا بعد وصول جميع الطلبات إلى الطاولة.' :
                        'يظهر زر «طلب الحساب» بعد إرسال أول طلب ووصول جميع الطلبات إلى الطاولة.';
            }
        }

        function getPreviousOrdersQuantity() {
            return getExistingOrders().reduce(function(total, item) {
                return total + getExistingOrderQuantity(item);
            }, 0);
        }

        function getPreviousOrdersTotal() {
            return getExistingOrders().reduce(function(total, item) {
                return total + getExistingOrderTotal(item);
            }, 0);
        }

        // =========================================================
        // استخراج حالة الطلب والوقت المتوقع للانتهاء
        // =========================================================

        function normalizeOrderStatuses(item) {
            const statuses = item?.statuses;

            if (Array.isArray(statuses)) {
                return statuses;
            }

            if (statuses && typeof statuses === 'object') {
                const collectionKey = Object.keys(statuses).find(function(key) {
                    return Array.isArray(statuses[key]);
                });

                if (collectionKey) {
                    return statuses[collectionKey];
                }

                if (statuses.status) {
                    return [statuses];
                }
            }

            if (item?.status) {
                return [{
                    status_code: item.status_code,
                    status: item.status,
                    expected_time: item.expected_time,
                    created_at: item.created_at
                }];
            }

            return [];
        }

        function parseServerDate(value) {
            if (!value) return null;

            const input = String(value).trim();

            const match = input.match(
                /^(\d{4})-(\d{2})-(\d{2})\s+(\d{1,2}):(\d{2}):(\d{2})\s*(AM|PM)?$/i
            );

            if (!match) {
                const parsed = new Date(input);

                return Number.isNaN(parsed.getTime()) ? null : parsed;
            }

            const year = Number(match[1]);
            const month = Number(match[2]);
            const day = Number(match[3]);
            let hour = Number(match[4]);

            const minute = Number(match[5]);
            const second = Number(match[6]);
            const period = match[7]?.toUpperCase();

            if (period === 'PM' && hour !== 12) {
                hour += 12;
            } else if (period === 'AM' && hour === 12) {
                hour = 0;
            }

            const parsed = new Date(
                year,
                month - 1,
                day,
                hour,
                minute,
                second
            );

            return Number.isNaN(parsed.getTime()) ? null : parsed;
        }

        function parseExpectedTime(value) {
            if (
                value === null ||
                value === undefined ||
                value === ''
            ) {
                return 0;
            }

            const parts = String(value).split(':').map(Number);

            if (
                parts.length !== 3 ||
                parts.some(function(part) {
                    return !Number.isFinite(part) || part < 0;
                })
            ) {
                return 0;
            }

            return (
                parts[0] * 3600 +
                parts[1] * 60 +
                parts[2]
            );
        }

        function getOrderStatusInfo(item) {
            const statuses = normalizeOrderStatuses(item);

            const latestStatus = statuses.length ?
                statuses[statuses.length - 1] :
                null;

            if (!latestStatus) {
                return {
                    status: 'غير محدد',
                    remaining: null,
                    endTime: null
                };
            }

            const status = String(
                latestStatus?.status ?? 'غير محدد'
            ).trim();

            const normalizedStatus = normalize(status);

            const timedStatuses = [
                normalize('في المطبخ')
            ];

            if (!timedStatuses.includes(normalizedStatus)) {
                return {
                    status,
                    remaining: null,
                    endTime: null
                };
            }

            const expectedTime = parseExpectedTime(
                latestStatus?.expected_time ??
                item?.expected_time ??
                latestStatus?.table_time ??
                item?.table_time
            );

            const createdAt = parseServerDate(
                latestStatus?.created_at ??
                item?.created_at
            );

            if (!createdAt || expectedTime <= 0) {
                return {
                    status,
                    remaining: null,
                    endTime: null
                };
            }

            const endTime = createdAt.getTime() + expectedTime * 1000;

            return {
                status,
                remaining: Math.max(
                    0,
                    Math.ceil((endTime - Date.now()) / 1000)
                ),
                endTime
            };
        }

        function formatRemainingTime(seconds) {
            if (seconds === null || seconds === undefined) {
                return 'لم يحدد وقت متوقع للانتهاء';
            }

            if (seconds <= 0) {
                return 'انتهى الوقت المتوقع';
            }

            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;

            function formatArabicNumber(number, unit) {
                const units = {
                    hour: {
                        one: 'ساعة',
                        two: 'ساعتان',
                        few: 'ساعات',
                        many: 'ساعة'
                    },
                    minute: {
                        one: 'دقيقة',
                        two: 'دقيقتان',
                        few: 'دقائق',
                        many: 'دقيقة'
                    },
                    second: {
                        one: 'ثانية',
                        two: 'ثانيتان',
                        few: 'ثوانٍ',
                        many: 'ثانية'
                    }
                };

                const labels = units[unit];

                if (number === 1) return labels.one;
                if (number === 2) return labels.two;
                if (number >= 3 && number <= 10) {
                    return `${number} ${labels.few}`;
                }

                return `${number} ${labels.many}`;
            }

            const parts = [];

            if (hours > 0) {
                parts.push(formatArabicNumber(hours, 'hour'));
            }

            if (minutes > 0) {
                parts.push(formatArabicNumber(minutes, 'minute'));
            }

            if (remainingSeconds > 0) {
                parts.push(formatArabicNumber(remainingSeconds, 'second'));
            }

            return parts.join(' و');
        }

        // =========================================================
        // ألوان شارات الحالات
        // =========================================================

        function getStatusBadgeClasses(status) {
            const normalizedStatus = normalize(status);

            if (normalizedStatus === normalize('على الطاولة')) {
                return 'bg-emerald-100 text-emerald-800';
            }

            if (
                normalizedStatus === normalize('قيد التأكيد') ||
                normalizedStatus === normalize('قيد التاكيد')
            ) {
                return 'bg-amber-200 text-amber-900';
            }

            if (
                normalizedStatus === normalize('مكتمل') ||
                normalizedStatus === normalize('تم') ||
                normalizedStatus === normalize('منتهي')
            ) {
                return 'bg-blue-100 text-blue-800';
            }

            if (
                normalizedStatus === normalize('ملغي') ||
                normalizedStatus === normalize('ملغى')
            ) {
                return 'bg-red-100 text-red-800';
            }

            return 'bg-gray-100 text-gray-700';
        }

        // =========================================================
        // إنشاء بطاقة الطلب السابق
        // =========================================================
        function createPreviousOrderRow(item) {
            const row = document.createElement('div');

            row.className =
                'flex w-full min-w-0 flex-col gap-3 rounded-2xl border border-[var(--restaurant-color-hex-d1d5db)] bg-[var(--restaurant-color-hex-f5faf6)] p-3 sm:p-4';

            const name = document.createElement('p');

            name.className =
                'break-words text-sm font-extrabold text-gray-800';

            name.textContent = String(
                item?.food_name ??
                item?.name ??
                'وجبة'
            );

            const details = document.createElement('div');

            details.className =
                'flex flex-wrap gap-2';

            function createInfoBox(label, value) {
                const box = document.createElement('div');

                box.className = 'min-w-0 w-[30%] rounded-xl bg-white p-2';

                const labelElement = document.createElement('p');

                labelElement.className =
                    'text-[10px] font-medium text-gray-500';

                labelElement.textContent = label;

                const valueElement = document.createElement('p');

                valueElement.className =
                    'mt-1 break-words text-xs font-bold text-gray-800';

                valueElement.textContent = String(value);

                box.append(labelElement, valueElement);

                return box;
            }

            const quantity = getExistingOrderQuantity(item);
            const price = getExistingOrderPrice(item);
            const total = getExistingOrderTotal(item);

            details.append(
                createInfoBox('سعر الوجبة', formatCurrency(price)),
                createInfoBox('الكمية', quantity),
                createInfoBox('إجمالي الصنف', formatCurrency(total))
            );

            const orderNotes = Array.isArray(item?.order_notes) ?
                item.order_notes :
                (Array.isArray(item?.notes) ?
                    item.notes.map(note => ({
                        amount: quantity,
                        note
                    })) :
                    (item?.note ? [{
                        amount: quantity,
                        note: item.note
                    }] : []));
            const validOrderNotes = orderNotes.filter(orderNote =>
                orderNote &&
                typeof orderNote.note === 'string' &&
                orderNote.note.trim()
            );
            if (validOrderNotes.length) {
                const notesContainer = document.createElement('div');
                notesContainer.className =
                    'order-customer-note rounded-xl border p-3';

                const notesTitle = document.createElement('p');
                notesTitle.className = 'mb-2 text-xs font-extrabold';
                notesTitle.textContent = 'ملاحظات الوجبة';
                notesContainer.appendChild(notesTitle);

                const notesList = document.createElement('div');
                notesList.className = 'space-y-2';

                validOrderNotes.forEach(function(orderNote) {
                    const noteRow = document.createElement('div');
                    noteRow.className =
                        'order-note-entry flex flex-wrap items-start gap-2 rounded-lg border p-2 text-xs leading-5';

                    const amount = document.createElement('span');
                    amount.className =
                        'order-note-amount shrink-0 rounded-full px-2 py-0.5 font-bold';
                    amount.textContent =
                        `الكمية: ${Number(orderNote.amount) || quantity}`;

                    const text = document.createElement('span');
                    text.className = 'order-note-text min-w-0 flex-1';
                    text.textContent = orderNote.note.trim();

                    noteRow.append(amount, text);
                    notesList.appendChild(noteRow);
                });

                notesContainer.appendChild(notesList);
                row.appendChild(notesContainer);
            }

            const statusInfo = getOrderStatusInfo(item);
            const statusEntries = normalizeOrderStatuses(item);

            const statusContainer = document.createElement('div');

            statusContainer.className =
                'flex flex-col gap-2 border-t border-[var(--restaurant-color-hex-d1d5db)] pt-3';

            const statusLine = document.createElement('div');

            statusLine.className =
                'flex flex-wrap items-center gap-2';

            const statusLabel = document.createElement('span');

            statusLabel.className =
                'text-xs font-semibold text-gray-600';

            statusLabel.textContent = 'حالة الطلب:';
            statusLine.appendChild(statusLabel);

            const displayedStatuses = statusEntries.length ?
                statusEntries :
                [statusInfo];

            displayedStatuses.forEach(function(statusEntry) {
                const statusName = String(statusEntry?.status ?? 'غير محدد').trim();
                const statusBadge = document.createElement('span');

                statusBadge.className =
                    'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ' +
                    getStatusBadgeClasses(statusName);

                statusBadge.textContent = statusName;

                if (statusEntry?.amount !== undefined) {
                    const amount = document.createElement('span');
                    amount.className = 'opacity-75';
                    amount.textContent = `× ${Number(statusEntry.amount) || 0}`;
                    statusBadge.appendChild(amount);
                }

                statusLine.appendChild(statusBadge);

                if (normalize(statusName) === normalize('في المطبخ')) {
                    const kitchenStatusInfo = getOrderStatusInfo({
                        statuses: [statusEntry]
                    });
                    const remainingLine = document.createElement('p');

                    remainingLine.className =
                        'basis-full text-xs font-medium text-gray-600';
                    remainingLine.dataset.remainingTime = 'true';

                    if (kitchenStatusInfo.endTime !== null) {
                        remainingLine.dataset.endTime = String(
                            kitchenStatusInfo.endTime
                        );
                    }

                    remainingLine.textContent =
                        `الوقت المتوقع للانتهاء: ${formatRemainingTime(kitchenStatusInfo.remaining)}`;

                    statusLine.appendChild(remainingLine);
                }
            });

            statusContainer.appendChild(statusLine);

            row.append(name, details, statusContainer);

            return row;
        } // =========================================================
        // تحديث الوقت المتبقي دون إعادة إنشاء البطاقات
        // =========================================================

        function updatePreviousOrderCountdowns() {
            if (!previousOrdersContainer) return;

            previousOrdersContainer
                .querySelectorAll('[data-remaining-time="true"]')
                .forEach(function(element) {
                    const rawEndTime = element.dataset.endTime;

                    if (!rawEndTime) return;

                    const endTime = Number(rawEndTime);

                    if (!Number.isFinite(endTime)) return;

                    const remaining = Math.max(
                        0,
                        Math.ceil((endTime - Date.now()) / 1000)
                    );

                    element.textContent =
                        `الوقت المتوقع للانتهاء: ${formatRemainingTime(remaining)}`;
                });
        }

        // =========================================================
        // عرض الطلبات السابقة
        // =========================================================

        function renderPreviousOrders() {
            if (!previousOrdersContainer) return;

            previousOrdersContainer.replaceChildren();

            const items = getExistingOrders();

            items.forEach(function(item) {
                previousOrdersContainer.appendChild(
                    createPreviousOrderRow(item)
                );
            });

            const hasOrders = items.some(function(item) {
                return getExistingOrderQuantity(item) > 0;
            });

            if (previousOrdersCount) {
                previousOrdersCount.textContent = `${items.length} صنف`;
            }

            if (previousOrdersSection) {
                toggleSection(previousOrdersSection, hasOrders);
            }

            updatePreviousOrderCountdowns();
        }

        // =========================================================
        // حساب الطلبات الحالية
        // =========================================================

        function getCurrentOrdersQuantity() {
            let quantity = 0;

            order.forEach(function(item) {
                quantity += Number(item.quantity) || 0;
            });

            return quantity;
        }

        function getCurrentOrdersTotal() {
            let total = 0;

            order.forEach(function(item) {
                total += (
                    (Number(item.price) || 0) *
                    (Number(item.quantity) || 0)
                );
            });

            return total;
        }

        function getTotalOrdersQuantity() {
            return (
                getPreviousOrdersQuantity() +
                getCurrentOrdersQuantity()
            );
        }

        function getServicesTotal() {
            const services = Array.isArray(tableServices) ?
                tableServices : [];

            return services.reduce(function(total, service) {
                const price = Number(service?.price ?? 0);

                return total + (
                    Number.isFinite(price) ? price : 0
                );
            }, 0);
        }

        function getFinalTotal() {
            return (
                getPreviousOrdersTotal() +
                getCurrentOrdersTotal() +
                getServicesTotal()
            );
        }

        // =========================================================
        // البحث والفلترة حسب التصنيف والاسم والحالة
        // =========================================================

        function filterFoods() {
            const query = normalize(searchInput?.value);
            const selectedStatus = String(statusFilter?.value ?? 'all');

            let visibleCount = 0;

            cards.forEach(function(card) {
                const matchesCategory =
                    selectedCategory === 'all' ||
                    card.dataset.category === selectedCategory;

                const matchesSearch = normalize(
                    card.dataset.name
                ).includes(query);

                const foodStatus = String(card.dataset.isActive ?? '');

                const matchesStatus =
                    selectedStatus === 'all' ||
                    foodStatus === selectedStatus;

                const visible =
                    matchesCategory &&
                    matchesSearch &&
                    matchesStatus;

                card.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            if (foodCount) {
                const countLabel = foodCount.querySelector('#food-count-label');

                if (countLabel) {
                    countLabel.textContent = `${visibleCount} وجبة`;
                } else {
                    foodCount.textContent = `${visibleCount} وجبة`;
                }
            }

            if (foodCountTop) {
                foodCountTop.textContent = visibleCount;
            }

            noResults?.classList.toggle('hidden', visibleCount === 0);

            if (clearSearch) {
                clearSearch.classList.toggle(
                    'hidden',
                    !(searchInput?.value.length)
                );
            }
        }

        statusFilter?.addEventListener('change', filterFoods);

        function activateCategory(button) {
            selectedCategory = button.dataset.category ?? 'all';

            categoryButtons.forEach(function(item) {
                const active = item === button;

                item.classList.toggle('active', active);

                item.setAttribute(
                    'aria-pressed',
                    active ? 'true' : 'false'
                );
            });

            filterFoods();
        }

        categoryButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                activateCategory(button);
            });
        });

        searchInput?.addEventListener('input', filterFoods);

        statusFilter?.addEventListener('change', filterFoods);

        clearSearch?.addEventListener('click', function() {
            if (!searchInput) return;

            searchInput.value = '';

            filterFoods();

            searchInput.focus();
        });

        document.getElementById('reset-filters')
            ?.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                }

                if (statusFilter) {
                    statusFilter.value = 'all';
                }

                const allButton = categoryButtons.find(function(button) {
                    return button.dataset.category === 'all';
                });

                if (allButton) {
                    activateCategory(allButton);
                } else {
                    filterFoods();
                }
            });

        // =========================================================
        // إنشاء بطاقة الوجبة ضمن الطلبات الجديدة
        // =========================================================

        function createOrderRow({
            id,
            name,
            price,
            quantity,
            total,
            note = '',
            isNew = false,
            canEdit = false
        }) {
            const row = document.createElement('div');

            row.className =
                'order-item flex w-full min-w-0 flex-col gap-3 rounded-2xl border p-3 sm:p-4 ' +
                (
                    isNew ?
                    'border-[var(--restaurant-color-hex-174c3c)]/20 bg-[var(--restaurant-color-hex-f5faf6)]' :
                    'border-[var(--restaurant-color-hex-d1d5db)] bg-[var(--restaurant-color-hex-f5faf6)]'
                );

            row.dataset.orderId = String(id ?? '');

            if (isNew && row.dataset.animateAdded === 'true') {
                row.classList.add('food-added-animation');
            }

            const header = document.createElement('div');

            header.className =
                'flex w-full min-w-0 items-start justify-between gap-3';

            const titleRow = document.createElement('div');

            titleRow.className =
                'flex min-w-0 flex-wrap items-center gap-2';

            const title = document.createElement('p');

            title.className =
                'break-words text-sm font-extrabold text-gray-800';

            title.textContent = String(name ?? 'وجبة');

            const status = document.createElement('span');

            status.className =
                'inline-flex shrink-0 items-center rounded-full px-2 py-1 text-[10px] font-bold ' +
                (
                    isNew ?
                    'bg-[var(--restaurant-color-hex-174c3c)] text-white' :
                    'bg-gray-200 text-gray-700'
                );

            status.textContent = isNew ? 'طلب جديد' : 'طلب سابق';

            titleRow.append(title, status);
            header.appendChild(titleRow);

            const priceDetails = document.createElement('div');

            priceDetails.className =
                'grid w-full grid-cols-3 gap-2';

            function createPriceBox(label, value, classes = '') {
                const box = document.createElement('div');

                box.className =
                    'min-w-0 rounded-xl bg-white p-2 ' + classes;

                const labelElement = document.createElement('p');

                labelElement.className =
                    'text-[10px] font-medium text-gray-500';

                labelElement.textContent = label;

                const valueElement = document.createElement('p');

                valueElement.className =
                    'mt-1 break-words text-xs font-bold text-gray-800';

                valueElement.textContent = String(value);

                box.append(labelElement, valueElement);

                return box;
            }

            priceDetails.append(
                createPriceBox(
                    'سعر الوجبة',
                    `${formatPrice(price)} ل.س`
                ),
                createPriceBox(
                    'الكمية',
                    Number(quantity) || 0
                ),
                createPriceBox(
                    'إجمالي الصنف',
                    `${formatPrice(total)} ل.س`,
                    'bg-[var(--restaurant-color-hex-e8f0e8)]'
                )
            );

            row.append(header, priceDetails);

            if (canEdit && isNew) {
                const noteField = document.createElement('label');
                noteField.className = 'block w-full text-xs font-bold text-gray-600';
                noteField.textContent = 'ملاحظة للوجبة (اختياري)';

                const noteInput = document.createElement('textarea');
                noteInput.rows = 2;
                noteInput.maxLength = 1000;
                noteInput.value = String(note ?? '');
                noteInput.placeholder = 'اكتب ملاحظاتك هنا';
                noteInput.className =
                    'order-note-input mt-1 block w-full resize-y rounded-xl border border-gray-200 bg-white p-3 text-sm font-normal text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[var(--restaurant-color-hex-59816d)] focus:ring-2 focus:ring-[var(--restaurant-color-hex-59816d)]/20';
                noteInput.setAttribute('aria-label', `ملاحظة لوجبة ${name}`);
                noteInput.addEventListener('input', function() {
                    const item = order.get(String(id));
                    if (item) {
                        item.note = noteInput.value;
                    }
                });
                noteField.appendChild(noteInput);
                row.appendChild(noteField);

                const actions = document.createElement('div');

                actions.className =
                    'flex w-full items-center justify-between gap-3 border-t border-[var(--restaurant-color-hex-174c3c)]/10 pt-3';

                const quantityControls = document.createElement('div');

                quantityControls.className =
                    'flex items-center gap-2';

                const decrease = document.createElement('button');

                decrease.type = 'button';

                decrease.className =
                    'flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-lg font-black text-gray-700';

                decrease.textContent = '−';

                decrease.setAttribute(
                    'aria-label',
                    `تقليل كمية ${name}`
                );

                decrease.addEventListener('click', function() {
                    if (isSubmitting) return;

                    const item = order.get(String(id));

                    if (!item) return;

                    if (item.quantity > 1) {
                        item.quantity--;
                    } else {
                        order.delete(String(id));
                    }

                    updateOrderSummary();
                });

                const quantityElement = document.createElement('span');

                quantityElement.className =
                    'min-w-6 text-center text-sm font-black text-gray-800';

                quantityElement.textContent = String(quantity);

                const increase = document.createElement('button');

                increase.type = 'button';

                increase.className =
                    'flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--restaurant-color-hex-e8f0e8)] text-lg font-black text-[var(--restaurant-color-hex-174c3c)]';

                increase.textContent = '+';

                increase.setAttribute(
                    'aria-label',
                    `زيادة كمية ${name}`
                );

                increase.addEventListener('click', function() {
                    if (isSubmitting) return;

                    const item = order.get(String(id));

                    if (!item) return;

                    item.quantity++;

                    updateOrderSummary();
                });

                quantityControls.append(
                    decrease,
                    quantityElement,
                    increase
                );

                const remove = document.createElement('button');

                remove.type = 'button';

                remove.className =
                    'flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50';

                remove.setAttribute(
                    'aria-label',
                    `حذف ${name} من الطلب`
                );

                remove.innerHTML = `
                    <svg class="h-4 w-4" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6"/>
                    </svg>
                    <span>حذف الوجبة</span>
                `;

                remove.addEventListener('click', function() {
                    if (isSubmitting) return;

                    order.delete(String(id));

                    updateOrderSummary();
                });

                actions.append(quantityControls, remove);

                row.appendChild(actions);
            }

            return row;
        }

        // =========================================================
        // عرض الطلبات الحالية
        // =========================================================

        function renderOrders() {
            if (ordersList) {
                ordersList.replaceChildren();

                order.forEach(function(item, id) {
                    ordersList.appendChild(createOrderRow({
                        id: item.id ?? id,
                        name: item.name,
                        price: item.price,
                        quantity: item.quantity,
                        total: Number(item.price) * Number(item.quantity),
                        note: item.note,
                        isNew: true,
                        canEdit: true
                    }));
                });
            }

            const hasNewOrders = order.size > 0;
            const hasOldOrders = hasPreviousOrders();
            updateRequestBillVisibility();

            currentOrdersSection?.classList.toggle(
                'hidden',
                !hasNewOrders
            );

            if (previousOrdersSection) {
                toggleSection(previousOrdersSection, hasOldOrders && !hasNewOrders);
            }

            ordersEmpty?.classList.toggle(
                'hidden',
                hasNewOrders || hasOldOrders
            );
        }

        // =========================================================
        // تحديث زر تأكيد الطلب
        // =========================================================

        function updateConfirmButton() {
            if (confirmOrderList) {
                confirmOrderList.disabled =
                    order.size === 0 || isSubmitting;

                confirmOrderList.setAttribute(
                    'aria-disabled',
                    confirmOrderList.disabled ? 'true' : 'false'
                );
            }

            if (approveConfirmOrder) {
                approveConfirmOrder.disabled =
                    order.size === 0 || isSubmitting;
            }
        }

        // =========================================================
        // تحديث إجماليات نافذة الطلبات
        // =========================================================

        function updateOrdersModalTotals() {
            const previousTotal = getPreviousOrdersTotal();
            const currentTotal = getCurrentOrdersTotal();
            const servicesTotal = getServicesTotal();

            const finalTotal =
                previousTotal + currentTotal + servicesTotal;

            const previousQuantity = getPreviousOrdersQuantity();
            const currentQuantity = getCurrentOrdersQuantity();

            const totalQuantity =
                previousQuantity + currentQuantity;

            setText(
                'modal-previous-orders-total',
                formatCurrency(previousTotal)
            );

            setText(
                'footer-previous-orders-total',
                formatCurrency(previousTotal)
            );

            setText(
                'modal-current-orders-total',
                formatCurrency(currentTotal)
            );

            setText(
                'footer-current-orders-total',
                formatCurrency(currentTotal)
            );

            setText(
                'order-services-total',
                formatCurrency(servicesTotal)
            );

            setText(
                'footer-services-total',
                formatCurrency(servicesTotal)
            );

            setText('modal-order-total', formatPrice(finalTotal));

            setText('current-orders-count', `${currentQuantity} وجبة`);
            setText('total-orders-count', totalQuantity);
            setText('new-orders-count', currentQuantity);
            setText('previous-orders-count', `${getExistingOrders().length} صنف`);

            toggleSection(
                document.getElementById('previous-orders-summary'),
                hasPreviousOrders()
            );

            toggleSection(
                document.getElementById('previous-orders-total-row'),
                hasPreviousOrders()
            );

            toggleSection(
                currentOrdersSection,
                currentQuantity > 0
            );
        }

        // =========================================================
        // تحديث الملخصات والعدادات
        // =========================================================

        function updateOrderSummary() {
            const currentQuantity = getCurrentOrdersQuantity();
            const previousQuantity = getPreviousOrdersQuantity();

            const currentTotal = getCurrentOrdersTotal();
            const previousTotal = getPreviousOrdersTotal();

            const hasOldOrders = hasPreviousOrders();
            const hasCurrentOrders = currentQuantity > 0;

            const totalQuantity =
                currentQuantity + previousQuantity;

            toggleSection(previousOrderSummary, hasOldOrders);
            toggleSection(currentOrderSummary, hasCurrentOrders);

            if (currentOrderSummary) {
                currentOrderSummary.classList.toggle(
                    'w-full',
                    hasCurrentOrders && !hasOldOrders
                );

                currentOrderSummary.classList.toggle(
                    'flex-1',
                    hasCurrentOrders && !hasOldOrders
                );

                currentOrderSummary.classList.toggle(
                    'min-w-0',
                    hasCurrentOrders && !hasOldOrders
                );

                currentOrderSummary.classList.toggle(
                    'flex-none',
                    hasCurrentOrders && hasOldOrders
                );
            }

            if (currentOrderCount) {
                currentOrderCount.textContent = currentQuantity;
            }

            if (currentOrderBadge) {
                currentOrderBadge.textContent = currentQuantity;

                currentOrderBadge.classList.toggle(
                    'hidden',
                    !hasCurrentOrders
                );
            }

            if (currentOrderTotal) {
                currentOrderTotal.textContent =
                    formatPrice(currentTotal);
            }

            if (previousOrderCount) {
                previousOrderCount.textContent = previousQuantity;
            }

            if (previousOrderBadge) {
                previousOrderBadge.textContent = previousQuantity;

                previousOrderBadge.classList.toggle(
                    'hidden',
                    !hasOldOrders
                );
            }

            if (previousOrderTotal) {
                previousOrderTotal.textContent =
                    formatPrice(previousTotal);
            }

            if (orderCount) {
                orderCount.textContent = totalQuantity;
            }

            if (orderBadge) {
                orderBadge.textContent = totalQuantity;

                orderBadge.classList.toggle(
                    'hidden',
                    totalQuantity === 0
                );
            }

            if (buttonOrderCount) {
                buttonOrderCount.textContent = totalQuantity;

                buttonOrderCount.classList.toggle(
                    'hidden',
                    totalQuantity === 0
                );
            }

            const finalTotal = getFinalTotal();

            if (orderTotal) {
                orderTotal.textContent = formatPrice(finalTotal);
            }

            if (orderTotalDesktop) {
                orderTotalDesktop.textContent = formatPrice(finalTotal);
            }

            renderPreviousOrders();
            renderOrders();

            updateOrdersModalTotals();
            updateConfirmButton();
        }

        // =========================================================
        // فتح وإغلاق نافذة الطلبات
        // =========================================================

        function openOrdersModal() {
            if (!ordersModal) return;

            previousFocusedElement = document.activeElement;

            renderPreviousOrders();
            renderOrders();

            updateOrdersModalTotals();

            ordersModal.classList.remove('hidden');
            ordersModal.classList.add('flex');

            ordersModal.setAttribute('aria-hidden', 'false');

            document.body.classList.add('overflow-hidden');

            document.getElementById('close-orders-modal')?.focus();
        }

        function closeOrdersModal() {
            if (!ordersModal) return;

            ordersModal.classList.add('hidden');
            ordersModal.classList.remove('flex');

            ordersModal.setAttribute('aria-hidden', 'true');

            document.body.classList.remove('overflow-hidden');

            previousFocusedElement?.focus?.();
        }

        document.getElementById('submit-order')
            ?.addEventListener('click', async function() {
                openOrdersModal();

                if (isSubmitting) return;

                try {
                    await refreshOrders();
                } catch (error) {
                    console.error(
                        'Failed to refresh orders when opening the order list:',
                        error
                    );

                    showMessage(
                        error.message || 'تعذر تحديث حالات الطلبات.',
                        'error'
                    );
                }
            });

        document.getElementById('request-bill')
            ?.addEventListener('click', function() {
                showMessage(
                    'يرجى إبلاغ أحد الموظفين بطلب الحساب. لا يتم إرسال الطلب من النظام حاليًا.',
                    'info'
                );
            });

        document.getElementById('close-orders-modal')
            ?.addEventListener('click', closeOrdersModal);

        document.getElementById('browse-menu')
            ?.addEventListener('click', function() {
                closeOrdersModal();

                document.getElementById('menu-items')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });

        ordersModal?.addEventListener('click', function(event) {
            if (event.target === ordersModal) {
                closeOrdersModal();
            }
        });

        // =========================================================
        // إضافة الوجبات إلى الطلب
        // =========================================================

        document.querySelectorAll('.add-to-order').forEach(function(button) {
            button.addEventListener('click', function() {
                if (isSubmitting) return;

                const id = String(button.dataset.id ?? '');
                const name = button.dataset.name;
                const price = Number(button.dataset.price);

                if (
                    !id ||
                    !name ||
                    !Number.isFinite(price) ||
                    price < 0
                ) {
                    return;
                }

                if (order.has(id)) {
                    order.get(id).quantity++;
                } else {
                    order.set(id, {
                        id,
                        name,
                        price,
                        quantity: 1
                    });
                }

                updateOrderSummary();

                const originalHtml = button.innerHTML;

                button.innerHTML = `
                    <svg class="h-4 w-4" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6"/>
                    </svg>
                    تمت الإضافة
                `;

                button.classList.add('!bg-emerald-700');

                window.setTimeout(function() {
                    button.innerHTML = originalHtml;
                    button.classList.remove('!bg-emerald-700');
                }, 1000);
            });
        });

        // =========================================================
        // فتح وإغلاق نافذة تأكيد الطلب
        // =========================================================

        function openConfirmOrderModal() {
            if (
                order.size === 0 ||
                isSubmitting ||
                !confirmOrderModal
            ) {
                return;
            }

            setText('confirm-order-items-count', order.size);

            setText(
                'confirm-order-quantity',
                getCurrentOrdersQuantity()
            );

            setText(
                'confirm-order-total',
                formatPrice(getCurrentOrdersTotal())
            );

            confirmOrderModal.classList.remove('hidden');
            confirmOrderModal.classList.add('flex');

            confirmOrderModal.setAttribute('aria-hidden', 'false');

            cancelConfirmOrder?.focus();
        }

        function closeConfirmOrderModal() {
            if (!confirmOrderModal) return;

            confirmOrderModal.classList.add('hidden');
            confirmOrderModal.classList.remove('flex');

            confirmOrderModal.setAttribute('aria-hidden', 'true');

            resetConfirmPress();
        }

        cancelConfirmOrder?.addEventListener(
            'click',
            closeConfirmOrderModal
        );

        confirmOrderModal?.addEventListener('click', function(event) {
            if (event.target === confirmOrderModal) {
                closeConfirmOrderModal();
            }
        });

        // =========================================================
        // الضغط المطول على زر تأكيد الطلب
        // =========================================================

        if (confirmOrderList) {
            confirmOrderList.style.position = 'relative';
            confirmOrderList.style.overflow = 'hidden';
            confirmOrderList.style.touchAction = 'none';
            confirmOrderList.style.userSelect = 'none';
            confirmOrderList.style.webkitUserSelect = 'none';

            const originalChildren = [...confirmOrderList.childNodes];

            confirmOrderList.replaceChildren();

            const buttonContent = document.createElement('span');

            buttonContent.className =
                'relative z-[2] inline-flex items-center gap-2';

            originalChildren.forEach(function(child) {
                buttonContent.appendChild(child);
            });

            if (!buttonContent.textContent.trim()) {
                buttonContent.textContent = 'اضغط مطولًا لتأكيد الطلب';
            }

            confirmOrderList.appendChild(buttonContent);

            confirmProgressLayer = document.createElement('span');

            confirmProgressLayer.setAttribute('aria-hidden', 'true');

            confirmProgressLayer.style.cssText = `
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                width: 0%;
                background: var(--restaurant-color-rgb-255-255-255-point-30);
                border-radius: inherit;
                pointer-events: none;
                z-index: 1;
                transition: none;
            `;

            confirmOrderList.appendChild(confirmProgressLayer);
        }

        function updateConfirmProgress() {
            if (!confirmPressStart || !confirmProgressLayer) return;

            const elapsed = performance.now() - confirmPressStart;

            const progress = Math.min(
                elapsed / CONFIRM_PRESS_DURATION,
                1
            );

            confirmProgressLayer.style.width = `${progress * 100}%`;

            if (progress < 1) {
                confirmAnimationFrame =
                    requestAnimationFrame(updateConfirmProgress);
            } else {
                confirmAnimationFrame = null;
            }
        }

        function resetConfirmPress() {
            if (confirmPressTimer !== null) {
                clearTimeout(confirmPressTimer);
                confirmPressTimer = null;
            }

            if (confirmAnimationFrame !== null) {
                cancelAnimationFrame(confirmAnimationFrame);
                confirmAnimationFrame = null;
            }

            confirmPressStart = 0;

            if (confirmProgressLayer) {
                confirmProgressLayer.style.transition = 'none';
                confirmProgressLayer.style.width = '0%';
            }

            confirmOrderList?.classList.remove('confirm-pressing');
        }

        function startConfirmPress(event) {
            if (
                isSubmitting ||
                order.size === 0 ||
                confirmPressTimer !== null ||
                confirmPressTriggered
            ) {
                return;
            }

            if (event.cancelable) {
                event.preventDefault();
            }

            confirmPressStart = performance.now();

            if (confirmProgressLayer) {
                confirmProgressLayer.style.transition = 'none';
                confirmProgressLayer.style.width = '0%';
            }

            confirmOrderList?.classList.add('confirm-pressing');

            updateConfirmProgress();

            confirmPressTimer = window.setTimeout(async function() {
                confirmPressTimer = null;
                confirmPressStart = 0;
                confirmPressTriggered = true;

                if (confirmAnimationFrame !== null) {
                    cancelAnimationFrame(confirmAnimationFrame);
                    confirmAnimationFrame = null;
                }

                if (confirmProgressLayer) {
                    confirmProgressLayer.style.width = '100%';
                }

                confirmOrderList?.classList.remove('confirm-pressing');

                try {
                    if (!isSubmitting && order.size > 0) {
                        await sendOrder();
                    }
                } finally {
                    confirmPressTriggered = false;

                    if (confirmProgressLayer) {
                        confirmProgressLayer.style.transition = 'none';
                        confirmProgressLayer.style.width = '0%';
                    }

                    confirmOrderList?.classList.remove('confirm-pressing');
                }
            }, CONFIRM_PRESS_DURATION);
        }

        function endConfirmPress(event) {
            if (event?.cancelable) {
                event.preventDefault();
            }

            if (confirmPressTimer !== null) {
                resetConfirmPress();
            }
        }

        confirmOrderList?.addEventListener(
            'pointerdown',
            startConfirmPress
        );

        confirmOrderList?.addEventListener(
            'pointerup',
            endConfirmPress
        );

        confirmOrderList?.addEventListener(
            'pointercancel',
            endConfirmPress
        );

        confirmOrderList?.addEventListener(
            'pointerleave',
            endConfirmPress
        );

        confirmOrderList?.addEventListener(
            'lostpointercapture',
            endConfirmPress
        );

        confirmOrderList?.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                resetConfirmPress();
            }
        });

        // =========================================================
        // تحديث الطلبات السابقة من الخادم
        // =========================================================

        async function refreshOrders() {
            if (refreshOrdersPromise) {
                refreshOrdersAgain = true;

                return refreshOrdersPromise;
            }

            isRefreshingOrders = true;

            if (refreshPreviousOrdersButton) {
                refreshPreviousOrdersButton.disabled = true;
            }

            refreshPreviousOrdersIcon?.classList.add('animate-spin');

            refreshOrdersPromise = (async function() {
                let refreshedOrders = oldOrders;

                do {
                    refreshOrdersAgain = false;

                    const response = await fetch(refreshUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        cache: 'no-store'
                    });

                    if (!response.ok) {
                        throw new Error(
                            `تعذر تحديث الطلبات (${response.status}).`
                        );
                    }

                    const result = await response.json();
                    const refreshedData = normalizeOrdersResponse(result);

                    if (!refreshedData) {
                        console.error(
                            'Unexpected orders response:',
                            result
                        );

                        throw new Error(
                            'تعذر قراءة الطلبات من الخادم. تحقق من تنسيق الاستجابة.'
                        );
                    }

                    oldOrders = refreshedData;
                    updateRequestBillVisibility();

                    const responseServices =
                        result?.services ??
                        result?.data?.services ??
                        result?.data?.data?.services;

                    if (Array.isArray(responseServices)) {
                        tableServices = responseServices;
                    }

                    updateOrderSummary();
                    refreshedOrders = oldOrders;
                } while (refreshOrdersAgain);

                return refreshedOrders;
            })();

            try {
                return await refreshOrdersPromise;
            } finally {
                refreshOrdersPromise = null;
                refreshOrdersAgain = false;
                isRefreshingOrders = false;

                if (refreshPreviousOrdersButton) {
                    refreshPreviousOrdersButton.disabled = false;
                }

                refreshPreviousOrdersIcon?.classList.remove('animate-spin');
            }
        }

        window.refreshOrders = refreshOrders;

        let isSubscribedToTableUpdates = false;

        function subscribeToTableUpdates() {
            if (!window.Echo) {
                return;
            }

            if (isSubscribedToTableUpdates) {
                return;
            }

            isSubscribedToTableUpdates = true;
            window.Echo
                .channel(`table.${tableId}`)
                .listen('.cashier.dashboard.updated', function() {
                    refreshOrders().catch(function(error) {
                        console.error('Failed to refresh order statuses after a live update:', error);
                    });
                });
        }

        window.addEventListener('restaurant:echo-ready', subscribeToTableUpdates);

        if (window.Echo) {
            subscribeToTableUpdates();
        } else {
            console.info('Waiting for Laravel Echo before subscribing to live menu order updates.');
        }

        refreshPreviousOrdersButton?.addEventListener(
            'click',
            async function() {
                if (isRefreshingOrders || isSubmitting) return;

                try {
                    await refreshOrders();
                } catch (error) {
                    console.error(
                        'Failed to refresh previous orders:',
                        error
                    );

                    showMessage(
                        error.message || 'تعذر تحديث حالات الطلبات.',
                        'error'
                    );
                }
            }
        );

        // =========================================================
        // إرسال الطلبات الجديدة إلى الخادم
        // =========================================================

        async function sendOrder() {
            if (isSubmitting || order.size === 0) return;

            isSubmitting = true;

            updateConfirmButton();

            confirmOrderList?.classList.add(
                'opacity-70',
                'cursor-not-allowed'
            );

            try {
                const response = await fetch(submitUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        billNumber: billNumber,
                        items: [...order.values()].map(function(item) {
                            return {
                                id: item.id,
                                quantity: item.quantity,
                                note: item.note?.trim() ?? ''
                            };
                        })
                    })
                });

                const contentType =
                    response.headers.get('content-type') || '';

                const data = contentType.includes('application/json') ?
                    await response.json() : {
                        message: await response.text()
                    };

                if (
                    !response.ok ||
                    data.status === false ||
                    data.status === 'failed' ||
                    data.success === false
                ) {
                    if (response.status === 422 && data.errors) {
                        throw new Error(
                            Object.values(data.errors).flat().join('\n')
                        );
                    }

                    if (response.status === 419) {
                        throw new Error(
                            'انتهت صلاحية الجلسة. حدّث الصفحة وحاول مجددًا.'
                        );
                    }

                    throw new Error(
                        data.message || 'فشل إرسال الطلب.'
                    );
                }

                order.clear();

                try {
                    await refreshOrders();
                } catch (refreshError) {
                    console.error(
                        'Failed to refresh orders after submission:',
                        refreshError
                    );
                }

                updateOrderSummary();

                closeConfirmOrderModal();
                closeOrdersModal();

                showOrderSuccessToast();
            } catch (error) {
                hideOrderSuccessToast();

                console.error('Failed to send order:', error);

                showMessage(
                    error.message || 'حدث خطأ أثناء إرسال الطلب.',
                    'error'
                );
            } finally {
                isSubmitting = false;
                confirmPressTriggered = false;

                confirmOrderList?.classList.remove(
                    'opacity-70',
                    'cursor-not-allowed',
                    'confirm-pressing'
                );

                resetConfirmPress();

                updateConfirmButton();
                updateOrderSummary();
            }
        }

        approveConfirmOrder?.addEventListener('click', sendOrder);

        // =========================================================
        // لوحة المفاتيح وإغلاق النوافذ
        // =========================================================

        document.addEventListener('keydown', function(event) {
            if (event.key !== 'Escape') return;

            if (confirmOrderModal?.classList.contains('flex')) {
                closeConfirmOrderModal();
                return;
            }

            if (
                ordersModal &&
                !ordersModal.classList.contains('hidden')
            ) {
                closeOrdersModal();
            }
        });

        // =========================================================
        // تهيئة الواجهة عند تحميل الصفحة
        // =========================================================

        filterFoods();

        updateOrderSummary();

        window.setInterval(updatePreviousOrderCountdowns, 1000);
    });

    // =============================================================
    // تحريك شريط التصنيفات أفقيًا
    // =============================================================

    function scrollCategories() {
        const categories = document.getElementById('categories');

        if (!categories) return;

        const direction =
            getComputedStyle(categories).direction === 'rtl' ? -1 : 1;

        categories.scrollBy({
            left: direction * Math.max(
                180,
                categories.clientWidth * 0.7
            ),
            behavior: 'smooth'
        });
    }
</script>
