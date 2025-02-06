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

async function deleteUser(userId) {
  try {
    const result = await Swal.fire({
      title: "Are you sure?",
      text: "This action cannot be undone!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!",
      showLoaderOnConfirm: true,
      preConfirm: async () => {
        try {
          const formData = new FormData();
          formData.append("action", "delete");
          formData.append("userId", userId);

          const response = await fetch("functions.php", {
            method: "POST",
            body: formData,
          });

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const data = await response.json();

          if (data.status === "success") {
            return data;
          }

          throw new Error(data.message || "An unknown error occurred");
        } catch (error) {
          Swal.showValidationMessage(
            error.message || "An unexpected error occurred"
          );
        }
      },
      allowOutsideClick: () => !Swal.isLoading(),
    });

    if (result.isConfirmed) {
      await Swal.fire(
        "Deleted!",
        "User has been deleted successfully.",
        "success"
      );
      window.location.reload();
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

async function editUser(userId) {
  try {
    console.log("User ID being sent:", userId);

    const formData = new FormData();
    formData.append("action", "get_user");
    formData.append("userId", userId);

    const response = await fetch("functions.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.status === "success") {
      if (!data.data.user) {
        throw new Error("User data is missing from response");
      }
      // Populate the edit form with user data
      document.getElementById("edit_user_id").value = data.data.user.id;
      document.getElementById("edit_firstname").value =
        data.data.user.first_name;
      document.getElementById("edit_middlename").value =
        data.data.user.middle_name || "";
      document.getElementById("edit_lastname").value = data.data.user.last_name;
      document.getElementById("edit_email").value = data.data.user.email;
      document.getElementById("edit_role").value = data.data.user.role_name;
      document.getElementById("edit_status").value = data.data.user.status;

      // Show the edit modal
      $("#editUserModal").modal("show");
    } else {
      throw new Error(data.message || "Failed to fetch user data");
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: error.message || "An unexpected error occurred",
    });
    console.error("Error:", error);
  }
}

async function handleEditFormSubmit(form) {
  Swal.fire({
    title: "Processing...",
    html: "Please wait while we update the user",
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    const formData = new FormData(form);
    formData.append("action", "update");

    const response = await fetch("functions.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    Swal.close();

    if (data.status === "success") {
      await Swal.fire({
        icon: "success",
        title: "Success!",
        text: "User updated successfully",
      });

      $("#editUserModal").modal("hide");
      window.location.reload();
    } else {
      throw new Error(data.message || "An unknown error occurred");
    }
  } catch (error) {
    Swal.close();

    Swal.fire({
      icon: "error",
      title: "Error!",
      text: error.message || "An unexpected error occurred",
    });
    console.error("Error:", error);
  }
}

// END OF USER CONTROL FUNCTIONS

// START OF USER ACCOUNT SETTINGS

async function handleAccountSettingsSubmit(event) {
  event.preventDefault();

  // Validate passwords if they're provided
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm_password").value;

  if (password || confirmPassword) {
    if (password !== confirmPassword) {
      Swal.fire({
        icon: "error",
        title: "Password Mismatch",
        text: "New password and confirmation password do not match",
      });
      return false;
    }

    if (password.length < 8) {
      Swal.fire({
        icon: "error",
        title: "Invalid Password",
        text: "Password must be at least 8 characters long",
      });
      return false;
    }
  }

  // Show loading state
  Swal.fire({
    title: "Processing...",
    html: "Please wait while we update your account settings",
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    const form = event.target;
    const formData = new FormData(form);
    formData.append("action", "update_account");

    // Debug logging
    const formDataObj = {};
    formData.forEach((value, key) => {
      formDataObj[key] = value;
    });
    console.log("Sending form data:", formDataObj);

    const response = await fetch("functions.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log("Server response:", data);

    if (data.status === "success") {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Account settings updated successfully",
        confirmButtonText: "OK",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.reload();
        }
      });
    } else {
      throw new Error(data.message || "Failed to update account settings");
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: error.message || "An unexpected error occurred",
    });
    console.error("Error:", error);
  }
}

// END OF USER ACCOUNT SETTINGS

// START OF SYSTEM SETTINGS

function setupDropzone(dropzoneId, inputId, previewId) {
  const dropzone = document.getElementById(dropzoneId);
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);

  if (!dropzone || !input) return;

  dropzone.addEventListener("click", () => input.click());

  dropzone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropzone.style.borderColor = "#000";
  });

  dropzone.addEventListener("dragleave", () => {
    dropzone.style.borderColor = "#ccc";
  });

  dropzone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropzone.style.borderColor = "#ccc";
    input.files = e.dataTransfer.files;
    updatePreview(input, preview);
  });

  input.addEventListener("change", () => updatePreview(input, preview));
}

function updatePreview(input, preview) {
  if (!preview) return;

  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = (e) => {
      preview.src = e.target.result;
      preview.style.display = "block";
    };
    reader.readAsDataURL(input.files[0]);
  }
}

async function handleSystemSettingsSubmit(event) {
  event.preventDefault();

  Swal.fire({
    title: "Processing...",
    html: "Please wait while we update the system settings",
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    const form = event.target;
    const formData = new FormData(form);
    formData.append("action", "update_system_settings");

    const response = await fetch("functions.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.status === "success") {
      await Swal.fire({
        icon: "success",
        title: "Success!",
        text: "System settings updated successfully",
        confirmButtonText: "OK",
      });

      window.location.reload();
    } else {
      throw new Error(data.message || "Failed to update system settings");
    }
  } catch (error) {
    console.error("Error:", error);
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: error.message || "An unexpected error occurred",
    });
  }
}

// END OF SYSTEM SETTINGS
