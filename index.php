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
            <button onclick="sendEmail()" class="btn secondary-btn">Send Email</button>
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

    <!-- Include html2canvas and html2pdf.js libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
function downloadImage() {
    const selectedWeek = document.getElementById("week-dropdown").value; // Get the selected week number
    const weekNumber = selectedWeek.split(' ')[0]; // Extract the week number from the selected option
    
    html2canvas(document.getElementById("jpg-image"), {
        scale: 6,
        useCORS: true,
    }).then(function (canvas) {
        var link = document.createElement("a");
        link.download = `Lounasbuffet_week_${weekNumber}.jpg`; // Set the filename to include the week number
        link.href = canvas.toDataURL("image/jpeg", 1.0);
        link.click();
    });
}


const generateWeeks = () => {
    const today = new Date();
    const dayOfWeek = today.getDay();
    const daysUntilNextMonday = (8 - dayOfWeek) % 7 || 7;
    const nextMonday = new Date(today);
    nextMonday.setDate(today.getDate() + daysUntilNextMonday);

    const currentWeek = getWeekNumber(nextMonday);
    const dropdown = document.getElementById("week-dropdown");

    for (let i = 0; i <= 8; i++) {
        const weekStart = new Date(nextMonday);
        weekStart.setDate(weekStart.getDate() + i * 7);

        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekEnd.getDate() + 4);

        const weekNumber = currentWeek + i;
        
        // Format the dates as "day.month"
        const startDayMonth = `${weekStart.getDate()}.${weekStart.getMonth() + 1}`;
        const endDayMonth = `${weekEnd.getDate()}.${weekEnd.getMonth() + 1}`;
        
        // Combine with the year only at the end
        const weekText = `${weekNumber} | ${startDayMonth} - ${endDayMonth}.${weekEnd.getFullYear()}`;

        const option = document.createElement("option");
        option.value = weekNumber;
        option.textContent = weekText;

        if (i === 0) {
            option.selected = true;
        }

        dropdown.appendChild(option);
    }
    updateWeeklyDate();
};

const getWeekNumber = (date) => {
    const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
    const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
    return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
};

const updateWeeklyDate = () => {
    const selectedWeek = document.getElementById("week-dropdown").value;
    const selectedOption = document.getElementById("week-dropdown").selectedOptions[0].text;
    document.querySelectorAll(".weekly-date")[0].textContent = `VIIKKO ${selectedWeek} | ${selectedOption.split("|")[1].trim()}`;
    document.querySelectorAll(".weekly-date")[1].textContent = `VIIKKO ${selectedWeek} | ${selectedOption.split("|")[1].trim()}`;
};

        const generateMenu = () => {
            const input = document.getElementById("menu-input").value;
            const days = input.trim().split(/(?=MAANANTAI|TIISTAI|KESKIVIIKKO|TORSTAI|PERJANTAI)/g);
            const menuOutput = document.getElementById("menu-output");
            const pdfMenuOutput = document.getElementById("pdf-menu-output");

            menuOutput.innerHTML = "";
            pdfMenuOutput.innerHTML = "";

            if (days.length < 5 || !/^(MAANANTAI|TIISTAI|KESKIVIIKKO|TORSTAI|PERJANTAI)/.test(input.trim())) {
                alert("The menu format is incorrect. Please ensure the days are properly formatted and in Finnish.");
                return;
            }

            days.forEach((dayMenu) => {
                const lines = dayMenu.trim().split("\n");
                const dayName = lines[0];
                const dishes = lines.slice(1).map((dish) => `<p>${dish}</p>`).join("");

                const dayHtml = `
                    <div>
                        <h1 class="day-name">${dayName}</h1>
                        ${dishes}
                    </div>
                `;

                menuOutput.innerHTML += dayHtml;
                pdfMenuOutput.innerHTML += dayHtml;
            });

            document.querySelector(".output-section").style.display = "block";
        };

function sendEmail() {
    const selectedWeek = document.getElementById("week-dropdown").value;
    const selectedOption = document.getElementById("week-dropdown").selectedOptions[0].text;
    const menuContent = document.getElementById("menu-input").value;

    // Capture the pdf-section and convert it to a PDF
    const element = document.getElementById('pdf-section');

    // Applying custom styles to center content and add padding
    element.style.display = 'flex';
    element.style.justifyContent = 'center';
    element.style.alignItems = 'center';
    element.style.padding = '20px'; // Adjust the padding as needed

    const opt = {
        margin: 0, // No margins
        filename: `Lounasbuffet_week_${selectedWeek}.pdf`,
        image: { type: 'jpeg', quality: 1.0 }, // Set quality to highest for print
        html2canvas: { scale: 4 }, // Increase the scale factor for higher resolution
        jsPDF: { unit: 'px', format: [element.clientWidth, element.clientHeight] } // Match the PDF size to the content size
    };

    html2pdf().from(element).set(opt).outputPdf('datauristring').then(function (pdfAsString) {
        // Send the PDF to the server for emailing
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "send_email.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'exists') {
                    if (confirm(response.message)) {
                        updateMenu(selectedWeek, menuContent, selectedOption);
                    }
                } else if (response.status === 'success') {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        };

        // Send PDF as base64 string
        xhr.send(`week=${selectedWeek}&content=${encodeURIComponent(menuContent)}&selectedOption=${encodeURIComponent(selectedOption)}&pdfData=${encodeURIComponent(pdfAsString)}`);
    }).finally(() => {
        // Reset styles after PDF generation
        element.style.display = '';
        element.style.justifyContent = '';
        element.style.alignItems = '';
        element.style.padding = '';
    });
}


function updateMenu(week, content, selectedOption) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_menu.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(response.message);
        }
    };

    xhr.send(`week=${week}&content=${encodeURIComponent(content)}&selectedOption=${encodeURIComponent(selectedOption)}`);
}

window.onload = generateWeeks;

    </script>
</body>
</html>
