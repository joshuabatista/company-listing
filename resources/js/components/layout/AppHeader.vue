<template>
  <header class="app-header">
    <div class="header-content">
      <div class="header-left">
        <img :src="logo" alt="Logo" class="logo" />
      </div>

      <div class="header-right">
        <span class="user-name">Joshua Batista</span>
        <User />
      </div>
    </div>
  </header>
</template>


<script setup>
import logo from '../../../images/opealogo.png';
import { User } from 'lucide-vue-next';

</script>

<style lang="scss" scoped>
.app-header {
  border-bottom: 1px solid #e5e7eb;
  background-color: #ffffff;
}

.header-content {
  display: grid;
  grid-template-columns: auto 1fr;
  align-items: center;
  padding: 1rem 2rem;

  .header-left {
    display: flex;
    align-items: center;

    .logo {
      height: 36px;
    }
  }

  .header-right {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: .75rem;

    .user-name {
      font-weight: 500;
      font-size: .95rem;
    }

    .icon-user {
      font-size: 1.2rem;
    }
  }
}

</style>