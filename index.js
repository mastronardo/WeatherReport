const container = document.getElementById("container"); // Ottenere l'elemento container
const search = document.getElementById("button"); // Ottenere l'elemento button
const weatherBox = document.getElementById("weather-box"); // Ottenere l'elemento weather-box
const weatherDetails = document.getElementById("weather-details"); // Ottenere l'elemento weather-details
const error404 = document.getElementById("not-found"); // Ottenere l'elemento not-found

search.addEventListener('click', () => {
    const APIKey = '<INSERT_YOUR_API_KEY>'; // OpenWeatherMap API Key
    const city = document.getElementById("input").value; // Ottenere il valore dell'input

    if (city === '') // Se l'input è vuoto
        return;

    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${APIKey}`) // Prendere i dati da OpenWeatherMap API
        .then(response => response.json()) // Convertire la risposta in JSON
        .then(json => { // Visualizzazione dei dati

            if (json.cod === '404') { // Se la città non viene trovata
                container.style.height = '400px'; // Riduci l'altezza del container
                weatherBox.style.display = 'none'; // Nascondere il weather box
                weatherDetails.style.display = 'none'; // Nascondere i weather details
                error404.style.display = 'block'; // Mostare il messaggio di errore
                error404.classList.add('fadeIn'); // Aggiungere l'animazione fadeIn
                return;
            }

            error404.style.display = 'none'; // Nascondere il messaggio di errore
            error404.classList.remove('fadeIn'); // Rimuovere l'animazione fadeIn

            const image = document.getElementById("img"); // Ottenere l'elemento img
            const temperature = document.getElementById("temperature"); // Ottenere l'elemento temperature
            const description = document.getElementById("description"); // Ottenere l'elemento description
            const humidity = document.getElementById("spanH"); // Ottenere l'elemento humidity
            const wind = document.getElementById("spanW"); // Ottener l'elemento wind

            // Impostare l'immagine in base al tempo
            switch (json.weather[0].main) {
                case 'Clear':
                    image.src = 'images/clear.png';
                    break;

                case 'Rain':
                    image.src = 'images/rain.png';
                    break;

                case 'Snow':
                    image.src = 'images/snow.png';
                    break;

                case 'Clouds':
                    image.src = 'images/cloud.png';
                    break;

                case 'Drizzle':
                    image.src = 'images/drizzle.png';
                    break;

                case 'Haze':
                    image.src = 'images/haze.png';
                    break;

                default:
                    image.src = '';
            }

            temperature.innerHTML = `${parseInt(json.main.temp)}<span>°C</span>`; // Mostare la temperatura
            description.innerHTML = `${json.weather[0].description}`; // Mostare la descrizione
            humidity.innerHTML = `${json.main.humidity}`; // Mostare l'umidità
            wind.innerHTML = `${parseInt(json.wind.speed)}`; // Mostare la velocità del vento

            weatherBox.style.display = ''; // Mostrare il weather box
            weatherDetails.style.display = ''; // Mostrare i weather details
            weatherBox.classList.add('fadeIn'); // Aggiungere l'animazione fadeIn
            weatherDetails.classList.add('fadeIn'); // Aggiungere l'animazione fadeIn
            container.style.height = '590px'; // Aumentare l'altezza del container

           $.ajax({ // Invio dei dati al database
                    url: 'api.php/history/insert_weather',
                    type: 'POST',
                    dataType: 'json',
                    data: {city: city, temperature: parseInt(json.main.temp), description: json.weather[0].description,
                        humidity: json.main.humidity, wind: parseInt(json.wind.speed)},
                    success: function (data) {
                        console.log(data);
                    }
                })
        });
});