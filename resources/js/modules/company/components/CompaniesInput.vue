<template>
  <div class="companies-input">
    <input
        :type="inputType"
        :value="modelValue"
        @input="onInput"
        :class="inputClasses"
        v-bind="$attrs"
    />
  </div>
</template>
<script setup>
import {ref, computed} from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    inputType: {
        type: String,
        default: 'text'
    },
    size: {
        type: String,
        default: 'md' 
    },
    variant: {
        type: String,
        default: 'default'
    }
});

const emit = defineEmits(['update:modelValue']);

const onInput = (event) => {
    emit('update:modelValue', event.target.value);
};

const base = 'base-input';

const inputClasses = computed(() => [
  'base-input',
  `base-input--${props.variant}`
])
</script>

<style lang="scss" scoped>
.base-input {
  width: 100%;
  padding: .5rem .75rem;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-family: 'Inter', sans-serif;
  font-size: .95rem;
  color: #374151;
  line-height: 1.4;
  transition: border-color .2s ease, box-shadow .2s ease;

  &:focus {
    outline: none;
    border-color: #c9c9c9;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, .15);
  }

  &--default {
    border-color: #d1d5db;
  }

  &--danger {
    border-color: #dc3545;
    color: #dc3545;

    &:focus {
      box-shadow: 0 0 0 3px rgba(220, 53, 69, .2);
    }
  }
}
</style>