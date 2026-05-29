import { ref } from 'vue'

export type ConfirmType = 'danger' | 'warning' | 'info'

interface ConfirmOptions {
  title?: string
  confirmText?: string
  cancelText?: string
  type?: ConfirmType
}

interface ConfirmState {
  visible: boolean
  message: string
  title: string
  confirmText: string
  cancelText: string
  type: ConfirmType
  resolve: ((v: boolean) => void) | null
}

const state = ref<ConfirmState>({
  visible: false,
  message: '',
  title: '',
  confirmText: 'Onayla',
  cancelText: 'İptal',
  type: 'danger',
  resolve: null,
})

export function useConfirm() {
  function confirm(message: string, options: ConfirmOptions = {}): Promise<boolean> {
    return new Promise((resolve) => {
      state.value = {
        visible: true,
        message,
        title: options.title ?? '',
        confirmText: options.confirmText ?? 'Onayla',
        cancelText: options.cancelText ?? 'İptal',
        type: options.type ?? 'danger',
        resolve,
      }
    })
  }

  function answer(value: boolean) {
    state.value.resolve?.(value)
    state.value.visible = false
  }

  return { confirmState: state, confirm, answer }
}
