function Register() {
  var name = document.getElementById("name").value
  var email = document.getElementById("email").value
  var password = document.getElementById("password").value

  if (name == "" || email == "" || password == "") {
    var modalll = new Modal();
    modalll.create("Értesítés", "Töltse ki a mezőket!").open();
    return;
  }
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/user/register",
    type: "POST",
    data: {
      name: document.getElementById("name").value,
      email: document.getElementById("email").value,
      password: document.getElementById("password").value,
    },
    success: function (response) {

      console.log("Sikeres regisztáció, jelentkezz be!");

    },
    error: function () {
      console.log("Regisztáció sikertelen");
    },
  });
}

function name() {

  return getCookie("username");;
}

function point() {

  return getCookie("points");

}

function Login() {
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/user/login",
    type: "POST",
    data: {
      email: document.getElementById("email").value,
      password: document.getElementById("password").value,
    },
    success: function (response) {
      var username = response.user.name;
      var id = response.user.id;
      var points = response.user.points;
      var role = response.user.role;
      document.cookie = `username=${username}`;
      document.cookie = `id=${id}`;
      document.cookie = `points=${points}`;
      document.cookie = `role=${role}`;
      window.location.href = "/profil.html";


    },
    error: function () {

      var modalll = new Modal();
      modalll.create("Értesítés", "Helytelen bejelentkezési adatok!").open();
    },
  });
}



function kuld() {
  var comment = document.getElementById("message").value

  if (comment == "") {
    var modall = new Modal();
    modall.create("Értesítés", "Töltse ki a mezőt!").open();
    return;
  }

  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/reviews",
    type: "POST",
    data: {
      user_id: getCookie("id"),
      comment: document.getElementById("message").value,
    },
    success: function (response) {
      var modal = new Modal();
      modal.create("Értesítés", "Az üzenetét sikeresen rögzítettük.").open();




    },
    error: function () {
      console.log("Hiba történt! Próbálja újra.");

    },
  });
}

function fizetes() {
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/Order",
    type: "POST",
    data: {
      user_id: getCookie("id"),
      order_details: getCookie("kosar")
    },
    success: function (response) {
      eval(response)

    },
    error: function (xhr, status, error) {

      var modalures = new Modal();
      modalures.create("Értesítés", "Hiba történt!").open();
      console.log(status)
    },
  });
}


function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}



function megjelenit() {
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/Menu/get",
    success: function (response) {
      //foreach, stb      
      const categories = response.categories;
      const menus = response.menus;
      try {

        menus.forEach(i => {
          document.getElementById("food").innerHTML += food(i.name, i.points);
        });

      } catch (err) {
        console.log(err);

      }
      try {
        menus.forEach(i => {
          document.getElementById("menu").innerHTML += kartya(i.name, i.description, i.price, i.images, i.id, i.points, i.category, i);
        });
      } catch (err) {
        console.log(err);

      }


   


    },

    error: function () {
      console.error("Hiba történt!");
    },
  });
}



function kartya(name, description, price, images, id, loyalty_points, category, item) {
  var st = window.btoa(window.encodeURIComponent(JSON.stringify(item)));

  return `

  <div class="col-sm-3 menu-item"> <div class="card" id="card">
    <p class="d-none card-id"> ${id} </p> 
    <p class="d-none loyalty_points"> ${loyalty_points} </p> 
    <div class="menu_img mb-1" style="background-image: url('https://burgerchamp-api.paraghtibor.hu/cdn/get/${btoa(images)}');"> </div>
      <div class="card-body">
        <h5 class="card-title">${name}</h5>
        <h6 class="card-title menu-item" "data-category="${category}"</h6>
        <p class="card-text">${description}</p>
      <div class="d-flex justify-content-between mt-2">   
        <p class="card-text mt-1 fw-bold">${price} FT</p>
        <div class="add-to-cart" style="width: 30; height: 30" onclick="Hozzaad('${st}')">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
         </svg
         ></div>
      </div>
    </div> 
  </div>
 

    `;
}




function food(name, points) {
  return `
    <div class="food-item">
      <h5 class="food-name">${name}</h5>
      <h4 class="food-points">${points} pont</h4>
    </div>
  `;
}










function Offers() {
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/Offer/get",
    success: function (response) {
      const offers = response;
      try {
        offers.forEach(i => {
          // Az ajánlat HTML-jének létrehozása

          var s = btoa(encodeURIComponent(JSON.stringify(i)));
          const offerHTML = `
          <div class="offer-item">
          <div class="menu_img mb-1" style="background-image: url('https://burgerchamp-api.paraghtibor.hu/cdn/get/${btoa(i.images)}');"> </div>
            <h5><strong>${i.name}</strong></h5>
            <h4>${i.point_reqiued} pont</h4>
            <button class="buy-button" onclick="Hozzaad('${s}')">Kosárba</button>
          </div>
        `;


          document.getElementById("offer").innerHTML += offerHTML;
        });
      } catch (err) {
        console.log(err);
      }
    },

    error: function () {
      console.error("Hiba történt!");
    },
  });
}

