<template>
  <div class="pds-page1">
    <header class="pds-header">
      <div class="header-top-left">
        <div class="form-title">CS Form No. 212</div>
        <div class="form-subtitle">Revised 2017</div>
      </div>
      <div class="header-center">
        <h1 class="main-title">PERSONAL DATA SHEET</h1>
      </div>
      <div class="header-top-right">
        <div class="form-title">
          <label class="label-box">PDS VIEW (Page 1)</label>
        </div>
      </div>
    </header>

    <div class="warning-section">
      <span class="warning-label">WARNING:</span>
      <span class="warning-text">
        Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the employee/applicant.
      </span>
      <span class="warning-label">READ THE ATTACHED GUIDE TO FILING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM</span>
      <span class="instruction-text">
        Print in black ink. Write legibly and put "N/A" if not applicable. For dates, use MM/DD/YYYY format.
        <span class="do-not-abbreviate">DO NOT ABBREVIATE.</span>
      </span>
      <span class="box-note">(Do not fill up. For CSC use only)</span>
    </div>

    <section class="pds-section personal-info">
      <div class="section-title">
        <div class="title-text">I. PERSONAL INFORMATION</div>
      </div>
      <div class="form-row">
        <div class="form-field full-width">
          <label>1. SURNAME</label>
          <div class="data">{{ formData.last_name || '' }}</div>
        </div>
      </div>
      <div class="form-row name-row">
        <div class="form-field">
          <label>FIRST NAME</label>
          <div class="data">{{ formData.first_name || '' }}</div>
        </div>
        <div class="form-field">
          <label>MIDDLE NAME</label>
          <div class="data">{{ formData.middle_name || '' }}</div>
        </div>
        <div class="form-field small">
          <label>NAME EXTENSION (JR., SR)</label>
          <div class="data">{{ formData.name_extension || '' }}</div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-field date-of-birth">
          <label>2. DATE OF BIRTH (mm/dd/yyyy)</label>
          <div class="data">{{ formData.birth_date || '' }}</div>
        </div>
        <div class="form-field place-of-birth">
          <label>3. PLACE OF BIRTH</label>
          <div class="data">{{ formData.place_of_birth || '' }}</div>
        </div>
        <div class="form-field citizenship-field">
          <label>16. CITIZENSHIP</label>
          <div class="data checkbox-row">
            <span class="checkbox-container">
              <label>{{ formData.citizenship }}</label>
            </span>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-field">
          <label>4. SEX</label>
          <div class="data checkbox-row">
            <span class="checkbox-container">
              <label>{{ formData.sex }}</label>
            </span>
          </div>
        </div>
        <div class="form-field">
          <label>5. CIVIL STATUS</label>
          <div class="data checkbox-row">
            <span class="checkbox-container">
              <label>{{ formData.civil_status }}</label>
            </span>
          </div>
        </div>
        <div class="form-field large">
          <label>17. RESIDENTIAL ADDRESS</label>
          <div class="data address-grid">
            <div class="address-line">
              <div class="address-field">{{ formData.residential_address || '' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-field">
          <label>6. HEIGHT (m)</label>
          <div class="data">{{ formData.height || '' }}</div>
        </div>
        <div class="form-field">
          <label>7. WEIGHT (kg)</label>
          <div class="data">{{ formData.weight || '' }}</div>
        </div>
        <div class="form-field large">
          <label>18. PERMANENT ADDRESS</label>
          <div class="data address-grid">
            <div class="address-line">
              <div class="address-field">{{ formData.address || '' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-field">
          <label>8. BLOOD TYPE</label>
          <div class="data">{{ formData.blood_type || '' }}</div>
        </div>
        <div class="form-field">
          <label>19. TELEPHONE NO.</label>
          <div class="data">{{ formData.telephone_no || '' }}</div>
        </div>
        <div class="form-field">
          <label>20. MOBILE NO.</label>
          <div class="data">{{ formData.mobile_no || '' }}</div>
        </div>
        <div class="form-field">
          <label>21. E-MAIL ADDRESS (if any)</label>
          <div>{{ formData.email || '' }}</div>
        </div>
      </div>

      <div class="form-row id-numbers">
        <div class="form-field">
          <label>9. GSIS ID NO.</label>
          <div class="data">{{ formData.email_no || '' }}</div>
        </div>
        <div class="form-field">
          <label>10. PAG-IBIG ID NO.</label>
          <div class="data">{{ formData.pagibig_id_no || '' }}</div>
        </div>
        <div class="form-field">
          <label>11. PHILHEALTH NO.</label>
          <div class="data">{{ formData.philhealth_no || '' }}</div>
        </div>
        <div class="form-field">
          <label>12. SSS NO.</label>
          <div class="data">{{ formData.sss_no || '' }}</div>
        </div>
        <div class="form-field">
          <label>13. TIN NO.</label>
          <div class="data">{{ formData.tin_no || '' }}</div>
        </div>
        <div class="form-field">
          <label>14. AGENCY EMPLOYEE NO.</label>
          <div class="data">{{ formData.agency_employee_no || '' }}</div>
        </div>
      </div>
    </section>

    <section class="pds-section family-background">
      <div class="section-title">
        <div class="title-text">II. FAMILY BACKGROUND</div>
      </div>

      <div class="form-row spouse-row">
        <div class="form-field">
          <label>22. SPOUSE'S NAME</label>
          <div class="data">{{ spouse?.name || '' }}</div>
        </div>
      </div>
      <div class="form-row spouse-details-row">
        <div class="form-field">
          <label>OCCUPATION</label>
          <div class="data">{{ spouse?.occupation || '' }}</div>
        </div>
        <div class="form-field">
          <label>EMPLOYER/BUSINESS NAME</label>
          <div class="data">{{ spouse?.employer || '' }}</div>
        </div>
        <div class="form-field">
          <label>BUSINESS ADDRESS</label>
          <div class="data">{{ spouse?.business_address || '' }}</div>
        </div>
        <div class="form-field">
          <label>TELEPHONE NO.</label>
          <div class="data">{{ spouse?.telephone_no || '' }}</div>
        </div>
      </div>

      <div class="form-row parent-row">
        <div class="form-field">
          <label>23. FATHER'S NAME</label>
          <div class="data">{{ father?.name || '' }}</div>
        </div>
      </div>
      <div class="form-row parent-row">
        <div class="form-field">
          <label>24. MOTHER'S NAME</label>
          <label>SURNAME</label>
          <div class="data">{{ mother?.name || '' }}</div>
        </div>
      </div>

      <div class="children-container">
        <div class="form-row children-header">
          <div class="form-field label-col">
            <label>25. NAME OF CHILDREN (Write full name and b-day all)</label>
          </div>
          <div class="form-field date-col">
            <label>DATE OF BIRTH (mm/dd/yyyy)</label>
          </div>
        </div>
        <div v-for="(child, index) in children" :key="index" class="form-row children-row">
          <div class="form-field label-col">
            <div class="data">{{ child.name }}</div>
          </div>
          <div class="form-field date-col">
            <div class="data">{{ child.birth_date }}</div>
          </div>
        </div>
        <template v-if="!children || children.length < 4">
          <div class="form-row children-row" v-for="n in (4 - (children ? children.length : 0))" :key="'child-placeholder-' + n">
            <div class="form-field label-col"><div class="data"></div></div>
            <div class="form-field date-col"><div class="data"></div></div>
          </div>
        </template>
      </div>
    </section>

    <section class="pds-section educational-background">
      <div class="section-title">
        <div class="title-text">III. EDUCATIONAL BACKGROUND</div>
      </div>

      <div class="edu-grid header-row">
        <div class="level-col">LEVEL</div>
        <div class="school-col">NAME OF SCHOOL <br>(Write in full)</div>
        <div class="degree-col">BASIC EDUCATION/DEGREE/COURSE <br>(Write in full)</div>
        <div class="period-col">PERIOD OF ATTENDANCE</div>
        <div class="units-col">
          HIGHEST LEVEL/UNITS EARNED <br>(if not graduated)
        </div>
        <div class="year-col">YEAR GRADUATED</div>
        <div class="honors-col">SCHOLARSHIP/<br> ACADEMIC <br>HONORS RECEIVED</div>
      </div>

    <template v-for="(levelData, levelName) in groupedEducations" :key="levelName">
        <div v-if="levelData.length > 0">
            <div class="edu-grid data-row" v-for="(edu, index) in levelData" :key="levelName + '-' + index">
                <div class="level-cell">{{ levelName.toUpperCase() }}</div>
                <div>{{ edu.school_name || '' }}</div>
                <div>{{ edu.degree_course || '' }}</div>
                <div class="attendance-period">
                <span>{{ edu.from ? edu.from.substring(0, 4) : '' }} - {{ edu.to ? edu.to.substring(0, 4) : '' }}</span>
                </div>
                <div>{{ edu.units_earned || '' }}</div>
                <div>{{ edu.year_graduated || '' }}</div>
                <div>{{ edu.honors || '' }}</div>
            </div>
        </div>
    </template>
    </section>

    <footer class="pds-footer">
      <div class="footer-left">
        <div class="form-field signature">
          <label>SIGNATURE</label>
        </div>
      </div>
      <div class="footer-right">
        <div class="form-field date">
          <label>DATE</label>
        </div>
      </div>
    </footer>
    <div class="footer-note">
      CS FORM 212 (Revised 2017), Page 1 of 4
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  formData: {
    type: Object,
    default: () => ({})
  }
});

// Map family roles
const spouse = computed(() => props.formData.family_members?.find(m => m.relationship?.toLowerCase() === 'spouse') || null);
const father = computed(() => props.formData.family_members?.find(m => m.relationship?.toLowerCase() === 'father') || null);
const mother = computed(() => props.formData.family_members?.find(m => m.relationship?.toLowerCase() === 'mother') || null);
const children = computed(() => props.formData.family_members?.filter(m => m.relationship?.toLowerCase() === 'child') || []);

// Group educations by level
const groupedEducations = computed(() => {
  const levels = ['elementary', 'secondary', 'vocational/trade course', 'college', 'graduate studies'];
  const grouped = {};
  levels.forEach(level => {
    grouped[level] = props.formData.educations?.filter(edu => edu.level?.toLowerCase() === level) || [];
  });
  return grouped;
});

// Helper to capitalize string data
const capitalize = (value) => {
  if (!value) return '';
  return String(value).toUpperCase();
};
</script>

<style scoped>
.pds-page1 {
  font-family: Arial, sans-serif;
  font-size: 10px;
  line-height: 1.2;
  color: #000;
  border: 1px solid #000;
  padding: 0.2rem; 
  width: 8.5in;
  height: 12in;
  margin: auto;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
}

@media print {
  @page {
    size: legal portrait;
    margin: 0.5in;
  }
  body * {
    visibility: hidden;
  }
  .pds-page1, .pds-page1 * {
    visibility: visible;
  }
  .pds-page1 {
    position: absolute;
    left: 0;
    top: 0;
    width: 8.5in;
    height: 14in;
    border: none; 
    padding: 0;
    margin: 0;
    box-sizing: border-box;
  }
  .pds-page1 section {
    page-break-inside: avoid;
  }
}

.pds-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.3rem;
}
.header-top-left {
  text-align: left;
  line-height: 1;
}
.form-title {
  font-size: 10px;
}
.form-subtitle {
  font-size: 8px;
  margin-top: -2px;
}
.header-center {
  flex-grow: 1;
  text-align: center;
}
.main-title {
  font-size: 20px;
  font-weight: bold;
  margin: 0;
}
.header-top-right {
  text-align: right;
}
.label-box {
  border: 1px solid #000;
  padding: 2px 5px;
  font-size: 10px;
}

.warning-section {
  font-size: 8px;
  border: 1px solid #000;
  padding: 3px;
  text-align: justify;
  margin-bottom: 0.3rem;
  line-height: 1.3;
}
.warning-label {
  font-weight: bold;
  margin-right: 5px;
}
.instruction-text {
  display: block;
  margin-top: 3px;
}
.do-not-abbreviate {
  font-weight: bold;
  font-style: italic;
  margin-left: 5px;
}
.box-note {
  border: 1px solid #000;
  padding: 1px 3px;
  display: inline-block;
  float: right;
  margin-left: 10px;
  background-color: #e0e0e0;
}

.pds-section {
  border: 1px solid #000;
  margin-bottom: 0.3rem;
  page-break-inside: avoid;
  display: flex;
  flex-direction: column;
}
.section-title {
  display: flex;
  background-color: #d3d3d3;
  padding: 2px 5px;
  font-weight: bold;
  font-size: 10px;
  border-bottom: 1px solid #000;
  text-transform: uppercase;
}
.section-title .title-text {
  flex-grow: 1;
}

.form-row {
  display: flex;
  border-bottom: 1px solid #000;
}
.form-row:last-of-type {
  border-bottom: none;
}
.form-field {
  flex: 1;
  display: flex;
  flex-direction: column;
  border-right: 1px solid #000;
  padding: 2px 3px; 
  position: relative;
  min-height: 25px; 
  justify-content: flex-end; 
}
.form-field:last-child {
  border-right: none;
}
.form-field label {
  font-size: 7px; 
  text-transform: uppercase;
  color: #555;
  line-height: 1; 
  margin-bottom: 1px; 
}
.form-field .data {
  font-weight: bold;
  min-height: 1.1em; 
  text-transform: uppercase; 
  word-break: break-word; 
}
.full-width {
  flex: 4;
}
.name-row .form-field {
  flex: 3; 
}
.name-row .small {
  flex: 1; 
}
.small {
  flex: 1;
}
.large {
  flex: 3;
}

.date-of-birth, .place-of-birth {
  flex: 2;
}
.citizenship-field {
  flex: 3;
}
.citizenship-details {
  margin-top: 2px;
  padding-left: 10px;
}
.citizenship-details .note {
  font-size: 7px;
  font-style: italic;
  color: #777;
  margin-top: 2px;
  line-height: 1;
}

.address-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0;
  min-height: 60px;
}
.address-grid .address-line {
  display: flex;
  flex-direction: column;
  border-bottom: 1px dotted #000;
  padding-bottom: 1px;
}
.address-grid .address-line:last-of-type {
  border-bottom: none;
}
.address-grid .address-field {
  flex-grow: 1;
  min-height: 1.1em;
}
.address-grid .address-label {
  font-size: 7px;
  color: #555;
  text-transform: uppercase;
  line-height: 1;
}
.full-span {
  grid-column: span 2;
  border-bottom: none;
}

