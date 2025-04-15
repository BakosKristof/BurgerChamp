function checkAuth() {
    if (!isLoggedIn()) {
        window.location.href = "index.html?login";       
    } 

    var role = getRoleCookie();
    const currentPage = window.location.pathname.split('/').pop();
    if (role === 'admin') {
        if (currentPage !== 'adminpanel.html') {
          window.location.href = "adminpanel.html";
        }
      } else {
        if (currentPage === 'adminpanel.html') {
          window.location.href = "index.html";
        }
      }
}

function getRoleCookie() {
    const cookies = document.cookie.split(';');
    for (const cookie of cookies) {
      const [name, value] = cookie.trim().split('=');
      if (name === 'role') {
        return decodeURIComponent(value);
      }
    }
    return null; // Ha nem található ilyen cookie
  }

// Megnézi, hogy a felhasználó be van-e jelentkezve
function isLoggedIn() {
    return document.cookie.includes("id=");
}

// Kijelentkezési függvény
function logout() {
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    window.location.href = "index.html";
}

// Meghívás minden védett oldalon
checkAuth();
