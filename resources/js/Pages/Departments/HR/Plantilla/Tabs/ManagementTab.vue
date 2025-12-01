<template>
    <div class="management-header">
        <Input class="search-inp" name="search" placeholder="Search..." size="md" />
        <div class="button-group">
            <Button variant="secondary"><img class="icon-print" src="images/icons/printer.png" /> Print Report</Button>
            <Button variant="primary"><img class="icon-add" src="images/icons/plus.png" /> Add Position</Button>
        </div>
    </div>

    <div class="management-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Item No</th>
                    <th>SG</th>
                    <th>Monthly Rate</th>
                    <th>Eligibility</th>
                    <th>Education</th>
                    <th>Department</th>
                    <th>Division</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>test</td>
                    <td>test</td>
                    <td>test</td>
                    <td>test</td>
                    <td>test</td>
                    <td>test</td>
                    <td>test</td>
                    <td>
                        <Tooltip text="View Details" placement="top">
                            <Button class="btn-action" variant="secondary" size="xs">
                                <img class="icon" src="images/icons/view.png" />
                            </Button>
                        </Tooltip>

                        <Button class="btn-action" variant="secondary" size="xs">
                            <img class="icon" src="images/icons/edit.png" />
                        </Button>
                        <Button class="btn-action" variant="secondary" size="xs">
                            <img class="icon" src="images/icons/delete.png" />
                        </Button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script setup>
    import Button from "@/components/Common/Button.vue";
    import Input from "@/components/Common/Input.vue";
    import Tooltip from "@/components/Common/Tooltip.vue";
</script>
