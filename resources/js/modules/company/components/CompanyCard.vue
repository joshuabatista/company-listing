<template>
    <div class="company-card">
        <div class="icon-wrapper">
            <Building />
        </div>
        <div class="company-info">
            <h3 class="company-name">{{ company.razao_social }}</h3>
            <div class="company-details">
                <p class="cnpj">CNPJ: {{ formatCnpj(company.cnpj) }}</p>
                <p v-if="company.cidade && company.estado" class="location">
                    {{ company.cidade }} - {{ company.estado }}
                </p>
                <p v-if="company.email" class="email">{{ company.email }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Building } from 'lucide-vue-next';

const props = defineProps({
    company: {
        type: Object,
        required: true
    }
});

const formatCnpj = (cnpj) => {
    if (!cnpj) return '';
    return cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
};
</script>

<style lang="scss" scoped>
.company-card {
    position: relative;
    width: 100%;
    min-height: 100px;
    border-radius: 80px 12px 12px 80px;
    border: 1px solid #d1d5db;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem 1rem 5rem;
    background: white;
    transition: all 0.2s ease;

    &:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
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

    .lucide {
        width: 100%;
        height: 100%;
        color: #6b7280;
    }
}

.company-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.company-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.company-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;

    p {
        margin: 0;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .cnpj {
        font-weight: 500;
        color: #4b5563;
    }

    .location {
        color: #6b7280;
    }

    .email {
        color: #6b7280;
    }
}

@media (max-width: 768px) {
    .company-card {
        padding: 1rem 1rem 1rem 4.5rem;
    }

    .company-name {
        font-size: 1rem;
    }

    .company-details p {
        font-size: 0.8125rem;
    }
}
</style>
