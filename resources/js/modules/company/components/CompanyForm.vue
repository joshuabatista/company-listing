<template>
  <form @submit.prevent="handleSubmit" class="company-form">
    
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
        @blur="() => { validateField('cnpj'); buscarCNPJ(); }"
      />
      <span v-if="loadingCNPJ.status" class="form-info">Buscando dados do CNPJ...</span>
      <span v-if="errors.cnpj" class="form-error">{{ errors.cnpj }}</span>
    </div>

    <div class="form-group">
      <label for="razao_social" class="form-label">
        Nome da Empresa <span class="required">*</span>
      </label>
      <input
        id="razao_social"
        v-model="formData.razao_social"
        type="text"
        class="form-input"
        :class="{ 'form-input--error': errors.razao_social }"
        placeholder="Ex: Tech Solutions LTDA"
        @blur="validateField('razao_social')"
      />
      <span v-if="errors.razao_social" class="form-error">{{ errors.razao_social }}</span>
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
      <label for="telefone" class="form-label">
        Telefone <span class="required">*</span>
      </label>
      <input
        id="telefone"
        v-model="formData.telefone"
        type="text"
        class="form-input"
        :class="{ 'form-input--error': errors.telefone }"
        placeholder="(00) 00000-0000"
        maxlength="15"
        @input="formatarTelefone"
        @blur="validateField('telefone')"
      />
      <span v-if="errors.telefone" class="form-error">{{ errors.telefone }}</span>
    </div>

    <div class="form-group">
      <label for="logradouro" class="form-label">
        Endereço
      </label>
      <input
        id="logradouro"
        v-model="formData.logradouro"
        type="text"
        class="form-input"
        placeholder="Rua"
      />
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="numero" class="form-label">
          Numero
        </label>
        <input
          id="numero"
          v-model="formData.numero"
          type="text"
          class="form-input"
          placeholder="Número do endereço"
        />
      </div>

      <div class="form-group">
        <label for="cidade" class="form-label">
          Cidade
        </label>
        <input
          id="cidade"
          v-model="formData.cidade"
          type="text"
          class="form-input"
          placeholder="São Paulo"
        />
      </div>

      <div class="form-group">
        <label for="estado" class="form-label">
          Estado
        </label>
        <input
          id="estado"
          v-model="formData.estado"
          type="text"
          class="form-input"
          placeholder="SP"/>
      </div>
    </div>

    <div class="form-group">
      <label for="descricao" class="form-label">
        Descrição
      </label>
      <textarea
        id="descricao"
        v-model="formData.descricao"
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


const formData = reactive({
  razao_social: '',
  cnpj: '',
  email: '',
  telefone: '',
  logradouro: '',
  numero: '',
  cidade: '',
  estado: '',
  descricao: ''
});

const errors = reactive({
  razao_social: '',
  cnpj: '',
  email: '',
  telefone: ''
});

if (props.company) {
  formData.razao_social = props.company.razao_social || '';
  formData.cnpj = props.company.cnpj || '';
  formData.email = props.company.email || '';
  formData.telefone = props.company.telefone || '';
  formData.logradouro = props.company.logradouro || '';
  formData.numero = props.company.numero || '';
  formData.cidade = props.company.cidade || '';
  formData.estado = props.company.estado || '';
  formData.descricao = props.company.descricao || '';
  
  if (formData.cnpj && formData.cnpj.length === 14) {
    formData.cnpj = formData.cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
  }
  
  if (formData.telefone && !formData.telefone.includes('(')) {
    const tel = formData.telefone.replace(/\D/g, '');
    if (tel.length === 11) {
      formData.telefone = tel.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else if (tel.length === 10) {
      formData.telefone = tel.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    }
  }
}

const validateField = (field) => {
  errors[field] = '';

  switch (field) {
    case 'razao_social':
      if (!formData.razao_social.trim()) {
        errors.razao_social = 'Nome da empresa é obrigatório';
      } else if (formData.razao_social.length < 3) {
        errors.razao_social = 'Nome deve ter pelo menos 3 caracteres';
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

    case 'telefone':
      if (!formData.telefone.trim()) {
        errors.telefone = 'Telefone é obrigatório';
      } else if (formData.telefone.replace(/\D/g, '').length < 10) {
        errors.telefone = 'Telefone inválido';
      }
      break;
  }
};

const validateForm = () => {
  validateField('razao_social');
  validateField('cnpj');
  validateField('email');
  validateField('telefone');

  return !Object.values(errors).some(error => error !== '');
};

const handleSubmit = () => {
  if (validateForm()) {
    emit('submit', { ...formData });
  }
};

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

const formatarTelefone = (event) => {
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
  
  formData.telefone = value;
};

const isValidEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

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

const loadingCNPJ = reactive({
  status: false,
  error: ''
});

const buscarCNPJ = async () => {
  
  const cnpjLimpo = formData.cnpj.replace(/\D/g, '')

  if(!isValidCNPJ(formData.cnpj)) return

  try {
    loadingCNPJ.status = true
    loadingCNPJ.error = ''

    const response = await fetch(`/api/cnpj/${cnpjLimpo}`);

    const data = await response.json();    

    if (response.ok) {
      formData.razao_social = data.nome || '';
      formData.email = data.email || '';
      formData.telefone = data.telefone || '';
      formData.logradouro = data.logradouro || '';
      formData.cidade = data.municipio || '';
      formData.estado = data.uf || '';
    } else {
      loadingCNPJ.error = data.message || 'Erro ao buscar dados do CNPJ.';
    }
    
  } catch (e) {
    loadingCNPJ.error = 'Erro de conexão';
  } finally {
    loadingCNPJ.status = false;
  }
  
}

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
  grid-template-columns: 1fr 1fr 1fr;
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

.form-info {
  font-size: 0.85rem;
  color: #2563eb;
}
</style>
