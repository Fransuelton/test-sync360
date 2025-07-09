import { ref } from "vue";

const toastContainer = ref(null);

export function useToast() {
  const registerContainer = (container) => {
    toastContainer.value = container;
  };

  const addToast = (options) => {
    if (!toastContainer.value) {
      console.warn(
        "ToastContainer não foi registrado. Certifique-se de incluí-lo em seu App.vue"
      );
      return;
    }

    return toastContainer.value.addToast(options);
  };

  const success = (title, message = "", options = {}) => {
    return addToast({
      type: "success",
      title,
      message,
      duration: 4000,
      ...options,
    });
  };

  const error = (title, message = "", options = {}) => {
    return addToast({
      type: "error",
      title,
      message,
      duration: 6000,
      ...options,
    });
  };

  const warning = (title, message = "", options = {}) => {
    return addToast({
      type: "warning",
      title,
      message,
      duration: 5000,
      ...options,
    });
  };

  const info = (title, message = "", options = {}) => {
    return addToast({
      type: "info",
      title,
      message,
      duration: 4000,
      ...options,
    });
  };

  const remove = (id) => {
    if (!toastContainer.value) return;
    toastContainer.value.removeToast(id);
  };

  const clear = () => {
    if (!toastContainer.value) return;
    toastContainer.value.clearAll();
  };

  return {
    registerContainer,
    addToast,
    success,
    error,
    warning,
    info,
    remove,
    clear,
  };
}
