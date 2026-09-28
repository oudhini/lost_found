document.addEventListener('DOMContentLoaded', () => {
    const over=document.getElementById('body')
    const loader = document.getElementById('loader');
    let isPageLoaded = false;
   // Afficher le loader pendant au moins 10 secondes
    setTimeout(() => {
        loader.style.display = 'none';
    }, 10000);

    // Marquer la page comme chargée lorsque le DOM est prêt
    window.onload = () => {
        isPageLoaded = true;
        if (loader.style.display !== 'none') {
            loader.style.display = 'none';
        }
    };
});