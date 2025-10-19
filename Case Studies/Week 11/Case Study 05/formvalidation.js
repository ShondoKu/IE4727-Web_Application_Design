function init() {
  "use strict";

  if (document && document.getElementById) {
    const name = document.getElementById("name");
    name.addEventListener("input", handleName);

    const email = document.getElementById("email");
    email.addEventListener("input", handleEmail);

    const startDate = document.getElementById("startDate");
    startDate.addEventListener("input", handleStartDate);

    const experience = document.getElementById("experience");
    experience.addEventListener("input", handleExperience);

    const education = document.getElementById("education");
    education.addEventListener("input", handleEducation);

    const resume = document.getElementById("resume");
    resume.addEventListener("change", handleResume);
  }
}

window.onload = init;

// --- Validation Functions ---

function handleName(event) {
  const input = event.currentTarget;
  const value = input.value.trim();

  // 1️⃣ Must contain at least one letter (\p{L})
  // 2️⃣ Only letters, spaces, apostrophes, or hyphens allowed
  const nameRegex = /^[\p{L}’'\- ]+$/u;
  const hasLetter = /\p{L}/u.test(value);

  if (value === "") {
    setError(input, "Please fill up your name!");
  } else if (!hasLetter) {
    setError(input, "Name must include at least one letter.");
  } else if (!nameRegex.test(value)) {
    setError(
      input,
      "Name can only contain letters, spaces, hyphens, or apostrophes."
    );
  } else {
    clearError(input);
  }
}

function handleEmail(event) {
  const input = event.currentTarget;
  const value = input.value.trim();
  const emailRegex =
    /^(?![.-])(?!.*\.\.)[A-Za-z0-9_.-]+(?<![.-])@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.){1,3}[A-Za-z]{2,3}$/;

  if (value === "") {
    setError(input, "Please fill up your email!");
  } else if (!emailRegex.test(value)) {
    setError(input, "Invalid email format.");
  } else {
    clearError(input);
  }
}

function handleStartDate(event) {
  const input = event.currentTarget;
  const value = input.value;
  if (!value) {
    setError(input, "Please fill up your start date!");
    return;
  }

  const startDate = new Date(value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  if (startDate <= today) {
    setError(input, "Start date must be a future date.");
  } else {
    clearError(input);
  }
}

function handleExperience(event) {
  const input = event.currentTarget;
  if (input.value.trim() === "") {
    setError(input, "Please fill up your experience!");
  } else {
    clearError(input);
  }
}

function handleEducation(event) {
  const input = event.currentTarget;
  if (input.value.trim() === "") {
    setError(input, "Please fill up your education!");
  } else {
    clearError(input);
  }
}

function handleResume(event) {
  const input = event.currentTarget;
  const file = input.files[0];

  if (!file) {
    setError(input, "Please upload your resume!");
    return;
  }

  const allowedExtensions = /(\.pdf|\.doc|\.docx)$/i;
  if (!allowedExtensions.test(file.name)) {
    setError(
      input,
      "Invalid file type. Only PDF or Word (.doc/.docx) allowed."
    );
    input.value = ""; // Clear invalid file
  } else {
    clearError(input);
  }
}
// --- Helper functions for showing inline error messages ---
function setError(input, message) {
  input.style.borderColor = "red";
  let error = input.nextElementSibling;
  if (!error || !error.classList.contains("error-message")) {
    error = document.createElement("span");
    error.className = "error-message";
    error.style.color = "red";
    error.style.fontSize = "0.9em";
    error.style.marginLeft = "10px";
    input.insertAdjacentElement("afterend", error);
  }
  error.textContent = message;
}

function clearError(input) {
  input.style.borderColor = "";
  const error = input.nextElementSibling;
  if (error && error.classList.contains("error-message")) {
    error.remove();
  }
}
