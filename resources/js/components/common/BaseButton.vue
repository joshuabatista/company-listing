<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="buttonClasses"
    @click="handleClick"
  >
    <span v-if="loading" class="button-spinner"></span>
    <slot v-else></slot>
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  type: {
    type: String,
    default: 'button',
    validator: (value) => ['button', 'submit', 'reset'].includes(value)
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'danger', 'success', 'outline'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  fullWidth: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['click']);

const buttonClasses = computed(() => [
  'base-button',
  `base-button--${props.variant}`,
  `base-button--${props.size}`,
  {
    'base-button--disabled': props.disabled || props.loading,
    'base-button--loading': props.loading,
    'base-button--full-width': props.fullWidth
  }
]);

const handleClick = (event) => {
  if (!props.disabled && !props.loading) {
    emit('click', event);
  }
};
</script>

<style lang="scss" scoped>
.base-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-family: 'Inter', sans-serif;
  font-weight: 500;
  border-radius: 6px;
  border: 1px solid transparent;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
  
  &:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
  }
  
  &:active:not(.base-button--disabled) {
    transform: scale(0.98);
  }
  
  &--sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
  }
  
  &--md {
    padding: 0.625rem 1.25rem;
    font-size: 0.95rem;
    line-height: 1.5rem;
  }
  
  &--lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    line-height: 1.75rem;
  }
  
  &--primary {
    background: #3b82f6;
    color: white;
    
    &:hover:not(.base-button--disabled) {
      background: #2563eb;
    }
  }
  
  &--secondary {
    background: #6b7280;
    color: white;
    
    &:hover:not(.base-button--disabled) {
      background: #4b5563;
    }
  }
  
  &--danger {
    background: #ef4444;
    color: white;
    
    &:hover:not(.base-button--disabled) {
      background: #dc2626;
    }
  }
  
  &--success {
    background: #10b981;
    color: white;
    
    &:hover:not(.base-button--disabled) {
      background: #059669;
    }
  }
  
  &--outline {
    background: transparent;
    border-color: #d1d5db;
    color: #374151;
    
    &:hover:not(.base-button--disabled) {
      background: #f9fafb;
      border-color: #9ca3af;
    }
  }
  
  &--disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  &--loading {
    cursor: wait;
  }
  
  &--full-width {
    width: 100%;
  }
}

.button-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
