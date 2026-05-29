export default {
  dir: 'ltr',
  nav: {
    dashboard: '概览', orders: '订单', products: '产品',
    categories: '分类', tables: '餐桌 & QR', settings: '设置', logout: '退出登录', departments: '部门'
  },
  common: {
    save: '保存', saving: '保存中...', cancel: '取消', delete: '删除', add: '添加',
    loading: '加载中...', active: '启用', inactive: '禁用',
    activate: '启用', deactivate: '禁用', error: '发生错误。',
    all: '全部', note: '备注', status: '状态', download: '下载',
    name: '名称', image_url: '图片链接', sort_order: '排序', price: '价格',
    description: '描述', category: '分类',
    translations_label: '翻译', default_name: '默认名称', default_desc: '默认描述',
  },
  login: {
    title: '登录', subtitle: 'QR菜单管理面板',
    email: '电子邮件', password: '密码',
    submit: '登录', submitting: '登录中...', error: '邮箱或密码不正确。',
    remember: '记住我',
  },
  dashboard: {
    title: '概览', today_orders: '今日订单', today_revenue: '今日营业额',
    total_products: '产品总数', active_tables: '活跃餐桌',
    recent_orders: '最近订单', no_orders: '暂无订单。', view_all: '查看全部 →',
  },
  products: {
    title: '产品', add: '+ 添加产品',
    auto_translate: '🌐 自动翻译', translating: '翻译中...',
    auto_translate_confirm: '所有产品将从主要语言自动翻译为所有活跃语言，现有翻译将被更新。是否继续？',
    auto_translate_done: '已为 {langs} 种语言添加/更新 {count} 条翻译。',
    auto_translate_error: '自动翻译失败。',
    total_count: '个产品', no_results: '未找到结果。', empty: '暂无产品。',
    edit_title: '编辑产品', add_title: '新产品',
    name: '默认名称 *', category: '分类 *',
    price: '价格 (₺) *', description: '默认描述', image_url: '图片链接',
    translations_label: '翻译', confirm_delete: '确定要删除此产品吗？',
    parent_label: '父分类', no_parent: '无（根分类）', span_label: '卡片宽度', span_normal: '普通', span_wide: '宽', span_desc: '宽卡片占满整行。', wide: '宽',
  },
  categories: {
    title: '分类', add: '+ 添加分类',
    edit_title: '编辑分类', add_title: '新分类',
    name: '默认名称 *', sort_order: '排序', image_url: '图片链接',
    translations_label: '翻译', confirm_delete: '确定要删除此分类吗？',
    parent_label: '父分类', no_parent: '无（根分类）', span_label: '卡片宽度', span_normal: '普通', span_wide: '宽', span_desc: '宽卡片占满整行。', wide: '宽',
  },
  tables: {
    title: '餐桌 & QR码', add: '+ 添加餐桌', add_title: '新餐桌',
    table_name: '餐桌名称', table_name_placeholder: '餐桌名称（例如：桌1）',
    download: '下载', active: '启用', inactive: '禁用',
    activate: '启用', deactivate: '禁用',
    confirm_delete: '确定要删除此餐桌吗？',
  },
  orders: {
    title: '订单', no_orders: '此筛选条件下无订单。', note_prefix: '备注：',
    status: { all: '全部', pending: '待处理', preparing: '准备中', ready: '已准备好', delivered: '已送达', cancelled: '已取消' },
  },
  settings: {
    title: '设置', general_title: '基本信息', restaurant_name: '餐厅名称',
    slug_label: '链接标识', slug_prefix: 'qrmenu.com/m/', logo_url: 'Logo链接', theme_color: '主题颜色',
    lang_title: '语言管理', lang_desc: '选择活跃语言并设置自动翻译的主要语言。',
    primary_badge: '⭐ 主语言', set_primary: '设为主语言',
    save_langs: '保存语言设置', saving_langs: '保存中...',
    nexopos_title: 'NexoPOS集成', nexopos_desc: '从NexoPOS同步产品（可选）',
    nexopos_url: 'NexoPOS链接', api_token: 'API令牌',
    sync: '🔄 从NexoPOS同步', syncing: '同步中...',
    password_title: '修改密码', current_password: '当前密码', new_password: '新密码',
    update_password: '更新密码', save: '保存设置', saving: '保存中...',
    saved_langs: '语言设置已保存。', saved_settings: '设置已保存。',
    saved_password: '密码已更新。', error_langs: '发生错误。',
    error_settings: '发生错误。', error_password: '密码更新失败。',
    sync_done: '已同步 {count} 个产品。', sync_error: '同步失败。',
    banner_image_label: '横幅图片URL', instagram_url_label: 'Instagram URL',
    at_least_one_lang: '至少需要一种语言处于活跃状态', features_title: '功能设置', tables_enabled_label: '桌位系统', tables_enabled_desc: '开启后每张桌子有独立二维码。', orders_enabled_label: '点单系统', orders_enabled_desc: '关闭后顾客只能浏览菜单。',
  },
  departments: {
    title: '部门', add: '+ 添加部门', add_title: '新部门', edit_title: '编辑部门', name: '部门名称', empty: '暂无部门。', multiplier: '价格倍数', multiplier_label: '价格倍数', multiplier_desc: '1.00=正常, 1.20=+20%, 0.90=-10%', products_btn: '产品', products_title: '产品设置', base_price: '基础价格', override_price_ph: '自定义价格', hide_product: '隐藏', show_product: '显示', confirm_delete: '确定删除此部门？',
  },
}




