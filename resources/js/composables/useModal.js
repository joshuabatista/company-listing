import { ref } from 'vue';

/**
 * @param {boolean} initialState - Estado inicial do modal (aberto/fechado)
 * @returns {Object} Objeto com estado e métodos do modal
 */
export function useModal(initialState = false) {
  const isOpen = ref(initialState);

  const open = () => {
    isOpen.value = true;
  };

  const close = () => {
    isOpen.value = false;
  };

  const toggle = () => {
    isOpen.value = !isOpen.value;
  };

  return {
    isOpen,
    open,
    close,
    toggle
  };
}
