document.addEventListener("DOMContentLoaded", function () {
  const body = document.body;
  body.classList.remove("gradient-animate");
  void body.offsetWidth; // Trigger reflow
  body.classList.add("gradient-animate");
});
