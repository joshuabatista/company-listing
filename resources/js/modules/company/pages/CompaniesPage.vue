
<template>
  <AppHeader />
  
  <div class="companies-page">
    <div class="search-bar">
      <CompaniesInput 
        v-model="searchQuery"
        input-type="text" 
        placeholder="Buscar empresa por nome..."
      />
    </div>
    
    <div class="add-company-wrapper" @click="openAddModal">
      <CompaniesAddCompany />
    </div>

    <div class="companies-list">
      <div v-if="isLoading" class="loading-state">
        <p>Carregando empresas...</p>
      </div>
      
      <div v-else-if="filteredCompanies.length > 0" class="companies-grid">
        <CompanyCard 
          v-for="company in filteredCompanies" 
          :key="company.id"
          :company="company"
          @click="openViewModal"
        />
      </div>
      
      <p v-else class="empty-state">
        {{ searchQuery ? 'Nenhuma empresa encontrada com esse nome.' : 'Nenhuma empresa cadastrada ainda.' }}
      </p>
    </div>
  </div>

  <CompanyModal
    v-model="isModalOpen"
    :company="selectedCompany"
    :loading="isSaving"
    @save="salvarEmpresa"
    @delete="excluirEmpresa"
    @close="handleCloseModal"
  />

  <ConfirmDialog
    ref="confirmDialogRef"
    :title="confirmDialog.title"
    :message="confirmDialog.message"
    :confirmText="confirmDialog.confirmText"
    :cancelText="confirmDialog.cancelText"
    :variant="confirmDialog.variant"
    @confirm="confirmDialog.onConfirm"
    @cancel="confirmDialog.onCancel"
  />
</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue';
import AppHeader from '../../../components/layout/AppHeader.vue';
import CompaniesInput from '../components/CompaniesInput.vue';
import CompaniesAddCompany from '../components/CompaniesAddCompany.vue';
import CompanyCard from '../components/CompanyCard.vue';
import CompanyModal from '../components/CompanyModal.vue';
import ConfirmDialog from '../../../components/common/ConfirmDialog.vue';
import { useModal } from '../../../composables/useModal.js';
import { useToast } from '../../../composables/useToast.js';
import axios from 'axios';

const searchQuery = ref('');
const companies = ref([]);
const isLoading = ref(false);

const { isOpen: isModalOpen, open: openModal, close: closeModal } = useModal();
const toast = useToast();
const selectedCompany = ref(null);
const isSaving = ref(false);
const confirmDialogRef = ref(null);

const confirmDialog = reactive({
  title: '',
  message: '',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  variant: 'danger',
  onConfirm: () => {},
  onCancel: () => {}
});

const filteredCompanies = computed(() => {
  if (!searchQuery.value) {
    return companies.value;
  }
  
  const query = searchQuery.value.toLowerCase();
  return companies.value.filter(company => 
    company.razao_social.toLowerCase().includes(query)
  );
});

const fetchCompanies = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/companies');
    companies.value = response.data.data;
  } catch (error) {
    console.error('Erro ao buscar empresas:', error);
    toast.error('Erro ao carregar empresas');
  } finally {
    isLoading.value = false;
  }
};

const openAddModal = () => {
  selectedCompany.value = null;
  openModal();
};

const openViewModal = (company) => {
  selectedCompany.value = { ...company };
  openModal();
};

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
    
    let response;
    
    if (selectedCompany.value?.id) {
      response = await axios.put(`/api/companies/${selectedCompany.value.id}`, payload);
      toast.success('Empresa atualizada com sucesso!');
    } else {
      response = await axios.post('/api/companies', payload);
      toast.success('Empresa cadastrada com sucesso!');
    }
    
    await fetchCompanies();
    
    closeModal();
  } catch (error) {
    console.error('Erro ao salvar empresa:', error);
    
    if (error.response) {
      const errorMessage = error.response.data.message || 'Erro ao salvar empresa';
      toast.error(errorMessage);
    } else {
      toast.error('Erro de conexão. Verifique sua internet e tente novamente.');
    }
  } finally {
    isSaving.value = false;
  }
};

const handleCloseModal = () => {
  selectedCompany.value = null;
};

const excluirEmpresa = (company) => {
  confirmDialog.title = 'Excluir Empresa';
  confirmDialog.message = `Tem certeza que deseja excluir a empresa \n\n "${company.razao_social}"?\n\nEsta ação não poderá ser desfeita.`;
  confirmDialog.confirmText = 'Excluir';
  confirmDialog.cancelText = 'Cancelar';
  confirmDialog.variant = 'danger';
  
  confirmDialog.onConfirm = async () => {
    isSaving.value = true;
    
    try {
      await axios.delete(`/api/companies/${company.id}`);
      
      toast.success('Empresa excluída com sucesso!');
      
      await fetchCompanies();
      
      closeModal();
    } catch (error) {
      console.error('Erro ao excluir empresa:', error);
      
      if (error.response) {
        const errorMessage = error.response.data.message || 'Erro ao excluir empresa';
        toast.error(errorMessage);
      } else {
        toast.error('Erro de conexão. Verifique sua internet e tente novamente.');
      }
    } finally {
      isSaving.value = false;
    }
  };
  
  confirmDialog.onCancel = () => {
  };
  
  confirmDialogRef.value.open();
};

onMounted(() => {
  fetchCompanies();
});
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

.loading-state {
  text-align: center;
  padding: 3rem 1rem;
  
  p {
    color: #6b7280;
    font-size: 1rem;
  }
}

.companies-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.empty-state {
  text-align: center;
  color: #6b7280;
  font-size: 1rem;
  padding: 3rem 1rem;
}

</style>
