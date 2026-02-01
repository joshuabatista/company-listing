
<template>
  <AppHeader />
  
  <div class="companies-page">
    <div class="search-bar">
      <CompaniesInput 
        v-model="searchQuery"
        input-type="text" 
        placeholder="Buscar empresa..."
      />
    </div>
    
    <div class="add-company-wrapper" @click="openAddModal">
      <CompaniesAddCompany />
    </div>

    <div class="companies-list">
      <p class="empty-state">Nenhuma empresa cadastrada ainda.</p>
    </div>
  </div>

  <CompanyModal
    v-model="isModalOpen"
    :company="selectedCompany"
    :loading="isSaving"
    @save="salvarEmpresa"
    @close="handleCloseModal"
  />
</template>

<script setup>
import { ref } from 'vue';
import AppHeader from '../../../components/layout/AppHeader.vue';
import CompaniesInput from '../components/CompaniesInput.vue';
import CompaniesAddCompany from '../components/CompaniesAddCompany.vue';
import CompanyModal from '../components/CompanyModal.vue';
import { useModal } from '../../../composables/useModal.js';
import axios from 'axios';

const searchQuery = ref('');

const { isOpen: isModalOpen, open: openModal, close: closeModal } = useModal();
const selectedCompany = ref(null);
const isSaving = ref(false);

const openAddModal = () => {
  selectedCompany.value = null;
  openModal();
};

const openEditModal = (company) => {
  selectedCompany.value = company;
  openModal();
};

// Salvar empresa
const salvarEmpresa = async (formData) => {
  isSaving.value = true;

  try {
    const payload = {
      cnpj: formData.cnpj.replace(/\D/g, ''),
      razao_social: formData.razao_social,
      email: formData.email,
      telefone: formData.telefone,
      logradouro: formData.logradouro,
      numero: formData.numero,
      estado: formData.estado,
      cidade: formData.cidade,
      descricao: formData.descricao
    }
    
    const response = await axios.post('/api/companies', payload);
    
    console.log('Empresa salva com sucesso:', response.data);
    alert('Empresa cadastrada com sucesso!');
    
    closeModal();
  } catch (error) {
    console.error('Erro ao salvar empresa:', error);
    
    if (error.response) {
      // Erro de validação ou do servidor
      console.error('Dados do erro:', error.response.data);
      alert(`Erro ao salvar empresa: ${error.response.data.message || 'Erro desconhecido'}`);
    } else {
      // Erro de rede
      alert('Erro de conexão. Verifique sua internet e tente novamente.');
    }
  } finally {
    isSaving.value = false;
  }
};

const handleCloseModal = () => {
  selectedCompany.value = null;
};
</script>

<style lang="scss" scoped>
.companies-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.search-bar {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1rem;
}

.add-company-wrapper {
  cursor: pointer;
  margin-bottom: 2rem;
}

.companies-list {
  margin-top: 2rem;
}

.empty-state {
  text-align: center;
  color: #6b7280;
  font-size: 1rem;
  padding: 3rem 1rem;
}
</style>
