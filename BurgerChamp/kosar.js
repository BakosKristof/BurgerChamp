function Hozzaad(item) {
  var modall = new Modal();
  var newItem = JSON.parse(decodeURIComponent(atob(item)));
  var newcartItem = {
    item: newItem,
    quantity: 1,
    isLItem: newItem.point_reqiued != undefined
  }
  let kosar = JSON.parse(getCookie('kosar') || '[]');

  console.log(newcartItem);

  if (newcartItem.isLItem) {
    var userPoints = getCookie("points") * 1;
    var eddigi = 0;
    kosar.forEach(i => {
      if (i.item.point_reqiued != null) eddigi += i.item.point_reqiued;
    })
    if (!(userPoints >= eddigi + newcartItem.item.point_reqiued)) {
      return modall.create("Értesítés", `Erre nincs elég pont`).open();
    }
    modall.create("Értesítés", `${newcartItem.item.name} hozzáadva a kosárhoz`).open();
    kosar.push(newcartItem)
    setCookie('kosar', JSON.stringify(kosar), 7);
    return;
  } else {
    var vane = kosar.find(i => i.item.id == newcartItem.item.id);
    if (vane != null) {
      vane.quantity++;
    } else {
      kosar.push(newcartItem);
    }
    modall.create("Értesítés", `${newcartItem.item.name} sikeresen hozzáadva, `).open();
    setCookie('kosar', JSON.stringify(kosar), 7);
  }
  location.href='#top';
}








function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i].trim();
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }
  return null;
}

// Süti beállítása
function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + encodeURIComponent(cvalue) + ";" + expires + ";path=/";
}



function MegjelenitKosar() {
  let kosar = JSON.parse(getCookie('kosar') || '[]');
  let kosarDiv = document.getElementById('kosar');

  if (!kosarDiv) {
    // console.error("Nincs 'kosar' elem az oldalon!");
    return;
  }

  kosarDiv.innerHTML = ""; // Kiürítjük az előző tartalmat

  if (kosar.length === 0) {
    kosarDiv.innerHTML = "<p>A kosár üres.</p>";
    return;
  }

  kosar.forEach((termek, index) => {
    if (!termek.isLItem) {
      let termekElem = document.createElement("div");
      termekElem.classList.add("kosar-item");
      termekElem.innerHTML = `
            <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                <img src="https://burgerchamp-api.paraghtibor.hu/cdn/get/${btoa(termek.item.images)}" alt="${termek.item.name}" style="width: 50px; height: 50px; object-fit: cover;">
                <span>${termek.item.name} (${termek.quantity} db)</span>
                <strong>${termek.item.price * termek.quantity} FT</strong>
                <button class="btn btn-danger btn-sm" onclick="TorolTermek(${index})">Törlés</button>
            </div>
        `;

      kosarDiv.appendChild(termekElem);
      return;

    }

    let termekElem = document.createElement("div");
    termekElem.classList.add("kosar-item");
    termekElem.innerHTML = `
          <div class="d-flex align-items-center justify-content-between border-bottom py-2">
              <img src="https://burgerchamp-api.paraghtibor.hu/cdn/get/${btoa(termek.item.images)}" style="width: 50px; height: 50px; object-fit: cover;">
              <span>${termek.item.name} (${termek.quantity} db)</span>
              <strong>${termek.item.point_reqiued * termek.quantity} pont</strong>
              <button class="btn btn-danger btn-sm" onclick="TorolTermek(${index})">Törlés</button>
          </div>
      `;

    kosarDiv.appendChild(termekElem);


  });
}



// Egy adott termék törlése a kosárból
function TorolTermek(index) {
  let kosar = JSON.parse(getCookie('kosar') || '[]');
  kosar.splice(index, 1); // Törli a kiválasztott elemet

  setCookie('kosar', JSON.stringify(kosar), 7); // Frissíti a sütit
  MegjelenitKosar(); // Frissíti a megjelenítést
}

// Kosár teljes kiürítése
function UritKosar() {
  setCookie('kosar', '[]', 7);
  MegjelenitKosar();
}

window.onload = MegjelenitKosar;


