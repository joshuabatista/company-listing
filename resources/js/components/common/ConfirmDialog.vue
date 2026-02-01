<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="isOpen" class="confirm-overlay" @click="handleOverlayClick">
        <div class="confirm-dialog" @click.stop>
          <div class="confirm-header">
            <div class="confirm-icon" :class="`confirm-icon--${variant}`">
              <AlertTriangle v-if="variant === 'danger'" :size="24" />
              <AlertCircle v-else-if="variant === 'warning'" :size="24" />
              <Info v-else :size="24" />
            </div>
            <h3 class="confirm-title">{{ title }}</h3>
          </div>
          
          <div class="confirm-body">
            <p class="confirm-message">{{ message }}</p>
          </div>
          
          <div class="confirm-footer">
            <BaseButton
              variant="outline"
              @click="handleCancel"
            >
              {{ cancelText }}
            </BaseButton>
            <BaseButton
              :variant="variant === 'danger' ? 'danger' : 'primary'"
              @click="handleConfirm"
            >
              {{ confirmText }}
            </BaseButton>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref } from 'vue';
import { AlertTriangle, AlertCircle, Info } from 'lucide-vue-next';
import BaseButton from './BaseButton.vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Confirmar ação'
  },
  message: {
    type: String,
    required: true
  },
  confirmText: {
    type: String,
    default: 'Confirmar'
  },
  cancelText: {
    type: String,
    default: 'Cancelar'
  },
  variant: {
    type: String,
    default: 'danger',
    validator: (value) => ['danger', 'warning', 'info'].includes(value)
  }
});

const emit = defineEmits(['confirm', 'cancel']);

const isOpen = ref(false);

const open = () => {
  isOpen.value = true;
};

const close = () => {
  isOpen.value = false;
};

const handleConfirm = () => {
  emit('confirm');
  close();
};

const handleCancel = () => {
  emit('cancel');
  close();
};

const handleOverlayClick = () => {
  handleCancel();
};

defineExpose({
  open,
  close
});
</script>

<style lang="scss" scoped>
.confirm-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 1rem;
}

.confirm-dialog {
  background: white;
  border-radius: 12px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  max-width: 400px;
  width: 100%;
  overflow: hidden;
}

.confirm-header {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  border-bottom: 1px solid #e5e7eb;
}

.confirm-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  
  &--danger {
    background: #fee2e2;
    color: #dc2626;
  }
  
  &--warning {
    background: #fef3c7;
    color: #d97706;
  }
  
  &--info {
    background: #dbeafe;
    color: #2563eb;
  }
}

.confirm-title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
  text-align: center;
}

.confirm-body {
  padding: 1.5rem;
}

.confirm-message {
  margin: 0;
  font-size: 0.95rem;
  color: #6b7280;
  text-align: center;
  line-height: 1.6;
  white-space: pre-line;
}

.confirm-footer {
  padding: 1rem 1.5rem;
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
  
  .confirm-dialog {
    transition: transform 0.2s ease, opacity 0.2s ease;
  }
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  
  .confirm-dialog {
    transform: scale(0.95);
    opacity: 0;
  }
}
</style>
