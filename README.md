#Technical task for Lukas Dabkowski

##User Story
As a User I would like to make a request to an API that will determine from a set of recipes what I can
have for lunch today based on the contents of my fridge, so that I quickly decide what I’ll be having.

##Acceptance Criteria
* Given that I have made a request to the `/lunch`​ endpoint I should receive a JSON response
of the recipes that I can prepare based on the availability of ingredients in my fridge
* Given that an ingredient is past its `use-by`​ date, I should not receive recipes containing this
ingredient
* Given that an ingredient is past its  `best-before`​ date, but is still within its use-by date, 
any recipe containing this ingredient should be sorted to the bottom of the response object

##Installation
###Vagrant

Run the Vagrantfile (edit it before if needed), stored in vagrant directory, 
with `vagrant up --provider=virtualbox`. 

If the VM does not start, please ensure that you're using the newest version of VirtualBox.
Tested on version: 5.0.32 r112930

After installation the address 127.0.0.1:8080 will be available for testing the 
application with a REST client.

###GitHub

Clone the repository from 

##Usage
Use a REST client to call the /lunch endpoint with the POST method. If no body 
will be given the application will take the default list of ingredients. 
A JSON array with the key "Lunch proposal" will be returned.

##Tests
The unit tests can be found in the "tests" directory. For the testing purpose
of the lunch endpoint, following command has to be executed (from the main directory):

` phpunit tests/ManageFlitter/LunchTest.php`
 
 

