console.log("hello");

document
  .querySelectorAll('button[data-action="delete"]')
  .forEach(function (button) {
    button.addEventListener("click", function () {
      this.parentNode.remove();
    });
  });

document
  .querySelector("#add-participation")
  .addEventListener("click", function (e) {
    //recuperer le prototipe
    const protoype = document
      .querySelector("#project_participations")
      .getAttribute("data-prototype");

    // connaitre l'index (remplacer __name__)
    const index = document.querySelectorAll('button[data-action="delete"]')
      .length;
    // reemplacer __name__
    const html = protoype.replace(/__name__/g, index);

    //ajouter le code html
    document
      .querySelector("#project_participations")
      .insertAdjacentHTML("beforeend", html);

    const button = document.querySelector(
      '#project_participations > div:last-child button[data-action="delete"]'
    );

    button.addEventListener("click", function () {
      this.parentNode.remove();
    });
  });
