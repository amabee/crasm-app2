// START OF USER CONTROL FUNCTIONS

function validateForm(form) {
  const requiredFields = form.querySelectorAll("[required]");
  let isValid = true;

  requiredFields.forEach((field) => {
    if (!field.value.trim()) {
      isValid = false;
      field.classList.add("is-invalid");
    } else {
      field.classList.remove("is-invalid");
    }
  });

  if (!isValid) {
    Swal.fire({
      icon: "error",
      title: "Validation Error",
      text: "Please fill in all required fields",
    });
  }

  return isValid;
}

async function handleFormSubmit(form) {
  Swal.fire({
    title: "Processing...",
    html: "Please wait while we create the user",
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    const response = await fetch("functions.php", {
      method: "POST",
      body: new FormData(form),
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const contentType = response.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
      throw new Error("Server didn't return JSON");
    }

    const data = await response.json();

    Swal.close();

    if (data.status === "success") {
      await Swal.fire({
        icon: "success",
        title: "User Created Successfully!",
        html: `
          <div class="text-left">
            <p><strong>Username:</strong> ${data.data.username}</p>
            <p><strong>Temporary Password:</strong> ${data.data.password}</p>
            <p class="text-sm text-gray-600 mt-2">Please save these credentials before closing.</p>
          </div>
        `,
        confirmButtonText: "OK",
      });

      form.reset();
      $("#userModal").modal("hide");
      window.location.reload();
    } else {
      throw new Error(data.message || "An unknown error occurred");
    }
  } catch (error) {
    // Close the loading alert before showing the error message
    Swal.close();

    Swal.fire({
      icon: "error",
      title: "Error!",
      text: error.message || "An unexpected error occurred",
    });
    console.error("Error:", error);
  }
}

async function loadProvincialOffices() {
  try {
    const response = await fetch("applicants_functions.php", {
      method: "POST",
      body: new FormData(
        Object.assign(document.createElement("form"), {
          innerHTML: '<input name="action" value="get_provincial_offices">',
        })
      ),
    });

    if (!response.ok) throw new Error("Network response was not ok");
    const data = await response.json();

    if (data.status === "success") {
      const select = document.getElementById("provincial_office");
      select.innerHTML = '<option value="">Select Provincial Office</option>';

      data.data.offices.forEach((office) => {
        const option = document.createElement("option");
        option.value = office.province_id;
        option.textContent = office.provincial_office;
        select.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Error loading provincial offices:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Failed to load provincial offices",
    });
  }
}

async function loadApplicationData(applicationId) {
  try {
    const formData = new FormData();
    formData.append("action", "get_application");
    formData.append("application_id", applicationId);

    const response = await fetch("applicants_functions.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) throw new Error("Network response was not ok");
    const data = await response.json();

    if (data.status === "success") {
      const form = document.getElementById("editUserForm");
      form.querySelector("#editApplicationId").value = data.data.application_id;
      form.querySelector("#name_of_applicant").value =
        data.data.name_of_applicant;
      form.querySelector("#provincial_office").value =
        data.data.provincial_office;

      // Set all the date fields
      const dateFields = [
        "date_received_by_po_from_so_applicant",
        "type_of_application",
        "date_of_payment",
        "or_number",
        "date_transmitted_to_ro",
        "date_received_by_ro",
        "ro_screener",
        "date_forwarded_to_the_office_of_oic",
        "date_reviewed_by_oic_crasd",
        "feedbacks",
        "date_forwarded_to_ord",
        "date_application_approved_by_rd",
        "for_issuance_of_crasm",
        "for_transmittal_of_crasm",
        "date_crasm_generated",
        "date_forwarded_back_to_the_office_of_oic_cao",
        "date_reviewed_and_initialed_by_oic_crasd",
        "date_forwarded_back_to_ord",
        "date_crasm_approved_by_rd",
        "date_transmitted_back_to_po",
        "date_received_by_po",
        "date_released_to_so",
        "remarks",
      ];

      dateFields.forEach((field) => {
        const element = form.querySelector(`[name="${field}"]`);
        if (element && data.data[field]) {
          element.value = data.data[field];
        }
      });

      $("#editUserModal").modal("show");
    } else {
      throw new Error(data.message || "Failed to load application data");
    }
  } catch (error) {
    console.error("Error:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: error.message || "Failed to load application data",
    });
  }
}

// Function to save edited application data
async function saveApplicationData(form) {
  try {
    Swal.fire({
      title: "Saving...",
      html: "Please wait while we update the application",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    const formData = new FormData(form);
    formData.append("action", "update_application");

    for (const [key, value] of formData.entries()) {
      console.log(key, value);
    }

    const response = await fetch("applicants_functions.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) throw new Error("Network response was not ok");
    const data = await response.json();

    Swal.close();

    if (data.status === "success") {
      await Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Application updated successfully",
      });

      $("#editUserModal").modal("hide");
      window.location.reload();
    } else {
      console.log(data);
      throw new Error(data.message || "Failed to update application");
    }
  } catch (error) {
    Swal.close();
    console.error("Error:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: error.message || "Failed to update application",
    });
  }
}

function deleteUser(element) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire("Deleted!", "The user has been deleted.", "success");
    }
  });
}
