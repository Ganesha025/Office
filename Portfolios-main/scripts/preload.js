const preloadAssets = () => {
  const assets = [
    "../Profile.webp",
    "../typing/test.html",
    "../images/image.html",
    "../certify/certificate.html",
    "../skill.html",
    "../main.js",
    "../tv.css",
    "../style.css",
  ];

  assets.forEach(src => {
    fetch(src, { cache: "force-cache" }).catch(() => {});
  });
};

window.addEventListener("load", preloadAssets);
