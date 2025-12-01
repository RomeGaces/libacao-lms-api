<template>
    <div class="dashboard">
        <!-- Sidebar -->
        <Sidebar :isSidebarOpen="isSidebarOpen" :tabs="tabs" :activeTab="activeTab" @close="isSidebarOpen = false" @switch-tab="switchTab" />

        <!-- Header -->
        <Header @toggle-sidebar="toggleSidebar" />

        <!-- Main content -->
        <main class="main">
            <h1 id="page-title">{{ currentTab.label }}</h1>

            <!-- Dashboard Tab -->
            <section v-show="activeTab === 'dashboard'" class="tab-content">
                <DashboardTab />
            </section>

            <!-- Employee Tab -->
            <section v-show="activeTab === 'employee'" id="employee">
                <EmployeeTab />
            </section>

            <!-- Plantilla Tab -->
            <section v-show="activeTab === 'plantilla'" class="tab-content">
                <PlantillaTab />
            </section>

            <!-- Salary Tab -->
            <section v-show="activeTab === 'salary'" class="tab-content">
                <SalaryScheduleTab />
            </section>

            <!-- Attendance Tab -->
            <section v-show="activeTab === 'attendance'" class="tab-content">
                <p>Attendance Management content goes here.</p>
            </section>

            <!-- Documents Tab -->
            <section v-show="activeTab === 'documents'" class="tab-content">
                <p>Documents content goes here.</p>
            </section>

            <!-- Leave Tab -->
            <section v-show="activeTab === 'leave'" class="tab-content">
                <p>Leave Management content goes here.</p>
            </section>

            <!-- Logs Tab -->
            <section v-show="activeTab === 'logs'" class="tab-content">
                <AuditLogTab />
            </section>

            <!-- Account Tab -->
            <section v-show="activeTab === 'account'" class="tab-content">
                <AccountTab />
            </section>

        </main>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import Header from '@/components/Header/Header.vue';
    import Sidebar from '@/components/Sidebar/Sidebar.vue';
    // import EmployeeTab from './Tabs/EmployeeTab.vue';
    // import DashboardTab from "./Tabs/DashboardTab.vue";
    // import AccountTab from "./Tabs/AccountTab.vue";
    // import AuditLogTab from "./Tabs/AuditLogTab.vue";
    // import SalaryScheduleTab from "./Tabs/SalaryScheduleTab.vue";
    // import PlantillaTab from "./Tabs/PlantillaTab.vue";

    import AccountTab from "./Account/AccountTab.vue";
    import AuditLogTab from "./AuditLog/AuditLogTab.vue";
    import EmployeeTab from "./Employee/EmployeeTab.vue";
    import SalaryScheduleTab from "./Salary/SalaryScheduleTab.vue";
    import DashboardTab from "./Dashboard/DashboardTab.vue";
    import PlantillaTab from "./Plantilla/PlantillaTab.vue";


    // Sidebar tabs
    const tabs = [
        { id: "dashboard", label: "Dashboard" },
        { id: "employee", label: "Employee Management" },
        { id: "plantilla", label: "Plantilla Management" },
        { id: "salary", label: "Salary Schedule" },
        { id: "attendance", label: "Attendance Management" },
        { id: "documents", label: "Documents" },
        { id: "leave", label: "Leave Management" },
        { id: "logs", label: "Audit Logs" },
        { id: "account", label: "Account Management" },
    ];

    const activeTab = ref("dashboard"); // default tab
    const isSidebarOpen = ref(false);

    // Computed current tab object for header title
    const currentTab = computed(() => tabs.find((tab) => tab.id === activeTab.value));

    function toggleSidebar() {
        isSidebarOpen.value = !isSidebarOpen.value;
    }

    function switchTab(tabId) {
        activeTab.value = tabId;
    }
</script>
