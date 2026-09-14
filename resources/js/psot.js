document.querySelectorAll("tr.clickable-row").forEach((row) => {
    row.addEventListener("click", () => {
        window.location = row.dataset.href;
    });
});
