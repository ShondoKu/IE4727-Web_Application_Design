function init() {
  "use strict";

  if (document && document.getElementById) {
    var name = document.getElementById("name");
    name.addEventListener("change", handleName);

    var email = document.getElementById("email");
    email.addEventListener("change", handleEmail);

    var startDate = document.getElementById("startDate");
    startDate.addEventListener("change", handleStartDate);

    var experience = document.getElementById("experience");
    experience.addEventListener("change", handleExperience);

    var education = document.getElementById("education");
    education.addEventListener("change", handleEducation);
  }
} // End of init() function.

// Assign an event listener to the window's load event:
window.onload = init;

function handleName(event) {
  "use strict";
  var name = event.currentTarget.value;
  if (name.length > 0) {
    let nameRegex = /^[A-Za-z ]+$/;

    if (!nameRegex.test(name)) {
      alert("Name must only contain alphabets and spaces.");
      name.focus();
      name.select();
      return false;
    }
  } else {
    alert("Please fill up your name!");
    name.focus();
    return false;
  }
}

function handleEmail(event) {
  "use strict";
  var email = event.currentTarget.value.trim();
  if (email.length > 0) {
    const emailRegex =
      /^(?![.-])(?!.*\.\.)[A-Za-z0-9_.-]+(?<![.-])@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.){1,3}[A-Za-z]{2,3}$/;
    if (!emailRegex.test(email) || email.includes("..")) {
      alert("Invalid email format.");
      email.focus();
      email.select();
      return false;
    }
  } else {
    alert("Please fill up your email!");
    email.focus();
    return false;
  }
}

function handleStartDate(event) {
  "use strict";
  var startdate = new Date(event.currentTarget.value);
  let today = new Date();
  today.setHours(0, 0, 0, 0); // clear time for comparison
  if (startdate.length > 0) {
    if (!(startDate > today)) {
      alert("Start date must be a future date.");
      startdate.focus();
      return false;
    }
  } else {
    alert("Please fill up your start date!");
    startdate.focus();
    return false;
  }
}

function handleExperience(event) {
  "use strict";
  var experience = event.currentTarget.value;
  if (experience.length == 0) {
    alert("Please fill up your experience!");
    return false;
  }
}
function handleEducation(event) {
  "use strict";
  var education = event.currentTarget.value;
  if (education.length == 0) {
    alert("Please fill up your education!");
    return false;
  }
}
