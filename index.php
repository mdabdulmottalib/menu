<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Weekly Menu</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <div class="menu-form">
        <div class="input-group">
            <select id="week-dropdown" onchange="updateWeeklyDate()" class="styled-select"></select>
            <textarea id="menu-input" rows="10" cols="50" placeholder="Enter your menu..." class="styled-textarea"></textarea>
        </div>
        <div class="button-group">
            <button onclick="generateMenu()" class="btn primary-btn">Generate Menu</button>
            <button onclick="downloadImage()" class="btn secondary-btn">Download Image</button>
            <button onclick="sendEmailAndPost()" class="btn secondary-btn">Send Email</button>
        </div>
    </div>

    <!-- Initially hide this section and show it when Generate Menu is clicked -->
    <div class="output-section" style="display: none">
        <div class="download-container" id="jpg-image">
            <div class="container">
                <div class="menu-left">
                    <img src="img/menu-left.jpg" class="menu-left" alt="Decorative Image" />
                </div>
                <div class="right-box">
                    <div class="box-container">
                        <div>
                            <h1 class="menu-heading">LOUNASBUFFET</h1>
                            <h2 class="weekly-date"></h2>
                        </div>
                        <div class="text-content" id="menu-output">
                            <!-- Generated menu items will appear here -->
                        </div>
                        <div class="bottom-container">
                            <img class="bottom-img" src="img/menu-bottom.png" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="blank-space"></div>
        <div class="pdf-container" id="pdf-section">
            <div class="container">
                <div class="menu-left">
                    <img src="img/black-menu-left.png" class="menu-left" alt="Decorative Image" />
                </div>
                <div class="right-box">
                    <div class="box-container">
                        <div>
                            <h1 class="pdf-menu-heading">LOUNASBUFFET</h1>
                            <h2 class="weekly-date"></h2>
                        </div>
                        <div class="text-content" id="pdf-menu-output">
                            <!-- Generated menu items will appear here -->
                        </div>
                        <div class="bottom-container">
                            <img class="bottom-img" src="img/black-menu-bottom.png" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="js/script.js"></script> <!-- External script file -->
</body>
</html>
