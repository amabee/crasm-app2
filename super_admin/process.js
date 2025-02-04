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
    });

    if (result.isConfirmed) {
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
        await Swal.fire(
          "Deleted!",
          "User has been deleted successfully.",
          "success"
        );
        window.location.reload();
      } else {
        throw new Error(data.message || "An unknown error occurred");
      }
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
      console.log("Mother Fucking data: ", data.data.user);
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
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: error.message || "An unexpected error occurred",
    });
    console.error("Error:", error);
  }
}
