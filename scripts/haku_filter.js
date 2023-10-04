// Cargar el archivo JSON
fetch('json/maakunnat.json')
.then(response => response.json())
.then(data => {
  // Obtiene el elemento select del desplegable de Sijainti
  const sijaintiSelect = document.getElementById("sijainti");

  // Recorre las Maakunnat en el objeto data
  data.Maakunta.forEach((maakunta) => {
    // Agrega una opción para la Maakunta
    const maakuntaOption = document.createElement("option");
    maakuntaOption.value = maakunta.nimi;
    maakuntaOption.text = maakunta.nimi;
    sijaintiSelect.appendChild(maakuntaOption);
  });
})
.catch(error => console.error('Error al cargar el archivo JSON:', error));
