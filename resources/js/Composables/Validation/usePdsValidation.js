import { ref, computed } from "vue";
import { useForm } from "vee-validate";
import * as yup from "yup";

export function usePdsValidation(activeTab, initialFormData) {
    const schemas = {
        personal: yup.object({
            first_name: yup.string().nullable(),
            last_name: yup.string().required("Last name is required."),
            middle_name: yup.string().nullable(),
            name_extension: yup.string().nullable(),
            birth_date: yup
                .date()
                .required("Date of birth is required.")
                .transform((value, original) => (original === "" ? null : value)),
            place_of_birth: yup.string().nullable(),
            sex: yup.string().nullable(),
            civil_status: yup.string().nullable(),
            citizenship: yup.string().nullable(),
            height: yup.string().nullable(),
            weight: yup.string().nullable(),
            blood_type: yup.string().nullable(),
            email_no: yup.string().nullable(),
            pagibig_id_no: yup.string().nullable(),
            philhealth_no: yup.string().nullable(),
            sss_no: yup.string().nullable(),
            tin_no: yup.string().nullable(),
            agency_employee_no: yup.string().nullable(),
            residential_address: yup.string().nullable(),
            residential_zip: yup.string().nullable(),
            permanent_address: yup.string().required("Permanent address is required"),
            permanent_zip: yup.string().nullable(),
            telephone_no: yup.string().nullable(),
            mobile_no: yup.string().nullable(),
            email: yup.string().nullable().email("Invalid email format"),
            ctc_number: yup.string().nullable(),
            ctc_place_of_issuance: yup.string().nullable(),
            ctc_date_of_issuance: yup
                .date()
                .required("CTC issuance date is required.")
                .transform((value, original) => (original === "" ? null : value)),
            plantilla_id: yup.number().nullable(),
            department_id: yup.number().nullable(),
        }),
        family: yup.object({
            // Spouse
            spouse: yup.object({
                name: yup.string().nullable(),
                birth_date: yup
                    .date()
                    .required("Birth date is required.")
                    .transform((value, original) => (original === "" ? null : value)),
                occupation: yup.string().nullable(),
                employer: yup.string().nullable(),
                business_address: yup.string().nullable(),
                telephone_no: yup.string().nullable(),
                relationship: yup.string().nullable(),
            }),

            // Father
            father: yup.object({
                name: yup.string().nullable(),
                birth_date: yup
                    .date()
                    .required("Birth date is required.")
                    .transform((value, original) => (original === "" ? null : value)),
                occupation: yup.string().nullable(),
                employer: yup.string().nullable(),
                business_address: yup.string().nullable(),
                telephone_no: yup.string().nullable(),
                relationship: yup.string().nullable(),
            }),

            // Mother
            mother: yup.object({
                name: yup.string().required("Mother's name is required."),
                birth_date: yup
                    .date()
                    .required("Birth date is required.")
                    .transform((value, original) => (original === "" ? null : value)),
                occupation: yup.string().nullable(),
                employer: yup.string().nullable(),
                business_address: yup.string().nullable(),
                telephone_no: yup.string().nullable(),
                relationship: yup.string().nullable(),
            }),

            // Children
            children: yup.array().of(
                yup.object({
                    full_name: yup.string().nullable(),
                    birth_date: yup
                        .date()
                        .nullable()
                        .transform((value, original) => (original === "" ? null : value)),
                })
            ),
        }),
        education: yup.object({
            educations: yup.array().of(
                yup.object({
                    highest_educational_attainment: yup.string().nullable(),
                    school_name: yup.string().required("School name is required."),
                    degree_course: yup.string().nullable(),
                    year_graduated: yup.string().nullable(),
                    highest_level_units: yup
                        .number()
                        .transform((value, originalValue) => {
                            if (originalValue === "") return null;
                                return isNaN(value) ? null : value;
                            })
                        .typeError("Highest level units must be a number")
                        .required("Highest level units is required."),
                    attendance_from: yup
                        .date()
                        .transform((value, original) => {
                            return original ? new Date(original) : null;
                        })
                        .required("Attendance from date is required."),
                    attendance_to: yup
                        .date()
                        .transform((value, original) => {
                            return original ? new Date(original) : null;
                        })
                        .required("Attendance to date is required."),
                    scholarships: yup.string().nullable(),
                })
            ),
        }),
        eligibility: yup.object({
            eligibilities: yup.array().of(
                yup.object({
                    career_service: yup.string().nullable(),
                    rating: yup
                        .number()
                        .typeError("Rating must be a number")
                        .nullable(),
                    exam_date: yup
                        .date()
                        .required("Exam date is required.")
                        .transform((value, original) => (original === "" ? null : value)),
                    place_of_examination: yup.string().nullable(),
                    license_no: yup.string().nullable(),
                    date_of_validity: yup
                        .date()
                        .required("Validity date is required.")
                        .transform((value, original) => (original === "" ? null : value)),
                })
            ),
        }),
        work: yup.object({
            work_experiences: yup.array().of(
                yup.object({
                    inclusive_from: yup
                        .date()
                        .nullable()
                        .transform((value, original) => (original === "" ? null : value)),
                    inclusive_to: yup
                        .date()
                        .nullable()
                        .transform((value, original) => (original === "" ? null : value)),
                    position_title: yup.string().nullable(),
                    agency: yup.string().nullable(),
                    monthly_salary: yup
                        .number()
                        .transform((value, originalValue) => (originalValue === "" ? null : value))
                        .typeError("Monthly salary must be a number")
                        .required("Monthly salary is required."),
                    salary_job_grade: yup.string().nullable(),
                    status_of_appointment: yup.string().nullable(),
                    is_gov_service: yup.boolean().nullable(),
                })
            ),
        }),

        voluntary: yup.object({
            voluntary_works: yup.array().of(
                yup.object({
                    organization_name: yup.string().required("Organization name is required"),
                    organization_address: yup.string().nullable(),
                    from: yup
                        .date()
                        .nullable()
                        .transform((value, original) => (original === "" ? null : value)),
                    to: yup
                        .date()
                        .nullable()
                        .transform((value, original) => (original === "" ? null : value)),
                    hours: yup.number()
                        .transform((value, originalValue) => (originalValue === "" ? null : value))
                        .typeError("This must be a number")
                        .required("This is required."),
                    position: yup.string().nullable(),
                    nature_of_work: yup.string().nullable(),
                })
            ),
        }),
        training: yup.object({
            trainings: yup.array().of(
                yup.object({
                    title: yup.string().required("Training title is required"),
                    from: yup
                        .date()
                        .nullable()
                        .transform((value, original) => (original === "" ? null : value)),
                    to: yup
                        .date()
                        .required("Inclusive date to is required")
                        .transform((value, original) => (original === "" ? null : value)),
                    hours: yup
                        .number()
                        .transform((value, originalValue) => (originalValue === "" ? null : value))
                        .typeError("This must be a number")
                        .nullable(),
                    conducted_by: yup.string().nullable(),
                })
            ),
        }),
        other: yup.object({
            other_infos: yup.array().of(
                yup.object({
                    special_skills: yup.string().required("Skill/Hobbies required"),
                    distinctions: yup.string().nullable(),
                    membership: yup.string().nullable(),
                    sponsored_by: yup.string().nullable(),
                })
            ),
            references: yup.array().of(
                yup.object({
                    name: yup.string().nullable(),
                    telephone_no: yup.string().nullable(),
                    address: yup.string().nullable(),
                })
            ),
        }),
    };

    const formData = ref({
        ...initialFormData,
        educations: initialFormData.educations ?? [
            {
                highest_educational_attainment: "",
                school_name: "",
                attendance_from: "",
                attendance_to: "",
                scholarships: "",
                year_graduated: "",
                highest_level_units: "",
                degree_course: "",
            },
        ],
        children: initialFormData.children ?? [
            {
                full_name: "",
                birth_date: ""
            }
        ],

        references: initialFormData.references ?? [
            {
                name: "",
                telephone_no: "",
                address: ""
            }
        ]
    });

    const { handleSubmit, errors, validate } = useForm({
        validationSchema: computed(() => schemas[activeTab.value]),
        initialValues: formData.value,
    });

    // Check all tabs if valid
    async function validateAllTabs(formData) {
        const invalidTabs = []
      
        for (const key of Object.keys(schemas)) {
            try {
                await schemas[key].validate(formData, { abortEarly: false })
            } catch (err) {
                invalidTabs.push(key)
            }
        }
      
        return {
            valid: invalidTabs.length === 0,
            invalidTabs,
        }
    }

    return {
        formData,
        handleSubmit,
        errors,
        validate,
        schemas,
        validateAllTabs
    };
}
