**POTA Hunter State Tracker** is a demo application built with Symfony as a learning exercise.

[Parks on the Air](https://parksontheair.com/) (POTA) is an amateur/ham radio activity wherein:
* "Activators" operate their radio equipment from within [desginated parks](https://pota.app/#/map) with the goal of successfully contacting at least 10 operators to "activate" the park.
* "Hunters" operate their radio equipment from home, another POTA park, or any other location to "hunt" activators.
* Both Activators and Hunters can earn [awards](https://docs.pota.app/docs/awards.html) for hunting/activating certain numbers of parks in general, or within specific geographies or time periods, by activating across different frequency bands, etc.

One of the geography-based awards is "working" all the states in the USA. Personally, I have been after this goal for a little while and thought that building a simple demo tool for that task in a new-to-me web application framework would be fun.

A convenient tool in the POTA world is "spotting". Spotting permits activators or hunters to announce the location (park, state, etc), radio operating mode, and frequency used by park activators. Spot data is available on the [POTA app website](https://pota.app/#/) and through an API, which this application accesses.

My goal with this project was to provide a place to record radio contacts ("**QSOs**") to keep track of my successfully *hunted/worked* states and remaining *needed* states. Additionally, I wanted to filter the current spot list to preferentially show me active operators in states that I still needed to fulfill my worked-all-states goal/award.

In its current form, this application will win no design awards :sweat_smile:, but my primary goal was to implement a simple idea in Symfony to learn the basics of the framework.

If you have [`ddev`](https://ddev.com/) intalled and working on your system, hopefully you can get this application running with he following commands:
```
git clone https://github.com/BillTalkington/pota-hunter-state-tracker.git
cd pota-hunter-state-tracker/
ddev start
ddev exec composer install
ddev exec php bin/console doctrine:migrations:migrate
```

For right now, the POTA spot API is hit with every refresh; one of my next additions would be regular polling that dynamically updates the dashboard. Along with design changes that add _any_ design and more information about the respective spots in the "Needed" list (i.e. park reference, time since last spot, etc.).