.checkbox-row, .sub-checkbox-row {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 5px;
  min-height: auto;
  line-height: 1;
}
.checkbox-container {
  display: flex;
  align-items: center;
  gap: 2px;
  white-space: nowrap;
}
.checkbox {
  width: 8px;
  height: 8px;
  border: 1px solid #000;
  text-align: center;
  font-size: 6px;
  line-height: 7px;
  flex-shrink: 0;
}
.checkbox-container label {
  font-size: 8px;
  color: #000;
  text-transform: none;
  margin-bottom: 0;
}

.id-numbers .form-field {
  min-height: 25px;
}

.family-member {
  border-bottom: 1px solid #000;
}
.family-member:last-of-type {
  border-bottom: none;
}
.spouse-row .form-field, .parent-row .form-field {
  flex: 1;
  min-height: 25px;
}
.spouse-row .small {
  flex: 0.7;
}
.spouse-details-row .form-field {
  flex: 1;
  min-height: 25px;
}
.children-container {
  border-bottom: 1px solid #000;
  flex-grow: 1; 
  display: flex;
  flex-direction: column;
}
.children-header {
  border-bottom: 1px solid #000;
  flex-shrink: 0;
}
.children-header .form-field {
  min-height: 25px;
  flex: 1; 
}
.children-header .label-col {
  flex: 2;
}
.children-header .date-col {
  flex: 1;
}

