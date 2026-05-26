/// <reference types="vite/client" />

declare module '*.css' {
  const css: string
  export default css
}

declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent<object, object, unknown>
  export default component
}
