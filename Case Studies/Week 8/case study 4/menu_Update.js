var cafe_Single_Price = 2.0;
var cafe_Double_Price = 3.0;
var java_Price = 2.0;
var capp_Single_Price = 4.75;
var capp_Double_Price = 5.75;
var total = 0.0;
var cafe_Single_Qty = document.getElementsByName("qty_Cafe_Single")[0];
var cafe_Double_Qty = document.getElementsByName("qty_Cafe_Double")[0];
var java_Qty = document.getElementsByName("qty_Java")[0];
var capp_Single_Qty = document.getElementsByName("qty_Cap_Single")[0];
var capp_Double_Qty = document.getElementsByName("qty_Cap_Double")[0];

cafe_Single_Qty.addEventListener("input", Cal_Cafe);
cafe_Double_Qty.addEventListener("input", Cal_Cafe);
java_Qty.addEventListener("input", Cal_Cafe);
capp_Single_Qty.addEventListener("input", Cal_Cafe);
capp_Double_Qty.addEventListener("input", Cal_Cafe);

function Cal_Cafe(event) {
  var cafe_Single_Subtotal = cafe_Single_Qty.value * cafe_Single_Price;
  var cafe_Double_Subtotal = cafe_Double_Qty.value * cafe_Double_Price;
  var cafe_Subtotal = cafe_Single_Subtotal + cafe_Double_Subtotal;
  document.querySelector('.subtotal[data-item="Cafe"]').innerText =
    "$" + cafe_Subtotal.toFixed(2);

  var capp_Single_Subtotal = capp_Single_Qty.value * capp_Single_Price;
  var capp_Double_Subtotal = capp_Double_Qty.value * capp_Double_Price;
  var capp_Subtotal = capp_Single_Subtotal + capp_Double_Subtotal;
  document.querySelector('.subtotal[data-item="Cap"]').innerText =
    "$" + capp_Subtotal.toFixed(2);

  var java_Subtotal = java_Qty.value * java_Price;
  document.querySelector('.subtotal[data-item="Java"]').innerText =
    "$" + java_Subtotal.toFixed(2);

  total = cafe_Subtotal + capp_Subtotal + java_Subtotal;
  document.getElementById("total").innerText = total.toFixed(2);
}
