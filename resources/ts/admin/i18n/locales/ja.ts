export default {
  dir: 'ltr',
  nav: {
    dashboard: 'ダッシュボード', orders: '注文', products: '商品',
    categories: 'カテゴリ', tables: 'テーブル & QR', settings: '設定', logout: 'ログアウト', departments: '部門'
  },
  common: {
    save: '保存', saving: '保存中...', cancel: 'キャンセル', delete: '削除', add: '追加',
    loading: '読み込み中...', active: '有効', inactive: '無効',
    activate: '有効にする', deactivate: '無効にする', error: 'エラーが発生しました。',
    all: 'すべて', note: 'メモ', status: 'ステータス', download: 'ダウンロード',
    name: '名前', image_url: '画像URL', sort_order: '並び順', price: '価格',
    description: '説明', category: 'カテゴリ',
    translations_label: '翻訳', default_name: 'デフォルト名', default_desc: 'デフォルト説明',
  },
  login: {
    title: 'ログイン', subtitle: 'QRメニュー管理パネル',
    email: 'メールアドレス', password: 'パスワード',
    submit: 'ログイン', submitting: 'ログイン中...', error: 'メールアドレスまたはパスワードが違います。',
    remember: 'ログイン状態を保持する',
  },
  dashboard: {
    title: 'ダッシュボード', today_orders: '本日の注文', today_revenue: '本日の売上',
    total_products: '商品数合計', active_tables: 'アクティブテーブル',
    recent_orders: '最近の注文', no_orders: 'まだ注文がありません。', view_all: 'すべて表示 →',
  },
  products: {
    title: '商品', add: '+ 商品を追加',
    auto_translate: '🌐 自動翻訳', translating: '翻訳中...',
    auto_translate_confirm: 'すべての商品がメイン言語からすべてのアクティブ言語に自動翻訳されます。既存の翻訳は更新されます。続けますか？',
    auto_translate_done: '{langs}言語に{count}件の翻訳を追加/更新しました。',
    auto_translate_error: '自動翻訳に失敗しました。',
    edit_title: '商品を編集', add_title: '新しい商品',
    name: 'デフォルト名 *', category: 'カテゴリ *',
    price: '価格 (₺) *', description: 'デフォルト説明', image_url: '画像URL',
    translations_label: '翻訳', confirm_delete: 'この商品を削除してもよろしいですか？',
    parent_label: '親カテゴリ', no_parent: 'なし（ルートカテゴリ）', span_label: 'カード幅', span_normal: '標準', span_wide: 'ワイド', span_desc: 'ワイドカードは行全体を占めます。', wide: 'ワイド',
  },
  categories: {
    title: 'カテゴリ', add: '+ カテゴリを追加',
    edit_title: 'カテゴリを編集', add_title: '新しいカテゴリ',
    name: 'デフォルト名 *', sort_order: '並び順', image_url: '画像URL',
    translations_label: '翻訳', confirm_delete: 'このカテゴリを削除してもよろしいですか？',
    parent_label: '親カテゴリ', no_parent: 'なし（ルートカテゴリ）', span_label: 'カード幅', span_normal: '標準', span_wide: 'ワイド', span_desc: 'ワイドカードは行全体を占めます。', wide: 'ワイド',
  },
  tables: {
    title: 'テーブル & QRコード', add: '+ テーブルを追加', add_title: '新しいテーブル',
    table_name: 'テーブル名', table_name_placeholder: 'テーブル名（例：テーブル1）',
    download: 'ダウンロード', active: '有効', inactive: '無効',
    activate: '有効にする', deactivate: '無効にする',
    confirm_delete: 'このテーブルを削除してもよろしいですか？',
  },
  orders: {
    title: '注文', no_orders: 'このフィルターには注文がありません。', note_prefix: 'メモ：',
    status: { all: 'すべて', pending: '保留中', preparing: '準備中', ready: '準備完了', delivered: '配達済み', cancelled: 'キャンセル' },
  },
  settings: {
    title: '設定', general_title: '基本情報', restaurant_name: 'レストラン名',
    slug_label: 'スラッグ（URL名）', slug_prefix: 'qrmenu.com/m/', logo_url: 'ロゴURL', theme_color: 'テーマカラー',
    lang_title: '言語管理', lang_desc: 'アクティブな言語を選択し、自動翻訳のメイン言語を設定してください。',
    primary_badge: '⭐ メイン', set_primary: 'メインに設定',
    save_langs: '言語設定を保存', saving_langs: '保存中...',
    nexopos_title: 'NexoPOS連携', nexopos_desc: 'NexoPOSから商品を同期（任意）',
    nexopos_url: 'NexoPOS URL', api_token: 'APIトークン',
    sync: '🔄 NexoPOSから同期', syncing: '同期中...',
    password_title: 'パスワード変更', current_password: '現在のパスワード', new_password: '新しいパスワード',
    update_password: 'パスワードを更新', save: '設定を保存', saving: '保存中...',
    saved_langs: '言語設定が保存されました。', saved_settings: '設定が保存されました。',
    saved_password: 'パスワードが更新されました。', error_langs: 'エラーが発生しました。',
    error_settings: 'エラーが発生しました。', error_password: 'パスワードを更新できませんでした。',
    sync_done: '{count}件の商品を同期しました。', sync_error: '同期に失敗しました。',
    banner_image_label: 'バナー画像URL', instagram_url_label: 'Instagram URL',
    at_least_one_lang: '少なくとも1つの言語を有効にする必要があります', features_title: '機能設定', tables_enabled_label: 'テーブル', tables_enabled_desc: 'オン: 各テーブルにQRコード。', orders_enabled_label: '注文', orders_enabled_desc: 'オフ: メニュー閲覧のみ。',
  },
  departments: {
    title: '部門', add: '+ 追加', add_title: '新しい部門', edit_title: '部門を編集', name: '部門名', empty: '部門がありません。', multiplier: '価格倍率', multiplier_label: '価格倍率', multiplier_desc: '1.00=通常, 1.20=+20%, 0.90=-10%', products_btn: '商品', products_title: '商品設定', base_price: '基本価格', override_price_ph: 'カスタム価格', hide_product: '非表示', show_product: '表示', confirm_delete: 'この部門を削除しますか？',
  },
}