// Vásárlás funkció, egyelőre üres
function vasarlas(rewardName) {
  console.log('Vásárlás indítva:', rewardName);
  // Itt fogjuk majd kezelni a vásárlás folyamatot.
}


//admin routes
function showUsers() {
  document.getElementById("alluser").innerHTML = "";
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/admin/user/{page}/get",
    type: "GET",
    success: function (response) {
      const users = response.users;
      users.forEach(k => {
        document.getElementById("alluser").innerHTML += `<ul class="list-group">
  <li class="list-group-item active" aria-current="true">${k.id}</li>
   <li class="list-group-item">${k.name}</li>
    <li class="list-group-item">${k.role}</li>
  <li class="list-group-item">${k.email}</li>
 
  <li class="list-group-item">${k.password}</li>
  <li class="list-group-item">${k.points}</li>
</ul>`;
      });

    },
    error: function () {
      console.log("sikertelen");
    },
  })

};



function userAdd() {
  document.getElementById("adduser").innerHTML = `
    <label for="name">Felhasználónév:</label>
    <input type="text" id="name" required />

    <label >Email:</label>
    <input type="email" id="email" required />

    <label>Jelszó:</label>
    <input type="password" id="password" required
      pattern="^(?=.*\\d)(?=.*[a-zA-Z])(?=.*[^a-zA-Z0-9])\\S{8,}$"
      title="A jelszóban legyen minimum 1 betű, 1 jel és 1 szám, és legalább 8 karakter hosszú" />

    <input type="button" value="Hozzáad" onclick="submitUserAdd()" />
  `;
  document.getElementById("updateuser").innerHTML = "";
  document.getElementById("deleteuser").innerHTML = "";
}

function submitUserAdd() {
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/admin/user/insert",
    type: "POST",
    data: {
      name: document.getElementById("name").value,
      email: document.getElementById("email").value,
      password: document.getElementById("password").value,
    },
    success: function (response) {
      console.log(response)
      console.log("Sikeres!");
    },
    error: function () {
      console.log("Sikertelen");
    },
  });
}

function userDelete() {
  document.getElementById("deleteuser").innerHTML = `
    <label>ID:</label>
    <input type="text" id="id" required />

    <input type="button" value="Töröl" onclick="submitUserDelete()" />
  `;
  document.getElementById("updateuser").innerHTML = "";
  document.getElementById("adduser").innerHTML = "";
}

function submitUserDelete() {
  let id = document.getElementById("id").value;

  $.ajax({
    url: `https://burgerchamp-api.paraghtibor.hu/admin/user/delete/${id}`,
    type: "GET",
    success: function (response) {
      console.log("Törlés sikeres!");
    },
    error: function () {
      console.log("Törlés sikertelen!");
    },
  });
}

function userUpdate() {
  document.getElementById("updateuser").innerHTML = `
    <label>ID:</label>
    <input type="text" id="id" required />

    <label>Név:</label>
    <input type="text" id="name"  />

     <label>Role:</label>
    <input type="text" id="role" />

    <label>Email:</label>
    <input type="text" id="email"  />


      <label>Pontok:</label>
    <input type="text" id="pont"  />

    <input type="button" value="Módosít" onclick="submitUserUpdate()" />
  `;
  document.getElementById("deleteuser").innerHTML = "";
  document.getElementById("adduser").innerHTML = "";
}

function submitUserUpdate() {
  const id = document.getElementById("id").value;

  const name = document.getElementById("name").value
  const role = document.getElementById("role").value
  const email = document.getElementById("email").value
  const pont = document.getElementById("pont").value

  $.ajax({
    url: `https://burgerchamp-api.paraghtibor.hu/admin/user/update/${id}`,
    type: "POST",
    data: { name, role, email, pont },
    success: function () {
      console.log("Sikeresen frissítve!");
    },
    error: function () {
      console.log("Nem sikerült a frissítés!");
    },
  });
}






function showOrder() {
  document.getElementById("allorder").innerHTML = "";
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/admin/order/get",
    type: "GET",
    success: function (response) {
      response.forEach(o => {
        document.getElementById("allorder").innerHTML += `
          <ul class="list-group mb-3">
            <li class="list-group-item active" aria-current="true">Rendelés #${o.id}</li>
            <li class="list-group-item">Felhasználó ID: ${o.user_id}</li>
            <li class="list-group-item">Név: ${o.name} (${o.email})</li>
            <li class="list-group-item">Dátum: ${o.order_date}</li>
            <li class="list-group-item">Összeg: ${o.total_amount} Ft</li>
            <li class="list-group-item">Állapot: ${o.status}</li>
          
          </ul>
        `;
      });
    },
    error: function () {
      console.log("Sikertelen lekérés.");
    },
  });
}




