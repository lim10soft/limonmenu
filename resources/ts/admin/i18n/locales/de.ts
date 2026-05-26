export default {
  dir: 'ltr',
  nav: {
    dashboard: 'Übersicht', orders: 'Bestellungen', products: 'Produkte',
    categories: 'Kategorien', tables: 'Tische & QR', settings: 'Einstellungen', logout: 'Abmelden', departments: 'Abteilungen'
  },
  common: {
    save: 'Speichern', saving: 'Speichern...', cancel: 'Abbrechen', delete: 'Löschen', add: 'Hinzufügen',
    loading: 'Laden...', active: 'Aktiv', inactive: 'Inaktiv',
    activate: 'Aktivieren', deactivate: 'Deaktivieren', error: 'Ein Fehler ist aufgetreten.',
    all: 'Alle', note: 'Notiz', status: 'Status', download: 'Herunterladen',
    name: 'Name', image_url: 'Bild-URL', sort_order: 'Reihenfolge', price: 'Preis',
    description: 'Beschreibung', category: 'Kategorie',
    translations_label: 'Übersetzungen', default_name: 'Standardname', default_desc: 'Standardbeschreibung',
  },
  login: {
    title: 'Anmelden', subtitle: 'QR-Menü Verwaltungspanel',
    email: 'E-Mail', password: 'Passwort',
    submit: 'Anmelden', submitting: 'Anmeldung...', error: 'Ungültige E-Mail oder Passwort.',
    remember: 'Angemeldet bleiben',
  },
  dashboard: {
    title: 'Übersicht', today_orders: 'Heutige Bestellungen', today_revenue: 'Heutiger Umsatz',
    total_products: 'Produkte gesamt', active_tables: 'Aktive Tische',
    recent_orders: 'Letzte Bestellungen', no_orders: 'Noch keine Bestellungen.', view_all: 'Alle anzeigen →',
  },
  products: {
    title: 'Produkte', add: '+ Produkt hinzufügen',
    auto_translate: '🌐 Auto-Übersetzen', translating: 'Übersetzen...',
    auto_translate_confirm: 'Alle Produkte werden automatisch von der Hauptsprache in alle aktiven Sprachen übersetzt. Bestehende Übersetzungen werden aktualisiert. Fortfahren?',
    auto_translate_done: '{count} Übersetzungen für {langs} Sprachen hinzugefügt/aktualisiert.',
    auto_translate_error: 'Automatische Übersetzung fehlgeschlagen.',
    edit_title: 'Produkt bearbeiten', add_title: 'Neues Produkt',
    name: 'Standardname *', category: 'Kategorie *',
    price: 'Preis (₺) *', description: 'Standardbeschreibung', image_url: 'Bild-URL',
    translations_label: 'Übersetzungen', confirm_delete: 'Möchten Sie dieses Produkt wirklich löschen?',
    parent_label: 'Übergeordnete Kategorie', no_parent: 'Keine (Stammkategorie)', span_label: 'Kartenbreite', span_normal: 'Normal', span_wide: 'Breit', span_desc: 'Breite Karte nimmt die gesamte Zeile ein.', wide: 'Breit',
  },
  categories: {
    title: 'Kategorien', add: '+ Kategorie hinzufügen',
    edit_title: 'Kategorie bearbeiten', add_title: 'Neue Kategorie',
    name: 'Standardname *', sort_order: 'Reihenfolge', image_url: 'Bild-URL',
    translations_label: 'Übersetzungen', confirm_delete: 'Möchten Sie diese Kategorie wirklich löschen?',
    parent_label: 'Übergeordnete Kategorie', no_parent: 'Keine (Stammkategorie)', span_label: 'Kartenbreite', span_normal: 'Normal', span_wide: 'Breit', span_desc: 'Breite Karte nimmt die gesamte Zeile ein.', wide: 'Breit',
  },
  tables: {
    title: 'Tische & QR-Codes', add: '+ Tisch hinzufügen', add_title: 'Neuer Tisch',
    table_name: 'Tischname', table_name_placeholder: 'Tischname (z.B. Tisch 1)',
    download: 'Herunterladen', active: 'Aktiv', inactive: 'Inaktiv',
    activate: 'Aktivieren', deactivate: 'Deaktivieren',
    confirm_delete: 'Möchten Sie diesen Tisch wirklich löschen?',
  },
  orders: {
    title: 'Bestellungen', no_orders: 'Keine Bestellungen für diesen Filter.', note_prefix: 'Notiz: ',
    status: { all: 'Alle', pending: 'Ausstehend', preparing: 'In Vorbereitung', ready: 'Fertig', delivered: 'Geliefert', cancelled: 'Storniert' },
  },
  settings: {
    title: 'Einstellungen', general_title: 'Allgemeine Informationen', restaurant_name: 'Restaurantname',
    slug_label: 'Slug (URL-Name)', slug_prefix: 'qrmenu.com/m/', logo_url: 'Logo-URL', theme_color: 'Themenfarbe',
    lang_title: 'Sprachverwaltung', lang_desc: 'Aktive Sprachen auswählen und Hauptsprache für die automatische Übersetzung festlegen.',
    primary_badge: '⭐ Hauptsprache', set_primary: 'Als Hauptsprache festlegen',
    save_langs: 'Spracheinstellungen speichern', saving_langs: 'Speichern...',
    nexopos_title: 'NexoPOS-Integration', nexopos_desc: 'Produkte von NexoPOS synchronisieren (optional)',
    nexopos_url: 'NexoPOS-URL', api_token: 'API-Token',
    sync: '🔄 Von NexoPOS synchronisieren', syncing: 'Synchronisierung...',
    password_title: 'Passwort ändern', current_password: 'Aktuelles Passwort', new_password: 'Neues Passwort',
    update_password: 'Passwort aktualisieren', save: 'Einstellungen speichern', saving: 'Speichern...',
    saved_langs: 'Spracheinstellungen gespeichert.', saved_settings: 'Einstellungen gespeichert.',
    saved_password: 'Passwort aktualisiert.', error_langs: 'Ein Fehler ist aufgetreten.',
    error_settings: 'Ein Fehler ist aufgetreten.', error_password: 'Passwort konnte nicht aktualisiert werden.',
    sync_done: '{count} Produkte synchronisiert.', sync_error: 'Synchronisierung fehlgeschlagen.',
    banner_image_label: 'Banner-Bild-URL', instagram_url_label: 'Instagram URL',
    at_least_one_lang: 'Mindestens eine Sprache muss aktiv sein', features_title: 'Funktionseinstellungen', tables_enabled_label: 'Tischsystem', tables_enabled_desc: 'Aktiviert: jeder Tisch hat QR.', orders_enabled_label: 'Bestellsystem', orders_enabled_desc: 'Deaktiviert: Kunden sehen nur die Speisekarte.',
  },
  departments: {
    title: 'Abteilungen', add: '+ Hinzufügen', add_title: 'Neue Abteilung', edit_title: 'Abteilung bearbeiten', name: 'Abteilungsname', empty: 'Keine Abteilungen.', multiplier: 'Preisfaktor', multiplier_label: 'Preisfaktor', multiplier_desc: '1.00=normal, 1.20=+20%, 0.90=-10%', products_btn: 'Produkte', products_title: 'Produkte', base_price: 'Grundpreis', override_price_ph: 'Sonderpreis', hide_product: 'Ausblenden', show_product: 'Anzeigen', confirm_delete: 'Abteilung wirklich löschen?',
  },
}




