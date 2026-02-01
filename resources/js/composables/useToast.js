import { useToast as useToastification } from 'vue-toastification'

export function useToast() {
  const toast = useToastification()

  return {
    success: (message, options = {}) => {
      toast.success(message, options)
    },
    error: (message, options = {}) => {
      toast.error(message, options)
    },
    warning: (message, options = {}) => {
      toast.warning(message, options)
    },
    info: (message, options = {}) => {
      toast.info(message, options)
    },
    clear: () => {
      toast.clear()
    }
  }
}
