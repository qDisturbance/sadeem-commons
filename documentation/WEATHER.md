# Open Weather API

inside `app/Console/Kernel.php`

the line `$schedule->command(UpdateWeather::class)->everyFiveMinutes();`

is responsible for updating the weather data from openweather api to the local table `weather`

a cron job on the server is required to run the scheduler
