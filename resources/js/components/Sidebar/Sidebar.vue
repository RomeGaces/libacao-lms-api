<template>
    <aside class="sidebar" :class="{ open: isSidebarOpen }">
        <button class="close-btn" @click="$emit('close')">×</button>
        <nav>
            <a
                v-for="tab in tabs"
                :key="tab.id"
                :class="{ active: activeTab === tab.id }"
                @click="selectTab(tab.id)"
            >
                {{ tab.label }}
            </a>
        </nav>
    </aside>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    isSidebarOpen: Boolean,
    tabs: Array,
    activeTab: String, // current active tab
});

const emit = defineEmits(['switch-tab', 'close']);

function selectTab(tabId) {
    emit('switch-tab', tabId);
    emit('close'); // optional: close sidebar on mobile
}
</script>
