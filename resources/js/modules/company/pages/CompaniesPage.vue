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
    @save="handleSaveCompany"
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
const handleSaveCompany = async (formData) => {
  isSaving.value = true;
  
  try {
    // TODO: Aqui vai a chamada para a API
    console.log('Salvando empresa:', formData);
    
    // Simulando delay de API
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // TODO: Adicionar toast de sucesso
    alert(selectedCompany.value ? 'Empresa atualizada com sucesso!' : 'Empresa cadastrada com sucesso!');
    
    closeModal();
  } catch (error) {
    console.error('Erro ao salvar empresa:', error);
    // TODO: Adicionar toast de erro
    alert('Erro ao salvar empresa. Tente novamente.');
  } finally {
    isSaving.value = false;
  }
};

// Fechar modal
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
