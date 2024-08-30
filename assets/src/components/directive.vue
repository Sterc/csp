<template>
  <div>
    <div>
      <div class="d-flex align-items-center">
        <div class="pe-2">
          <div class="h5 m-0">{{ record.key }}</div>
          <div class="text-muted">{{ record.description }}</div>
        </div>
        <div class="ms-auto">
          <b-button v-if="canSave" variant="primary" class="me-2" @click="onSave">&check;</b-button>
          <b-button variant="secondary" size="sm" @click="onAdd"><i class="icon icon-plus"></i></b-button>
        </div>
      </div>
    </div>
    <div v-for="(value, idx) in record.value" :key="idx" class="d-flex align-items-center">
      <b-input-group class="mt-2" @keydown="onKeydown">
        <b-form-input v-model.trim="record.value[idx]" />
        <template #append>
          <b-button v-if="idx > 0" variant="danger" @click="onRemove(idx)">&times;</b-button>
        </template>
      </b-input-group>
    </div>
  </div>
</template>

<script setup lang="ts">
import {Directive} from '../types'

const saved = ref('')
const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: Object as PropType<Directive>,
    default() {
      return {}
    },
  },
})

const record = computed({
  get() {
    const value = props.modelValue
    if (!value.value) {
      value.value = ['']
    }
    return props.modelValue
  },
  set(newValue) {
    emit('update:modelValue', newValue)
  },
})

function onAdd() {
  record.value.value?.push('')
}
function onRemove(idx: string | number) {
  record.value.value?.splice(Number(idx), 1)
}
const canSave = computed(() => {
  return JSON.stringify(record.value.value?.filter((i) => i !== '')) !== saved.value
})
async function onSave() {
  if (!canSave.value) {
    return
  }
  await usePatch('admin/directives/' + record.value.key, {value: record.value.value})
  setSaved()
}

function onKeydown(e: KeyboardEvent) {
  if (e.key === 'Enter' && canSave.value) {
    onSave()
  }
}

function setSaved() {
  saved.value = JSON.stringify(record.value.value?.filter((i) => i !== ''))
}

setSaved()
</script>
