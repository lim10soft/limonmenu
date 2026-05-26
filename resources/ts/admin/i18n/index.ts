import { createI18n } from 'vue-i18n'
import tr from './locales/tr'
import en from './locales/en'
import ru from './locales/ru'
import ar from './locales/ar'
import zh from './locales/zh'
import ja from './locales/ja'
import ko from './locales/ko'
import pt from './locales/pt'
import es from './locales/es'
import de from './locales/de'
import fr from './locales/fr'
import it from './locales/it'

export const ADMIN_LANGUAGES = [
  { code: 'tr', label: 'Türkçe',    flag: '🇹🇷' },
  { code: 'en', label: 'English',   flag: '🇬🇧' },
  { code: 'ru', label: 'Русский',   flag: '🇷🇺' },
  { code: 'ar', label: 'العربية',   flag: '🇸🇦' },
  { code: 'zh', label: '中文',      flag: '🇨🇳' },
  { code: 'ja', label: '日本語',    flag: '🇯🇵' },
  { code: 'ko', label: '한국어',    flag: '🇰🇷' },
  { code: 'pt', label: 'Português', flag: '🇧🇷' },
  { code: 'es', label: 'Español',   flag: '🇪🇸' },
  { code: 'de', label: 'Deutsch',   flag: '🇩🇪' },
  { code: 'fr', label: 'Français',  flag: '🇫🇷' },
  { code: 'it', label: 'Italiano',  flag: '🇮🇹' },
]

const messages: Record<string, any> = { tr, en, ru, ar, zh, ja, ko, pt, es, de, fr, it }

function detectLang(): string {
  const stored = localStorage.getItem('admin_lang')
  if (stored && messages[stored]) return stored
  const browser = navigator.language.split('-')[0]
  if (messages[browser]) return browser
  return 'tr'
}

function applyDir(lang: string) {
  const dir = messages[lang]?.dir ?? 'ltr'
  document.documentElement.setAttribute('dir', dir)
}

const initialLang = detectLang()
applyDir(initialLang)

const adminI18n = createI18n({
  legacy: false,
  locale: initialLang,
  fallbackLocale: 'tr',
  messages,
})

export function setAdminLanguage(lang: string) {
  if (!messages[lang]) return
  ;(adminI18n.global.locale as { value: string }).value = lang
  localStorage.setItem('admin_lang', lang)
  applyDir(lang)
}

export default adminI18n
