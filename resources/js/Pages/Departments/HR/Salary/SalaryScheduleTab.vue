<template>
    <section>
        <div class="salary-schedule-log-header">
            <Input class="search-inp" name="search" placeholder="Search Salary..." size="md" />
            <div class="button-group">
                <Button variant="secondary"><img class="icon-upload" src="images/icons/upload.png" /> Upload Excel</Button>
                <Button variant="primary"><img class="icon-save" src="images/icons/diskette.png" /> Save & Changes</Button>
            </div>
        </div>
        <div class="salary-schedule-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Salary Grade</th>
                        <!-- Steps header -->
                        <th>Step 1</th>
                        <th>Step 2</th>
                        <th>Step 3</th>
                        <th>Step 4</th>
                        <th>Step 5</th>
                        <th>Step 6</th>
                        <th>Step 7</th>
                        <th>Step 8</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example rows -->
                    <tr>
                        <td class="salary-grade">1</td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                    </tr>
                    <tr>
                        <td class="salary-grade">2</td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                        <td><Input  size="sm" type="number" /></td>
                    </tr>
                    <!-- Repeat rows up to 33 -->
                </tbody>
            </table>
        </div>
    </section>
</template>
<script setup>
    import Button from "@/Components/Common/Button.vue";
    import Input from "@/Components/Common/Input.vue";
    import Tooltip from "@/Components/Common/Tooltip.vue";
</script>
