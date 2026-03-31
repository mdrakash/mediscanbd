import { ref } from 'vue'

const toasts = ref([])
let nextId = 0

export function useToast() {
  const show = (message, type = 'info', duration = 5000) => {
    const id = nextId++
    const toast = {
      id,
      message,
      type, // 'success', 'error', 'warning', 'info'
      duration
    }

    toasts.value.push(toast)

    if (duration > 0) {
      setTimeout(() => {
        remove(id)
      }, duration)
    }

    return id
  }

  const remove = (id) => {
    const index = toasts.value.findIndex(t => t.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  const success = (message, duration = 5000) => {
    return show(message, 'success', duration)
  }

  const error = (message, duration = 7000) => {
    return show(message, 'error', duration)
  }

  const warning = (message, duration = 6000) => {
    return show(message, 'warning', duration)
  }

  const info = (message, duration = 5000) => {
    return show(message, 'info', duration)
  }

  return {
    toasts,
    show,
    remove,
    success,
    error,
    warning,
    info
  }
}
