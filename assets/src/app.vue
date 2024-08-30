<template>
  <div class="overflow-auto vh-100 p-3">
    <h3>Content Security Policy</h3>

    <div class="mt-4">
      <router-view v-slot="{Component}">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </div>
  </div>
</template>

<script setup lang="ts"></script>
