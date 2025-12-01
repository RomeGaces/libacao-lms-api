<template>
    <section id="account">
      <div class="account-header">
        <Input
          class="search-inp" 
          name="search" 
          placeholder="Search Account..." 
          size="md" 
        />
        <Button variant="primary">
          <img class="icon" src="images/icons/plus.png"/> Add Account
        </Button>
      </div>
  
      <div class="account-table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Date of Birth</th>
              <th>Account No.</th>
              <th>Sex</th>
              <th>Civil Status</th>
              <th>Citizenship</th>
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