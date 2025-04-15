const searchParams = new URLSearchParams(window.location.search);
let url = searchParams.get("login");
if (url !== null) {
  var modal = new Modal();
  modal.create("Értesítés", "Tartalom eléréséhez jelentkezzen be!").open();
}
console.log(url);
