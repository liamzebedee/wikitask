# WikiTask
WikiTask is a simple task manager I built for usage with the MediaWiki platform. Tasks are of a certain type (a category) and belong to a category. Tasks are ranked according to their task type's priority and the days until they are due. 

The WikiTask interface provides the Special:Tasks page for viewing tasks and the Special:ManageTasks page for managing tasks and task types. The Special:Tasks page can be included, and if a user has the appropriate permissions, a dynamic inline editor for managing tasks that uses AJAX will be displayed which provides easy editing. 

## Background
I built this extension to automatically prioritise my schoolwork according to when it is due and what type it is (assessment, homework etc.). It was built in little more than a weekend as a **minimal working prototype** and according to my needs, so it is really only suitable for a personal wiki. Nonetheless it does use several idiomatic technologies, such as *ORMTable* for database interaction, the *MediaWiki API framework* and an *AJAX frontend*. 
