<template>
  <div v-if="visible" class="modal-overlay">
    <div class="modal-content">
      <header class="modal-header">
        <h2>PDS VIEW (Page 1)</h2>
        <button class="close-btn" @click="close">&times;</button>
      </header>

      <main class="modal-body">
        <Page1 :formData="formData" />
      </main>

      <footer class="modal-footer">
        <button @click="printPage" class="print-btn">PRINT AS PDF</button>
        <button @click="close">CLOSE</button>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick } from "vue";
import Page1 from "./Pages/Page1.vue";
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';

defineProps({
  formData: {
    type: Object,
    default: () => ({})
  }
});

const visible = ref(false);

function open() {
  visible.value = true;
}

function close() {
  visible.value = false;
}

async function printPage() {
  try {
    const element = document.querySelector('.modal-body .pds-page1');
    if (!element) {
      console.error('PDS form element not found.');
      return;
    }

    // Use html2canvas to render the form as a canvas
    const canvas = await html2canvas(element, {
      scale: 2, // Increase resolution for better quality
      useCORS: true, // Handle images from external sources
    });

    // Get the dimensions of the canvas and the PDF
    const imgData = canvas.toDataURL('image/png');
    const imgWidth = 210; // A4 width in mm
    const pageHeight = 297; // A4 height in mm
    const imgHeight = canvas.height * imgWidth / canvas.width;
    let heightLeft = imgHeight;

    const pdf = new jsPDF('p', 'mm', 'a4');
    let position = 0;

    // Add the image to the PDF
    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
    heightLeft -= pageHeight;

    // Handle multi-page documents if necessary
    while (heightLeft >= 0) {
      position = heightLeft - imgHeight;
      pdf.addPage();
      pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
      heightLeft -= pageHeight;
    }

    // Save the PDF
    pdf.save('personal-data-sheet.pdf');

  } catch (error) {
    console.error("PDF generation failed:", error);
    alert("Failed to generate PDF. Please try again.");
  }
}

// expose open/close/print to parent
defineExpose({
  open,
  close,
  printPage
});
</script>

<style scoped>
/* MODAL STYLING (The print button now generates a PDF) */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal-content {
  background: white;
  width: 90%;
  max-width: 1200px;
  max-height: 90vh;
  overflow-y: auto;
  border-radius: 6px;
  padding: 1rem;
}
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.close-btn {
  font-size: 1.5rem;
  background: none;
  border: none;
  cursor: pointer;
}
.modal-footer {
  margin-top: 1rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}
.print-btn {
  background: #2d89ef;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}
</style>