.children-row {
  flex: 1; 
  min-height: 20px; 
}
.children-row .form-field {
  justify-content: center; 
}
.children-row .label-col {
  flex: 2;
}
.children-row .date-col {
  flex: 1;
}

.note-children {
  font-size: 8px;
  text-align: right;
  padding: 2px;
  border-top: 1px solid #000; 
  margin-top: auto; 
  flex-shrink: 0;
}

.educational-background .section-title {
  border-bottom: 1px solid #000;
}
.edu-grid {
  display: grid;
  grid-template-columns: 8% 22% 20% 17% 15% 9% 9%; 
  border-bottom: 1px solid #000;
}
.edu-grid > div {
  border-right: 1px solid #000;
  padding: 2px 3px;
  word-break: break-word;
  display: flex;
  align-items: center;
}
.edu-grid.header-row {
  font-weight: bold;
  text-align: center;
  font-size: 8px;
  padding: 0;
  background-color: #d3d3d3;
}
.edu-grid.header-row > div {
  justify-content: center; 
  align-items: center;
}
.edu-grid.data-row {
  min-height: 20px;
}
.edu-grid.data-row > div {
  text-transform: uppercase;
}
.edu-grid .attendance-period {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 0;
}
.edu-grid .attendance-period span {
  display: block;
  text-align: center;
  line-height: 1;
}
.edu-grid.placeholder-row {
  border-bottom: 1px dotted #000;
}
.edu-grid.placeholder-row > div {
  border-bottom: 1px dotted #000; 
  min-height: 20px;
}
.edu-grid.placeholder-row:last-of-type > div {
    border-bottom: none;
}
.note-edu {
  font-size: 8px;
  text-align: right;
  padding: 2px;
  border-top: 1px solid #000;
  margin-top: auto;
  flex-shrink: 0;
}

.pds-footer {
  display: flex;
  border: 1px solid #000;
  font-size: 8px;
  margin-top: auto;
  flex-shrink: 0;
}
.footer-left, .footer-right {
  flex: 1;
}
.footer-left .signature {
  border-right: 1px solid #000;
  min-height: 30px;
  padding: 5px;
  display: flex;
  align-items: flex-end; 
}
.footer-right .date {
  min-height: 30px;
  padding: 5px;
  display: flex;
  align-items: flex-end; 
}
.footer-left .signature label,
.footer-right .date label {
  width: 100%;
  text-align: center;
  border-top: 1px solid #000;
  padding-top: 2px;
}
.footer-note {
  font-size: 8px;
  text-align: right;
  margin-top: 2px;
  flex-shrink: 0;
}
</style>