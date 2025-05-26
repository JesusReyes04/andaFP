const toggleBtn = document.getElementById("menu-toggle");
const closeBtn = document.getElementById("close-btn");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.add("open");
});

closeBtn.addEventListener("click", () => {
  sidebar.classList.remove("open");
});

document.addEventListener("DOMContentLoaded", () => {
  const toggleButtons = document.querySelectorAll(".toggle-applicants-btn");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const offerId = button.dataset.offerId;
      const list = document.getElementById(`applicants-${offerId}`);
      if (list.style.display === "none" || list.style.display === "") {
        list.style.display = "block";
        button.textContent = "Ocultar aplicantes";
      } else {
        list.style.display = "none";
        button.textContent = "Ver aplicantes";
      }
    });
  });

  document.querySelectorAll(".btn-accept, .btn-reject").forEach((button) => {
    button.addEventListener("click", () => {
      const studentId = button.getAttribute("data-student-id");
      const offerId = button.getAttribute("data-offer-id");
      const status = button.classList.contains("btn-accept")
        ? "approved"
        : "rejected";
      const statusMessage = document.querySelector(
        `#status-${offerId}-${studentId}`
      );

      fetch("/andaFP/src/backend/sections/update-application-status.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `student_id=${studentId}&offer_id=${offerId}&status=${status}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            statusMessage.textContent = `Estado actualizado: ${
              status === "approved" ? "Aprobado" : "Rechazado"
            }`;
            statusMessage.style.color = status === "approved" ? "green" : "red";
          } else {
            statusMessage.textContent = "Error: " + data.message;
            statusMessage.style.color = "orange";
          }
        })
        .catch((error) => {
          statusMessage.textContent = "Error de red";
          statusMessage.style.color = "orange";
          console.error("Error:", error);
        });
    });
  });
});
