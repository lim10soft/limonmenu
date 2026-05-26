export default {
  dir: 'ltr',
  nav: {
    dashboard: '대시보드', orders: '주문', products: '상품',
    categories: '카테고리', tables: '테이블 & QR', settings: '설정', logout: '로그아웃', departments: '부서'
  },
  common: {
    save: '저장', saving: '저장 중...', cancel: '취소', delete: '삭제', add: '추가',
    loading: '로딩 중...', active: '활성', inactive: '비활성',
    activate: '활성화', deactivate: '비활성화', error: '오류가 발생했습니다.',
    all: '전체', note: '메모', status: '상태', download: '다운로드',
    name: '이름', image_url: '이미지 URL', sort_order: '정렬', price: '가격',
    description: '설명', category: '카테고리',
    translations_label: '번역', default_name: '기본 이름', default_desc: '기본 설명',
  },
  login: {
    title: '로그인', subtitle: 'QR 메뉴 관리 패널',
    email: '이메일', password: '비밀번호',
    submit: '로그인', submitting: '로그인 중...', error: '이메일 또는 비밀번호가 올바르지 않습니다.',
    remember: '로그인 상태 유지',
  },
  dashboard: {
    title: '대시보드', today_orders: '오늘의 주문', today_revenue: '오늘의 매출',
    total_products: '전체 상품', active_tables: '활성 테이블',
    recent_orders: '최근 주문', no_orders: '아직 주문이 없습니다.', view_all: '전체 보기 →',
  },
  products: {
    title: '상품', add: '+ 상품 추가',
    auto_translate: '🌐 자동 번역', translating: '번역 중...',
    auto_translate_confirm: '모든 상품이 기본 언어에서 활성 언어로 자동 번역됩니다. 기존 번역이 업데이트됩니다. 계속하시겠습니까?',
    auto_translate_done: '{langs}개 언어에 {count}개 번역이 추가/업데이트되었습니다.',
    auto_translate_error: '자동 번역에 실패했습니다.',
    edit_title: '상품 편집', add_title: '새 상품',
    name: '기본 이름 *', category: '카테고리 *',
    price: '가격 (₺) *', description: '기본 설명', image_url: '이미지 URL',
    translations_label: '번역', confirm_delete: '이 상품을 삭제하시겠습니까?',
    parent_label: '상위 카테고리', no_parent: '없음 (루트 카테고리)', span_label: '카드 너비', span_normal: '보통', span_wide: '넓게', span_desc: '넓은 카드는 전체 행을 차지합니다.', wide: '넓게',
  },
  categories: {
    title: '카테고리', add: '+ 카테고리 추가',
    edit_title: '카테고리 편집', add_title: '새 카테고리',
    name: '기본 이름 *', sort_order: '정렬', image_url: '이미지 URL',
    translations_label: '번역', confirm_delete: '이 카테고리를 삭제하시겠습니까?',
    parent_label: '상위 카테고리', no_parent: '없음 (루트 카테고리)', span_label: '카드 너비', span_normal: '보통', span_wide: '넓게', span_desc: '넓은 카드는 전체 행을 차지합니다.', wide: '넓게',
  },
  tables: {
    title: '테이블 & QR 코드', add: '+ 테이블 추가', add_title: '새 테이블',
    table_name: '테이블 이름', table_name_placeholder: '테이블 이름 (예: 테이블 1)',
    download: '다운로드', active: '활성', inactive: '비활성',
    activate: '활성화', deactivate: '비활성화',
    confirm_delete: '이 테이블을 삭제하시겠습니까?',
  },
  orders: {
    title: '주문', no_orders: '이 필터에 해당하는 주문이 없습니다.', note_prefix: '메모: ',
    status: { all: '전체', pending: '대기 중', preparing: '준비 중', ready: '준비 완료', delivered: '배달 완료', cancelled: '취소됨' },
  },
  settings: {
    title: '설정', general_title: '기본 정보', restaurant_name: '레스토랑 이름',
    slug_label: '슬러그 (URL 이름)', slug_prefix: 'qrmenu.com/m/', logo_url: '로고 URL', theme_color: '테마 색상',
    lang_title: '언어 관리', lang_desc: '활성 언어를 선택하고 자동 번역에 사용할 기본 언어를 설정하세요.',
    primary_badge: '⭐ 기본', set_primary: '기본 언어로 설정',
    save_langs: '언어 설정 저장', saving_langs: '저장 중...',
    nexopos_title: 'NexoPOS 연동', nexopos_desc: 'NexoPOS에서 상품 동기화 (선택사항)',
    nexopos_url: 'NexoPOS URL', api_token: 'API 토큰',
    sync: '🔄 NexoPOS에서 동기화', syncing: '동기화 중...',
    password_title: '비밀번호 변경', current_password: '현재 비밀번호', new_password: '새 비밀번호',
    update_password: '비밀번호 업데이트', save: '설정 저장', saving: '저장 중...',
    saved_langs: '언어 설정이 저장되었습니다.', saved_settings: '설정이 저장되었습니다.',
    saved_password: '비밀번호가 업데이트되었습니다.', error_langs: '오류가 발생했습니다.',
    error_settings: '오류가 발생했습니다.', error_password: '비밀번호를 업데이트할 수 없습니다.',
    sync_done: '{count}개 상품이 동기화되었습니다.', sync_error: '동기화 실패.',
    banner_image_label: '배너 이미지 URL', instagram_url_label: 'Instagram URL',
    at_least_one_lang: '최소 하나의 언어가 활성화되어야 합니다', features_title: '기능 설정', tables_enabled_label: '테이블 시스템', tables_enabled_desc: '활성화: 각 테이블에 QR 코드.', orders_enabled_label: '주문 시스템', orders_enabled_desc: '비활성화: 메뉴만 볼 수 있습니다.',
  },
  departments: {
    title: '부서', add: '+ 추가', add_title: '새 부서', edit_title: '편집', name: '부서 이름', empty: '부서 없음.', multiplier: '가격 배수', multiplier_label: '가격 배수', multiplier_desc: '1.00=정상, 1.20=+20%, 0.90=-10%', products_btn: '제품', products_title: '제품 설정', base_price: '기본 가격', override_price_ph: '맞춤 가격', hide_product: '숨기기', show_product: '표시', confirm_delete: '이 부서를 삭제할까요?',
  },
}




