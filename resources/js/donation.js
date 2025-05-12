document.addEventListener("DOMContentLoaded", function () {
    const oneTimeBtn = document.getElementById("oneTimeBtn");
    const monthlyBtn = document.getElementById("monthlyBtn");
    const donationTypeInput = document.getElementById("donationTypeInput");
    const amountButtons = document.querySelectorAll(".amount-btn");
    const selectedAmountInput = document.getElementById("selectedAmount");
    const amountOtherInput = document.querySelector(".amount-other");

    oneTimeBtn.addEventListener("click", () => {
        donationTypeInput.value = "one-time";
        oneTimeBtn.classList.add("active");
        monthlyBtn.classList.remove("active");
    });

    monthlyBtn.addEventListener("click", () => {
        donationTypeInput.value = "monthly";
        monthlyBtn.classList.add("active");
        oneTimeBtn.classList.remove("active");
    });

    amountButtons.forEach(button => {
        button.addEventListener("click", () => {
            amountButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
            selectedAmountInput.value = button.dataset.amount;
            amountOtherInput.value = "";
        });
    });

    amountOtherInput.addEventListener("input", () => {
        amountButtons.forEach(btn => btn.classList.remove("active"));
        selectedAmountInput.value = amountOtherInput.value;
    });
});
