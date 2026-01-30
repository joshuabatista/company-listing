<template>
  <BaseModal
    v-model="isOpen"
    :title="modalTitle"
    size="lg"
    @close="handleClose"
  >
    <!-- Body do Modal -->
    <CompanyForm
      ref="companyFormRef"
      :company="company"
      @submit="handleSubmit"
    />

    <!-- Footer do Modal -->
    <template #footer>
      <BaseButton
        variant="outline"
        @click="handleClose"
      >
        Cancelar
      </BaseButton>
      <BaseButton
        variant="primary"
        :loading="loading"
        @click="handleSave"
      >
        {{ isEditing ? 'Atualizar' : 'Salvar' }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script setup>
import { ref, computed } from 'vue';
import BaseModal from '../../../components/common/BaseModal.vue';
import BaseButton from '../../../components/common/BaseButton.vue';
import CompanyForm from './CompanyForm.vue';

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  company: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue', 'save', 'close']);

const companyFormRef = ref(null);

const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

const isEditing = computed(() => !!props.company?.id);

const modalTitle = computed(() => {
  return isEditing.value ? 'Editar Empresa' : 'Adicionar Nova Empresa';
});

const handleClose = () => {
  isOpen.value = false;
  emit('close');
};

const handleSave = () => {
  if (companyFormRef.value && companyFormRef.value.validateForm()) {
    const formData = companyFormRef.value.formData;
    emit('save', formData);
  }
};

const handleSubmit = (formData) => {
  emit('save', formData);
};
</script>
