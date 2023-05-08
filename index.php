<!DOCTYPE html>
<html>

<head>
    <title>QR Code Generator</title>
    <link rel="shortcut Icon" href="https://cdn.row-hosting.de/BBT/Website/bb-logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>QR-Code Generator zum bestellen mit unseren Roboter </h1>
    <h2>Bitte halten Sie Ihr Smartphone bereit </h2>
    <form id="generate-form">
        <label for="tableSelect">Tischnummer:</label>
        <select name="tableNo" id="tableSelect">
        </select><br>
        <button id="generate" type="submit">QR-Code erstellen</button>
    </form>
    <div id="qr-code"></div>
    <div id="countdown"></div>
    <script src="qr-generator.js"></script>
</body>

</html>
