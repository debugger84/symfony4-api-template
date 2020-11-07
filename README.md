A template for console command
===

This project uses laradoc to orchestrate environment of a project. 
The Symfony framework have been chosen to organize code architecture of the code. 
The Specification pattern in conjunction with the Repository pattern was used to work with DB.
The code is split to modules. Documentation module has all knowledge about imaginable documents. User module is a stub for authentication system and its purpose only to show dividing the code to separate logical parts.  
   

How to start 
===
* Pull the project to your local directory.
* Run `make start`. All containers will start.
* If you want to test, than run `make test`

If you want to know all available commands from the Makefile - run `make help`  

How to test API manually
===
* Start a project, using the instruction from the previous article
* Add to your `/etc/hosts` file the string `127.0.0.1 test.local`
* Open the documentation page http://test.local:8081/doc
* Find authorization url `/user/auth/login` and send the data from example.
* The JWT token, that has been gotten from the previous step, add swagger's authorization popup as `Bearer eY...`. It is necessary. Don't forget the string **Bearer** before the token.
* Try to use other APIs.  
