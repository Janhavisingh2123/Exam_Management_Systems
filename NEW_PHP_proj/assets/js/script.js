// ========================
// Alert Display Function
// ========================
function showAlert(type, message) {
    let alertBox = document.createElement("div");
    alertBox.className = `alert alert-${type}`;
    alertBox.innerHTML = message;
    document.body.prepend(alertBox);

    setTimeout(() => alertBox.remove(), 4000);
}

// ========================
// Form Validation
// ========================
function validateLogin(formId) {
    let form = document.getElementById(formId);
    let inputs = form.querySelectorAll("input");
    for (let input of inputs) {
        if (!input.value.trim()) {
            showAlert("danger", "⚠️ All fields are required!");
            return false;
        }
    }
    return true;
}

// ========================
// Print Report
// ========================
function printReport() {
    let reportContent = document.getElementById("reportContent");
    if (!reportContent) {
        showAlert("danger", "⚠️ Nothing to print!");
        return;
    }
    let printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head><title>Report</title></head>
        <body>${reportContent.innerHTML}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// ========================
// Export to PDF (Simulated)
// ========================
function exportToPDF() {
    showAlert("success", "📄 PDF export will be added with PHP (TCPDF/FPDF).");
}
