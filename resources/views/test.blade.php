<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديد الموقع الجغرافي</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f6f8;
        }

        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .1);
            text-align: center;
            max-width: 350px;
            width: 90%;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:disabled {
            background-color: #999;
            cursor: not-allowed;
        }

        #status {
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }
    </style>
</head>

<body>

    <div class="card">
        {{-- <h2>التحقق من الموقع</h2>

        <p>يرجى السماح للمتصفح بالوصول إلى موقعك للتحقق من وجودك ضمن النطاق المسموح.</p> --}}

        <button id="locateBtn" type="button">
            تحديد موقعي الآن 📍
        </button>

        <div id="status" role="status" aria-live="polite"></div>
    </div>
    {{--
    <script>
        const targetLat = {{ config('location.latitude') }};
        const targetLng = {{ config('location.longitude') }};
        const maxAllowedDistance = 50;

        const statusDiv = document.getElementById('status');
        const locateBtn = document.getElementById('locateBtn');

        let requestingLocation = false;

        function showMessage(message, color = '#333') {
            statusDiv.style.color = color;
            statusDiv.textContent = message;
        }

        function showBlockedMessage() {
            statusDiv.replaceChildren();

            const message = document.createElement('p');
            message.textContent =
                'تم حظر إذن الموقع. لإعادة طلب الإذن، افتح إعدادات الموقع في المتصفح وغيّر الموقع إلى السماح، ثم اضغط إعادة المحاولة.';

            const instructions = document.createElement('p');
            instructions.textContent =
                'في Chrome: اضغط على رمز الإعدادات بجانب عنوان الموقع ← إعدادات الموقع ← الموقع الجغرافي ← السماح.';

            statusDiv.append(message, instructions);
        }

        async function requestUserLocation() {
            if (requestingLocation) return;

            if (!navigator.geolocation) {
                showMessage('متصفحك لا يدعم تحديد الموقع.', 'red');
                return;
            }

            if (!window.isSecureContext) {
                showMessage(
                    'افتح الصفحة باستخدام HTTPS أو localhost.',
                    'red'
                );
                return;
            }

            // Check current browser permission.
            if (navigator.permissions) {
                const permission = await navigator.permissions.query({
                    name: 'geolocation'
                });

                if (permission.state === 'denied') {
                    showBlockedMessage();
                    return;
                }
            }

            requestingLocation = true;
            locateBtn.disabled = true;

            showMessage('جارٍ طلب إذن الموقع وتحديد موقعك...');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    requestingLocation = false;
                    locateBtn.disabled = false;

                    const distance = calculateDistance(
                        targetLat,
                        targetLng,
                        position.coords.latitude,
                        position.coords.longitude
                    );

                    if (distance <= maxAllowedDistance) {
                        showMessage(
                            '✅ أنت داخل النطاق المسموح به!',
                            'green'
                        );
                    } else {
                        showMessage(
                            `❌ أنت خارج النطاق المسموح. المسافة: ${Math.round(distance)} متر.`,
                            'red'
                        );
                    }
                },

                function(error) {
                    requestingLocation = false;
                    locateBtn.disabled = false;

                    if (error.code === error.PERMISSION_DENIED) {
                        showBlockedMessage();
                        if (navigator.permissions) {
                            const permission = navigator.permissions.query({
                                name: 'geolocation'
                            });

                            if (permission.state === 'denied') {
                                showBlockedMessage();
                                return;
                            }
                        }

                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        showMessage(
                            'تعذر تحديد موقعك. تحقق من إعدادات الموقع في جهازك.',
                            'red'
                        );
                    } else if (error.code === error.TIMEOUT) {
                        showMessage(
                            'انتهت مهلة تحديد الموقع. حاول مجدداً.',
                            'red'
                        );
                    } else {
                        showMessage('حدث خطأ أثناء تحديد الموقع.', 'red');
                    }
                },

                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const earthRadius = 6371000;

            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;

            const a =
                Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1 * Math.PI / 180) *
                Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) ** 2;

            return earthRadius * 2 * Math.atan2(
                Math.sqrt(a),
                Math.sqrt(1 - a)
            );
        }

        // Register only once.
        locateBtn.addEventListener('click', requestUserLocation);

        // Ask when the page opens or reloads.
        window.addEventListener('load', requestUserLocation);
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const locateBtn = document.getElementById('locateBtn');
            locateBtn.addEventListener('click', () => {
                const deviceId = getAppDeviceId();
                console.log(deviceId);
            });

            function getAppDeviceId() {
                const key = 'appDeviceId';
                let id = localStorage.getItem(key);

                if (!id) {
                    id = crypto.randomUUID();
                    localStorage.setItem(key, id);
                }

                return id;
            }
        });
    </script>

</body>

</html>
