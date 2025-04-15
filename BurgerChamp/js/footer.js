function footer(){

document.getElementsByClassName("footer_section")[0].innerHTML= `    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>
              Kapcsolat információ
            </h4>
            <div class="contact_link_box">
              <a
                href="https://www.google.com/maps/@47.9471908,22.3244408,3a,75y,300.77h,72.39t/data=!3m7!1e1!3m5!1s-CyfZ6F2Oa_wcFsEqQ1PxQ!2e0!6shttps:%2F%2Fstreetviewpixels-pa.googleapis.com%2Fv1%2Fthumbnail%3Fcb_client%3Dmaps_sv.tactile%26w%3D900%26h%3D600%26pitch%3D17.60825397427743%26panoid%3D-CyfZ6F2Oa_wcFsEqQ1PxQ%26yaw%3D300.7652670024484!7i16384!8i8192?entry=ttu&g_ep=EgoyMDI1MDEyOS4xIKXMDSoASAFQAw%3D%3D">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Helyszín
                </span>
              </a>
              <a href="https://www.telekom.hu/lakossagi">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Tel:+01 1234567890
                </span>
              </a>
              <a href="https://mail.google.com/mail/u/0/#inbox ">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  BurgerChamp@gmail.com
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="" class="footer-logo">
              BurgerChamp
            </a>
            <p>
              Ha az éhség beáll, a Bömös megáll
            </p>
            <div class="footer_social">
              <a href="https://www.facebook.com/?locale=hu_HU">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a
                href="https://twitter.com/x/migrate?tok=7b2265223a222f3f6c616e673d6875222c2274223a313733383636303837377d99b9d8b8ab7b1f4d16c48e73281ab96f">
                <i class="fa fa-twitter" aria-hidden="true"></i>
              </a>
              <a href="https://www.instagram.com/">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>
            Nyitva Tartás
          </h4>
          <p>
            Hétfő - Szombat
          </p>
          <p>
            10.00 - 22.00
          </p>
        </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
           BurgerChamp
        </p>
      </div>
    </div>`;
}
footer();