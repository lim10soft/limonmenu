export default {
  dir: 'ltr',
  nav: {
    dashboard: 'Panel', orders: 'Pedidos', products: 'Productos',
    categories: 'Categorías', tables: 'Mesas & QR', settings: 'Ajustes', logout: 'Cerrar sesión', departments: 'Departamentos'
  },
  common: {
    save: 'Guardar', saving: 'Guardando...', cancel: 'Cancelar', delete: 'Eliminar', add: 'Añadir',
    loading: 'Cargando...', active: 'Activo', inactive: 'Inactivo',
    activate: 'Activar', deactivate: 'Desactivar', error: 'Ha ocurrido un error.',
    all: 'Todos', note: 'Nota', status: 'Estado', download: 'Descargar',
    name: 'Nombre', image_url: 'URL de imagen', sort_order: 'Orden', price: 'Precio',
    description: 'Descripción', category: 'Categoría',
    translations_label: 'Traducciones', default_name: 'Nombre predeterminado', default_desc: 'Descripción predeterminada',
  },
  login: {
    title: 'Iniciar sesión', subtitle: 'Panel de administración QR Menú',
    email: 'Correo electrónico', password: 'Contraseña',
    submit: 'Iniciar sesión', submitting: 'Iniciando sesión...', error: 'Correo o contraseña incorrectos.',
    remember: 'Recordarme',
  },
  dashboard: {
    title: 'Panel', today_orders: 'Pedidos de hoy', today_revenue: 'Ingresos de hoy',
    total_products: 'Total de productos', active_tables: 'Mesas activas',
    recent_orders: 'Pedidos recientes', no_orders: 'Aún no hay pedidos.', view_all: 'Ver todos →',
  },
  products: {
    title: 'Productos', add: '+ Añadir Producto',
    auto_translate: '🌐 Traducción automática', translating: 'Traduciendo...',
    auto_translate_confirm: 'Todos los productos se traducirán automáticamente del idioma principal a todos los idiomas activos. Las traducciones existentes se actualizarán. ¿Continuar?',
    auto_translate_done: '{count} traducciones añadidas/actualizadas para {langs} idiomas.',
    auto_translate_error: 'La traducción automática falló.',
    edit_title: 'Editar Producto', add_title: 'Nuevo Producto',
    name: 'Nombre predeterminado *', category: 'Categoría *',
    price: 'Precio (₺) *', description: 'Descripción predeterminada', image_url: 'URL de imagen',
    translations_label: 'Traducciones', confirm_delete: '¿Está seguro de que desea eliminar este producto?',
    parent_label: 'Categoría Padre', no_parent: 'Ninguna (categoría raíz)', span_label: 'Ancho del Card', span_normal: 'Normal', span_wide: 'Ancho', span_desc: 'El card ancho ocupa toda la fila.', wide: 'Ancho',
  },
  categories: {
    title: 'Categorías', add: '+ Añadir Categoría',
    edit_title: 'Editar Categoría', add_title: 'Nueva Categoría',
    name: 'Nombre predeterminado *', sort_order: 'Orden', image_url: 'URL de imagen',
    translations_label: 'Traducciones', confirm_delete: '¿Está seguro de que desea eliminar esta categoría?',
    parent_label: 'Categoría Padre', no_parent: 'Ninguna (categoría raíz)', span_label: 'Ancho del Card', span_normal: 'Normal', span_wide: 'Ancho', span_desc: 'El card ancho ocupa toda la fila.', wide: 'Ancho',
  },
  tables: {
    title: 'Mesas & Códigos QR', add: '+ Añadir Mesa', add_title: 'Nueva Mesa',
    table_name: 'Nombre de la mesa', table_name_placeholder: 'Nombre de mesa (ej: Mesa 1)',
    download: 'Descargar', active: 'Activa', inactive: 'Inactiva',
    activate: 'Activar', deactivate: 'Desactivar',
    confirm_delete: '¿Está seguro de que desea eliminar esta mesa?',
  },
  orders: {
    title: 'Pedidos', no_orders: 'No hay pedidos para este filtro.', note_prefix: 'Nota: ',
    status: { all: 'Todos', pending: 'Pendiente', preparing: 'Preparando', ready: 'Listo', delivered: 'Entregado', cancelled: 'Cancelado' },
  },
  settings: {
    title: 'Ajustes', general_title: 'Información general', restaurant_name: 'Nombre del restaurante',
    slug_label: 'Slug (nombre URL)', slug_prefix: 'qrmenu.com/m/', logo_url: 'URL del logo', theme_color: 'Color del tema',
    lang_title: 'Gestión de idiomas', lang_desc: 'Seleccione los idiomas activos y establezca el idioma principal para la traducción automática.',
    primary_badge: '⭐ Principal', set_primary: 'Establecer como principal',
    save_langs: 'Guardar configuración de idiomas', saving_langs: 'Guardando...',
    nexopos_title: 'Integración NexoPOS', nexopos_desc: 'Sincronizar productos desde NexoPOS (opcional)',
    nexopos_url: 'URL de NexoPOS', api_token: 'Token API',
    sync: '🔄 Sincronizar desde NexoPOS', syncing: 'Sincronizando...',
    password_title: 'Cambiar contraseña', current_password: 'Contraseña actual', new_password: 'Nueva contraseña',
    update_password: 'Actualizar contraseña', save: 'Guardar ajustes', saving: 'Guardando...',
    saved_langs: 'Configuración de idiomas guardada.', saved_settings: 'Ajustes guardados.',
    saved_password: 'Contraseña actualizada.', error_langs: 'Ha ocurrido un error.',
    error_settings: 'Ha ocurrido un error.', error_password: 'No se pudo actualizar la contraseña.',
    sync_done: '{count} productos sincronizados.', sync_error: 'Error de sincronización.',
    banner_image_label: 'URL de Imagen del Banner', instagram_url_label: 'URL de Instagram',
    at_least_one_lang: 'Al menos un idioma debe estar activo', features_title: 'Configuración', tables_enabled_label: 'Sistema mesas', tables_enabled_desc: 'Activado: cada mesa tiene su QR.', orders_enabled_label: 'Sistema pedidos', orders_enabled_desc: 'Desactivado: clientes solo ven el menú.',
  },
  departments: {
    title: 'Departamentos', add: '+ Agregar', add_title: 'Nuevo', edit_title: 'Editar', name: 'Nombre', empty: 'Sin departamentos.', multiplier: 'Multiplicador', multiplier_label: 'Multiplicador precio', multiplier_desc: '1.00=normal, 1.20=+20%, 0.90=-10%', products_btn: 'Productos', products_title: 'Productos', base_price: 'Precio base', override_price_ph: 'Precio especial', hide_product: 'Ocultar', show_product: 'Mostrar', confirm_delete: '¿Eliminar departamento?',
  },
}




