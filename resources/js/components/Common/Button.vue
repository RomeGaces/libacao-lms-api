<template>
  <button
    :class="['btn', variantClass, sizeClass]"
    :disabled="disabled"
  >
    <slot />
  </button>
</template>
  
  <script setup>
  import { computed } from "vue"
  
  const props = defineProps({
    variant: { type: String, default: "primary" }, // primary, secondary, danger
    size: { type: String, default: "md" }, // sm, md, lg
    disabled: { type: Boolean, default: false }
  })
  
  const variantClass = computed(() => {
    switch (props.variant) {
      case "secondary": return "btn-secondary"
      case "danger": return "btn-danger"
      default: return "btn-primary"
    }
  })
  
  const sizeClass = computed(() => {
    switch (props.size) {
      case "xs": return "btn-xs"
      case "sm": return "btn-sm"
      case "lg": return "btn-lg"
      default: return "btn-md"
    }
  })
  </script>
  
  