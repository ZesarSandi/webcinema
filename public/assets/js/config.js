// Backend API Base URL
// For offline mode: ''
// For online backend: auto-detect based on current host
(() => {
	const host = window.location.hostname || 'localhost';
  
  // Use Railway backend for production, auto-detect for local
  let apiBase;
  if(host === 'localhost' || host === '127.0.0.1' || host.includes('192.168')) {
    apiBase = `http://${host}:3001`;
  } else {
    // Production: use Railway backend
    apiBase = 'https://webcinema2-production.up.railway.app';
  }
	window.API_BASE = apiBase;

	// Public base URL for QR generation (akses dari HP)
	const publicBase = `http://${host}:5500`;
	window.PUBLIC_BASE_URL = publicBase;
})();