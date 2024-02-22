var text="",amount,date;
function Swift()
{
    localStorage.setItem("text","Maruti Swift");
    localStorage.setItem("amount",2400);
    alert("Maruti Swift will be booked for Rs2400/-");
}
function Hyundai()
{
    localStorage.setItem("text","Hyundai I10");
    localStorage.setItem("amount",2500);
    alert("Hyundai I10 will be booked for Rs2500/-");
}
function Innova()
{
    localStorage.setItem("text","Toyota Innova");
    localStorage.setItem("amount",3200);
    alert("Toyota Innova will be booked for Rs3200/-");
}
function Audi()
{
    localStorage.setItem("text","Audi A4");
    localStorage.setItem("amount",5500);
    alert("Audi A4 will be booked for Rs5500/-");
}
function Fortuner()
{
    localStorage.setItem("text","Toyota Fortuner");
    localStorage.setItem("amount",4600);
    alert("Toyota Fortuner will be booked for Rs4600/-");
}
function RE350()
{
    localStorage.setItem("text","Royal Enfield 350");
    localStorage.setItem("amount",900);
    alert("Royal Enfield 350 will be booked for Rs900/-");
}
function RC390()
{
    localStorage.setItem("text","KTM RC 390");
    localStorage.setItem("amount",2500);
    alert("KTM RC 390 will be booked for Rs2500\-");
}
function Duke390()
{
    localStorage.setItem("text","KTM Duke 390");
    localStorage.setItem("amount",1800);
    alert("KTM Duke 390 will be booked for Rs1800/-");
}
function Ninja300()
{
    localStorage.setItem("text","Kawasaki Ninja 300");
    localStorage.setItem("amount",3500);
    alert("Kawasaki Ninja 300 will be booked for Rs3500/-");
}
function HD883()
{
    localStorage.setItem("text","Harley Davidson Iron 883");
    localStorage.setItem("amount",7000);
    alert("Harley Davidson Iron 883 will be booked for Rs7000\-");
}
function Display()
{
    if(localStorage.getItem("text").length!=0)
    {
        document.getElementById("display1").value=localStorage.getItem("text");
        document.getElementById("display2").value=localStorage.getItem("amount") + "/-";
        document.getElementById("date").required=true;
        document.getElementById("accept").required=true;
    }
}
function DATE()
{
    localStorage.setItem("date",document.getElementById("date").value);
}
function Location()
{
     window.location.href="feedback.php";
}
function clear()
{
    localStorage.removeItem("text");
    localStorage.removeItem("amount");
    localStorage.removeItem("date");
}
function ClearTable()
{
    document.getElementById("display1").value="";
    document.getElementById("display2").value="";
    document.getElementById("date").value="";
    document.getElementById("accept").value="";	
    document.getElementByName("name").value="";
    document.getElementByName("email").value="";
    document.getElementByName("phone").value="";
    document.getElementByName("subject").value="";	
    document.getElementByName("message").value="";	
}
// function updateModels() {
//     var selectedType = document.querySelector('input[name="vehicleType"]:checked').value;
//     var modelDropdown = document.getElementById("model");

//     // Use AJAX to fetch models from the server based on the selected type
//     var xhr = new XMLHttpRequest();
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState == 4 && xhr.status == 200) {
//             var models = JSON.parse(xhr.responseText);

//             // Clear existing options
//             modelDropdown.innerHTML = "";

//             // Populate the dropdown with the fetched models
//             models.forEach(function (model) {
//                 var option = document.createElement("option");
//                 option.value = model;
//                 option.text = model;
//                 modelDropdown.add(option);
//             });
//         }
//     };

//     xhr.open("POST", "getModels.php", true);
//     xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     xhr.send("vehicleType=" + selectedType);
// }

// // Call updateModels when the radio buttons are clicked
// var radioButtons = document.querySelectorAll('input[name="vehicleType"]');
// radioButtons.forEach(function (radioButton) {
//     radioButton.addEventListener("click", updateModels);
// });
function bookNow() {
    // Retrieve orderId from the URL (assuming you have orderId available)
    var urlParams = new URLSearchParams(window.location.search);
    var orderId = urlParams.get('orderId');

    // Redirect to payment.php with orderId as a parameter
    window.location.href = "payment.php?orderId=" + orderId;
}
function updateModels() {
    var selectedType = document.querySelector('input[name="vehicleType"]:checked').value;
    var modelDropdown = document.getElementById("model");

    // Use AJAX to fetch models from the server based on the selected type
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var models = JSON.parse(xhr.responseText);

            // Clear existing options
            modelDropdown.innerHTML = "";

            // Populate the dropdown with the fetched models
            models.forEach(function (model) {
                var option = document.createElement("option");
                option.value = model;
                option.text = model;
                modelDropdown.add(option);
            });
        }
    };

    xhr.open("POST", "getModels.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("vehicleType=" + selectedType);
}

function calculateTotal() {
    var selectedType = document.querySelector('input[name="vehicleType"]:checked').value;
    var selectedModel = document.getElementById("model").value;

    // Use AJAX to fetch rent per day for the selected model
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.hasOwnProperty('rentPerDay')) {
                var rentPerDay = parseFloat(response.rentPerDay);
                var orderDate = new Date(document.getElementById("orderDate").value);
                var returnDate = new Date(document.getElementById("returnDate").value);

                // Calculate the number of days between order and return dates
                var daysDiff = Math.ceil((returnDate - orderDate) / (1000 * 60 * 60 * 24));

                // Calculate the total price
                var totalPrice = rentPerDay * daysDiff;

                // Update the total price in the HTML
                document.getElementById("pricePerDay").innerText = rentPerDay.toFixed(2);
                document.getElementById("totalPrice").innerText = totalPrice.toFixed(2);
            } else {
                alert('Error: ' + response.error);
            }
        }
    };

    xhr.open("POST", "calculateTotal.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("vehicleType=" + selectedType + "&model=" + selectedModel);
}

// Call updateModels when the radio buttons are clicked
var radioButtons = document.querySelectorAll('input[name="vehicleType"]');
radioButtons.forEach(function (radioButton) {
    radioButton.addEventListener("click", updateModels);
});

// Call calculateTotal when the order and return dates are changed
document.getElementById("orderDate").addEventListener("change", calculateTotal);
document.getElementById("returnDate").addEventListener("change", calculateTotal);
document.getElementById("model").addEventListener("change", calculateTotal);
function bookNow() {
    console.log("Book Now button clicked"); // Check if function is being called
    var selectedModel = document.getElementById('model').value;
    var selectedPrice = document.getElementById('pricePerDay').innerText;
    var redirectUrl = 'index1.php?model=' + encodeURIComponent(selectedModel) + '&price=' + encodeURIComponent(selectedPrice);
    console.log("Redirect URL:", redirectUrl); // Check constructed URL
    window.location.href = redirectUrl;
}
