const form = document.getElementById('generate-form');
const qr = document.getElementById('qr-code');
const countdownElement = document.getElementById("countdown");
let isGeneratingQRCode = false;

getCurrentTables();
var tables;
function getCurrentTables() {
  jQuery.ajax({
    type: "GET",
    url: 'utils.php',
    data: { functionName: 'getTables' },
    success: function (response) {
      tables = JSON.parse(response);
      updateTableList();
    }
  });
}

// Define a function that updates the select element with the new options
function updateTableList() {
  // Get the select element
  var select = $('#tableSelect');

  // Clear all existing options
  select.empty();

  // Loop through the tables and create a new option for each one
  for (var i = 0; i < tables.length; i++) {
    var table = tables[i];

    // Create a new option element
    if (!table.reserved) {
      var option = $('<option>');
      option.attr('value', table.tableNo);
      option.text(table.tableNo);

      // Add the option to the select element
      select.append(option);
    }
  }
}

const onGenerateSubmit = async (e) => {
  if (!isGeneratingQRCode) {
    isGeneratingQRCode = true;
    e.preventDefault();
    clearUI();

    const selectValue = document.getElementById('tableSelect').value;

    // Validate user input
    if (selectValue === undefined) {
      console.error('Invalid table number:', selectValue);
      return;
    }

    try {
      // Register token and generate QR-Code safely

      // Sanitize data input
      const sanitizedTableNo = encodeURIComponent(selectValue);
      await registerQRCode(sanitizedTableNo);
      await reserveTable(sanitizedTableNo);
      getCurrentTables(); 
    } catch (error) {
      console.error('Error generating QR code:', error);
    }

    form.style.display = "none";

    // Start countdown safely
    startCountdown(30, function () {
      clearUI();
      form.style.display = "block";
      countdownElement.style.display = "none";
      isGeneratingQRCode = false;
    });
  }
};

const generateQRCode = (url) => {
  // Encode user input
  const encodedUrl = encodeURI(url);

  const qrcode = new QRCode('qr-code', {
    text: encodedUrl,
    width: 480,
    height: 480,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
  });
};

const registerQRCode = async (tableNo) => {
  jQuery.ajax({
    type: "GET",
    url: 'utils.php',
    data: { functionName: 'registerQRCode', tableNo: tableNo },
    success: function (response) {
      data = JSON.parse(response);
      if (data.qrcodeurl !== undefined) {
        const qrCodeUrl = data.qrcodeurl;
        generateQRCode(qrCodeUrl);
      }
    }
  });
};

const reserveTable = async (tableNo) => {
  jQuery.ajax({
    type: "GET",
    url: 'utils.php',
    data: { functionName: 'reserveTable', tableNo: tableNo},
    success: function (response) {

    }
  });

}

const clearUI = () => {
  // Reset QR code safely
  qr.textContent = '';
  qr.title = '';
};

function startCountdown(duration, onCountdownEnd) {
  countdownElement.style.display = "block";
  let timeLeft = duration;
  let minutes, seconds;

  const countdownInterval = setInterval(function () {
    minutes = parseInt(timeLeft / 60, 10);
    seconds = parseInt(timeLeft % 60, 10);

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    countdownElement.innerHTML = minutes + ":" + seconds;

    if (--timeLeft < 0) {
      clearInterval(countdownInterval);
      if (onCountdownEnd) {
        onCountdownEnd();
      }
    }
  }, 1000);
}

form.addEventListener('submit', onGenerateSubmit);
