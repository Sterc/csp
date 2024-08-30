<template>
  <div>
    <b-tabs v-if="items.length" v-model="tab" underline content-class="main-wrapper border-start border-end">
      <b-tab title="Global">
        <b-alert variant="light" :model-value="true">
          Use these switches below to globally enable/disable directives.
        </b-alert>
        <group v-for="group in items" :key="group.id" :model-value="group" class="mt-4" />
      </b-tab>
      <b-tab v-for="group in items" :key="group.id" :title="ucfirst(group.key)">
        <b-alert variant="light" :model-value="true">
          {{ group.description }}
        </b-alert>
        <directive v-for="directive in group.directives" :key="directive.key" :model-value="directive" class="mt-4" />
      </b-tab>
    </b-tabs>
  </div>
</template>

<script setup>
const url = 'admin/groups'
const items = ref([])
const tab = ref(0)

onMounted(load)

async function load() {
  const {data, error} = await useGet(url)
  if (!error.value) {
    items.value = data.value.rows
  }
}

function ucfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1)
}
</script>
