# Utility insights
This personal project collects all kinds of utility related data and can be installed on a raspberry pi.

## About the project
The main goal is to collect and visualize data from
- a green energy meter used in solar installations
- the Belgian digital electricity meter using the P1 port

In the future the idea is to expand the project bny integrating
- monitoring the water level in the rain water tank
- activate smart plugs based on the collected data to increase self usage of solar generated electricity

## Technical
The project is build to run on a Raspberry pi and consists of 3 parts.
The different parts are kept seperated on purpose to make the application easily extendable.

### Data collection scripts
Plain python scripts that collect data from various inputs and store it in the database

### API
A few plain PHP scripts that read data from the database, process them and return them through an API

### Web app
A web page that displays a variety of graphs and data insights based on the API calls