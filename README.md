# نظام إدارة المطعم

تطبيق لإدارة قائمة الطعام والطلبات والطاولات، مع واجهات للإدارة والكاشير وتحديث فوري لحالات الطلبات باستخدام Laravel Reverb.

## المتطلبات

- PHP 8.2 أو أحدث مع إضافات PHP المطلوبة من Laravel وقاعدة البيانات المستخدمة.
- Composer.
- Node.js وnpm لبناء ملفات الواجهة.
- قاعدة بيانات مضبوطة في ملف `.env`.
- خادم ويب مثل Nginx أو Apache في الإنتاج.

## إعداد المشروع لأول مرة

من مجلد المشروع:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

في Windows PowerShell، استخدم `Copy-Item .env.example .env` بدل `cp`.

عدّل `.env` وأدخل إعدادات قاعدة البيانات وإعدادات Reverb الموضحة أدناه، ثم نفّذ:

```bash
php artisan migrate
npm install
npm run build
```

لا تستخدم `migrate:fresh` على قاعدة بيانات تحتوي بيانات مهمة؛ هذا الأمر يحذف الجداول والبيانات.

## إعداد Reverb والتحديثات الفورية

يستخدم التطبيق Reverb لإرسال تحديثات حالات الطلبات إلى صفحة الطاولة ولوحة الكاشير. أضف بيانات تطبيق Reverb إلى `.env` من دون مشاركة المفتاح السري أو حفظه في Git:

عند الإعداد لأول مرة، أنشئ إعدادات التطبيق ومفاتيحه باستخدام الأمر التالي، ثم راجع قيم Reverb في `.env`:

```bash
php artisan reverb:install
```

```dotenv
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=ضع_معرف_التطبيق
REVERB_APP_KEY=ضع_المفتاح_العام
REVERB_APP_SECRET=ضع_المفتاح_السري

# عنوان Reverb الذي يستطيع Laravel الوصول إليه.
REVERB_HOST=restaurant.example.com
REVERB_PORT=443
REVERB_SCHEME=https

# عنوان ومنفذ الاستماع الداخلي لخادم Reverb.
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8081

# هذه القيم تُضمّن في ملفات JavaScript عند تنفيذ npm run build.
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

استبدل `restaurant.example.com` باسم النطاق الفعلي. يجب أن يوجّه إعداد الوكيل العكسي (مثل Nginx) اتصالات WebSocket الآمنة `wss://` من المنفذ العام 443 إلى Reverb على المنفذ الداخلي 8081. افتح المنفذ الداخلي مباشرةً على الإنترنت فقط إذا كان ذلك مقصودًا ومؤمّنًا.

بعد تغيير أي قيمة `VITE_REVERB_*`، أعد بناء ملفات الواجهة:

```bash
npm run build
```

## تشغيل محلي للتطوير

لتشغيل خادم Laravel وVite وReverb معًا:

```bash
composer run dev
```

أو شغّل كل عملية في نافذة طرفية منفصلة:

```bash
php artisan serve
npm run dev
php artisan reverb:start --host=0.0.0.0 --port=8081
```

هذه أوامر تطوير محلي؛ لا تستخدم `php artisan serve` كخادم إنتاج.

## تشغيل النظام على خادم إنتاج

بعد رفع إصدار التطبيق إلى الخادم وضبط `.env` وقاعدة البيانات وإعدادات النطاق وReverb:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
```

وجّه Nginx أو Apache إلى مجلد `public`، واضبط HTTPS، واجعل `storage` و`bootstrap/cache` قابلين للكتابة من مستخدم PHP. في إعداد الإنتاج يجب أن يكون:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://restaurant.example.com
```

احتفظ بنسخة احتياطية من قاعدة البيانات قبل تشغيل الترحيلات على بيانات فعلية. بعد تغيير `.env` أو ملفات الإعدادات، أعد تحميل إعدادات Laravel عند الحاجة:

```bash
php artisan optimize:clear
php artisan config:cache
```

### تشغيل Reverb باستمرار

يجب أن يبقى Reverb عاملًا في الخلفية. شغّله مؤقتًا للاختبار باستخدام:

```bash
php artisan reverb:start --host=0.0.0.0 --port=8081
```

في الإنتاج، شغّله بواسطة مدير عمليات مثل Supervisor أو systemd كي يعيد تشغيله تلقائيًا بعد إعادة تشغيل الخادم أو تعطل العملية. مثال Supervisor (عدّل المسارات والمستخدم لتناسب الخادم):

```ini
[program:restaurant-reverb]
directory=/var/www/restaurant
command=/usr/bin/php artisan reverb:start --host=0.0.0.0 --port=8081
user=www-data
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
stdout_logfile=/var/log/restaurant-reverb.log
redirect_stderr=true
```

بعد حفظ ملف Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start restaurant-reverb
sudo supervisorctl status restaurant-reverb
```

### تشغيل المهمة المجدولة

المهمة `orders:mark-ready` تفحص كل خمس دقائق الطلبات النشطة التي حالتها `1`، وتنقل الطلبات التي انقضى وقت تحضيرها إلى الحالة `3`، ثم تبث تحديثًا للطاولة. شغّل مجدول Laravel من cron **مرة واحدة كل دقيقة**؛ Laravel هو الذي يقرر أن الأمر المجدول ينفذ كل خمس دقائق:

```cron
* * * * * cd /var/www/restaurant && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

استبدل المسار ومسار PHP بالقيم الصحيحة على الخادم. لا تضف هذا السطر أكثر من مرة. لا تستخدم `schedule:work` كبديل دائم عن cron في خادم الإنتاج.

للتحقق من الجدولة يدويًا:

```bash
php artisan schedule:list
php artisan orders:mark-ready
```

الأمر الثاني ينفذ الفحص فورًا للاختبار؛ وهو لا يغيّر إلا الطلبات التي حان وقتها.

## دورة الطلب والحالات

| الحالة | المعنى |
| --- | --- |
| `0` | بانتظار تأكيد الكاشير |
| `1` | في المطبخ؛ يبدأ حساب وقت التحضير بعد تأكيد الكاشير |
| `2` | على الطاولة |
| `3` | على الطاولة؛ الحالة التي تعيّنها المهمة المجدولة عند انتهاء التحضير |

يظهر زر **«طلب الحساب»** في قائمة الطلبات عندما توجد طلبات سابقة، وتكون كل حالاتها `2` أو `3`، ولا توجد وجبات جديدة غير مرسلة في سلة الطلب الحالية. الطلب الجديد يخفي الزر حتى تُرسل الطلبات وتصل كل الحالات إلى الطاولة. عند استلام تحديث حالة من Reverb، يعيد المتصفح جلب بيانات الطلبات ويحدّث إظهار الزر.

زر «طلب الحساب» يعرض حاليًا رسالة تطلب إبلاغ الموظف؛ لا ينشئ فاتورة دفع أو يغلق الحساب من النظام.

## أوامر مفيدة

```bash
php artisan schedule:list
php artisan orders:mark-ready
php artisan reverb:start --host=0.0.0.0 --port=8081
php artisan test
php artisan optimize:clear
```
