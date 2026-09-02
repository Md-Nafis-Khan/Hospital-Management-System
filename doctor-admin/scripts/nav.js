var tabLinks = document.querySelectorAll(".tab-link");
var tabSections = document.querySelectorAll(".tab-section");

for (var i = 0; i < tabLinks.length; i++) {
  tabLinks[i].onclick = function () {
    for (var j = 0; j < tabLinks.length; j++) tabLinks[j].classList.remove("active");
    for (var k = 0; k < tabSections.length; k++) tabSections[k].classList.remove("active");
    this.classList.add("active");
    document.getElementById(this.getAttribute("data-tab")).classList.add("active");
  };
}
