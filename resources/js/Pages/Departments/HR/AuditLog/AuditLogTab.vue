<template>
    <section id="audit-log">
      <div class="audit-log-header">
        <Input
          class="search-inp" 
          name="search" 
          placeholder="Search Logs..." 
          size="md" 
        />
        <!-- <Button variant="primary">
          <img class="icon" src="images/icons/plus.png"/> Add Account
        </Button> -->
      </div>
  
      <div class="audit-log-table-wrapper">
        <table>
          <thead>
            <tr>
              <th>User ID</th>
              <th>User Name</th>
              <th>Date & Time</th>
              <th>Action Performed</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>test</td>
              <td>test</td>
              <td>test</td>
              <td>test</td>
              <td>
                <Tooltip text="View Details" placement="top">
                  <Button class="btn-action" variant="secondary" size="xs">
                    <img class="icon" src="images/icons/view.png"/>
                  </Button>
                </Tooltip>
                
                <Button class="btn-action" variant="secondary" size="xs">
                  <img class="icon" src="images/icons/edit.png"/>
                </Button>
                <Button class="btn-action" variant="secondary" size="xs">
                  <img class="icon" src="images/icons/delete.png"/>
                </Button>
              </td>
            </tr>
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