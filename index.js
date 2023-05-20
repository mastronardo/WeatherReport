const container = document.getElementById("container"); // Getting the container element
const search = document.getElementById("button"); // Getting the search button element
const weatherBox = document.getElementById("weather-box"); // Getting the weather box element
const weatherDetails = document.getElementById("weather-details"); // Getting the weather details element
const error404 = document.getElementById("not-found"); // Getting the error message element

search.addEventListener('click', () => {
    const APIKey = '<INSERT_YOUR_API_KEY>'; // OpenWeatherMap API Key
    const city = document.getElementById("input").value; // Getting the input value

    if (city === '') // If the input is empty
        return;

    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${APIKey}`) // Fetching data from OpenWeatherMap API
        .then(response => response.json()) // Converting the response to JSON
        .then(json => { // Displaying the data

            if (json.cod === '404') { // If the city is not found
                container.style.height = '400px'; // Reducing the height of the container
                weatherBox.style.display = 'none'; // Hiding the weather box
                weatherDetails.style.display = 'none'; // Hiding the weather details
                error404.style.display = 'block'; // Displaying the error message
                error404.classList.add('fadeIn'); // Adding the fade in animation
                return;
            }

            error404.style.display = 'none'; // Hiding the error message
            error404.classList.remove('fadeIn'); // Removing the fade in animation

            const image = document.getElementById("img"); // Getting the image element
            const temperature = document.getElementById("temperature"); // Getting the temperature element
            const description = document.getElementById("description"); // Getting the description element
            const humidity = document.getElementById("spanH"); // Getting the humidity element
            const wind = document.getElementById("spanW"); // Getting the wind element

            // Setting the image according to the weather
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

            temperature.innerHTML = `${parseInt(json.main.temp)}<span>°C</span>`; // Displaying the temperature
            description.innerHTML = `${json.weather[0].description}`; // Displaying the description
            humidity.innerHTML = `${json.main.humidity}`; // Displaying the humidity
            wind.innerHTML = `${parseInt(json.wind.speed)}`; // Displaying the wind

            weatherBox.style.display = ''; // Displaying the weather box
            weatherDetails.style.display = ''; // Displaying the weather details
            weatherBox.classList.add('fadeIn'); // Adding the fade in animation
            weatherDetails.classList.add('fadeIn'); // Adding the fade in animation
            container.style.height = '590px'; // Increasing the height of the container

           $.ajax({ // Sending the data to the database
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