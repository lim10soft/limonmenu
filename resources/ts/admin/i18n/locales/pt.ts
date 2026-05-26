export default {
  dir: 'ltr',
  nav: {
    dashboard: 'Visão Geral', orders: 'Pedidos', products: 'Produtos',
    categories: 'Categorias', tables: 'Mesas & QR', settings: 'Configurações', logout: 'Sair', departments: 'Departamentos'
  },
  common: {
    save: 'Salvar', saving: 'Salvando...', cancel: 'Cancelar', delete: 'Excluir', add: 'Adicionar',
    loading: 'Carregando...', active: 'Ativo', inactive: 'Inativo',
    activate: 'Ativar', deactivate: 'Desativar', error: 'Ocorreu um erro.',
    all: 'Todos', note: 'Nota', status: 'Status', download: 'Baixar',
    name: 'Nome', image_url: 'URL da Imagem', sort_order: 'Ordem', price: 'Preço',
    description: 'Descrição', category: 'Categoria',
    translations_label: 'Traduções', default_name: 'Nome Padrão', default_desc: 'Descrição Padrão',
  },
  login: {
    title: 'Entrar', subtitle: 'Painel de administração QR Menu',
    email: 'E-mail', password: 'Senha',
    submit: 'Entrar', submitting: 'Entrando...', error: 'E-mail ou senha inválidos.',
    remember: 'Lembrar-me',
  },
  dashboard: {
    title: 'Visão Geral', today_orders: 'Pedidos de Hoje', today_revenue: 'Receita de Hoje',
    total_products: 'Total de Produtos', active_tables: 'Mesas Ativas',
    recent_orders: 'Pedidos Recentes', no_orders: 'Ainda sem pedidos.', view_all: 'Ver todos →',
  },
  products: {
    title: 'Produtos', add: '+ Adicionar Produto',
    auto_translate: '🌐 Tradução Automática', translating: 'Traduzindo...',
    auto_translate_confirm: 'Todos os produtos serão traduzidos automaticamente do idioma principal para todos os idiomas ativos. As traduções existentes serão atualizadas. Continuar?',
    auto_translate_done: '{count} traduções adicionadas/atualizadas para {langs} idiomas.',
    auto_translate_error: 'A tradução automática falhou.',
    edit_title: 'Editar Produto', add_title: 'Novo Produto',
    name: 'Nome Padrão *', category: 'Categoria *',
    price: 'Preço (₺) *', description: 'Descrição Padrão', image_url: 'URL da Imagem',
    translations_label: 'Traduções', confirm_delete: 'Tem certeza que deseja excluir este produto?',
    parent_label: 'Categoria Pai', no_parent: 'Nenhuma (categoria raiz)', span_label: 'Largura do Card', span_normal: 'Normal', span_wide: 'Largo', span_desc: 'Card largo ocupa toda a linha.', wide: 'Largo',
  },
  categories: {
    title: 'Categorias', add: '+ Adicionar Categoria',
    edit_title: 'Editar Categoria', add_title: 'Nova Categoria',
    name: 'Nome Padrão *', sort_order: 'Ordem', image_url: 'URL da Imagem',
    translations_label: 'Traduções', confirm_delete: 'Tem certeza que deseja excluir esta categoria?',
    parent_label: 'Categoria Pai', no_parent: 'Nenhuma (categoria raiz)', span_label: 'Largura do Card', span_normal: 'Normal', span_wide: 'Largo', span_desc: 'Card largo ocupa toda a linha.', wide: 'Largo',
  },
  tables: {
    title: 'Mesas & Códigos QR', add: '+ Adicionar Mesa', add_title: 'Nova Mesa',
    table_name: 'Nome da mesa', table_name_placeholder: 'Nome da mesa (ex: Mesa 1)',
    download: 'Baixar', active: 'Ativa', inactive: 'Inativa',
    activate: 'Ativar', deactivate: 'Desativar',
    confirm_delete: 'Tem certeza que deseja excluir esta mesa?',
  },
  orders: {
    title: 'Pedidos', no_orders: 'Sem pedidos para este filtro.', note_prefix: 'Nota: ',
    status: { all: 'Todos', pending: 'Pendente', preparing: 'Preparando', ready: 'Pronto', delivered: 'Entregue', cancelled: 'Cancelado' },
  },
  settings: {
    title: 'Configurações', general_title: 'Informações Gerais', restaurant_name: 'Nome do Restaurante',
    slug_label: 'Slug (nome URL)', slug_prefix: 'qrmenu.com/m/', logo_url: 'URL do Logo', theme_color: 'Cor do Tema',
    lang_title: 'Gerenciamento de Idiomas', lang_desc: 'Selecione os idiomas ativos e defina o idioma principal para tradução automática.',
    primary_badge: '⭐ Principal', set_primary: 'Definir como Principal',
    save_langs: 'Salvar Configurações de Idioma', saving_langs: 'Salvando...',
    nexopos_title: 'Integração NexoPOS', nexopos_desc: 'Sincronizar produtos do NexoPOS (opcional)',
    nexopos_url: 'URL do NexoPOS', api_token: 'Token API',
    sync: '🔄 Sincronizar do NexoPOS', syncing: 'Sincronizando...',
    password_title: 'Alterar Senha', current_password: 'Senha Atual', new_password: 'Nova Senha',
    update_password: 'Atualizar Senha', save: 'Salvar Configurações', saving: 'Salvando...',
    saved_langs: 'Configurações de idioma salvas.', saved_settings: 'Configurações salvas.',
    saved_password: 'Senha atualizada.', error_langs: 'Ocorreu um erro.',
    error_settings: 'Ocorreu um erro.', error_password: 'Não foi possível atualizar a senha.',
    sync_done: '{count} produtos sincronizados.', sync_error: 'Falha na sincronização.',
    banner_image_label: 'URL da Imagem do Banner', instagram_url_label: 'URL do Instagram',
    at_least_one_lang: 'Pelo menos um idioma deve estar ativo', features_title: 'Configurações', tables_enabled_label: 'Sistema mesas', tables_enabled_desc: 'Ativado: cada mesa tem seu QR.', orders_enabled_label: 'Sistema pedidos', orders_enabled_desc: 'Desativado: clientes só visualizam o menu.',
  },
  departments: {
    title: 'Departamentos', add: '+ Adicionar', add_title: 'Novo', edit_title: 'Editar', name: 'Nome', empty: 'Sem departamentos.', multiplier: 'Multiplicador', multiplier_label: 'Multiplicador preço', multiplier_desc: '1.00=normal, 1.20=+20%, 0.90=-10%', products_btn: 'Produtos', products_title: 'Produtos', base_price: 'Preço base', override_price_ph: 'Preço especial', hide_product: 'Ocultar', show_product: 'Mostrar', confirm_delete: 'Excluir departamento?',
  },
}




