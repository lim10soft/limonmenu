export default {
  dir: 'rtl',
  nav: {
    dashboard: 'لوحة التحكم', orders: 'الطلبات', products: 'المنتجات',
    categories: 'الفئات', tables: 'الطاولات & QR', settings: 'الإعدادات', logout: 'تسجيل الخروج', departments: 'الأقسام'
  },
  common: {
    save: 'حفظ', saving: 'جارٍ الحفظ...', cancel: 'إلغاء', delete: 'حذف', add: 'إضافة',
    loading: 'جارٍ التحميل...', active: 'نشط', inactive: 'غير نشط',
    activate: 'تفعيل', deactivate: 'إلغاء التفعيل', error: 'حدث خطأ.',
    all: 'الكل', note: 'ملاحظة', status: 'الحالة', download: 'تنزيل',
    name: 'الاسم', image_url: 'رابط الصورة', sort_order: 'الترتيب', price: 'السعر',
    description: 'الوصف', category: 'الفئة',
    translations_label: 'الترجمات', default_name: 'الاسم الافتراضي', default_desc: 'الوصف الافتراضي',
  },
  login: {
    title: 'تسجيل الدخول', subtitle: 'لوحة إدارة قائمة QR',
    email: 'البريد الإلكتروني', password: 'كلمة المرور',
    submit: 'دخول', submitting: 'جارٍ الدخول...', error: 'بريد إلكتروني أو كلمة مرور غير صحيحة.',
    remember: 'تذكرني',
  },
  dashboard: {
    title: 'لوحة التحكم', today_orders: 'طلبات اليوم', today_revenue: 'إيرادات اليوم',
    total_products: 'إجمالي المنتجات', active_tables: 'الطاولات النشطة',
    recent_orders: 'آخر الطلبات', no_orders: 'لا توجد طلبات بعد.', view_all: 'عرض الكل →',
  },
  products: {
    title: 'المنتجات', add: '+ إضافة منتج',
    auto_translate: '🌐 ترجمة تلقائية', translating: 'جارٍ الترجمة...',
    auto_translate_confirm: 'سيتم ترجمة جميع المنتجات تلقائياً من اللغة الأساسية إلى جميع اللغات النشطة. سيتم تحديث الترجمات الموجودة. هل تريد المتابعة؟',
    auto_translate_done: 'تمت إضافة/تحديث {count} ترجمة لـ {langs} لغات.',
    auto_translate_error: 'فشلت الترجمة التلقائية.',
    total_count: 'منتج', no_results: 'لا توجد نتائج.', empty: 'لم تتم إضافة منتجات بعد.',
    edit_title: 'تعديل المنتج', add_title: 'منتج جديد',
    name: 'الاسم الافتراضي *', category: 'الفئة *',
    price: 'السعر (₺) *', description: 'الوصف الافتراضي', image_url: 'رابط الصورة',
    translations_label: 'الترجمات', confirm_delete: 'هل أنت متأكد من حذف هذا المنتج؟',
    parent_label: 'الفئة الأم', no_parent: 'لا يوجد (فئة جذر)', span_label: 'عرض البطاقة', span_normal: 'عادي', span_wide: 'عريض', span_desc: 'تمتد البطاقة العريضة على كامل العرض.', wide: 'عريض',
  },
  categories: {
    title: 'الفئات', add: '+ إضافة فئة',
    edit_title: 'تعديل الفئة', add_title: 'فئة جديدة',
    name: 'الاسم الافتراضي *', sort_order: 'الترتيب', image_url: 'رابط الصورة',
    translations_label: 'الترجمات', confirm_delete: 'هل أنت متأكد من حذف هذه الفئة؟',
    parent_label: 'الفئة الأم', no_parent: 'لا يوجد (فئة جذر)', span_label: 'عرض البطاقة', span_normal: 'عادي', span_wide: 'عريض', span_desc: 'تمتد البطاقة العريضة على كامل العرض.', wide: 'عريض',
  },
  tables: {
    title: 'الطاولات & رموز QR', add: '+ إضافة طاولة', add_title: 'طاولة جديدة',
    table_name: 'اسم الطاولة', table_name_placeholder: 'اسم الطاولة (مثال: طاولة 1)',
    download: 'تنزيل', active: 'نشطة', inactive: 'غير نشطة',
    activate: 'تفعيل', deactivate: 'إلغاء التفعيل',
    confirm_delete: 'هل أنت متأكد من حذف هذه الطاولة؟',
  },
  orders: {
    title: 'الطلبات', no_orders: 'لا توجد طلبات لهذا الفلتر.', note_prefix: 'ملاحظة: ',
    status: { all: 'الكل', pending: 'قيد الانتظار', preparing: 'قيد التحضير', ready: 'جاهز', delivered: 'تم التسليم', cancelled: 'ملغي' },
  },
  settings: {
    title: 'الإعدادات', general_title: 'المعلومات العامة', restaurant_name: 'اسم المطعم',
    slug_label: 'الرابط المختصر', slug_prefix: 'qrmenu.com/m/', logo_url: 'رابط الشعار', theme_color: 'لون السمة',
    lang_title: 'إدارة اللغات', lang_desc: 'اختر اللغات النشطة وحدد اللغة الأساسية للترجمة التلقائية.',
    primary_badge: '⭐ أساسية', set_primary: 'جعلها أساسية',
    save_langs: 'حفظ إعدادات اللغة', saving_langs: 'جارٍ الحفظ...',
    nexopos_title: 'تكامل NexoPOS', nexopos_desc: 'مزامنة المنتجات من NexoPOS (اختياري)',
    nexopos_url: 'رابط NexoPOS', api_token: 'رمز API',
    sync: '🔄 مزامنة من NexoPOS', syncing: 'جارٍ المزامنة...',
    password_title: 'تغيير كلمة المرور', current_password: 'كلمة المرور الحالية', new_password: 'كلمة المرور الجديدة',
    update_password: 'تحديث كلمة المرور', save: 'حفظ الإعدادات', saving: 'جارٍ الحفظ...',
    saved_langs: 'تم حفظ إعدادات اللغة.', saved_settings: 'تم حفظ الإعدادات.',
    saved_password: 'تم تحديث كلمة المرور.', error_langs: 'حدث خطأ.',
    error_settings: 'حدث خطأ.', error_password: 'تعذّر تحديث كلمة المرور.',
    sync_done: 'تمت مزامنة {count} منتج.', sync_error: 'فشلت المزامنة.',
    banner_image_label: 'رابط صورة البانر', instagram_url_label: 'رابط إنستغرام',
    at_least_one_lang: 'يجب أن تكون لغة واحدة على الأقل نشطة', features_title: 'الميزات', tables_enabled_label: 'نظام الطاولات', tables_enabled_desc: 'مفعل: لكل طاولة رمز QR.', orders_enabled_label: 'نظام الطلبات', orders_enabled_desc: 'معطل: العملاء يرون القائمة فقط.',
  },
  departments: {
    title: 'الأقسام', add: '+ إضافة', add_title: 'قسم جديد', edit_title: 'تعديل', name: 'اسم القسم', empty: 'لا توجد أقسام.', multiplier: 'مضاعف', multiplier_label: 'مضاعف السعر', multiplier_desc: '1.00=عادي, 1.20=+20%, 0.90=-10%', products_btn: 'المنتجات', products_title: 'المنتجات', base_price: 'السعر الأساسي', override_price_ph: 'سعر مخصص', hide_product: 'إخفاء', show_product: 'إظهار', confirm_delete: 'حذف هذا القسم?',
  },
}




