<template>
    <section id="plantilla">
        <div class="plantilla-tabs">
            <!-- Tab labels -->
            <div class="tab-labels">
                <button v-for="tab in tabs" :key="tab.id" class="tab-btn" :class="{ active: activeTab === tab.id }" @click="activeTab = tab.id">
                    {{ tab.label }}
                </button>
            </div>

            <!-- Tab contents -->
            <div class="tab-contents">
                <div class="content management" :class="{ active: activeTab === 'management' }">
                    <ManagementTab />
                </div>

                <div class="content" :class="{ active: activeTab === 'dept_and_div' }">
                    <p>Here you can manage department and division.</p>
                </div>

                <div class="content" :class="{ active: activeTab === 'plantilla_of_personel' }">
                    <p>Here you can add plantilla of personel.</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    import { ref } from "vue";
    import ManagementTab from "./Tabs/ManagementTab.vue";
    // Default active tab = management
    const activeTab = ref("management");

    const tabs = [
        {
            id: "management",
            label: "Plantilla Management",
        },
        {
            id: "dept_and_div",
            label: "Department & Division",
        },
        {
            id: "plantilla_of_personel",
            label: "Plantilla of Personel",
        },
    ];
</script>
