export default {
  dir: 'ltr',
  nav: {
    dashboard: 'Panoramica', orders: 'Ordini', products: 'Prodotti',
    categories: 'Categorie', tables: 'Tavoli & QR', settings: 'Impostazioni', logout: 'Esci', departments: 'Reparti'
  },
  common: {
    save: 'Salva', saving: 'Salvataggio...', cancel: 'Annulla', delete: 'Elimina', add: 'Aggiungi',
    loading: 'Caricamento...', active: 'Attivo', inactive: 'Inattivo',
    activate: 'Attiva', deactivate: 'Disattiva', error: 'Si è verificato un errore.',
    all: 'Tutti', note: 'Nota', status: 'Stato', download: 'Scarica',
    name: 'Nome', image_url: 'URL immagine', sort_order: 'Ordine', price: 'Prezzo',
    description: 'Descrizione', category: 'Categoria',
    translations_label: 'Traduzioni', default_name: 'Nome predefinito', default_desc: 'Descrizione predefinita',
  },
  login: {
    title: 'Accedi', subtitle: 'Pannello di amministrazione QR Menu',
    email: 'E-mail', password: 'Password',
    submit: 'Accedi', submitting: 'Accesso...', error: 'E-mail o password non validi.',
    remember: 'Ricordami',
  },
  dashboard: {
    title: 'Panoramica', today_orders: 'Ordini di oggi', today_revenue: 'Ricavi di oggi',
    total_products: 'Prodotti totali', active_tables: 'Tavoli attivi',
    recent_orders: 'Ordini recenti', no_orders: 'Ancora nessun ordine.', view_all: 'Vedi tutti →',
  },
  products: {
    title: 'Prodotti', add: '+ Aggiungi prodotto',
    auto_translate: '🌐 Traduzione automatica', translating: 'Traduzione...',
    auto_translate_confirm: 'Tutti i prodotti verranno tradotti automaticamente dalla lingua principale in tutte le lingue attive. Le traduzioni esistenti verranno aggiornate. Continuare?',
    auto_translate_done: '{count} traduzioni aggiunte/aggiornate per {langs} lingue.',
    auto_translate_error: 'Traduzione automatica fallita.',
    edit_title: 'Modifica prodotto', add_title: 'Nuovo prodotto',
    name: 'Nome predefinito *', category: 'Categoria *',
    price: 'Prezzo (₺) *', description: 'Descrizione predefinita', image_url: 'URL immagine',
    translations_label: 'Traduzioni', confirm_delete: 'Sei sicuro di voler eliminare questo prodotto?',
    parent_label: 'Categoria Genitore', no_parent: 'Nessuna (categoria radice)', span_label: 'Larghezza Scheda', span_normal: 'Normale', span_wide: 'Larga', span_desc: 'La scheda larga occupa tutta la riga.', wide: 'Larga',
  },
  categories: {
    title: 'Categorie', add: '+ Aggiungi categoria',
    edit_title: 'Modifica categoria', add_title: 'Nuova categoria',
    name: 'Nome predefinito *', sort_order: 'Ordine', image_url: 'URL immagine',
    translations_label: 'Traduzioni', confirm_delete: 'Sei sicuro di voler eliminare questa categoria?',
    parent_label: 'Categoria Genitore', no_parent: 'Nessuna (categoria radice)', span_label: 'Larghezza Scheda', span_normal: 'Normale', span_wide: 'Larga', span_desc: 'La scheda larga occupa tutta la riga.', wide: 'Larga',
  },
  tables: {
    title: 'Tavoli & Codici QR', add: '+ Aggiungi tavolo', add_title: 'Nuovo tavolo',
    table_name: 'Nome tavolo', table_name_placeholder: 'Nome tavolo (es. Tavolo 1)',
    download: 'Scarica', active: 'Attivo', inactive: 'Inattivo',
    activate: 'Attiva', deactivate: 'Disattiva',
    confirm_delete: 'Sei sicuro di voler eliminare questo tavolo?',
  },
  orders: {
    title: 'Ordini', no_orders: 'Nessun ordine per questo filtro.', note_prefix: 'Nota: ',
    status: { all: 'Tutti', pending: 'In attesa', preparing: 'In preparazione', ready: 'Pronto', delivered: 'Consegnato', cancelled: 'Annullato' },
  },
  settings: {
    title: 'Impostazioni', general_title: 'Informazioni generali', restaurant_name: 'Nome del ristorante',
    slug_label: 'Slug (nome URL)', slug_prefix: 'qrmenu.com/m/', logo_url: 'URL logo', theme_color: 'Colore tema',
    lang_title: 'Gestione lingue', lang_desc: 'Seleziona le lingue attive e imposta la lingua principale per la traduzione automatica.',
    primary_badge: '⭐ Principale', set_primary: 'Imposta come principale',
    save_langs: 'Salva impostazioni lingua', saving_langs: 'Salvataggio...',
    nexopos_title: 'Integrazione NexoPOS', nexopos_desc: 'Sincronizza prodotti da NexoPOS (opzionale)',
    nexopos_url: 'URL NexoPOS', api_token: 'Token API',
    sync: '🔄 Sincronizza da NexoPOS', syncing: 'Sincronizzazione...',
    password_title: 'Cambia password', current_password: 'Password attuale', new_password: 'Nuova password',
    update_password: 'Aggiorna password', save: 'Salva impostazioni', saving: 'Salvataggio...',
    saved_langs: 'Impostazioni lingua salvate.', saved_settings: 'Impostazioni salvate.',
    saved_password: 'Password aggiornata.', error_langs: 'Si è verificato un errore.',
    error_settings: 'Si è verificato un errore.', error_password: 'Impossibile aggiornare la password.',
    sync_done: '{count} prodotti sincronizzati.', sync_error: 'Sincronizzazione fallita.',
    banner_image_label: 'URL Immagine Banner', instagram_url_label: 'URL Instagram',
    at_least_one_lang: 'Almeno una lingua deve essere attiva', features_title: 'Funzionalità', tables_enabled_label: 'Sistema tavoli', tables_enabled_desc: 'Attivo: ogni tavolo ha il suo QR.', orders_enabled_label: 'Sistema ordini', orders_enabled_desc: 'Disattivo: i clienti vedono solo il menu.',
  },
  departments: {
    title: 'Reparti', add: '+ Aggiungi', add_title: 'Nuovo reparto', edit_title: 'Modifica', name: 'Nome reparto', empty: 'Nessun reparto.', multiplier: 'Moltiplicatore', multiplier_label: 'Moltiplicatore prezzo', multiplier_desc: '1.00=normale, 1.20=+20%, 0.90=-10%', products_btn: 'Prodotti', products_title: 'Prodotti', base_price: 'Prezzo base', override_price_ph: 'Prezzo custom', hide_product: 'Nascondi', show_product: 'Mostra', confirm_delete: 'Eliminare questo reparto?',
  },
}




