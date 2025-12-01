<template>
  <header class="header">
    <button class="menu-btn" @click="toggleSidebar">☰</button>

    <div class="logo">
      <img src="images/logo.png" alt="Logo" />
      <div class="logo-text">
        <strong>HRMS</strong>
        <small>LGU LIBACAO</small>
      </div>
    </div>

    <Dropdown placement="bottom-right">
      <template #trigger>
        <div class="user" ref="userDiv">
          <span>Hello, {{ user?.name }}</span>
          <img src="images/profile.webp" alt="User Avatar" />
        </div>
      </template>
      <!-- Dropdown menu items -->
      <a href="#">Account Settings</a>
      <a href="#">Account Management</a>
      <a href="#" @click.prevent="logout">Logout</a>
    </Dropdown>
  </header>
</template>

<script setup>
import { computed } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import Dropdown from "@/components/Common/Dropdown.vue";

// Correctly read reactive user from Inertia props
const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

const emit = defineEmits(["toggle-sidebar"]);

function toggleSidebar() {
  emit("toggle-sidebar");
}

async function logout() {
  try {
    await axios.post("/logout");
    window.location.href = "/login";
  } catch (error) {
    console.error("Logout failed:", error);
  }
}
</script>
