export default {
  dir: 'ltr',
  nav: {
    dashboard: 'Обзор', orders: 'Заказы', products: 'Продукты',
    categories: 'Категории', tables: 'Столики & QR', settings: 'Настройки', logout: 'Выйти', departments: 'Отделы'
  },
  common: {
    save: 'Сохранить', saving: 'Сохранение...', cancel: 'Отмена', delete: 'Удалить', add: 'Добавить',
    loading: 'Загрузка...', active: 'Активен', inactive: 'Неактивен',
    activate: 'Активировать', deactivate: 'Деактивировать', error: 'Произошла ошибка.',
    all: 'Все', note: 'Примечание', status: 'Статус', download: 'Скачать',
    name: 'Название', image_url: 'URL изображения', sort_order: 'Порядок', price: 'Цена',
    description: 'Описание', category: 'Категория',
    translations_label: 'Переводы', default_name: 'Название по умолчанию', default_desc: 'Описание по умолчанию',
  },
  login: {
    title: 'Войти', subtitle: 'Панель управления QR-меню',
    email: 'Эл. почта', password: 'Пароль',
    submit: 'Войти', submitting: 'Вход...', error: 'Неверный адрес эл. почты или пароль.',
    remember: 'Запомнить меня',
  },
  dashboard: {
    title: 'Обзор', today_orders: 'Заказы сегодня', today_revenue: 'Выручка сегодня',
    total_products: 'Всего продуктов', active_tables: 'Активных столиков',
    recent_orders: 'Последние заказы', no_orders: 'Заказов ещё нет.', view_all: 'Все →',
  },
  products: {
    title: 'Продукты', add: '+ Добавить продукт',
    auto_translate: '🌐 Авто-перевод', translating: 'Перевод...',
    auto_translate_confirm: 'Все продукты будут автоматически переведены с основного языка на все активные языки. Существующие переводы будут обновлены. Продолжить?',
    auto_translate_done: '{count} переводов добавлено/обновлено для {langs} языков.',
    auto_translate_error: 'Ошибка автоматического перевода.',
    total_count: 'товаров', no_results: 'Ничего не найдено.', empty: 'Товары пока не добавлены.',
    edit_title: 'Редактировать продукт', add_title: 'Новый продукт',
    name: 'Название по умолчанию *', category: 'Категория *',
    price: 'Цена (₺) *', description: 'Описание по умолчанию', image_url: 'URL изображения',
    translations_label: 'Переводы', confirm_delete: 'Вы уверены, что хотите удалить этот продукт?',
  },
  categories: {
    title: 'Категории', add: '+ Добавить категорию',
    edit_title: 'Редактировать категорию', add_title: 'Новая категория',
    name: 'Название по умолчанию *', sort_order: 'Порядок', image_url: 'URL изображения',
    translations_label: 'Переводы', confirm_delete: 'Вы уверены, что хотите удалить эту категорию?',
    parent_label: 'Родительская категория', no_parent: 'Нет (корневая)', span_label: 'Ширина карточки', span_normal: 'Обычная', span_wide: 'Широкая', span_desc: 'Широкая карточка занимает всю строку.', wide: 'Широкая',
  },
  tables: {
    title: 'Столики & QR-коды', add: '+ Добавить столик', add_title: 'Новый столик',
    table_name: 'Название столика', table_name_placeholder: 'Название (напр. Столик 1)',
    download: 'Скачать', active: 'Активен', inactive: 'Неактивен',
    activate: 'Активировать', deactivate: 'Деактивировать',
    confirm_delete: 'Вы уверены, что хотите удалить этот столик?',
  },
  orders: {
    title: 'Заказы', no_orders: 'Нет заказов для этого фильтра.', note_prefix: 'Примечание: ',
    status: { all: 'Все', pending: 'Ожидает', preparing: 'Готовится', ready: 'Готов', delivered: 'Доставлен', cancelled: 'Отменён' },
  },
  settings: {
    title: 'Настройки', general_title: 'Общая информация', restaurant_name: 'Название ресторана',
    slug_label: 'Слаг (URL-имя)', slug_prefix: 'qrmenu.com/m/', logo_url: 'URL логотипа', theme_color: 'Цвет темы',
    lang_title: 'Управление языками', lang_desc: 'Выберите активные языки и установите основной язык для авто-перевода.',
    primary_badge: '⭐ Основной', set_primary: 'Сделать основным',
    save_langs: 'Сохранить настройки языков', saving_langs: 'Сохранение...',
    nexopos_title: 'Интеграция NexoPOS', nexopos_desc: 'Синхронизация продуктов с NexoPOS (опционально)',
    nexopos_url: 'URL NexoPOS', api_token: 'API Token',
    sync: '🔄 Синхронизировать с NexoPOS', syncing: 'Синхронизация...',
    password_title: 'Изменить пароль', current_password: 'Текущий пароль', new_password: 'Новый пароль',
    update_password: 'Обновить пароль', save: 'Сохранить настройки', saving: 'Сохранение...',
    saved_langs: 'Настройки языков сохранены.', saved_settings: 'Настройки сохранены.',
    saved_password: 'Пароль обновлён.', error_langs: 'Произошла ошибка.',
    error_settings: 'Произошла ошибка.', error_password: 'Не удалось обновить пароль.',
    sync_done: '{count} продуктов синхронизировано.', sync_error: 'Ошибка синхронизации.',
    banner_image_label: 'URL баннера', instagram_url_label: 'Instagram URL',
    at_least_one_lang: 'Должен быть активен хотя бы один язык', features_title: 'Настройки', tables_enabled_label: 'Столики', tables_enabled_desc: 'Включено: у каждого стола QR-код.', orders_enabled_label: 'Заказы', orders_enabled_desc: 'Выключено: только просмотр меню.',
  },
  departments: {
    title: 'Отделы', add: '+ Добавить', add_title: 'Новый отдел', edit_title: 'Редактировать', name: 'Название', empty: 'Отделов нет.', multiplier: 'Множитель', multiplier_label: 'Множитель цены', multiplier_desc: '1.00=обычный, 1.20=+20%, 0.90=-10%', products_btn: 'Товары', products_title: 'Товары', base_price: 'Базовая цена', override_price_ph: 'Спец. цена', hide_product: 'Скрыть', show_product: 'Показать', confirm_delete: 'Удалить отдел?',
  },
}




