// The hardcoded PRICES constant has been removed from this file.
// This script now assumes a global 'PRICES' object exists,
// which is provided by menu.php.

// Elements
const qtyJava = document.getElementsByName("qty_java")[0];
const qtyCafe = document.getElementsByName("qty_cafe")[0];
const qtyCapp = document.getElementsByName("qty_capp")[0];

const subtotalJava = document.getElementsByName("subtotal_java")[0];
const subtotalCafe = document.getElementsByName("subtotal_cafe")[0];
const subtotalCapp = document.getElementsByName("subtotal_capp")[0];

const totalEl = document.getElementById("total");

const shotCafeRadios = document.getElementsByName("shot_cafe");
const shotCappRadios = document.getElementsByName("shot_capp");

// Attach listeners
qtyJava.addEventListener("input", calculateAll);
qtyCafe.addEventListener("input", calculateAll);
qtyCapp.addEventListener("input", calculateAll);

Array.from(shotCafeRadios).forEach((r) =>
  r.addEventListener("change", calculateAll)
);
Array.from(shotCappRadios).forEach((r) =>
  r.addEventListener("change", calculateAll)
);

// Helpers
function parseQty(inputEl) {
  const v = parseInt(inputEl.value, 10);
  return Number.isNaN(v) || v < 0 ? 0 : v;
}

function getSelectedValue(radioNodeList) {
  for (let i = 0; i < radioNodeList.length; i++) {
    if (radioNodeList[i].checked) return radioNodeList[i].value;
  }
  return null;
}

// Calculation function
function calculateAll() {
  // Check if PRICES object is available
  if (typeof PRICES === "undefined") {
    console.error(
      "PRICES object not found. Make sure it is defined in the PHP file."
    );
    return;
  }

  const qJava = parseQty(qtyJava);
  const subJava = qJava * PRICES.java;
  subtotalJava.value = "$" + subJava.toFixed(2);

  const qCafe = parseQty(qtyCafe);
  const shotCafe = getSelectedValue(shotCafeRadios) || "single";
  const subCafe = qCafe * PRICES.cafe[shotCafe];
  subtotalCafe.value = "$" + subCafe.toFixed(2);

  const qCapp = parseQty(qtyCapp);
  const shotCapp = getSelectedValue(shotCappRadios) || "single";
  const subCapp = qCapp * PRICES.capp[shotCapp];
  subtotalCapp.value = "$" + subCapp.toFixed(2);

  const total = subJava + subCafe + subCapp;
  totalEl.innerText = total.toFixed(2);
}

// Initialize on load
document.addEventListener("DOMContentLoaded", calculateAll);
