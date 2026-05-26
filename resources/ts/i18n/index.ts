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

export const LANGUAGES = [
  { code: 'tr', label: 'Türkçe',    flag: '🇹🇷' },
  { code: 'en', label: 'English',   flag: '🇬🇧' },
  { code: 'ru', label: 'Русский',   flag: '🇷🇺' },
  { code: 'ar', label: 'العربية',   flag: '🇸🇦' },
  { code: 'zh', label: '中文',       flag: '🇨🇳' },
  { code: 'ja', label: '日本語',     flag: '🇯🇵' },
  { code: 'ko', label: '한국어',     flag: '🇰🇷' },
  { code: 'pt', label: 'Português', flag: '🇧🇷' },
  { code: 'es', label: 'Español',   flag: '🇪🇸' },
  { code: 'de', label: 'Deutsch',   flag: '🇩🇪' },
  { code: 'fr', label: 'Français',  flag: '🇫🇷' },
  { code: 'it', label: 'Italiano',  flag: '🇮🇹' },
]

const messages = { tr, en, ru, ar, zh, ja, ko, pt, es, de, fr, it }

function detectLang(): string {
  const saved = localStorage.getItem('qr_lang')
  if (saved && saved in messages) return saved
  const browser = navigator.language.split('-')[0]
  if (browser in messages) return browser
  return 'tr'
}

export function applyDir(lang: string) {
  const locale = messages[lang as keyof typeof messages]
  const dir = (locale as { dir: string }).dir ?? 'ltr'
  document.documentElement.setAttribute('dir', dir)
  document.documentElement.setAttribute('lang', lang)
}

const i18n = createI18n({
  legacy: false,
  locale: detectLang(),
  fallbackLocale: 'en',
  messages,
})

applyDir(detectLang())

export function setLanguage(lang: string) {
  if (!(lang in messages)) return
  ;(i18n.global.locale as { value: string }).value = lang
  localStorage.setItem('qr_lang', lang)
  applyDir(lang)
}

export default i18n
