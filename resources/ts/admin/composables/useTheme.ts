import { ref } from 'vue'

const STORAGE_KEY = 'lm_theme'

export function useTheme(defaultTheme: 'dark' | 'light' = 'dark') {
  const stored = (typeof localStorage !== 'undefined'
    ? localStorage.getItem(STORAGE_KEY)
    : null) as 'dark' | 'light' | null

  const theme = ref<'dark' | 'light'>(stored ?? defaultTheme)

  function toggle() {
    theme.value = theme.value === 'dark' ? 'light' : 'dark'
    localStorage.setItem(STORAGE_KEY, theme.value)
  }

  return { theme, toggle }
}
