A template for console command
===

This project uses laradoc to orchestrate environment of a project. 
The Symfony framework have been chosen to organize code architecture of the code. 
The Specification pattern in conjunction with the Repository pattern were used to work with DB. 
   

How to start 
===
* Pull the project to your local directory.
* Run `make start`. All containers will start.
* If you want to test, than run `make test`
* Add data to database and run `make run-command`

If you want to know all available commands from the Makefile - run `make help`  


Timetable
==

| # | Task     | Estimation | Spent | Comment |
|---|----------|----|-----|-------|
| 1 | Create skeleton of a project | 1h | 40m | Copying of own template of a project to the new project |
| 2 | Create API to create a document | 1h | 3h 30m | API for creation the document |
| 3 | Create tasks and a timetable | 30m | 30m | Adding of the timetable |
| 4 | Create API to update a document | 2h |2h 30m |  API for updating the document |
| 5 | Create API to publish a document | 1h | 30m | API for publishing the document |
| 6 | Create API to return a list of documents | 1h | 1h | APIs for returning the data about a document or about a list of documents |
| 7 | Remove unnecessary code, that have been left from the copied skeleton | 1h | | |
| 8 | Implement the documentation for an API | 2h | | |
| 9 | Make the final polishing of code | 1h | | |
