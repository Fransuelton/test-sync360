import { ref } from 'vue'

// Estado global dos toasts
const toastContainer = ref(null)

export function useToast() {
  // Função para registrar o container
  const registerContainer = (container) => {
    toastContainer.value = container
  }

  // Função base para adicionar toast
  const addToast = (options) => {
    if (!toastContainer.value) {
      console.warn('ToastContainer não foi registrado. Certifique-se de incluí-lo em seu App.vue')
      return
    }

    return toastContainer.value.addToast(options)
  }

  // Métodos de conveniência
  const success = (title, message = '', options = {}) => {
    return addToast({
      type: 'success',
      title,
      message,
      duration: 4000,
      ...options
    })
  }

  const error = (title, message = '', options = {}) => {
    return addToast({
      type: 'error',
      title,
      message,
      duration: 6000,
      ...options
    })
  }

  const warning = (title, message = '', options = {}) => {
    return addToast({
      type: 'warning',
      title,
      message,
      duration: 5000,
      ...options
    })
  }

  const info = (title, message = '', options = {}) => {
    return addToast({
      type: 'info',
      title,
      message,
      duration: 4000,
      ...options
    })
  }

  // Remover toast específico
  const remove = (id) => {
    if (!toastContainer.value) return
    toastContainer.value.removeToast(id)
  }

  // Limpar todos os toasts
  const clear = () => {
    if (!toastContainer.value) return
    toastContainer.value.clearAll()
  }

  return {
    registerContainer,
    addToast,
    success,
    error,
    warning,
    info,
    remove,
    clear
  }
}
