function acceptCookies(){ document.getElementById('cookieConsent').style.display='none'; localStorage.cookieAccepted='1'; }
document.addEventListener('DOMContentLoaded', ()=>{ if(localStorage.cookieAccepted) document.getElementById('cookieConsent').style.display='none'; });
// Simple notification helper
function notify(msg){ let n = document.createElement('div'); n.className='notify'; n.textContent=msg; n.style.cssText='position:fixed;right:20px;top:20px;padding:10px;border-radius:8px;background:rgba(0,0,0,0.6);color:#fff;z-index:9999;'; document.body.appendChild(n); setTimeout(()=>n.remove(),3500); }

// star rating selection
document.addEventListener('click', function(e){ if(e.target.matches('.rating-select .star') || e.target.classList.contains('star')){ var star = e.target; var wrap = star.closest('.rating-select'); var val = parseInt(star.getAttribute('data-value')); wrap.setAttribute('data-selected', val); var inp = document.getElementById('ratingInput'); if(inp) inp.value = val; var spans = wrap.querySelectorAll('.star'); spans.forEach(function(s,i){ if(i<val) s.classList.add('on'); else s.classList.remove('on'); }); } });
