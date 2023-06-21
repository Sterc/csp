<template>
  <div>
    <div class="d-flex align-items-center justify-content-between">
      <div class="h4 m-0 cursor-pointer" @click="checkGroup(record)">{{ record.title }}</div>
      <b-form-checkbox
        :model-value="record.active"
        switch
        style="font-size: 1.25rem"
        class="m-0 cursor-pointer"
        @click="checkGroup(record)"
      />
    </div>
    <div class="ps-md-2">
      <div
        v-for="directive in record.directives"
        :key="directive.key"
        class="d-flex align-items-center justify-content-between mt-2"
      >
        <div class="pe-md-3">
          <div class="fw-bold cursor-pointer" @click="checkDirective(directive)">{{ directive.key }}</div>
          <div class="small text-muted">{{ directive.description }}</div>
        </div>
        <b-form-checkbox
          :model-value="directive.active"
          switch
          class="m-0 cursor-pointer"
          @click="checkDirective(directive)"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {Group, Directive} from '../types'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: Object as PropType<Group>,
    default() {
      return {}
    },
  },
})

const record = computed({
  get() {
    return props.modelValue
  },
  set(newValue) {
    emit('update:modelValue', newValue)
  },
})

function checkGroup(group: Group) {
  group.active = !group.active
  group.directives?.forEach((i) => {
    i.active = group.active
  })
  usePatch('admin/groups/' + group.id, {active: group.active})
}

function checkDirective(directive: Directive) {
  directive.active = !directive.active
  usePatch('admin/directives/' + directive.key, {active: directive.active})
}
</script>