function showReviews() {

  document.getElementById("allreview").innerHTML = "";
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/admin/reviews",
    type: "GET",
    dataType: "json",
    success: function (response) {
      const reviews = response.reviews;
      reviews.forEach(r => {
        document.getElementById("allreview").innerHTML += `
          <ul class="list-group mb-3">
            <li class="list-group-item active">Vélemény #${r.id}</li>
            <li class="list-group-item"><strong>Felhasználó ID:</strong> ${r.user_id}</li>
            <li class="list-group-item"><strong>Menü ID:</strong> ${r.menu_item_id}</li>
            <li class="list-group-item"><strong>Megjegyzés:</strong> ${r.comment}</li>
          </ul>
        `;
      });
    },
    error: function () {
      console.log("Sikertelen lekérés a véleményekhez.");
    }
  });
}



function reviewDelete() {
  document.getElementById("deletereview").innerHTML = `
    <label>ID:</label>
    <input type="text" id="id" required />

    <input type="button" value="Töröl" onclick="submitReviewDelete()" />
  `;

}

function submitReviewDelete() {
  let id = document.getElementById("id").value;

  $.ajax({
    url: `https://burgerchamp-api.paraghtibor.hu/admin/reviews/del/${id}`,
    type: "GET",
    success: function (response) {
      console.log("Törlés sikeres!");
    },
    error: function () {
      console.log("Törlés sikertelen!");
    },
  });
}







function showMenu() {
  document.getElementById("allmenu").innerHTML = "";
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/admin/menu",
    type: "GET",
    dataType: "json",
    success: function (response) {
      const menus = response.menus;
      menus.forEach(m => {
        document.getElementById("allmenu").innerHTML += `
          <ul class="list-group mb-3">
            <li class="list-group-item active">Menü #${m.id}</li>
            <li class="list-group-item"><strong>Név:</strong> ${m.name}</li>
            <li class="list-group-item"><strong>Kategória:</strong> ${m.category}</li>
            <li class="list-group-item"><strong>Ár:</strong> ${m.price} Ft</li>
            <li class="list-group-item"><strong>Leírás:</strong> ${m.description}</li>
            <li class="list-group-item"><strong>Elérhetőség:</strong> ${m.availability === 1 ? "Elérhető" : "Nem elérhető"}</li>
            <li class="list-group-item"><strong>Kép:</strong> <img src="https://burgerchamp-api.paraghtibor.hu/${m.images}" alt="${m.name}" style="max-width: 100px;"></li>
            <li class="list-group-item"><strong>Pontok:</strong> ${m.points}</li>
          </ul>
        `;
      });


    },
    error: function () {
      console.log("Sikertelen lekérés a menühöz.");
    }
  });
}









function showLoyalty() {

  document.getElementById("allreward").innerHTML = "";
  $.ajax({
    url: "https://burgerchamp-api.paraghtibor.hu/admin/loyalty_rewards",
    type: "GET",
    dataType: "json",
    success: function (response) {

      const rewards = response.loyalty_rewards;
      rewards.forEach(t => {
        document.getElementById("allreward").innerHTML += `
          <ul class="list-group mb-3">
            <li class="list-group-item active">Jutalom #${t.id}</li>
            <li class="list-group-item"><strong>Menü ID:</strong> ${t.menu_id}</li>
            <li class="list-group-item"><strong>Jutalom Pontok:</strong> ${t.point_reqiued}</li>
            <li class="list-group-item"><strong>Elérhetőség:</strong> ${t.availability === 1 ? "Elérhető" : "Nem elérhető"}</li>
          </ul>
        `;
      });
    },
    error: function () {
      console.log("Sikertelen lekérés a hűségjutalmakhoz.");
    }
  });
}


function orderDelete() {
  document.getElementById("deleteorder").innerHTML = `
    <label>ID:</label>
    <input type="text" id="idorder" required />

    <input type="button" value="Töröl" onclick="submitOrderDelete()" />
  `;
  document.getElementById("updateorder").innerHTML = "";
  document.getElementById("addorder").innerHTML = "";
}

function submitOrderDelete() {

  let id = document.getElementById("idorder").value;

  $.ajax({
    url: `https://burgerchamp-api.paraghtibor.hu/admin/order/del/${id}`,
    type: "GET",
    success: function (response) {
      console.log("Törlés sikeres!");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

Offers()

megjelenit()

showUsers()

showOrder()

showReviews()

showMenu()

showLoyalty()