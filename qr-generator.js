const form = document.getElementById('generate-form');
const qr = document.getElementById('qr-code');
const countdownElement = document.getElementById("countdown");

const onGenerateSubmit = async (e) => {
  e.preventDefault();
  clearUI();

  const selectValue = parseInt(document.getElementById('tableSelect').value, 10);

  // Validate user input
  if (isNaN(selectValue) || selectValue <= 0) {
    console.error('Invalid table number:', selectValue);
    return;
  }

  try {
    // Register token and generate QR-Code safely
    await registerToken(selectValue);
  } catch (error) {
    console.error('Error generating QR code:', error);
  }

  form.style.display = "none";

  // Start countdown safely
  startCountdown(30, function () {
    clearUI();
    form.style.display = "block";
    countdownElement.style.display = "none";
  });
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

const registerToken = async (tableNo) => {
  // Sanitize data input
  const sanitizedTableNo = encodeURIComponent(tableNo);

  const url = `registerQrCode.php/?tableNo=${sanitizedTableNo}`;

  const response = await fetch(url, { credentials: 'include' });
  const data = await response.json();

  const qrCodeUrl = data.qrcodeurl;
  generateQRCode(qrCodeUrl);
};

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
