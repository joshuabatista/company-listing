<template>
  <form @submit.prevent="handleSubmit" class="company-form">
    <div class="form-group">
      <label for="name" class="form-label">
        Nome da Empresa <span class="required">*</span>
      </label>
      <input
        id="name"
        v-model="formData.name"
        type="text"
        class="form-input"
        :class="{ 'form-input--error': errors.name }"
        placeholder="Ex: Tech Solutions LTDA"
        @blur="validateField('name')"
      />
      <span v-if="errors.name" class="form-error">{{ errors.name }}</span>
    </div>

    <div class="form-group">
      <label for="cnpj" class="form-label">
        CNPJ <span class="required">*</span>
      </label>
      <input
        id="cnpj"
        v-model="formData.cnpj"
        type="text"
        class="form-input"
        :class="{ 'form-input--error': errors.cnpj }"
        placeholder="00.000.000/0000-00"
        maxlength="18"
        @input="formatCNPJ"
        @blur="validateField('cnpj')"
      />
      <span v-if="errors.cnpj" class="form-error">{{ errors.cnpj }}</span>
    </div>

    <div class="form-group">
      <label for="email" class="form-label">
        Email <span class="required">*</span>
      </label>
      <input
        id="email"
        v-model="formData.email"
        type="email"
        class="form-input"
        :class="{ 'form-input--error': errors.email }"
        placeholder="contato@empresa.com.br"
        @blur="validateField('email')"
      />
      <span v-if="errors.email" class="form-error">{{ errors.email }}</span>
    </div>

    <div class="form-group">
      <label for="phone" class="form-label">
        Telefone <span class="required">*</span>
      </label>
      <input
        id="phone"
        v-model="formData.phone"
        type="text"
        class="form-input"
        :class="{ 'form-input--error': errors.phone }"
        placeholder="(00) 00000-0000"
        maxlength="15"
        @input="formatPhone"
        @blur="validateField('phone')"
      />
      <span v-if="errors.phone" class="form-error">{{ errors.phone }}</span>
    </div>

    <div class="form-group">
      <label for="address" class="form-label">
        Endereço
      </label>
      <input
        id="address"
        v-model="formData.address"
        type="text"
        class="form-input"
        placeholder="Rua, número, bairro"
      />
    </div>

    <!-- Cidade e Estado -->
    <div class="form-row">
      <div class="form-group">
        <label for="city" class="form-label">
          Cidade
        </label>
        <input
          id="city"
          v-model="formData.city"
          type="text"
          class="form-input"
          placeholder="São Paulo"
        />
      </div>

      <div class="form-group">
        <label for="state" class="form-label">
          Estado
        </label>
        <select
          id="state"
          v-model="formData.state"
          class="form-input"
        >
          <option value="">Selecione</option>
          <option v-for="state in brazilianStates" :key="state" :value="state">
            {{ state }}
          </option>
        </select>
      </div>
    </div>

    <!-- Website -->
    <div class="form-group">
      <label for="website" class="form-label">
        Website
      </label>
      <input
        id="website"
        v-model="formData.website"
        type="url"
        class="form-input"
        placeholder="https://www.empresa.com.br"
      />
    </div>

    <!-- Descrição -->
    <div class="form-group">
      <label for="description" class="form-label">
        Descrição
      </label>
      <textarea
        id="description"
        v-model="formData.description"
        class="form-textarea"
        rows="4"
        placeholder="Breve descrição sobre a empresa..."
      ></textarea>
    </div>
  </form>
</template>

<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
  company: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['submit', 'update:formData']);

const brazilianStates = [
  'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
  'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
  'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
];

const formData = reactive({
  name: '',
  cnpj: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  state: '',
  website: '',
  description: ''
});

const errors = reactive({
  name: '',
  cnpj: '',
  email: '',
  phone: ''
});

if (props.company) {
  Object.assign(formData, props.company);
}

const validateField = (field) => {
  errors[field] = '';

  switch (field) {
    case 'name':
      if (!formData.name.trim()) {
        errors.name = 'Nome da empresa é obrigatório';
      } else if (formData.name.length < 3) {
        errors.name = 'Nome deve ter pelo menos 3 caracteres';
      }
      break;

    case 'cnpj':
      if (!formData.cnpj.trim()) {
        errors.cnpj = 'CNPJ é obrigatório';
      } else if (!isValidCNPJ(formData.cnpj)) {
        errors.cnpj = 'CNPJ inválido';
      }
      break;

    case 'email':
      if (!formData.email.trim()) {
        errors.email = 'Email é obrigatório';
      } else if (!isValidEmail(formData.email)) {
        errors.email = 'Email inválido';
      }
      break;

    case 'phone':
      if (!formData.phone.trim()) {
        errors.phone = 'Telefone é obrigatório';
      } else if (formData.phone.replace(/\D/g, '').length < 10) {
        errors.phone = 'Telefone inválido';
      }
      break;
  }
};

const validateForm = () => {
  validateField('name');
  validateField('cnpj');
  validateField('email');
  validateField('phone');

  return !Object.values(errors).some(error => error !== '');
};

const handleSubmit = () => {
  if (validateForm()) {
    emit('submit', { ...formData });
  }
};

// Formatação de CNPJ
const formatCNPJ = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  
  if (value.length <= 14) {
    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
    value = value.replace(/(\d{4})(\d)/, '$1-$2');
  }
  
  formData.cnpj = value;
};

// Formatação de Telefone
const formatPhone = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  
  if (value.length <= 11) {
    if (value.length <= 10) {
      value = value.replace(/^(\d{2})(\d)/, '($1) $2');
      value = value.replace(/(\d{4})(\d)/, '$1-$2');
    } else {
      value = value.replace(/^(\d{2})(\d)/, '($1) $2');
      value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
  }
  
  formData.phone = value;
};

// Validação de Email
const isValidEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

// Validação de CNPJ
const isValidCNPJ = (cnpj) => {
  cnpj = cnpj.replace(/\D/g, '');
  
  if (cnpj.length !== 14) return false;
  if (/^(\d)\1+$/.test(cnpj)) return false;
  
  let size = cnpj.length - 2;
  let numbers = cnpj.substring(0, size);
  let digits = cnpj.substring(size);
  let sum = 0;
  let pos = size - 7;
  
  for (let i = size; i >= 1; i--) {
    sum += numbers.charAt(size - i) * pos--;
    if (pos < 2) pos = 9;
  }
  
  let result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
  if (result != digits.charAt(0)) return false;
  
  size = size + 1;
  numbers = cnpj.substring(0, size);
  sum = 0;
  pos = size - 7;
  
  for (let i = size; i >= 1; i--) {
    sum += numbers.charAt(size - i) * pos--;
    if (pos < 2) pos = 9;
  }
  
  result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
  if (result != digits.charAt(1)) return false;
  
  return true;
};

defineExpose({
  validateForm,
  formData
});

watch(formData, (newValue) => {
  emit('update:formData', newValue);
}, { deep: true });
</script>

<style lang="scss" scoped>
.company-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  
  .required {
    color: #ef4444;
  }
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 0.625rem 0.75rem;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-family: 'Inter', sans-serif;
  font-size: 0.95rem;
  color: #374151;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
  
  &:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }
  
  &::placeholder {
    color: #9ca3af;
  }
  
  &--error {
    border-color: #ef4444;
    
    &:focus {
      border-color: #ef4444;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
  }
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

.form-error {
  font-size: 0.875rem;
  color: #ef4444;
  margin-top: -0.25rem;
}

select.form-input {
  cursor: pointer;
}
</style>
