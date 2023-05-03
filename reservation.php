<!DOCTYPE html>
<html>

<head>
    <title>One-Time-Use QR Code Generator</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>QR-Code Generator zum bestellen mit unseren Roboter </h1>
    <h2>Bitte halten Sie ihr Smartphone bereit </h2>
    <form id="generate-form">
        <label for="tableSelect">Tischnummer:</label>
        <select name="tableNo" id="tableSelect">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select><br>
        <button id="generate" type="submit">QR-Code erstellen</button>
    </form>
    <div id="qr-code"></div>
    <div id="countdown"></div>
    <script src="qr-generator.js"></script>
</body>

</html>