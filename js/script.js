document.addEventListener("DOMContentLoaded", function () {
  generateWeeks();

  document
    .querySelector(".btn.primary-btn")
    .addEventListener("click", generateMenu);
  document
    .querySelector(".btn.secondary-btn")
    .addEventListener("click", downloadImage);
  document
    .querySelector(".btn.secondary-btn")
    .addEventListener("click", sendEmailAndPost);
});

function downloadImage() {
  const selectedWeek = document.getElementById("week-dropdown").value;
  const weekNumber = selectedWeek.split(" ")[0];

  html2canvas(document.getElementById("jpg-image"), {
    scale: 6,
    useCORS: true,
  }).then(function (canvas) {
    var link = document.createElement("a");
    link.download = `Lounasbuffet_week_${weekNumber}.jpg`;
    link.href = canvas.toDataURL("image/jpeg", 1.0);
    link.click();
  });
}

function generateMenu() {
  const input = document.getElementById("menu-input").value;
  const days = input
    .trim()
    .split(/(?=MAANANTAI|TIISTAI|KESKIVIIKKO|TORSTAI|PERJANTAI)/g);
  const menuOutput = document.getElementById("menu-output");
  const pdfMenuOutput = document.getElementById("pdf-menu-output");

  menuOutput.innerHTML = "";
  pdfMenuOutput.innerHTML = "";

  if (
    days.length < 5 ||
    !/^(MAANANTAI|TIISTAI|KESKIVIIKKO|TORSTAI|PERJANTAI)/.test(input.trim())
  ) {
    alert(
      "The menu format is incorrect. Please ensure the days are properly formatted and in Finnish."
    );
    return;
  }

  days.forEach((dayMenu) => {
    const lines = dayMenu.trim().split("\n");
    const dayName = lines[0];
    const dishes = lines
      .slice(1)
      .map((dish) => `<p>${dish}</p>`)
      .join("");

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
}

function sendEmailAndPost() {
  const selectedWeek = document.getElementById("week-dropdown").value;
  const selectedOption =
    document.getElementById("week-dropdown").selectedOptions[0].text;
  const menuContent = document.getElementById("menu-input").value;

  // Capture the pdf-section and convert it to a PDF
  const element = document.getElementById("pdf-section");

  // Applying custom styles to center content and add padding
  element.style.display = "flex";
  element.style.justifyContent = "center";
  element.style.alignItems = "center";
  element.style.padding = "20px";

  const opt = {
    margin: 0,
    filename: `Lounasbuffet_week_${selectedWeek}.pdf`,
    image: { type: "jpeg", quality: 1.0 },
    html2canvas: { scale: 4 },
    jsPDF: { unit: "px", format: [element.clientWidth, element.clientHeight] },
  };

  html2pdf()
    .from(element)
    .set(opt)
    .outputPdf("datauristring")
    .then(function (pdfAsString) {
      // Send the PDF to the server for emailing
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "send_email.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.status === "exists") {
            if (confirm(response.message)) {
              updateMenu(selectedWeek, menuContent, selectedOption);
            }
          } else if (response.status === "success") {
            alert(response.message);
          } else {
            alert(response.message);
          }
        }
      };

      // Send PDF as base64 string
      xhr.send(
        `week=${selectedWeek}&content=${encodeURIComponent(
          menuContent
        )}&selectedOption=${encodeURIComponent(
          selectedOption
        )}&pdfData=${encodeURIComponent(pdfAsString)}`
      );

      // Post to social media
      const imagePath = `path_to_your_generated_image/Lounasbuffet_week_${selectedWeek}.jpg`;
      const caption = `Menu for ${selectedWeek}: \n${menuContent}`;

      const socialXhr = new XMLHttpRequest();
      socialXhr.open("POST", "social_post.php", true);
      socialXhr.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
      );

      socialXhr.onreadystatechange = function () {
        if (socialXhr.readyState === 4 && socialXhr.status === 200) {
          console.log("Posted to social media:", socialXhr.responseText);
        }
      };

      socialXhr.send(
        `imagePath=${imagePath}&caption=${encodeURIComponent(caption)}`
      );
    })
    .finally(() => {
      // Reset styles after PDF generation
      element.style.display = "";
      element.style.justifyContent = "";
      element.style.alignItems = "";
      element.style.padding = "";
    });
}
