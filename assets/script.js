var DOMReady = function(callback) {
  if (document.readyState === "interactive" || document.readyState === "complete") {
      callback();
  } else if (document.addEventListener) {
      document.addEventListener("DOMContentLoaded", callback);
  } else if (document.attachEvent) {
      document.attachEvent("onreadystatechange", function() {
          if (document.readyState != "loading") {
              callback();
          }
      });
  }
};

function closeCookiePopup() {
	let e = "cookies_gdpr_consent=true; path=/; expires=",
		t = new Date(),
		s = t.getTime() + 31536e6;
	t.setTime(s), (e += t.toUTCString()), (document.cookie = e), document.getElementById("cookies-gdpr-modal").classList.remove("cookies-gdpr--show");
}

DOMReady(function() {
  let close_cookie_btn = document.getElementById("cookies-gdpr-button");
  if(close_cookie_btn !== null)
  {
    close_cookie_btn.addEventListener("click", closeCookiePopup);
    
  }
  setTimeout(function () {
    let cookie_modal = document.getElementById("cookies-gdpr-modal");
    if( cookie_modal !== null)
    {
      cookie_modal.classList.add("cookies-gdpr--show");
    }
  }, 3000);
  
});