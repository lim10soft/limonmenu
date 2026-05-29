export default {
  dir: 'ltr',
  nav: {
    dashboard: 'Tableau de bord', orders: 'Commandes', products: 'Produits',
    categories: 'Catégories', tables: 'Tables & QR', settings: 'Paramètres', logout: 'Déconnexion', departments: 'Départements'
  },
  common: {
    save: 'Enregistrer', saving: 'Enregistrement...', cancel: 'Annuler', delete: 'Supprimer', add: 'Ajouter',
    loading: 'Chargement...', active: 'Actif', inactive: 'Inactif',
    activate: 'Activer', deactivate: 'Désactiver', error: 'Une erreur est survenue.',
    all: 'Tous', note: 'Note', status: 'Statut', download: 'Télécharger',
    name: 'Nom', image_url: "URL de l'image", sort_order: 'Ordre', price: 'Prix',
    description: 'Description', category: 'Catégorie',
    translations_label: 'Traductions', default_name: 'Nom par défaut', default_desc: 'Description par défaut',
  },
  login: {
    title: 'Connexion', subtitle: 'Panneau d\'administration QR Menu',
    email: 'E-mail', password: 'Mot de passe',
    submit: 'Se connecter', submitting: 'Connexion...', error: 'E-mail ou mot de passe incorrect.',
    remember: 'Se souvenir de moi',
  },
  dashboard: {
    title: 'Tableau de bord', today_orders: "Commandes d'aujourd'hui", today_revenue: "Chiffre d'affaires du jour",
    total_products: 'Total des produits', active_tables: 'Tables actives',
    recent_orders: 'Commandes récentes', no_orders: 'Pas encore de commandes.', view_all: 'Voir tout →',
  },
  products: {
    title: 'Produits', add: '+ Ajouter un produit',
    auto_translate: '🌐 Traduction automatique', translating: 'Traduction...',
    auto_translate_confirm: 'Tous les produits seront automatiquement traduits de la langue principale vers toutes les langues actives. Les traductions existantes seront mises à jour. Continuer ?',
    auto_translate_done: '{count} traductions ajoutées/mises à jour pour {langs} langues.',
    auto_translate_error: 'La traduction automatique a échoué.',
    total_count: 'produits', no_results: 'Aucun résultat.', empty: 'Aucun produit ajouté.',
    edit_title: 'Modifier le produit', add_title: 'Nouveau produit',
    name: 'Nom par défaut *', category: 'Catégorie *',
    price: 'Prix (₺) *', description: 'Description par défaut', image_url: "URL de l'image",
    translations_label: 'Traductions', confirm_delete: 'Êtes-vous sûr de vouloir supprimer ce produit ?',
    parent_label: 'Catégorie Parente', no_parent: 'Aucune (catégorie racine)', span_label: 'Largeur de la Carte', span_normal: 'Normal', span_wide: 'Large', span_desc: 'La carte large occupe toute la ligne.', wide: 'Large',
  },
  categories: {
    title: 'Catégories', add: '+ Ajouter une catégorie',
    edit_title: 'Modifier la catégorie', add_title: 'Nouvelle catégorie',
    name: 'Nom par défaut *', sort_order: 'Ordre', image_url: "URL de l'image",
    translations_label: 'Traductions', confirm_delete: 'Êtes-vous sûr de vouloir supprimer cette catégorie ?',
    parent_label: 'Catégorie Parente', no_parent: 'Aucune (catégorie racine)', span_label: 'Largeur de la Carte', span_normal: 'Normal', span_wide: 'Large', span_desc: 'La carte large occupe toute la ligne.', wide: 'Large',
  },
  tables: {
    title: 'Tables & Codes QR', add: '+ Ajouter une table', add_title: 'Nouvelle table',
    table_name: 'Nom de la table', table_name_placeholder: 'Nom de table (ex : Table 1)',
    download: 'Télécharger', active: 'Active', inactive: 'Inactive',
    activate: 'Activer', deactivate: 'Désactiver',
    confirm_delete: 'Êtes-vous sûr de vouloir supprimer cette table ?',
  },
  orders: {
    title: 'Commandes', no_orders: 'Aucune commande pour ce filtre.', note_prefix: 'Note : ',
    status: { all: 'Toutes', pending: 'En attente', preparing: 'En préparation', ready: 'Prêt', delivered: 'Livré', cancelled: 'Annulé' },
  },
  settings: {
    title: 'Paramètres', general_title: 'Informations générales', restaurant_name: 'Nom du restaurant',
    slug_label: 'Slug (nom URL)', slug_prefix: 'qrmenu.com/m/', logo_url: 'URL du logo', theme_color: 'Couleur du thème',
    lang_title: 'Gestion des langues', lang_desc: 'Sélectionnez les langues actives et définissez la langue principale pour la traduction automatique.',
    primary_badge: '⭐ Principale', set_primary: 'Définir comme principale',
    save_langs: 'Enregistrer les paramètres de langue', saving_langs: 'Enregistrement...',
    nexopos_title: 'Intégration NexoPOS', nexopos_desc: 'Synchroniser les produits depuis NexoPOS (optionnel)',
    nexopos_url: 'URL NexoPOS', api_token: 'Token API',
    sync: '🔄 Synchroniser depuis NexoPOS', syncing: 'Synchronisation...',
    password_title: 'Changer le mot de passe', current_password: 'Mot de passe actuel', new_password: 'Nouveau mot de passe',
    update_password: 'Mettre à jour le mot de passe', save: 'Enregistrer les paramètres', saving: 'Enregistrement...',
    saved_langs: 'Paramètres de langue enregistrés.', saved_settings: 'Paramètres enregistrés.',
    saved_password: 'Mot de passe mis à jour.', error_langs: 'Une erreur est survenue.',
    error_settings: 'Une erreur est survenue.', error_password: 'Impossible de mettre à jour le mot de passe.',
    sync_done: '{count} produits synchronisés.', sync_error: 'Échec de la synchronisation.',
    banner_image_label: 'URL de l\'image bannière', instagram_url_label: 'URL Instagram',
    at_least_one_lang: 'Au moins une langue doit être active', features_title: 'Fonctionnalités', tables_enabled_label: 'Système tables', tables_enabled_desc: 'Activé: chaque table a son QR.', orders_enabled_label: 'Système commandes', orders_enabled_desc: 'Désactivé: les clients voient seulement le menu.',
  },
  departments: {
    title: 'Départements', add: '+ Ajouter', add_title: 'Nouveau', edit_title: 'Modifier', name: 'Nom', empty: 'Aucun département.', multiplier: 'Multiplicateur', multiplier_label: 'Multiplicateur de prix', multiplier_desc: '1.00=normal, 1.20=+20%, 0.90=-10%', products_btn: 'Produits', products_title: 'Produits', base_price: 'Prix de base', override_price_ph: 'Prix perso', hide_product: 'Masquer', show_product: 'Afficher', confirm_delete: 'Supprimer ce département ?',
  },
}




