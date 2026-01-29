<template>
    <div class="card-add-company">
        <div class="icon-wrapper">
            <Building />
        </div>
        <div>
            <span>Adicionar Empresa</span>
        </div>
    </div>
</template> 
<script setup>
import { Building } from 'lucide-vue-next';
</script>

<style lang="scss" scoped>
    .card-add-company {
        position: relative;
        width: 100%;
        height: 70px;
        border-radius: 80px 12px 12px 80px;
        border: 1px solid #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center; 
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;

        &:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }
    }
    .lucide {
        width: 100%;
        height: 100%;
        color: #6b7280;
    }


    .icon-wrapper {
        position: absolute;
        left: 12px;

        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f3f4f6;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #d1d5db;
        padding: 10px;
    }
</style>