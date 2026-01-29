<template>
    <AppHeader />
    <div class="linha-input">
        <CompaniesInput input-type="text" placeholder="Buscar empresa..."></CompaniesInput>
    </div>  
    <div class="adicionar-empresa">
        <CompaniesAddCompany></CompaniesAddCompany>
    </div>



</template>
<script setup>
import { ref } from 'vue';
import AppHeader from '../../../components/layout/AppHeader.vue';
import CompaniesInput from '../components/CompaniesInput.vue';
import CompaniesAddCompany from '../components/CompaniesAddCompany.vue';
</script>

<style lang="scss" scoped>

.linha-input {
  margin-top: 30px;
  display: flex;
  justify-content: end;
//   padding: 15px;
  padding: 0 15px 0 0;
}

.adicionar-empresa {
  display: flex;
  justify-content: center;
  padding: 0 15px 0 15px;
  margin-top: 10px;
}
</style>