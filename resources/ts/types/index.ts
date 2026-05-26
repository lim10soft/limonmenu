export interface Tenant {
  id: number
  name: string
  slug: string
  logo: string | null
  plan: 'free' | 'starter' | 'pro'
  theme_color: string
  active: boolean
  tables_enabled: boolean
  orders_enabled: boolean
  currency_code: string
  banner_image: string | null
  instagram_url: string | null
  primary_lang: string
  created_at?: string
}

export interface Department {
  id: number
  tenant_id: number
  name: string
  display_name: string | null
  logo: string | null
  banner_image: string | null
  token: string
  price_multiplier: number
  active: boolean
  sort_order: number
}

export interface DepartmentCategoryItem {
  id: number
  name: string
  parent_id: number | null
  hidden: boolean
}

export interface DepartmentProduct {
  id: number
  name: string
  price: number
  image: string | null
  category_id: number
  hidden: boolean
  override_price: number | null
}

export interface QrTable {
  id: number
  tenant_id: number
  name: string
  token: string
  active: boolean
}

export interface TenantLanguage {
  id: number
  lang_code: string
  active: boolean
  sort_order: number
}

export interface CategoryTranslation {
  name: string
}

export interface ProductTranslation {
  name: string
  description?: string
}

export interface Category {
  id: number
  tenant_id: number
  name: string
  image: string | null
  sort_order: number
  active: boolean
  parent_id: number | null
  span: 'normal' | 'wide'
  products?: Product[]
  children?: Category[]
  translations?: Record<string, CategoryTranslation>
}

export interface ProductUnit {
  id?: number
  label: string | null
  price: number
  sort_order: number
}

export interface Product {
  id: number
  tenant_id: number
  category_id: number
  name: string
  description: string | null
  price: number
  image: string | null
  active: boolean
  in_stock: boolean
  calories: number | null
  ingredients: string | null
  allergens: string[]
  is_vegan: boolean
  is_vegetarian: boolean
  has_alcohol: boolean
  has_pork: boolean
  hidden?: boolean
  category?: Category
  translations?: Record<string, ProductTranslation>
  units?: ProductUnit[]
}

export interface CartItem {
  product: Product
  quantity: number
  note: string
}

export interface Order {
  id: number
  tenant_id: number
  table_id: number
  status: 'pending' | 'preparing' | 'ready' | 'delivered' | 'cancelled'
  total: number
  note: string | null
  items: OrderItem[]
  table?: QrTable
  created_at: string
}

export interface OrderItem {
  id: number
  order_id: number
  product_id: number
  quantity: number
  unit_price: number
  note: string | null
  product?: Product
}

export interface TenantUser {
  id: number
  tenant_id: number | null
  name: string
  email: string
  role: 'owner' | 'staff' | 'superadmin'
}

export interface MenuSettings {
  theme_color: string
  logo: string | null
  welcome_message: string | null
  currency: string
  currency_position: 'before' | 'after'
}

export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface ApiResponse<T = unknown> {
  data: T
  message?: string
}
